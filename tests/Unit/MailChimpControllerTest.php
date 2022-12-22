<?php

namespace Tests;

use App\Http\Controllers\Common\MailChimpController;
use Illuminate\Http\Request;

class MailChimpControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_addSubscriberByClientPanel_throwexception_existingmember()
    {
        $email = 'sowmisowmi@gmail.com';
        $response = (new MailChimpController())->addSubscriberByClientPanel(new Request(['email' => $email]));
        // $this->assertEquals('Member already exists',  $response['data']['message']);
        $this->assertEquals(json_decode($response->content())->message, 'Member already exists');
    }
}
