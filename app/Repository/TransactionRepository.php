<?php

namespace App\Repository;

use App\Models\Transaction;

/**
 * Class TransactionRepository
 *
 * Repositório para interagir com o modelo Transaction.
 */
class TransactionRepository 
{
    /**
     * Instância do modelo Transaction.
     *
     * @var Transaction
     */
    protected $transaction;

    /**
     * Cria uma nova instância do TransactionRepository.
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Cria uma nova transação.
     *
     * @param array $data Os dados da transação a serem criados.
     * @return Transaction A instância da transação criada.
     */
    public function create(array $data): Transaction
    {
        return $this->transaction->create($data);
    }

    /**
     * Recupera uma transação pelo ID.
     *
     * @param int $id O ID da transação.
     * @return Transaction|null A instância da transação ou null se não encontrada.
     */
    public function getById(int $id): ?Transaction
    {
        return $this->transaction->find($id);
    }
}
