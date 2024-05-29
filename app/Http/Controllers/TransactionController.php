<?php

namespace App\Http\Controllers;

use App\Repository\TransactionRepository;
use App\Repository\AccountRepository;
use App\Models\PaymentType;
use App\Validators\TransactionValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class TransactionController
 *
 * Este controller lida com solicitações HTTP relacionadas a transações.
 */
class TransactionController extends Controller
{
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * TransactionController constructor.
     *
     * @param TransactionRepository $transactionRepository
     * @param AccountRepository $accountRepository
     */
    public function __construct(TransactionRepository $transactionRepository, AccountRepository $accountRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
    }

    /**
     * Armazena uma nova transação.
     *
     * @param Request $request A solicitação contendo os dados da transação.
     * @return JsonResponse A resposta JSON.
     *
     * @route POST /transaction
     * @input JSON {"payment_type": "D", "account_number": 234, "value": 10}
     * @output HTTP STATUS 201 / JSON {"account_number": 234, "balance": 170.07}
     * @output HTTP STATUS 404 (Caso não tenha saldo disponível)
     */
    public function store(Request $request): JsonResponse
    {
        // Validação dos dados recebidos na solicitação
        $validator = TransactionValidator::validate($request->all());

        // Se a validação falhar, retorna uma resposta com os erros
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only(['payment_type', 'account_number', 'value']);

        // Recupera a conta pelo número
        $account = $this->accountRepository->getByNumber($data['account_number']);

        // Verifica se a conta existe
        if (!$account) {
            return response()->json(['error' => 'Conta não encontrada'], 404);
        }

        // Recupera o tipo de pagamento pelo código
        $paymentType = PaymentType::where('code', $data['payment_type'])->first();

        if (!$paymentType) {
            return response()->json(['error' => 'Tipo de pagamento inválido'], 422);
        }

        // Calcula o valor com a taxa
        $valueWithFee = $this->calculateValueWithFee($data['value'], $paymentType->fee);

        // Verifica se a conta tem saldo suficiente
        if (!$this->hasSufficientBalance($account->balance, $valueWithFee)) {
            return response()->json(['error' => 'Saldo insuficiente'], 400);
        }

        // Prepara os dados da transação com account_id e payment_type_id
        $transactionData = $this->prepareTransactionData($data, $account, $paymentType);

        // Cria a transação
        $transaction = $this->transactionRepository->create($transactionData);

        // Verifica se a transação foi criada com sucesso
        if (!$transaction) {
            return response()->json(['error' => 'Não foi possível criar a transação'], 500);
        }

        // Atualiza o saldo da conta
        $newBalance = $this->updateAccountBalance($account, $valueWithFee);

        // Formata a resposta
        $response = $this->formatResponse($account->account_number, $newBalance);

        // Retorna a resposta JSON com a transação criada e o status HTTP 201 (Criado)
        return response()->json($response, 201);
    }

    /**
     * Calcula o valor com a taxa baseada no tipo de pagamento.
     *
     * @param float $value O valor original.
     * @param float $fee A taxa aplicada.
     * @return float O valor com a taxa aplicada.
     */
    private function calculateValueWithFee(float $value, float $fee): float
    {
        return $value + ($value * ($fee / 100));
    }

    /**
     * Verifica se o saldo da conta é suficiente.
     *
     * @param float $balance O saldo da conta.
     * @param float $valueWithFee O valor com a taxa aplicada.
     * @return bool True se o saldo é suficiente, False caso contrário.
     */
    private function hasSufficientBalance(float $balance, float $valueWithFee): bool
    {
        return $balance >= $valueWithFee;
    }

    /**
     * Prepara os dados da transação para criação.
     *
     * @param array $data Os dados da solicitação.
     * @param \App\Models\Account $account A instância da conta.
     * @param \App\Models\PaymentType $paymentType A instância do tipo de pagamento.
     * @return array Os dados preparados para criação da transação.
     */
    private function prepareTransactionData(array $data, $account, $paymentType): array
    {
        return [
            'account_id' => $account->id,
            'payment_type_id' => $paymentType->id,
            'value' => $data['value'],
        ];
    }

    /**
     * Atualiza o saldo da conta.
     *
     * @param \App\Models\Account $account A instância da conta.
     * @param float $valueWithFee O valor com a taxa aplicada.
     * @return float O novo saldo da conta.
     */
    private function updateAccountBalance($account, float $valueWithFee): float
    {
        $newBalance = $account->balance - $valueWithFee;
        $this->accountRepository->updateBalance($account->account_number, $newBalance);
        return $newBalance;
    }

    /**
     * Formata a resposta para a solicitação de criação de transação.
     *
     * @param int $accountNumber O número da conta.
     * @param float $newBalance O novo saldo da conta.
     * @return array A resposta formatada.
     */
    private function formatResponse(int $accountNumber, float $newBalance): array
    {
        return [
            'account_number' => $accountNumber,
            'balance' => $newBalance,
        ];
    }
}
