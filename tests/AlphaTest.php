<?php

class AlphaTest extends TestCase
{
    public function testDisplaysAlpha()
    {
        $this->visit('/alpha')
             ->see('Alpha')
             ->dontSee('Beta');
    }
}
