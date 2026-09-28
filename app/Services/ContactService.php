<?php

namespace App\Services;

use App\Mail\BudgetWelcome;
use App\Models\Cidade;
use App\Models\Cliente;
use App\Models\Expectativa;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ContactService
{
    public function create(array $data): array
    {
        $cidade = Cidade::with('estado')
            ->where('estado_id', $data['estado_id'])
            ->findOrFail($data['cidade_id']);

        $result = (new Cliente())->getConnection()->transaction(function () use ($data, $cidade) {
            return DB::connection('8poroito')->transaction(function () use ($data, $cidade) {
                $cliente = Cliente::create([
                    'nome' => $data['nome'],
                    'telefone' => $data['telefone'],
                    'email' => $data['email'],
                    'uf' => $cidade->estado->uf,
                    'cidade' => $cidade->nome,
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
 
                ]);

                $observacao = $data['mensagem'];

                if (!empty($data['ocupacao'])) {
                    $observacao .= "\n\nCargo: " . $data['ocupacao'];
                }

                $expectativa = Expectativa::create([
                    'cliente_id' => $cliente->id,
                    'cod_marca' => 'dellanno',
                    'observacao_cliente' => $observacao,
                ]);

                $conversoes = Lead::query()->where([
                    'email' => $cliente->email,
                    'cliente' => 'dellanno',
                    'projeto' => 'facaseuprojeto',
                ])->count();

                $lead = Lead::create([
                    'nome' => $cliente->nome,
                    'email' => $cliente->email,
                    'telefone' => $cliente->telefone,
                    'uf' => $cliente->uf,
                    'cidade' => $cliente->cidade,
                    'conversoes' => $conversoes,
                    'cliente' => 'dellanno',
                    'projeto' => 'facaseuprojeto',
                    'token' => $cliente->token,
                    'entrada' => !empty($data['entrada']) ? Carbon::parse($data['entrada']) : null,
                    'dispositivo' => $this->detectDevice(),
                    'posicao_formulario' => $data['posicao_formulario'] ?? null,
                    'origem' => $data['origem'] ?? null,
                    'campanha' => $data['campanha'] ?? null,
                    'grupo' => $data['grupo'] ?? null,
                    'anuncio' => $data['anuncio'] ?? null,
                    'termo' => $data['termo'] ?? null,
                ]);

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
