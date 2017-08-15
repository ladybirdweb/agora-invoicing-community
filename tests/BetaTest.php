<?php

class BetaTest extends TestCase
{
    public function testDisplaysBeta()
    {
        $this->visit('/beta')
             ->see('Beta')
             ->dontSee('Alpha');
    }
}
