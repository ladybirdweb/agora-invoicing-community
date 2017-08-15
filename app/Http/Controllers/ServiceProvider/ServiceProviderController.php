<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Model\licence\Licence;
use App\Model\Licence\LicencedOrganization;
use App\Model\licence\Sla;
use App\Model\licence\SlaServiceRelation;
use App\Model\Product\Service;
use App\Organization;
use Illuminate\Http\Request;

class ServiceProviderController extends Controller
{
    public $slaServiceRelation;
    public $service;
    public $licence;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('service.provider');

        $cart = new \App\Http\Controllers\Front\CheckoutController();
        $auth = ''; //$cart->GetXdeskAuthOrganization();
        $this->org = $auth;

        $sla = new Sla();
        $this->sla = $sla;

        $LicencedOrg = new LicencedOrganization();
        $this->LicencedOrg = $LicencedOrg;

        $slaServiceRelation = new SlaServiceRelation();
        $this->slaServiceRelation = $slaServiceRelation;

        $service = new Service();
        $this->service = $service;

        $organization = new Organization();
        $this->organization = $organization;

        $licence = new Licence();
        $this->licence = $licence;
    }

    public function Orders()
    {
        try {
            return view('themes.default1.serviceprovider.orders');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function Sla()
    {
        try {
            return view('themes.default1.serviceprovider.sla');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function Pricing()
    {
        try {
            $licence = new \App\Model\licence\Licence();
            $licences = $licence->get();

            return view('themes.default1.serviceprovider.pricing', compact('licences'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetOrders()
    {
        return \Datatable::collection($this->LicencedOrg->where('organization_id', $this->org->id)->get())

                        ->showColumns('licence_name', 'licence_description', 'number_of_slas', 'price', 'payment_status')
                        ->searchColumns('licence_name')
                        ->orderColumns('licence_name')
                        ->make();
    }

    public function GetSlas()
    {
        return \Datatable::collection($this->sla->where('service_provider_id', $this->org->id)->get())
                        ->addColumn('licence_id', function ($model) {
                            $licence_name = $this->licence->where('id', $model->licence_id)->first()->name;

                            return $licence_name;
                        })
                        ->showColumns('name', 'description')
                        ->addColumn('service', function ($model) {
                            $serviceid = $this->slaServiceRelation->where('sla_id', $model->id)->first()->service_id;

                            return $this->service->where('id', $serviceid)->first()->name;
                        })
                        ->addColumn('organization_id', function ($model) {
                            $name = $this->organization->where('id', $model->organization_id)->where('type', 'client')->first()->name;

                            return $name;
                        })

                        ->showColumns('start_date', 'end_date', 'grace_period')
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('slas/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
                        })
                        ->searchColumns('name')
                        ->orderColumns('name')
                        ->make();
    }

    public function AddtoCart($id, Request $request)
    {
        try {
            $licence = new \App\Model\licence\Licence();
            $item = $licence->where('id', $id)->first();
            //dd($item);
            $order = $this->LicencedOrg->create([
                'organization_id'     => $this->org->id,
                'licence_name'        => $item->name,
                'licence_description' => $item->description,
                'number_of_slas'      => $item->number_of_sla,
                'price'               => $item->price,

                ]);

            //dd($request);
            \Event::fire(new \App\Events\SmsIntegration($request));
            //\Event::fire(new \App\Events\PaymentGateway(['request' => $request, 'cart' => [], 'order' => $order]));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
