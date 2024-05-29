<?php

namespace Database\Factories;

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTypeFactory extends Factory
{
    /**
     * O modelo associado à fábrica.
     *
     * @var string
     */
    protected $model = PaymentType::class;

    /**
     * Define os atributos padrão do modelo.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->word(); // Gerar um nome único para cada forma de pagamento
        
        $prefix = 'test_'; // Prefixo para o nome
        $uniqueId = uniqid(); // Gera um ID único
        $name = $prefix . $uniqueId; // Combinação de prefixo e ID único

        return [
            'name' => "test_{$name}",
            'fee' => $this->faker->randomFloat(2, 0, 10), // Gerar uma taxa aleatória entre 0 e 10
            'code' => $this->faker->randomElement(['P', 'C', 'D']), // Gerar um código aleatório entre 'P', 'C' e 'D'
        ];
    }
}
