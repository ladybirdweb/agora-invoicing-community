<?php

namespace Tests\Unit\Common;

use Cache;
use Carbon\Carbon;
use Tests\DBTestCase;

class HelpersTest extends DBTestCase
{
    public function test_getTimeInLoggedInUserTimeZone_whenUserTimezoneIsPresent_shouldConsiderTimezoneAsUserTimezone()
    {
        $this->getLoggedInUser('admin');
        $this->user->timezone()->updateOrCreate(['name'=>'Asia/Kolkata']);
        $this->assertEquals('Jan 1, 2001, 5:30 am', getTimeInLoggedInUserTimeZone(Carbon::now()->startOfMillennium()));
    }

    public function test_getTimeInLoggedInUserTimeZone_cachesUserTimezoneForFiveSeconds()
    {
        $this->getLoggedInUser('admin');

        Cache::shouldReceive('remember')->once()->withArgs(['timezone_'.$this->user->id, 5, \Closure::class])->andReturn('Asia/Kolkata');

        getTimeInLoggedInUserTimeZone(Carbon::now()->startOfMillennium());
    }

    public function test_getDateHtml_whenDateTimeStringIsPassedAsNull_shouldReturnDash()
    {
        $this->getLoggedInUser('admin');

        $this->assertEquals('--', getDateHtml(null));
    }

    public function test_getDateHtml_whenValidDateTimeStringIsPassedAsNull_shouldReturnFormattedDateInHTMLForm()
    {
        $this->getLoggedInUser('admin');

        $now = Carbon::now();

        $expectedDateTime = $now->clone()->setTimezone('Asia/Kolkata')->format('M j, Y, g:i a');
        $expectedDate = $now->clone()->setTimezone('Asia/Kolkata')->format('M j, Y');
        $this->assertEquals("<label data-toggle='tooltip' style='font-weight:500;' data-placement='top' title='$expectedDateTime'>$expectedDate</label>", getDateHtml($now->toDateTimeString()));
    }

    public function test_getDateHtml_whenAnInValidAndNonEmptyDateIsPassed_shouldGiveReturnEmptyDate()
    {
        $this->getLoggedInUser('admin');

        $this->assertEquals('--', getDateHtml('invalid_format'));
    }
}
