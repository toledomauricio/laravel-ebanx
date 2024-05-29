<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class AccountValidator
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'account_number' => 'required|integer|unique:accounts,account_number',
            'balance' => 'required|numeric|min:0',
        ], self::messages());

        return $validator;
    }

    private static function messages()
    {
        return [
            'account_number.required' => 'O número da conta é obrigatório.',
            'account_number.integer' => 'O número da conta deve ser um número inteiro.',
            'account_number.unique' => 'O número da conta já está em uso.',
            'balance.required' => 'O balance é obrigatório.',
            'balance.numeric' => 'O balance deve ser um número válido.',
            'balance.min' => 'O balance deve ser pelo maior ou igual a :min.',
        ];
    }
}
