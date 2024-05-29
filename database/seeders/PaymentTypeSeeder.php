<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentType;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentTypes = [
            ['name' => 'Pix', 'code' => 'P', 'fee' => 0.00],
            ['name' => 'Cartão de Crédito', 'code' => 'C',  'fee' => 5.00],
            ['name' => 'Cartão de Débito', 'code' => 'D',  'fee' => 3.00],
        ];

        foreach ($paymentTypes as $type) {
            PaymentType::create($type);
        }
    }
}
