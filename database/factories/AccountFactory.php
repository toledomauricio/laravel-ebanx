<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * O modelo associado à fábrica.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define os atributos padrão do modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'account_number' => $this->faker->unique()->randomNumber(6),
            'balance' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
