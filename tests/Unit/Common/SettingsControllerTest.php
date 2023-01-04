<?php

namespace Tests\Unit\Common;

use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_validation_settings()
    {
        $rules = [
            'company' => 'required|max:35',
            'company_email' => 'required|email',
            'website' => 'required|url',
            'phone' => 'required',
            'address' => 'required',
            'state' => 'required',
            'country' => 'required',
            'default_currency' => 'required',
            'admin-logo' => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
            'fav-icon' => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
            'logo' => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
        ];
        $data = [
            'company' => 'Ladybird',
            'company_email' => 'demo@gmail.com',
            'website' => 'https://lws.com',
            'phone' => '9789909887',
            'address' => 'bangalore',
            'state' => 'karnataka',
            'default_currency' => 'USD',
            'country' => 'IN',
        ];
        $v = $this->app['validator']->make($data, $rules);
        $this->assertTrue($v->passes());
    }
}
