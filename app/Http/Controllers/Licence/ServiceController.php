<?php

namespace App\Http\Controllers\Licence;

use App\Http\Controllers\Controller;
use App\Model\Product\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public $service;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

        $service = new Service();
        $this->service = $service;
    }

    public function index()
    {
        try {
            return view('themes.default1.services.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetServices()
    {
        return \Datatable::collection($this->service->get())
                        ->showColumns('name')
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('services/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
                        })
                        ->searchColumns('name')
                        ->orderColumns('name')
                        ->make();
    }

    public function create()
    {
        try {
            return view('themes.default1.services.create');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->service->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $service = $this->service->where('id', $id)->first();

            return view('themes.default1.services.edit', compact('service'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $service = $this->service->where('id', $id)->first();
            $service->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
