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

    public function GetSlas()
    {
        return \Datatable::collection($this->sla->get())
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
                        ->addColumn('service_provider_id', function ($model) {
                            $name = $this->organization->where('id', $model->service_provider_id)->where('type', 'service_provider')->first()->name;

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

    public function create()
    {
        try {
            //dd($this->org);
            $organizations = $this->organization->where('type', 'client')->lists('name', 'id')->toArray();
            if ($this->org->type == 'xdesk') {
                $licences = $this->licence->lists('name', 'id')->toArray();
                $serviceProviders = $this->organization->where('type', 'service_provider')->lists('name', 'id')->toArray();
            } else {
                $purchased = $this->licencedOrganization->where('organization_id', $this->org->id)->where('payment_status', 'success')->distinct('licence_name')->lists('licence_name');
                if (count($purchased) == 0) {
                    return redirect()->back()->with('fails', 'No licence to create SLA');
                }
                $licences = $this->licence->whereIn('name', $purchased)->lists('name', 'id')->toArray();
                $serviceProviders = [$this->org->id => $this->org->name];
            }
            $services = $this->service->lists('name', 'id')->toArray();

            return view('themes.default1.sla.create', compact('organizations', 'serviceProviders', 'services', 'licences'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            if ($this->org->type == 'xdesk') {
                $serviceprovider_id = $request->input('service_provider_id');
            } else {
                $serviceprovider_id = $this->org->id;
            }
            $service_id = $request->input('service');

            $serviceprovider = $this->organization->where('id', $serviceprovider_id)->where('type', 'service_provider')->first();
            $licenced = $this->licencedOrganization->where('organization_id', $serviceprovider->id)->lists('number_of_slas', 'licence_name')->toArray();
            //dd($licenced);
            //check if service provider purchased licence
            if (count($licenced) == 0) {
                return redirect()->back()->with('fails', 'No Licence');
            }
            $CountOfSlaCanCreate = array_sum($licenced);
            //dd($CountOfSlaCanCreate);
            $CountOfSlaCreated = $this->sla->where('service_provider_id', $serviceprovider->id)->count();

            //check if service provider has enough licence to create slas OR it is unlimited licence
            if ($CountOfSlaCanCreate > $CountOfSlaCreated || in_array('0', $licenced)) {
                $this->sla->fill($request->input())->save();
                $this->slaServiceRelation->create(['sla_id' => $this->sla->id, 'service_id' => $service_id]);

                return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
            } else {
                return redirect()->back()->with('fails', 'Licence Over to create Slas ! ');
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $organizations = $this->organization->where('type', 'client')->lists('name', 'id')->toArray();
            if ($this->org->type == 'xdesk') {
                $licences = $this->licence->lists('name', 'id')->toArray();
                $serviceProviders = $this->organization->where('type', 'service_provider')->lists('name', 'id')->toArray();
            } else {
                $purchased = $this->licencedOrganization->where('organization_id', $this->org->id)->where('payment_status', 'success')->distinct('licence_name')->lists('licence_name');
                if (count($purchased) == 0) {
                    return redirect()->back()->with('fails', 'No licence to create SLA');
                }
                $licences = $this->licence->whereIn('name', $purchased)->lists('name', 'id')->toArray();
                $serviceProviders = $this->organization->where('type', 'service_provider')->where('id', $this->org->id)->lists('name', 'id')->toArray();
            }
            $services = $this->service->lists('name', 'id')->toArray();
            $sla = $this->sla->where('id', $id)->first();

            return view('themes.default1.sla.edit', compact('organizations', 'serviceProviders', 'services', 'sla', 'licences'));
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
