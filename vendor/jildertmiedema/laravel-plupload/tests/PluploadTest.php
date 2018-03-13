<?php

use JildertMiedema\LaravelPlupload\Plupload;
use Mockery as m;

class PluploadTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetDefaultView()
    {
        $config = m::mock(\Illuminate\Contracts\Config\Repository::class);

        $config->shouldReceive('get')->with('laravel-plupload::plupload.view')->once()->andReturn('default-view');

        $plupload = new Plupload($config);

        $this->assertEquals('default-view', $plupload->getDefaultView());
    }
}
