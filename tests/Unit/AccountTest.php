<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountController;
use App\Models\Account;
use App\Repository\AccountRepository;

/**
 * Classe de teste para a funcionalidade de contas
 */
class AccountTest extends TestCase
{
    /**
     * Repositório de contas (mockado)
     *
     * @var \Mockery\MockInterface
     */
    protected $accountRepo;

    /**
     * Controlador de contas
     *
     * @var \App\Http\Controllers\AccountController
     */
    protected $accountController;

    /**
     * Configura o ambiente de teste
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Criar um mock para AccountRepository
        $this->accountRepo = \Mockery::mock(AccountRepository::class);
        // Inicializar o controlador de contas com o repositório mockado
        $this->accountController = new AccountController($this->accountRepo);
    }

    /**
     * Testa a criação de uma nova conta
     *
     * @return void
     */
    public function testStoreAccount()
    {
        // Dados de entrada para a criação de uma conta
        $data = ['account_number' => 234, 'balance' => 180.37];

        // Cria um mock de uma instância da conta com os dados fornecidos
        $mockAccount = new Account($data);

        // Define a expectativa do repositório mockado para o método create
        $this->accountRepo->shouldReceive('create')
            ->once()
            ->andReturn($mockAccount);

        // Cria uma nova requisição com os dados de entrada
        $request = new Request($data);

        // Chama o método create do controlador de contas
        $response = $this->accountController->create($request);

        // Verifica se o status da resposta é 201 (Created)
        $this->assertEquals(201, $response->getStatusCode());
        // Verifica se o conteúdo da resposta é igual aos dados de entrada em formato JSON
        $this->assertJsonStringEqualsJsonString(json_encode($data), $response->getContent());
    }

    /**
     * Testa a visualização de uma conta existente
     *
     * @return void
     */
    public function testShowAccount()
    {
        // Dados de uma conta existente
        $data = ['account_number' => 234, 'balance' => 180.37];
        $account_number = 234;
        $mockAccount = new Account($data);

        // Define a expectativa do repositório mockado para o método getByNumber
        $this->accountRepo->shouldReceive('getByNumber')
            ->once()
            ->andReturn($mockAccount);

        // Chama o método getByNumber do controlador de contas com o número da conta
        $response = $this->accountController->getByNumber($account_number);

        // Verifica se o status da resposta é 200 (OK)
        $this->assertEquals(200, $response->getStatusCode());
        // Verifica se o conteúdo da resposta é igual aos dados da conta em formato JSON
        $this->assertJsonStringEqualsJsonString(json_encode($data), $response->getContent());
    }

    /**
     * Limpa o ambiente de teste
     *
     * @return void
     */
    public function tearDown(): void
    {
        // Fecha todos os mocks do Mockery
        \Mockery::close();
        parent::tearDown();
    }
}
