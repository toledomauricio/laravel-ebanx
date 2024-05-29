<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountController;
use App\Models\Account;
use App\Repository\AccountRepository;

class AccountTest extends TestCase
{
    protected $accountRepo;
    protected $accountController;

    public function setUp(): void
    {
        parent::setUp();

        // Criar um mock para AccountRepository
        $this->accountRepo = \Mockery::mock(AccountRepository::class);
        $this->accountController = new AccountController($this->accountRepo);
    }

    public function testStoreAccount()
    {
        $data = ['account_number' => 234, 'balance' => 180.37];

        $mockAccount = new Account($data);

        $this->accountRepo->shouldReceive('create')
            ->once()
            ->andReturn($mockAccount);

        $request = new Request($data);

        $response = $this->accountController->create($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode($data), $response->getContent());
    }

    public function testShowAccount()
    {
        $data = ['account_number' => 234, 'balance' => 180.37];
        $account_number = 234;
        $mockAccount = new Account($data);

        $this->accountRepo->shouldReceive('getByNumber')
            ->once()
            ->andReturn($mockAccount);

        $response = $this->accountController->getByNumber($account_number);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode($data), $response->getContent());
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}

