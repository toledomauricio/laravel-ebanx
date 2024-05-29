<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\TransactionController;
use App\Repository\TransactionRepository;
use App\Repository\AccountRepository;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\PaymentType;

class TransactionControllerTest extends TestCase
{
    /**
     * Repositório de transações mocado
     * @var \App\Repository\TransactionRepository|\Mockery\MockInterface
     */
    protected $transactionRepository;

    /**
     * Repositório de contas mockado.
     *
     * @var \App\Repository\AccountRepository|\Mockery\MockInterface
     */
    protected $accountRepository;

    /**
     * Controlador de transações a ser testado.
     *
     * @var \App\Http\Controllers\TransactionController
     */
    protected $transactionController;

    /**
     * Configuração inicial para cada teste.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Criar mocks para TransactionRepository e AccountRepository
        $this->transactionRepository = \Mockery::mock(TransactionRepository::class);
        $this->accountRepository = \Mockery::mock(AccountRepository::class);

        $this->transactionController = new TransactionController($this->transactionRepository, $this->accountRepository);
    }

    /**
     * Testa a criação de uma transação com dados válidos.
     *
     * @return void
     */
    public function testStoreTransactionWithValidData()
    {
        $account = Account::factory()->create(['balance' => 200]);
        $paymentType = PaymentType::factory()->create(['fee' => 5]);

        $requestData = [
            'payment_type' => $paymentType->code,
            'account_number' => $account->account_number,
            'value' => 100,
        ];

        // Enviando uma solicitação POST JSON para o método store do TransactionController
        $response = $this->postJson('/api/transaction', $requestData);

        // Verificando se o status da resposta é 201 (Criado)
        $response->assertStatus(201);
    }

    /**
     * Testa a criação de transações para diferentes tipos de pagamento.
     *
     * @return void
     */
    public function testStoreTransactionForDifferentPaymentTypes()
    {
        $account = Account::factory()->create(['balance' => 200]);

        // Débito
        $debitPaymentType = PaymentType::factory()->create(['code' => 'D', 'fee' => 3]);
        $debitRequestData = [
            'payment_type' => $debitPaymentType->code,
            'account_number' => $account->account_number,
            'value' => 10,
        ];
        $debitResponse = $this->postJson('/api/transaction', $debitRequestData);
        $debitResponse->assertStatus(201);

        // Crédito
        $creditPaymentType = PaymentType::factory()->create(['code' => 'C', 'fee' => 5]);
        $creditRequestData = [
            'payment_type' => $creditPaymentType->code,
            'account_number' => $account->account_number,
            'value' => 10,
        ];
        $creditResponse = $this->postJson('/api/transaction', $creditRequestData);
        $creditResponse->assertStatus(201);

        // Pix
        $pixPaymentType = PaymentType::factory()->create(['code' => 'P', 'fee' => 0]);
        $pixRequestData = [
            'payment_type' => $pixPaymentType->code,
            'account_number' => $account->account_number,
            'value' => 10,
        ];
        $pixResponse = $this->postJson('/api/transaction', $pixRequestData);
        $pixResponse->assertStatus(201);
    }

    /**
     * Testa a criação de uma transação com uma conta existente.
     *
     * @return void
     */
    public function testStoreTransactionWithExistingAccount()
    {
        $account = Account::factory()->create(['balance' => 200]);
        $paymentType = PaymentType::factory()->create(['fee' => 5]);

        $requestData = [
            'payment_type' => $paymentType->code,
            'account_number' => $account->account_number,
            'value' => 250, // Valor maior que o saldo da conta
        ];

        $response = $this->postJson('/api/transaction', $requestData);
        $response->assertStatus(400); // Verifica se o status da resposta é 400 (Bad Request) ou outro código adequado
    }

    /**
     * Testa a criação de uma transação com saldo insuficiente na conta.
     *
     * @return void
     */
    public function testStoreTransactionWithInsufficientBalance()
    {
        $account = Account::factory()->create(['balance' => 10]); // Saldo insuficiente
        $paymentType = PaymentType::factory()->create(['fee' => 5]);

        $requestData = [
            'payment_type' => $paymentType->code,
            'account_number' => $account->account_number,
            'value' => 20, // Valor maior que o saldo da conta
        ];

        $response = $this->postJson('/api/transaction', $requestData);
        $response->assertStatus(400); // Verifica se o status da resposta é 400 (Bad Request) ou outro código adequado
    }

    /**
     * Limpa as configurações após cada teste.
     *
     * @return void
     */
    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
