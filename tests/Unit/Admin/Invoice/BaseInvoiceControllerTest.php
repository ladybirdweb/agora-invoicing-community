<?php

namespace Tests\Unit\Admin\Invoice;

use PHPUnit\Framework\TestCase;
use Tests\DBTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\Order\BaseInvoiceController;

class BaseInvoiceControllerTest extends DBTestCase
{

	use DatabaseTransactions;

	public function setUp(): void
    {
        parent::setUp();
        $this->classObject = new BaseInvoiceController();
    }
   
     /** @group baseinvoicecontroller */
    public function test_calculateTotal_calculateTotalAfterApplyingRateWhenInclusiveOfTax_returnsPriceAfterAddingTax()
	{
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $price = $this->classObject->calculateTotal('10%','1000');
        $this->assertEquals($price,'1100');
    }

    /** @group baseinvoicecontroller */
    public function test_calculateTotal_calculateTotalAfterApplyingRateWhenExclusiveOfTax_returnsPriceWithoutTax()
	{
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $tax_rule = new \App\Model\Payment\TaxOption();
        $rule = $tax_rule->findOrFail(1)->update(['inclusive'=>1]);
        $price = $this->classObject->calculateTotal('10%','1000');
        $this->assertEquals($price,'1000');
    }
}
