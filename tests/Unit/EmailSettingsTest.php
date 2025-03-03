<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\Email\EmailSettingRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;

class EmailSettingsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     */


    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_smtp_driver_correct_fields()
    {
        $data = ['driver' => 'smtp','password' => 'password'];
        $request = new EmailSettingRequest();
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
    }


}
