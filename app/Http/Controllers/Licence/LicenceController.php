<?php

namespace App\Http\Controllers\Licence;

use App\Http\Controllers\Controller;
use App\Model\licence\Licence;
use App\Model\Licence\LicencedOrganization;
use Illuminate\Http\Request;

class LicenceController extends Controller
{
    public $licence;
    public $licencedOrganization;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

        $licence = new Licence();
        $this->licence = $licence;

        $licencedOrganization = new LicencedOrganization();
        $this->licencedOrganization = $licencedOrganization;
    }

    public function index()
    {
        try {
            return view('themes.default1.licence.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetLicences()
    {
        return \Datatable::collection($this->licence->get())
                        ->showColumns('name', 'description', 'number_of_sla', 'price')
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('licences/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
                        })
                        ->searchColumns('description')
                        ->orderColumns('description')
                        ->make();
    }

    public function create()
    {
        try {
            $productController = new \App\Http\Controllers\Product\ProductController();
            $url = $productController->GetMyUrl();
            $i = $this->licence->orderBy('created_at', 'desc')->first()->id + 1;
            $cartUrl = $url.'/cart?id='.$i;

            return view('themes.default1.licence.create', compact('cartUrl'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->licence->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $licence = $this->licence->where('id', $id)->first();

            return view('themes.default1.licence.edit', compact('licence'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $licence = $this->licence->where('id', $id)->first();
            $licence->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
