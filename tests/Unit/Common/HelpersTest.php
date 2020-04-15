<?php


namespace Tests\Unit\Common;


use Carbon\Carbon;
use Tests\DBTestCase;
use Cache;

class HelpersTest extends DBTestCase
{
    public function test_getTimeInLoggedInUserTimeZone_whenUserTimezoneIsPresent_shouldConsiderTimezoneAsUserTimezone()
    {
        $this->getLoggedInUser("admin");
        $this->user->timezone()->updateOrCreate(["name"=>"Asia/Kolkata"]);
        $this->assertEquals("Jan 1, 2001, 5:30 am", getTimeInLoggedInUserTimeZone(Carbon::now()->startOfMillennium()));
    }

    public function test_getTimeInLoggedInUserTimeZone_cachesUserTimezoneForFiveSeconds()
    {
        $this->getLoggedInUser("admin");

        Cache::shouldReceive('remember')->once()->withArgs(["timezone_".$this->user->id, 5, \Closure::class])->andReturn("Asia/Kolkata");

        getTimeInLoggedInUserTimeZone(Carbon::now()->startOfMillennium());
    }
}