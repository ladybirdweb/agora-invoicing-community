<?php

namespace Database\Factories\Model\Payment;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Model\Payment\Plan;

class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Helpdesk Advance',
            'allow_tax' => '1',
            'days' => 365,
        ];
    }
}
