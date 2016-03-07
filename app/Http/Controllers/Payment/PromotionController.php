<?php

namespace App\Http\Controllers\Payment;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Payment\Promotion;
use App\Model\Product\Product;
use App\Http\Requests\Payment\PromotionRequest;
use App\Model\Payment\PromoProductRelation;
use App\Model\Payment\PromotionType;

class PromotionController extends Controller {

    public $promotion;
    public $product;
    public $promoRelation;
    public $type;

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');

        $promotion = new Promotion();
        $this->promotion = $promotion;

        $product = new Product();
        $this->product = $product;

        $promoRelation = new PromoProductRelation();
        $this->promoRelation = $promoRelation;

        $type = new PromotionType();
        $this->type = $type;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        try {
            return view('themes.default1.payment.promotion.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetPromotion() {
        return \Datatable::collection($this->promotion->select('code', 'type', 'id')->get())
                        ->addColumn('#', function($model) {
                            return "<input type='checkbox' value=" . $model->id . " name=select[] id=check>";
                        })
                        ->showColumns('code')
                        ->addColumn('type', function($model) {
                            return $this->type->where('id',$model->type)->first()->name;
                        })
                        ->addColumn('products', function($model) {
                            $selected = $this->promoRelation->select('product_id')->where('promotion_id', $model->id)->get();

                            foreach ($selected as $key => $select) {

                                $result[$key] = $this->product->where('id', $select->product_id)->first()->name;
                            }
                            if(!empty($result)){
                            return implode(',', $result);
                            }else{
                                return 'None';
                            }
                        })
                        ->addColumn('action', function($model) {
                            return "<a href=" . url('promotions/' . $model->id . '/edit') . " class='btn btn-sm btn-primary'>Edit</a>";
                        })
                        ->searchColumns('products')
                        ->orderColumns('code')
                        ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        try {
            $product = $this->product->lists('name', 'id')->toArray();
            $type = $this->type->lists('name', 'id')->toArray();
            return view('themes.default1.payment.promotion.create', compact('product', 'type'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PromotionRequest $request) {
        try {
            $promo = $this->promotion->fill($request->input())->save();
            //dd($this->promotion);
            $products = $request->input('applied');

            foreach ($products as $product) {
                $this->promoRelation->create(['product_id' => $product, 'promotion_id' => $this->promotion->id]);
            }

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        try {
            $promotion = $this->promotion->where('id', $id)->first();
            $product = $this->product->lists('name', 'id')->toArray();
            $type = $this->type->lists('name', 'id')->toArray();
            if ($promotion->start != 0) {
                $date = new \DateTime($promotion->start);
                $start = \Carbon\Carbon::createFromFormat('d/m/Y', $date->format('d/m/Y'));
            } else {
                $start = null;
            }
            if ($promotion->expiry != 0) {
                $date2 = new \DateTime($promotion->expiry);
                $expiry = \Carbon\Carbon::createFromFormat('d/m/Y', $date2->format('d/m/Y'));
            } else {
                $expiry = null;
            }
            $selectedProduct = $this->promoRelation->where('promotion_id', $id)->lists('product_id', 'product_id')->toArray();
            //dd($selectedProduct);
            return view('themes.default1.payment.promotion.edit', compact('product', 'promotion', 'start', 'expiry', 'selectedProduct', 'type'));
        } catch (\Exception $ex) {
            dd($ex);
            //return redirect()->back()->with('fails',$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, PromotionRequest $request) {
        try {
            $promotion = $this->promotion->where('id', $id)->first();
            $promotion->fill($request->input())->save();
            /* Delete the products has this id */
            $deletes = $this->promoRelation->where('promotion_id', $id)->get();
            foreach ($deletes as $delete) {
                $delete->delete();
            }
            /* Update the realtion details */
            $products = $request->input('applied');
            foreach ($products as $product) {
                $this->promoRelation->create(['product_id' => $product, 'promotion_id' => $promotion->id]);
            }
            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request) {
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $promotion = $this->promotion->where('id', $id)->first();
                    if ($promotion) {
                        $promotion->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>" . \Lang::get('message.alert') . "!</b> " . \Lang::get('message.failed') . "
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        " . \Lang::get('message.no-record') . "
                </div>";
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>" . \Lang::get('message.alert') . "!</b> " . \Lang::get('message.success') . "
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        " . \Lang::get('message.deleted-successfully') . "
                </div>";
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>" . \Lang::get('message.alert') . "!</b> " . \Lang::get('message.failed') . "
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        " . \Lang::get('message.select-a-row') . "
                </div>";
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>" . \Lang::get('message.alert') . "!</b> " . \Lang::get('message.failed') . "
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        " . $e->getMessage() . "
                </div>";
        }
    }

    public function GetCode() {
        try {
            $code = str_random(6);
            echo strtoupper($code);
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

}
