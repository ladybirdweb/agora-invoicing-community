<?php

namespace Tests\Unit\Common;

use Tests\TestCase;

class SocialMediaControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_validation_socialmedia()
    {
        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        $rules =
            [
                'name' => 'required|unique:social_media',
                'link' => 'required|url|regex:'.$regex,
            ];

        $data = [
            'name' => 'Telegram',
            'link' => 'https://telegram.com',
        ];
        $v = $this->app['validator']->make($data, $rules);
        $this->assertTrue($v->passes());
    }
}
