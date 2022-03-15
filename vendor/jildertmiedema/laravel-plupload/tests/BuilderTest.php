<?php

use JildertMiedema\LaravelPlupload\Builder;
use Mockery as m;

class BuilderTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetContainer()
    {
        $plupload = m::mock('JildertMiedema\LaravelPlupload\Plupload');

        $builder = new Builder($plupload);
        $builder->setPrefix('test');

        $container = $builder->getContainer();

        $this->assertEquals('<div id="test-container"><button type="button" id="test-browse-button" class="btn btn-primary">Browse...</button><button type="button" id="test-start-upload" class="btn btn-success">Upload</button></div>', $container);
    }

    public function testAddScripts()
    {
        $plupload = m::mock('JildertMiedema\LaravelPlupload\Plupload');

        $builder = new Builder($plupload);

        $result = $builder->addScripts();

        $this->assertEquals('<script type="text/javascript" src="/vendor/jildertmiedema/laravel-plupload/js/plupload.full.min.js"></script>', $result);
    }

    public function testCreateJsInit()
    {
        $plupload = m::mock('JildertMiedema\LaravelPlupload\Plupload');

        $builder = new Builder($plupload);
        $builder->setPrefix('test');

        $result = $builder->createJsInit();

        $this->assertContains('var test_uploader = new plupload.Uploader({', $result);
    }

    public function testCreateJsRun()
    {
        $plupload = m::mock('JildertMiedema\LaravelPlupload\Plupload');

        $builder = new Builder($plupload);
        $builder->setPrefix('test');

        $result = $builder->createJsRun();

        $this->assertEquals('test_uploader.init();document.getElementById(\'test-start-upload\').onclick = function() {test_uploader.start();};', $result);
    }

    public function testGetSettings()
    {
        $plupload = m::mock('JildertMiedema\LaravelPlupload\Plupload');

        $builder = new Builder($plupload);

        $result = $builder->getSettings();

        $this->assertTrue(is_array($result));
    }

    public function testUpdateSettings()
    {
        $plupload = m::mock('JildertMiedema\LaravelPlupload\Plupload');

        $builder = new Builder($plupload);
        $builder->updateSettings(['a' => 'b']);

        $result = $builder->getSettings();

        $this->assertEquals('b', $result['a']);
    }

    public function testSetPrefix()
    {
        $plupload = m::mock('JildertMiedema\LaravelPlupload\Plupload');

        $builder = new Builder($plupload);
        $builder->setPrefix('abcd');

        $result = $builder->getSettings();

        $this->assertEquals('abcd-browse-button', $result['browse_button']);
        $this->assertEquals('abcd-container', $result['container']);
    }
}
