<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

/**
 * Class TransactionValidator
 *
 * Validações para as transações.
 */
class TransactionValidator
{
    /**
     * Valida os dados da transação.
     *
     * @param array $data Os dados da transação a serem validados.
     * @return \Illuminate\Contracts\Validation\Validator O validador com os resultados da validação.
     */
    public static function validate(array $data)
    {
        return Validator::make($data, [
            'payment_type' => 'required|string|in:P,C,D',
            'account_number' => 'required|integer',
            'value' => 'required|numeric|min:0.01',
        ], [
            'payment_type.required' => 'A forma de pagamento é obrigatória.',
            'payment_type.string' => 'A forma de pagamento deve ser uma string.',
            'payment_type.in' => 'A forma de pagamento deve ser P, C ou D.',
            'account_number.required' => 'O número da conta é obrigatório.',
            'account_number.integer' => 'O número da conta deve ser um número inteiro.',
            'value.required' => 'O valor é obrigatório.',
            'value.numeric' => 'O valor deve ser um número.',
            'value.min' => 'O valor deve ser pelo menos :min.',
        ]);
    }
}
