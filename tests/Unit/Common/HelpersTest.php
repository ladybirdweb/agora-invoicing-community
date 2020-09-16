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

    public function test_userCurrency_whenUserIsNotLoggedIn_returnsCurrencyAndSymbol()
    {
        $this->withoutMiddleware();
        $currency = userCurrency();
        $this->assertEquals($currency['currency'],'USD');
    }

    public function test_userCurrency_whenUserIsLoggedInAndRoleIsClient_returnsCurrencyAndSymbol()
    {
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $currency = userCurrency();
        $this->assertEquals($currency['currency'],'INR');
    }

    public function test_userCurrency_whenUserIsLoggedInAndRoleIsAdmin_returnsCurrencyAndSymbol()
    {
        $this->getLoggedInUser('admin');
        $this->withoutMiddleware();
        $currency = userCurrency($this->user->id);
        $this->assertEquals($currency['currency'],'INR');
    }

    public function test_rounding_whenRoundingIsOn_returnsRoundedOffPrice()
    {
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $price = rounding('999.90');
        $this->assertEquals($price,1000);
    }

    public function test_rounding_whenRoundingIsOff_returnsPriceUptoTwoDecimalPlace()
    {
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $tax_rule = new \App\Model\Payment\TaxOption();
        $rule = $tax_rule->findOrFail(1)->update(['rounding'=>0]);
        $price = rounding('999.6677777');
        $this->assertEquals($price,'999.67');
    }
}
