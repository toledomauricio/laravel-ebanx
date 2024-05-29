<?php

namespace App\Http\Controllers;

use App\Repository\AccountRepository;
use App\Http\Requests\AccountRequest;
use App\Validators\AccountValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class AccountController
 *
 * Este controller lida com solicitações HTTP relacionadas a contas do Ebanx.
 */
class AccountController extends Controller
{
    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * AccountController constructor.
     *
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * Cria uma nova conta a partir dos dados inputados no request.
     *
     * @param AccountRequest $request
     * @return JsonResponse
     *
     * @route POST /account
     * @input JSON { "numero_conta": 234, "saldo": 180.37 }
     * @output HTTP STATUS 201 / JSON { "numero_conta": 234, "saldo": 180.37 }
     */
    public function create(Request $request): JsonResponse
    {
        // Validação dos dados recebidos na solicitação
        $validator = AccountValidator::validate($request->all());
    
        // Se a validação falhar, retorna uma resposta com os erros
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Se a validação passar, criar uma nova conta usando o repositório
        $account = $this->accountRepository->create([
            'account_number' => $request->account_number,
            'balance' => $request->balance
        ]);
    
        // Formatar a resposta
        $response = [
            'account_number' => $account->account_number,
            'balance' => $account->balance,
        ];
    
        // Retornar a resposta JSON com a nova conta e o status HTTP 201 (Criado)
        return response()->json($response, 201);
    }

    /**
     * Obtém uma conta pelo número da conta.
     *
     * @param string $number
     * @return JsonResponse
     *
     * @route GET /account/{account_number}
     */
    public function getByNumber(string $number): JsonResponse
    {
        // Obtém a conta pelo número usando o repositório
        $account = $this->accountRepository->getByNumber($number);

        // Verifica se a conta foi encontrada
        if ($account) {
            // Retorna a resposta JSON com a conta e o status HTTP 200 (OK)
            return response()->json([
                'account_number' => $account->account_number,
                'balance' => $account->balance,
            ], 200);
        } else {
            // Retorna uma resposta JSON vazia com o status HTTP 404 (Não Encontrado)
            return response()->json([], 404);
        }
    }
}
