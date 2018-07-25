<?php

namespace App\Http\Controllers\Licence;

use App\Http\Controllers\Controller;
use App\Model\Licence\LicencedOrganization;
use App\Organization;
use Illuminate\Http\Request;

class LicenceOrderController extends Controller
{
    public $LicencedOrg;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

        $licenced = new LicencedOrganization();
        $this->LicencedOrg = $licenced;

        $organization = new Organization();
        $this->org = $organization;
    }

    public function index()
    {
        try {
            return view('themes.default1.licence.order.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

}
