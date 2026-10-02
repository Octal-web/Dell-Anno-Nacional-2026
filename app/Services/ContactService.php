<?php

namespace App\Services;

use App\Mail\BudgetWelcome;
use App\Models\Cliente;
use App\Models\Expectativa;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ContactService
{
    public const EXPECTATIVAS = [
        '20_40' => 'ENTRE R$ 20.000,00 A 40.000,00',
        '40_60' => 'ENTRE R$ 40.000,00 A 60.000,00',
        '60_80' => 'ENTRE R$ 60.000,00 A 80.000,00',
        'a_80' => 'ACIMA DE R$ 80.000,00',
    ];

    public function create(array $data): array
    {
        // Consulted before the transactions so the HTTP call never holds them open.
        $cepData = $this->fetchCepData($data['cep']);

        $result = (new Cliente())->getConnection()->transaction(function () use ($data, $cepData) {
            return DB::connection('8poroito')->transaction(function () use ($data, $cepData) {
                $cliente = Cliente::create($this->prepareClientData($data, $cepData));

                $expectativa = $this->createExpectativa($cliente->id, $data);

                $conversoes = $this->countConversions($cliente->email);

                $lead = $this->createLead($cliente, $expectativa, $data, $conversoes);

                return compact('cliente', 'expectativa', 'lead');
            });
        });

        // Send only after both databases have committed the contact.
        try {
            Mail::to($result['cliente']->email)->send(new BudgetWelcome());
        } catch (\Throwable $exception) {
            // A mail failure must not prompt a duplicate CRM submission.
            report($exception);
        }

        return $result;
    }

    /**
     * Returns the ViaCEP address, or an empty array when the service is unreachable
     * (the lead is kept without address, as the old site did).
     *
     * @throws ValidationException When ViaCEP does not know the CEP.
     */
    protected function fetchCepData(string $cep): array
    {
        $cep = preg_replace("/[^0-9]/", "", $cep);

        try {
            $response = Http::timeout(30)
                ->acceptJson()
                ->get("https://viacep.com.br/ws/{$cep}/json/");
        } catch (\Throwable $exception) {
            report($exception);

            return [];
        }

        if (!$response->successful()) {
            return [];
        }

        $data = $response->json();

        if (!is_array($data) || !empty($data['erro'])) {
            throw ValidationException::withMessages([
                'cep' => 'Por favor, informe um CEP válido.',
            ]);
        }

        return $data;
    }

    protected function prepareClientData(array $data, array $cepData): array
    {
        return [
            'nome' => $data['nome'],
            'telefone' => $data['telefone'],
            'email' => $data['email'],
            'cep' => preg_replace("/[^0-9]/", "", $data['cep']),
            'uf' => $cepData['uf'] ?? 'NI',
            'endereco' => $cepData['logradouro'] ?? null,
            'bairro' => $cepData['bairro'] ?? null,
            'cidade' => $cepData['localidade'] ?? null,
            'cod_marca' => 'dellanno',
            'token' => Str::random(32),
            'canal_atendimento_id' => 1,
            'tipo_cadastro' => 'L',
            'status_cliente' => 'C',
            'data' => now(),
            'mkt_midia_origem' => $data['origem'] ?? null,
            'mkt_campanha_origem' => $data['campanha'] ?? null,
            'mkt_grupo_origem' => $data['grupo'] ?? null,
            'mkt_anuncio_origem' => $data['anuncio'] ?? null,
        ];
    }

    protected function createExpectativa(int $clienteId, array $data): Expectativa
    {
        return Expectativa::create([
            'cliente_id' => $clienteId,
            'cod_marca' => 'dellanno',
            'expectativa_investimento' => self::EXPECTATIVAS[$data['expectativa_investimento']] ?? null,
        ]);
    }

    protected function countConversions(string $email): int
    {
        return Lead::query()
            ->where([
                'email' => $email,
                'cliente' => 'dellanno',
                'projeto' => 'facaseuprojeto',
            ])
            ->count();
    }

    protected function createLead(Cliente $cliente, Expectativa $expectativa, array $data, int $conversoes): Lead
    {
        return Lead::create([
            'nome' => $cliente->nome,
            'email' => $cliente->email,
            'telefone' => $cliente->telefone,
            'cep' => $cliente->cep,
            'uf' => $cliente->uf,
            'cidade' => $cliente->cidade,
            'conversoes' => $conversoes,
            'cliente' => 'dellanno',
            'projeto' => 'facaseuprojeto',
            'token' => $cliente->token,
            'expectativa_investimento' => $expectativa->expectativa_investimento,
            'entrada' => !empty($data['entrada']) ? Carbon::parse($data['entrada']) : null,
            'dispositivo' => $this->detectDevice(),
            'posicao_formulario' => $data['posicao_formulario'] ?? null,
            'origem' => $data['origem'] ?? null,
            'campanha' => $data['campanha'] ?? null,
            'grupo' => $data['grupo'] ?? null,
            'anuncio' => $data['anuncio'] ?? null,
            'termo' => $data['termo'] ?? null,
        ]);
    }

    protected function detectDevice(): string
    {
        $userAgent = request()->userAgent() ?? '';

        foreach (['iPhone', 'iPad', 'Android', 'BlackBerry', 'Windows Phone'] as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                return 'Mobile';
            }
        }

        return 'Computador';
    }
}
