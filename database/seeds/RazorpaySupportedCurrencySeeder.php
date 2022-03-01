<?php

namespace database\seeds;

use Illuminate\Database\Seeder;
use App\Plugins\Razorpay\Model\RazorpayPayment;


class RazorpaySupportedCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RazorpayPayment::create([
            'currencies'             => 'INR',
            'base_currency'               => 'INR',
            'processing_fee'               => '0',
        ]);

        RazorpayPayment::create([
            'currencies'             => 'USD',
            'base_currency'               => 'INR',
            'processing_fee'               => '0',
        ]);

    }
}
