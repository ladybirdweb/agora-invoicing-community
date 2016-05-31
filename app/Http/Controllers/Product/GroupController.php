<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\GroupRequest;
use App\Model\Product\ConfigurableOption;
use App\Model\Product\GroupFeatures;
use App\Model\Product\ProductGroup;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public $group;
    public $feature;
    public $config;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

        $group = new ProductGroup();
        $this->group = $group;

        $feature = new GroupFeatures();
        $this->feature = $feature;

        $config = new ConfigurableOption();
        $this->config = $config;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            return view('themes.default1.product.group.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetGroups()
    {
        return \Datatable::collection($this->group->select('id', 'name')->where('id', '!=', 1)->get())
                        ->addColumn('#', function ($model) {
                            return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
                        })
                        ->showColumns('name')
                        ->addColumn('features', function ($model) {
                            $features = $this->feature->select('features')->where('group_id', $model->id)->get();
                            //dd($features);
                            $result = [];
                            foreach ($features as $key => $feature) {
                                //dd($feature);
                                $result[$key] = $feature->features;
                            }
                            //dd($result);
                            return implode(',', $result);
                        })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('groups/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
                        })
                        ->searchColumns('name')
                        ->orderColumns('name')
                        ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        try {
            return view('themes.default1.product.group.create');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(GroupRequest $request)
    {
        try {
            $this->group->fill($request->input())->save();

            $features = $request->input('features');
            foreach ($features as $feature) {
                $this->feature->create(['group_id' => $this->group->id, 'features' => $feature['name']]);
            }

            $values = $request->input('value');
            $prices = $request->input('price');
            $title = $request->input('title');
            $type = $request->input('type');

            for ($i = 0; $i < count($prices); $i++) {
                $this->config->create(['group_id' => $this->group->id, 'type' => $type, 'title' => $title, 'options' => $values[$i]['name'], 'price' => $prices[$i]['name']]);
            }

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $group = $this->group->where('id', $id)->first();
            $features = $this->feature->select('id', 'group_id', 'features')->where('group_id', $id)->get();
            $configs = $this->config->select('price', 'options')->where('group_id', $id)->get();
            $title = $this->config->where('group_id', $id)->first()->title;
            $type = $this->config->where('group_id', $id)->first()->type;

            return view('themes.default1.product.group.edit', compact('group', 'features', 'configs', 'title', 'type'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id, GroupRequest $request)
    {
        try {
            //dd($request);
            $group = $this->group->where('id', $id)->first();
            $group->fill($request->input())->save();
            /*
             * Features
             */
            $selects = $this->feature->where('group_id', $id)->get();
            if (!empty($selects)) {
                foreach ($selects as $select) {
                    if ($select) {
                        $select->delete();
                    }
                }
            }
            $features = $request->input('features');

            foreach ($features as $feature) {
                $this->feature->create(['group_id' => $group->id, 'features' => $feature['name']]);
            }
            /*
             * Configurations
             */

            $deletes = $this->config->where('group_id', $id)->get();
            if (!empty($deletes)) {
                foreach ($deletes as $delete) {
                    if ($delete) {
                        $delete->delete();
                    }
                }
            }

            $values = $request->input('value');
            $prices = $request->input('price');
            $title = $request->input('title');
            $type = $request->input('type');

            for ($i = 0; $i < count($prices); $i++) {
                $this->config->create(['group_id' => $group->id, 'type' => $type, 'title' => $title, 'options' => $values[$i]['name'], 'price' => $prices[$i]['name']]);
            }

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $group = $this->group->where('id', $id)->first();

                    if ($group) {
                        $group->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.no-record').'
                </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.deleted-successfully').'
                </div>';
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.select-a-row').'
                </div>';
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.$e->getMessage().'
                </div>';
        }
    }
}
