<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PromotionRequest;
use App\Model\Order\Invoice;
use App\Model\Payment\PromoProductRelation;
use App\Model\Payment\Promotion;
use App\Model\Payment\PromotionType;
use App\Model\Product\Product;
use Darryldecode\Cart\CartCondition;
use Illuminate\Http\Request;

class PromotionController extends Controller
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
     * @return Response
     */
    public function index()
    {
        try {
            return view('themes.default1.payment.promotion.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetPromotion()
    {
        return \Datatable::collection($this->promotion->select('code', 'type', 'id')->get())
                        ->addColumn('#', function ($model) {
                            return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
                        })
                        ->showColumns('code')
                        ->addColumn('type', function ($model) {
                            return $this->type->where('id', $model->type)->first()->name;
                        })
                        ->addColumn('products', function ($model) {
                            $selected = $this->promoRelation->select('product_id')->where('promotion_id', $model->id)->get();

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
                            return '<a href='.url('promotions/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
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
    public function create()
    {
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
    public function store(PromotionRequest $request)
    {
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
            $promotion = $this->promotion->where('id', $id)->first();
            $product = $this->product->lists('name', 'id')->toArray();
            $type = $this->type->lists('name', 'id')->toArray();
            //            if ($promotion->start != null) {
            //                $start = $promotion->start;
            //            } else {
            //                $start = null;
            //            }
            //            if ($promotion->expiry != null) {
            //                $expiry=$promotion->expiry;
            //            } else {
            //                $expiry = null;
            //            }
            $selectedProduct = $this->promoRelation->where('promotion_id', $id)->lists('product_id', 'product_id')->toArray();
            //dd($selectedProduct);
            return view('themes.default1.payment.promotion.edit', compact('product', 'promotion', 'selectedProduct', 'type'));
        } catch (\Exception $ex) {
            dd($ex);
            //return redirect()->back()->with('fails',$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id, PromotionRequest $request)
    {
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
     * @param int $id
     *
     * @return Response
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

    public function GetCode()
    {
        try {
            $code = str_random(6);
            echo strtoupper($code);
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function checkCode($code, $productid)
    {
        try {
            $promo = $this->promotion->where('code', $code)->first();
            //check promotion code is valid
            if (!$promo) {
                return redirect()->back()->with('fails', 'No Code');
            }
            $relation = $promo->relation()->get();
            //check the relation between code and product
            if (count($relation) == 0) {
                return redirect()->back()->with('fails', \Lang::get('message.no-product-related-to-this-code'));
            }
            //check the usess
            $uses = $this->checkNumberOfUses($code);
            //dd($uses);
            if ($uses != 'success') {
                return redirect()->back()->with('fails', \Lang::get('message.usage-of-code-completed'));
            }
            //check for the expiry date
            $expiry = $this->checkExpiry($code);
            //dd($expiry);
            if ($expiry != 'success') {
                return redirect()->back()->with('fails', \Lang::get('message.usage-of-code-expired'));
            }
            $value = $this->findCostAfterDiscount($promo->id, $productid);
            //dd($value);
            //dd($promo->code);
            //return the updated cartcondition
            $coupon = new CartCondition([
                'name'   => $promo->code,
                'type'   => 'coupon',
                'target' => 'item',
                'value'  => $value,
            ]);

            $items = \Cart::getContent();

            foreach ($items as $item) {
                if (count($item->conditions) == 2 || count($item->conditions) == 1) {
                    \Cart::addItemCondition($productid, $coupon);
                }
            }
            //dd($items);

            return 'success';
        } catch (\Exception $ex) {
            dd($ex);

            throw new \Exception(\Lang::get('message.check-code-error'));
        }
    }

    public function findCostAfterDiscount($promoid, $productid)
    {
        try {
            $promotion = $this->promotion->findOrFail($promoid);
            $product = $this->product->findOrFail($productid);
            $promotion_type = $promotion->type;
            $promotion_value = $promotion->value;
            $product_price = 0;
            $userid = \Auth::user()->id;
            $control = new \App\Http\Controllers\Order\RenewController();
            $cart_control = new \App\Http\Controllers\Front\CartController();
            $currency = $cart_control->checkCurrencySession();
            if ($cart_control->checkPlanSession() == true) {
                $planid = \Session::get('plan');
            }
            if ($product->subscription != 1) {
                $product_price = $product->price()->where('currency', $currency)->first()->sales_price;
                if (!$product_price) {
                    $product_price = $product->price()->where('currency', $currency)->first()->price;
                }
            } else {
                $product_price = $control->planCost($planid, $userid);
            }
            if (count(\Cart::getContent())) {
                $product_price = \Cart::getSubTotal();
            }

            $updated_price = $this->findCost($promotion_type, $promotion_value, $product_price, $productid);
            //dd([$product_price,$promotion_type,$updated_price]);
            return $updated_price;
        } catch (\Exception $ex) {
            throw new \Exception(\Lang::get('message.find-discount-error'));
        }
    }

    public function findCost($type, $value, $price, $productid)
    {
        try {
            switch ($type) {

                case 1:
                    $percentage = $price * ($value / 100);

                    return '-'.$percentage;
                case 2:
                    return '-'.$value;
                case 3:
                    \Cart::update($productid, [
                        'price' => $value,
                    ]);

                    return '-0';
                case 4:
                    return '-'.$price;
            }
        } catch (\Exception $ex) {
            throw new \Exception(\Lang::get('message.find-cost-error'));
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
            //dd($end);
            $now = \Carbon\Carbon::now();
            //both not set, always true
            if (($start == null || $start == '0000-00-00 00:00:00') && ($end == null || $end == '0000-00-00 00:00:00')) {
                return 'success';
            }
            //only starting date set, check the date is less or equel to today
            if (($start != null || $start != '0000-00-00 00:00:00') && ($end == null || $end == '0000-00-00 00:00:00')) {
                if ($start <= $now) {
                    return 'success';
                }
            }
            //only ending date set, check the date is greater or equel to today
            if (($end != null || $end != '0000-00-00 00:00:00') && ($start == null || $start == '0000-00-00 00:00:00')) {
                if ($end >= $now) {
                    return 'success';
                }
            }
            //both set
            if (($end != null || $end != '0000-00-00 00:00:00') && ($start != null || $start != '0000-00-00 00:00:00')) {
                if ($end >= $now && $start <= $now) {
                    return 'success';
                }
            }
        } catch (\Exception $ex) {
            dd($ex);

            throw new \Exception(\Lang::get('message.check-expiry'));
        }
    }
}
