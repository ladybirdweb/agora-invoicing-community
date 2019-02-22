<?php

namespace App\Http\Controllers\Payment;

use App\Http\Requests\Payment\PromotionRequest;
use App\Model\Order\Invoice;
use App\Model\Payment\PromoProductRelation;
use App\Model\Payment\Promotion;
use App\Model\Payment\PromotionType;
use App\Model\Product\Product;
use Darryldecode\Cart\CartCondition;
use Illuminate\Http\Request;

class PromotionController extends BasePromotionController
{
    public $promotion;
    public $product;
    public $promoRelation;
    public $type;
    public $invoice;

    public function __construct()
    {
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

        $invoice = new Invoice();
        $this->invoice = $invoice;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Response
     */
    public function index()
    {
        try {
            return view('themes.default1.payment.promotion.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getPromotion()
    {
        $new_promotion = $this->promotion->select('code', 'type', 'id')->get();

        return\ DataTables::of($new_promotion)
                            ->addColumn('checkbox', function ($model) {
                                return "<input type='checkbox' class='promotion_checkbox'
                                 value=".$model->id.' name=select[] id=check>';
                            })
                        ->addColumn('code', function ($model) {
                            return ucfirst($model->code);
                        })
                        ->addColumn('type', function ($model) {
                            return $this->type->where('id', $model->type)->first()->name;
                        })
                        ->addColumn('products', function ($model) {
                            $selected = $this->promoRelation->select('product_id')
                            ->where('promotion_id', $model->id)->get();
                            $result = [];
                            foreach ($selected as $key => $select) {
                                $result[$key] = $this->product->where('id', $select->product_id)->first()->name;
                            }
                            if (!empty($result)) {
                                return implode(',', $result);
                            } else {
                                return 'None';
                            }
                        })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('promotions/'.$model->id.'/edit')
                            ." class='btn btn-sm btn-primary btn-xs'><i class='fa fa-edit' 
                            style='color:white;'> </i>&nbsp;&nbsp;Edit</a>";
                        })
                         ->rawColumns(['checkbox', 'code', 'products', 'action'])

                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create()
    {
        try {
            $product = $this->product->pluck('name', 'id')->toArray();
            $type = $this->type->pluck('name', 'id')->toArray();

            return view('themes.default1.payment.promotion.create', compact('product', 'type'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Response
     */
    public function store(PromotionRequest $request)
    {
        try {
            $startdate = date_create($request->input('start'));
            $start = date_format($startdate, 'Y-m-d H:m:i');
            $enddate = date_create($request->input('expiry'));
            $expiry = date_format($enddate, 'Y-m-d H:m:i');
            $this->promotion->code = $request->input('code');
            $this->promotion->type = $request->input('type');
            $this->promotion->value = $request->input('value');
            $this->promotion->uses = $request->input('uses');
            $this->promotion->start = $start;
            $this->promotion->expiry = $expiry;
            $this->promotion->save();
            //dd($this->promotion);
            $products = $request->input('applied');

            foreach ($products as $product) {
                $this->promoRelation->create(['product_id' => $product, 'promotion_id' => $this->promotion->id]);
            }

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function edit($id)
    {
        try {
            $promotion = $this->promotion->where('id', $id)->first();
            $product = $this->product->pluck('name', 'id')->toArray();
            $type = $this->type->pluck('name', 'id')->toArray();
            $selectedProduct = $this->promoRelation
            ->where('promotion_id', $id)
            ->pluck('product_id', 'product_id')->toArray();

            return view(
                'themes.default1.payment.promotion.edit',
                compact('product', 'promotion', 'selectedProduct', 'type')
            );
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function update($id, PromotionRequest $request)
    {
        try {
            $startdate = date_create($request->input('start'));
            $start = date_format($startdate, 'Y-m-d H:m:i');
            $enddate = date_create($request->input('expiry'));
            $expiry = date_format($enddate, 'Y-m-d H:m:i');

            $promotion = $this->promotion->where('id', $id)->update([
            'code'   => $request->input('code'),
            'type'   => $request->input('type'),
            'value'  => $request->input('value'),
            'uses'   => $request->input('uses'),
            'start'  => $start,
            'expiry' => $expiry,
            ]);
            /* Delete the products has this id */
            $deletes = $this->promoRelation->where('promotion_id', $id)->get();
            foreach ($deletes as $delete) {
                $delete->delete();
            }
            /* Update the realtion details */
            $products = $request->input('applied');
            foreach ($products as $product) {
                $this->promoRelation->create(['product_id' => $product, 'promotion_id' => $id]);
            }

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            dd($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function destroy(Request $request)
    {
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
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.deleted-successfully').'
                </div>';
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.select-a-row').'
                </div>';
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.$e->getMessage().'
                </div>';
        }
    }

    public function checkCode($code, $productid)
    {
        try {
            $inv_cont = new \App\Http\Controllers\Order\InvoiceController();
            $promo = $inv_cont->getPromotionDetails($code);
            $value = $this->findCostAfterDiscount($promo->id, $productid);
            $coupon = new CartCondition([
                'name'   => $promo->code,
                'type'   => 'coupon',
                'target' => 'item',
                'value'  => $value,
            ]);
            if (\Session::get('usage') == null || \Session::get('usage') != 1) {
                $userId = \Auth::user()->id;
                \Cart::update($productid, [
                'id'         => $productid,
                'price'      => $value,
                'conditions' => $coupon,

           // new item price, price can also be a string format like so: '98.67'
                ]);
                \Session::put('usage', 1);
                \Session::put('code', $promo->code);
                \Session::put('codevalue', $promo->value);
            }
            $items = \Cart::getContent();
            \Session::put('items', $items);

            foreach ($items as $item) {
                if ($item->conditions) {
                    // if (count($item->conditions) == 2 || count($item->conditions) == 1) {
                    \Cart::addItemCondition($productid, $coupon);
                }
            }

            return 'success';
        } catch (\Exception $ex) {
            if (!\Auth::user()) {
                throw new \Exception('Please Login');
            } else {
                throw new \Exception($ex->getMessage());
            }
        }
    }

    public function checkNumberOfUses($code)
    {
        try {
            $promotion = $this->promotion->where('code', $code)->first();
            $uses = $promotion->uses;
            if ($uses == 0) {
                return 'success';
            }
            $used_number = $this->invoice->where('coupon_code', $code)->count();
            if ($uses >= $used_number) {
                return 'success';
            } else {
                return 'fails';
            }
        } catch (\Exception $ex) {
            throw new \Exception(\Lang::get('message.find-cost-error'));
        }
    }

    public function checkExpiry($code)
    {
        try {
            $promotion = $this->promotion->where('code', $code)->first();
            $start = $promotion->start;
            $end = $promotion->expiry;
            $now = \Carbon\Carbon::now();
            $inv_cont = new \App\Http\Controllers\Order\InvoiceController();
            $getExpiryStatus = $inv_cont->getExpiryStatus($start, $end, $now);

            return $getExpiryStatus;
        } catch (\Exception $ex) {
            throw new \Exception(\Lang::get('message.check-expiry'));
        }
    }
}
