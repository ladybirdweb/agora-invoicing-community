<?php

namespace Database\Factories\Model\Payment;

use App\Model\Payment\PlanPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanPriceFactory extends Factory
{
    protected $model = PlanPrice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'currency' => 'USD',
            'add_price' => 500000,
            'renew_price' => 25000,
            'product_quantity' => 1,
            'no_of_agents' => 1
        ];
    }
}
