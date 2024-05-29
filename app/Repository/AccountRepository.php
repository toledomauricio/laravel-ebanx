<?php

namespace App\Repository;

use App\Models\Account;

/**
 * Class AccountRepository
 *
 * Repositório para interagir com o modelo Account.
 */
class AccountRepository 
{
    /**
     * Instância do modelo Account.
     *
     * @var Account
     */
    protected $account;

    /**
     * Cria uma nova instância do AccountRepository.
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Cria uma nova conta no banco de dados.
     *
     * @param array $data
     * @return Account
     */
    public function create(array $data): Account
    {
        return $this->account->create($data);
    }

    /**
     * Recupera uma conta pelo ID.
     *
     * @param int $id O ID da conta.
     * @return Account|null A instância da conta ou null se não encontrada.
     */
    public function getById(int $id): ?Account
    {
        return $this->account->find($id);
    }

    /**
     * Obtém uma conta pelo número da conta.
     *
     * @param string $number
     * @return Account|null
     */
    public function getByNumber(string $number): ?Account
    {
        return $this->account->where('account_number', $number)->first();
    }

    /**
     * Atualiza o saldo de uma conta.
     *
     * @param string $accountNumber O número da conta.
     * @param float $newBalance O novo saldo.
     * @return bool Se a operação foi bem-sucedida.
     */
    public function updateBalance(string $accountNumber, float $newBalance): bool
    {
        return $this->account->where('account_number', $accountNumber)->update(['balance' => $newBalance]);
    }
}
