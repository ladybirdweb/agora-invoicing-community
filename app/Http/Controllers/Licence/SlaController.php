<?php

namespace App\Http\Controllers\Licence;

use App\Http\Controllers\Controller;
use App\Model\licence\Licence;
use App\Model\Licence\LicencedOrganization;
use App\Model\licence\Sla;
use App\Model\licence\SlaServiceRelation;
use App\Model\Product\Service;
use App\Organization;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    public $sla;
    public $slaServiceRelation;
    public $service;
    public $licence;
    public $organization;
    public $licencedOrganization;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('service.provider', ['only' => ['create', 'store', 'edit', 'update']]);
        //$this->middleware('admin');
        $cart = new \App\Http\Controllers\Front\CheckoutController();
        $auth = ''; //$cart->GetXdeskAuthOrganization();
        $this->org = $auth;

        $sla = new Sla();
        $this->sla = $sla;

        $slaServiceRelation = new SlaServiceRelation();
        $this->slaServiceRelation = $slaServiceRelation;

        $service = new Service();
        $this->service = $service;

        $licence = new Licence();
        $this->licence = $licence;

        $organization = new Organization();
        $this->organization = $organization;

        $licencedOrganization = new LicencedOrganization();
        $this->licencedOrganization = $licencedOrganization;
    }

    public function index()
    {
        try {
            return view('themes.default1.sla.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $service = $this->slaServiceRelation->where('sla_id', $id)->first();
            $service->delete();

            $service_id = $request->input('service');
            $sla = $this->sla->where('id', $id)->first();
            $sla->fill($request->input())->save();
            $this->slaServiceRelation->create(['sla_id' => $id, 'service_id' => $service_id]);

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
