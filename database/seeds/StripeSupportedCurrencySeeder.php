<?php

namespace database\seeds;

use App\Plugins\Stripe\Model\Stripe;
use Illuminate\Database\Seeder;

class StripeSupportedCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Stripe::create([
		'supported_currencies'             => 'USD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'AED',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'AFN',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'ALL',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'AMD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'ANG',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'AOA',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'ARS',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'AUD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'AWG',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'AZN',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'BAM',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'BBD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'BDT',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'BGN',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'BIF',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'BMD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'BND',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'BOB',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'BRL',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'BSD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'BWP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'BZD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'CAD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'CDF',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'CHF',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'CLP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'CNY',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'COP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'CRC',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'CVE',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'CZK',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'DJF',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'DKK',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'DOP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'DZD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'EGP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'ETB',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'EUR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'FJD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'FKP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'GBP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'GEL',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'GIP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'GMD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'GNF',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'GTQ',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'GYD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'HKD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'HNL',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'HRK',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'HTG',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'HUF',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'IDR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'ILS',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'INR',
		'base_currency'               => 'USD',
		'processing_fee'               => '1',
		]);

         Stripe::create([
		'supported_currencies'             => 'ISK',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'JMD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'JPY',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'KES',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'KGS',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'KHR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'KMF',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'KRW',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'KYD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'KZT',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'LAK',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'LBP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'ZMW',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'ZAR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'YER',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'XPF',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'XOF',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'XCD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'XAF',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'WST',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'VUV',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'VND',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'UZS',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'UYU',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'UGX',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'UAH',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'TZS',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'TWD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'TTD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

    
         Stripe::create([
		'supported_currencies'             => 'TOP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'TJS',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'THB',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'SZL',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'STD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'SRD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'SOS',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'SLL',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'SHP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'SGD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'SEK',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'SCR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'SBD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'SAR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'RWF',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'RUB',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'RSD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'RON',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'QAR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'PYG',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'PLN',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'PKR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'PHP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'PGK',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'PEN',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'PAB',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'NZD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'NPR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'NOK',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'NIO',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'NGN',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'NAD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MZN',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MYR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MXN',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MWK',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MVR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MUR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MRO',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MOP',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MNT',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MMK',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MKD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MGA',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

         Stripe::create([
		'supported_currencies'             => 'MDL',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

          Stripe::create([
		'supported_currencies'             => 'MAD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

           Stripe::create([
		'supported_currencies'             => 'LSL',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

            Stripe::create([
		'supported_currencies'             => 'LRD',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

             Stripe::create([
		'supported_currencies'             => 'LKR',
		'base_currency'               => 'USD',
		'processing_fee'               => '5',
		]);

    }
}
