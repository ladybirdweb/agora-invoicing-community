<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\BundleRequest;
use App\Model\Product\Product;
use App\Model\Product\ProductBundle;
use App\Model\Product\ProductBundleRelation;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public $product;
    public $bundle;
    public $relation;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

        $product = new Product();
        $this->product = $product;

        $bundle = new ProductBundle();
        $this->bundle = $bundle;

        $relation = new ProductBundleRelation();
        $this->relation = $relation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            return view('themes.default1.product.bundle.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetBundles()
    {
        return \Datatable::collection($this->bundle->select('id', 'name', 'valid_from', 'valid_till', 'uses', 'maximum_uses')->get())
                        ->addColumn('#', function ($model) {
                            return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
                        })
                        ->showColumns('name', 'valid_from', 'valid_till', 'uses', 'maximum_uses')
                        ->addColumn('item', function ($model) {
                            $name = $this->relation->where('bundle_id', $model->id)->pluck('product_id');
                            //dd($name);
                            $result = [];
                            foreach ($name as $key => $id) {
                                $result[$key] = (string) $this->product->where('id', $id)->first()->name;
                            }
                            //dd($result);
                            return implode(',', $result);
                        })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('bundles/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
                        })
                        ->searchColumns('name', 'item')
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
            $products = $this->product->lists('name', 'id')->toArray();

            return view('themes.default1.product.bundle.create', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(BundleRequest $request)
    {
        try {
            $this->bundle->fill($request->input())->save();
            $items = $request->input('items');
            if (is_array($items) && !empty($items)) {
                foreach ($items as $item) {
                    $this->relation->create(['product_id' => $item, 'bundle_id' => $this->bundle->id]);
                }
            }

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (Exception $e) {
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
        //
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
            $bundle = $this->bundle->where('id', $id)->first();
            $products = $this->product->lists('name', 'id')->toArray();
            $relation = $this->relation->where('bundle_id', $id)->lists('product_id', 'product_id')->toArray();
            if ($bundle->valid_till != 0) {
                $date = new \DateTime($bundle->valid_till);
                $till = \Carbon\Carbon::createFromFormat('d/m/Y', $date->format('d/m/Y'));
            } else {
                $till = null;
            }
            if ($bundle->valid_from != 0) {
                $date2 = new \DateTime($bundle->valid_from);
                $from = \Carbon\Carbon::createFromFormat('d/m/Y', $date2->format('d/m/Y'));
            } else {
                $from = null;
            }

            return view('themes.default1.product.bundle.edit', compact('products', 'bundle', 'relation', 'till', 'from'));
        } catch (Exception $e) {
            return redirect()->back()->with('fails', $ex->getMessage());
        } catch (\InvalidArgumentException $e) {
            if ($e->getMessage() == 'Unexpected data found.') {
                $till = null;
                $from = null;

                return view('themes.default1.product.bundle.edit', compact('products', 'bundle', 'relation', 'till', 'from'));
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id, BundleRequest $request)
    {
        // dd($request);
        try {
            $bundle = $this->bundle->where('id', $id)->first();
            $bundle->fill($request->input())->save();

            $items = $request->input('items');
            if (is_array($items) && !empty($items)) {
                $delete = $this->relation->where('bundle_id', $id)->get();
                foreach ($delete as $del) {
                    $del->delete();
                }

                foreach ($items as $item) {
                    $this->relation->create(['product_id' => $item, 'bundle_id' => $bundle->id]);
                }
            }

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (Exception $e) {
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
                    $bundle = $this->bundle->where('id', $id)->first();
                    if ($bundle) {
                        $bundle->delete();
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
