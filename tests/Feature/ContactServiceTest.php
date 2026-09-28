<?php

namespace Tests\Feature;

use App\Mail\BudgetWelcome;
use App\Http\Requests\PostContactRequest;
use App\Models\Cliente;
use App\Models\Expectativa;
use App\Models\Lead;
use App\Services\ContactService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ContactServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        foreach (['sqlite', 'unicasa', '8poroito'] as $connection) {
            config(["database.connections.$connection" => [
                'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
            ]]);
            DB::purge($connection);
        }
        config(['database.default' => 'sqlite']);

        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->string('uf');
        });
        Schema::create('cidades', function (Blueprint $table) {
            $table->id();
            $table->integer('estado_id');
            $table->string('nome');
        });
        DB::table('estados')->insert(['id' => 1, 'uf' => 'RS']);
        DB::table('cidades')->insert(['id' => 1, 'estado_id' => 1, 'nome' => 'Bento Gonçalves']);

        Schema::connection((new Cliente())->getConnectionName())->create('clientes', function (Blueprint $table) {
            $table->id();
            foreach (['nome', 'telefone', 'email', 'uf', 'cidade', 'cod_marca', 'token',
                'canal_atendimento_id', 'tipo_cadastro', 'status_cliente', 'data',
                'mkt_midia_origem', 'mkt_campanha_origem', 'mkt_grupo_origem', 'mkt_anuncio_origem', 'mkt_termo_origem'] as $field) {
                $table->string($field)->nullable();
            }
            $table->timestamps();
        });
        Schema::connection((new Expectativa())->getConnectionName())->create('expectativa_projetos', function (Blueprint $table) {
            $table->id();
            $table->integer('cliente_id');
            $table->string('cod_marca');
            $table->text('observacao_cliente');
            $table->timestamps();
        });
        Schema::connection('8poroito')->create('leads', function (Blueprint $table) {
            $table->id();
            foreach (['nome', 'email', 'telefone', 'uf', 'cidade', 'cliente', 'projeto', 'token',
                'entrada', 'dispositivo', 'posicao_formulario', 'origem', 'campanha', 'grupo', 'anuncio', 'termo'] as $field) {
                $table->string($field)->nullable();
            }
            $table->integer('conversoes');
            $table->timestamp('criado')->nullable();
            $table->timestamp('modificado')->nullable();
        });
    }

    private function data(): array
    {
        return [
            'nome' => 'Cliente Teste', 'email' => 'cliente@example.com',
            'telefone' => '(54) 99999-1234', 'estado_id' => 1, 'cidade_id' => 1,
            'mensagem' => 'Uma cozinha integrada à sala.', 'ocupacao' => 'Arquiteto',
            'politica' => true, 'origem' => 'google', 'campanha' => 'projetos',
            'grupo' => 'cozinhas', 'anuncio' => 'anuncio-1',
            'termo' => 'cozinha planejada',
            'entrada' => '2026-09-28 10:30:00', 'posicao_formulario' => 'Home',
        ];
    }

    public function test_creates_crm_records_with_project_and_tracking(): void
    {
        request()->headers->set('User-Agent', 'Android');
        $service = app(ContactService::class);
        $result = $service->create($this->data());

        $this->assertSame('dellanno', $result['cliente']->cod_marca);
        $this->assertSame('RS', $result['cliente']->uf);
        $this->assertSame('Bento Gonçalves', $result['cliente']->cidade);
        $this->assertSame('google', $result['cliente']->mkt_midia_origem);
        $this->assertSame("Uma cozinha integrada à sala.\n\nCargo: Arquiteto", $result['expectativa']->observacao_cliente);
        $this->assertSame($result['cliente']->id, $result['expectativa']->cliente_id);
        $this->assertSame($result['cliente']->token, $result['lead']->token);
        $this->assertSame('Mobile', $result['lead']->dispositivo);
        $this->assertSame('Home', $result['lead']->posicao_formulario);
        $this->assertSame('projetos', $result['lead']->campanha);
        $this->assertSame('cozinha planejada', $result['lead']->termo);
        $this->assertSame(0, $result['lead']->conversoes);
        Mail::assertSent(BudgetWelcome::class, function ($mail) {
            return $mail->hasTo('cliente@example.com');
        });
        Mail::assertSentCount(1);

        Lead::create(array_merge($result['lead']->getAttributes(), ['id' => null, 'cliente' => 'casabrasileira']));
        $this->assertSame(1, $service->create($this->data())['lead']->conversoes);
    }

    public function test_optional_fields_can_be_omitted(): void
    {
        $data = array_intersect_key($this->data(), array_flip([
            'nome', 'email', 'telefone', 'estado_id', 'cidade_id', 'mensagem', 'politica',
        ]));
        $result = app(ContactService::class)->create($data);

        $this->assertSame($data['mensagem'], $result['expectativa']->observacao_cliente);
        $this->assertNull($result['lead']->entrada);
        $this->assertNull($result['lead']->origem);
        $this->assertNull($result['lead']->termo);
        $this->assertNull($result['cliente']->mkt_termo_origem);
    }

    public function test_lead_failure_rolls_back_client_and_expectation(): void
    {
        Schema::connection('8poroito')->drop('leads');

        try {
            app(ContactService::class)->create($this->data());
            $this->fail('Expected missing leads table to fail.');
        } catch (\Illuminate\Database\QueryException $exception) {
            $this->assertSame(0, Cliente::count());
            $this->assertSame(0, Expectativa::count());
            Mail::assertNothingSent();
        }
    }

    public function test_welcome_email_preserves_content_and_addresses(): void
    {
        $mail = new BudgetWelcome();
        $mail->assertHasSubject('Bem-vindo ao atendimento exclusivo Dell Anno!');
        $mail->assertFrom('naoresponder@dellanno.com.br', 'Dell Anno | Site');
        $mail->assertHasBcc('rafael@8poroito.com.br');
        $mail->assertSeeInHtml('Agradecemos o seu interesse em orçar com a Dell Anno!');
        $mail->assertSeeInHtml('em até 24h');
        $mail->assertSeeInHtml('revenda autorizada Dell Anno mais próxima');
        $mail->assertSeeInHtml('totalmente sem custo');
    }

    public function test_mail_failure_does_not_discard_crm_records(): void
    {
        $pendingMail = \Mockery::mock(\Illuminate\Mail\PendingMail::class);
        $pendingMail->shouldReceive('send')->once()->with(\Mockery::type(BudgetWelcome::class))
            ->andReturnUsing(function () {
                $this->assertSame(0, (new Cliente())->getConnection()->transactionLevel());
                $this->assertSame(0, DB::connection('8poroito')->transactionLevel());
                throw new \RuntimeException('Test mail transport failure');
            });
        Mail::shouldReceive('to')->once()->with('cliente@example.com')->andReturn($pendingMail);

        $result = app(ContactService::class)->create($this->data());

        $this->assertTrue($result['cliente']->exists);
        $this->assertSame(1, Cliente::count());
        $this->assertSame(1, Expectativa::count());
        $this->assertSame(1, Lead::count());
    }

    public function test_validates_current_fields_and_city_state_pair(): void
    {
        $request = PostContactRequest::create('/', 'POST', $this->data());
        $rules = $request->rules();
        // Keep this test independent of external DNS availability.
        $rules['email'] = 'required|email:rfc|max:255';
        $validator = Validator::make($this->data(), $rules, $request->messages());
        $this->assertFalse($validator->fails(), $validator->errors()->toJson());
        $this->assertSame('Arquiteto', $validator->validated()['ocupacao']);
        $this->assertSame('google', $validator->validated()['origem']);
        $this->assertSame('cozinha planejada', $validator->validated()['termo']);

        DB::table('cidades')->where('id', 1)->update(['estado_id' => 2]);
        $validator = Validator::make(array_merge($this->data(), [
            'entrada' => 'invalid', 'politica' => false,
        ]), $rules, $request->messages());
        $this->assertTrue($validator->fails());
        foreach (['cidade_id', 'entrada', 'politica'] as $field) {
            $this->assertTrue($validator->errors()->has($field));
        }
    }
}
