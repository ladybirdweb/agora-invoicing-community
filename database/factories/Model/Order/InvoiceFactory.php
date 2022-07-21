<?php

namespace Database\Factories\Model\Order;

use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => '2344353',
            'grand_total' => 10000,
            'currency' => 'INR',
            'status' => 'success',
        ];
    }
}
