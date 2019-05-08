<?php

namespace App\Http\Controllers\Product;

// use Illuminate\Http\Request;
    use App\Http\Controllers\License\LicenseController;
    use App\Http\Controllers\License\LicensePermissionsController;
    use App\Model\Common\Setting;
    use App\Model\Common\StatusSetting;
    use App\Model\License\LicenseType;
    use App\Model\Order\Order;
    use App\Model\Payment\Currency;
    use App\Model\Payment\Period;
    use App\Model\Payment\Plan;
    use App\Model\Payment\Tax;
    use App\Model\Payment\TaxClass;
    use App\Model\Payment\TaxProductRelation;
    use App\Model\Product\Price;
    use App\Model\Product\Product;
    use App\Model\Product\ProductGroup;
    use App\Model\Product\ProductUpload;
    use App\Model\Product\Subscription;
    use App\Model\Product\Type;
    use App\Traits\Upload\ChunkUpload;
    use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Input;
    use Spatie\Activitylog\Models\Activity;

    // use Input;

class ProductController extends BaseProductController
{
    use ChunkUpload;

    public $product;
    public $price;
    public $type;
    public $subscription;
    public $currency;
    public $group;
    public $plan;
    public $tax;
    public $tax_relation;
    public $tax_class;
    public $product_upload;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin', ['except' => ['adminDownload', 'userDownload']]);

        $product = new Product();
        $this->product = $product;

        $price = new Price();
        $this->price = $price;

        $type = new LicenseType();
        $this->type = $type;

        $subscription = new Subscription();
        $this->subscription = $subscription;

        $currency = new Currency();
        $this->currency = $currency;

        $group = new ProductGroup();
        $this->group = $group;

        $plan = new Plan();
        $this->plan = $plan;

        $tax = new Tax();
        $this->tax = $tax;

        $period = new Period();
        $this->period = $period;

        $tax_relation = new TaxProductRelation();
        $this->tax_relation = $tax_relation;

        $tax_class = new TaxClass();
        $this->tax_class = $tax_class;

        $product_upload = new ProductUpload();
        $this->product_upload = $product_upload;

        $license = new LicenseController();
        $this->licensing = $license;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Response
     */
    public function index()
    {
        try {
            return view('themes.default1.product.product.index');
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect('/')->with('fails', $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Response
     */
    public function getProducts()
    {
        try {
            $new_product = Product::select('id', 'name', 'type', 'image', 'group', 'image')->get();

            return\ DataTables::of($new_product)

                            ->addColumn('checkbox', function ($model) {
                                return "<input type='checkbox' class='product_checkbox' 
                                value=".$model->id.' name=select[] id=check>';
                            })
                            ->addColumn('name', function ($model) {
                                return ucfirst($model->name);
                            })
                              ->addColumn('image', function ($model) {
                                  // return $model->image;
                                  return "<img src= '$model->image' + height=\"80\"/>";
                              })
                            ->addColumn('type', function ($model) {
                                if ($this->type->where('id', $model->type)->first()) {
                                    return $this->type->where('id', $model->type)->first()->name;
                                } else {
                                    return 'Not available';
                                }
                            })
                            ->addColumn('group', function ($model) {
                                if ($this->group->where('id', $model->group)->first()) {
                                    return $this->group->where('id', $model->group)->first()->name;
                                } else {
                                    return 'Not available';
                                }
                            })

                            ->addColumn('Action', function ($model) {
                                $permissions = LicensePermissionsController::getPermissionsForProduct($model->id);
                                $url = '';
                                if ($permissions['downloadPermission'] == 1) {
                                    $url = '<a href='.url('product/download/'.$model->id).
                                    " class='btn btn-sm btn-primary btn-xs'><i class='fa fa-download' 
                                    style='color:white;'> </i>&nbsp;&nbsp;Download</a>";
                                }

                                return '<p><a href='.url('products/'.$model->id.'/edit').
                                " class='btn btn-sm btn-primary btn-xs'><i class='fa fa-edit'
                                 style='color:white;'> </i>&nbsp;&nbsp;Edit</a>&nbsp;$url</p>";
                            })

                            ->rawColumns(['checkbox', 'name', 'image', 'type', 'group', 'Action'])
                            ->make(true);
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    // Save file Info in Modal popup
    public function save(Request $request)
    {
        $this->validate(
            $request,
            [
       'producttitle'  => 'required',
        'version'      => 'required',
       'filename'      => 'required',
       ],
       ['filename.required' => 'Please Uplaod A file',
        ]
        );

        try {
            $product_id = Product::where('name', $request->input('productname'))->select('id')->first();

            $this->product_upload->product_id = $product_id->id;
            $this->product_upload->title = $request->input('producttitle');
            $this->product_upload->description = $request->input('description');
            $this->product_upload->version = $request->input('version');
            $this->product_upload->file = $request->input('filename');
            $this->product_upload->save();
            $this->product->where('id', $product_id->id)->update(['version'=>$request->input('version')]);
            $autoUpdateStatus = StatusSetting::pluck('update_settings')->first();
            if ($autoUpdateStatus == 1) { //If License Setting Status is on,Add Product to the License Manager
                $updateClassObj = new \App\Http\Controllers\AutoUpdate\AutoUpdateController();
                $addProductToAutoUpdate = $updateClassObj->addNewVersion($product_id->id, $request->input('version'), $request->input('filename'), '1');
            }
            $response = ['success'=>'true', 'message'=>'Product Uploaded Successfully'];

            return $response;
        } catch (\Exception $e) {
            app('log')->error($e->getMessage());
            Bugsnag::notifyException($e);
            $message = [$e->getMessage()];
            $response = ['success'=>'false', 'message'=>$message];

            return response()->json(compact('response'), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create()
    {
        try {
            /*
             * server url
             */
            $url = url('/');
            $id = $this->product->orderBy('id', 'desc')->first();
            $i = $id ? $id->id + 1 : 1;
            $cartUrl = $url.'/pricing?id='.$i;
            $type = $this->type->pluck('name', 'id')->toArray();
            $subscription = $this->plan->pluck('name', 'id')->toArray();
            $currency = $this->currency->where('status', 1)->pluck('name', 'code')->toArray();
            $group = $this->group->pluck('name', 'id')->toArray();
            $products = $this->product->pluck('name', 'id')->toArray();
            $periods = $this->period->pluck('name', 'days')->toArray();
            $taxes = $this->tax_class->pluck('name', 'id')->toArray();

            return view(
                'themes.default1.product.product.create',
                compact(
                    'subscription',
                    'type',
                    'periods',
                    'currency',
                    'group',
                    'cartUrl',
                    'products',
                    'taxes'
                )
            );
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $v = \Validator::make($input, [
                        'name'       => 'required|unique:products,name',
                        'type'       => 'required',
                        'description'=> 'required',
                        'category'   => 'required',
                        'image'      => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
                        'product_sku'=> 'required|unique:products,product_sku',
                        'group'      => 'required',
                        'show_agent' => 'required',
                        // 'version' => 'required',
            ], [
            'show_agent.required' => 'Select you Cart Page Preference',
            ]);

        if ($v->fails()) {
            //     $currency = $input['currency'];

            return redirect()->back()
                        ->withErrors($v)
                        ->withInput($request->input());
        }

        try {
            $licenseStatus = StatusSetting::pluck('license_status')->first();
            if ($licenseStatus == 1) { //If License Setting Status is on,Add Product to the License Manager
                $addProductToLicensing = $this->licensing->addNewProduct($input['name'], $input['product_sku']);
            }
            $updateCont = new \App\Http\Controllers\AutoUpdate\AutoUpdateController();
            $addProductToLicensing = $updateCont->addNewProductToAUS($input['name'], $input['product_sku']);
            if ($request->hasFile('image')) {
                $image = $request->file('image')->getClientOriginalName();
                $imagedestinationPath = 'common/images';
                $request->file('image')->move($imagedestinationPath, $image);
                $this->product->image = $image;
            }
            $can_modify_agent = $request->input('can_modify_agent');
            $can_modify_quantity = $request->input('can_modify_quantity');
            $product = $this->product;
            $product->fill($request->except('image', 'file', 'cartquantity', 'can_modify_agent', 'can_modify_quantity'))->save();
            $this->saveCartValues($input, $can_modify_agent, $can_modify_quantity);
            $product_id = $product->id;
            $subscription = $request->input('subscription');
            $taxes = $request->input('tax');
            if ($taxes) {
                foreach ($taxes as $key => $value) {
                    $newtax = new TaxProductRelation();
                    $newtax->product_id = $product_id;
                    $newtax->tax_class_id = $value;
                    $newtax->save();
                }
            }

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $e) {
            app('log')->error($e->getMessage());
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
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
            $type = $this->type->pluck('name', 'id')->toArray();

            $subscription = $this->plan->pluck('name', 'id')->toArray();
            $currency = $this->currency->pluck('name', 'code')->toArray();
            $group = $this->group->pluck('name', 'id')->toArray();
            $products = $this->product->pluck('name', 'id')->toArray();
            $checkowner = Product::where('id', $id)->value('github_owner');
            $periods = $this->period->pluck('name', 'days')->toArray();
            // $url = $this->GetMyUrl();
            $url = url('/');
            $cartUrl = $url.'/cart?id='.$id;
            $product = $this->product->where('id', $id)->first();
            $selectedGroup = ProductGroup:: where('id', $product->group)->pluck('name')->toArray();
            $taxes = $this->tax_class->pluck('name', 'id')->toArray();
            $selectedCategory = \App\Model\Product\ProductCategory::
                where('category_name', $product->category)->pluck('category_name')->toArray();
            $taxes = $this->tax_class->pluck('name', 'id')->toArray();
            // dd($taxes);
            $saved_taxes = $this->tax_relation->where('product_id', $id)->get();
            $savedTaxes = $this->tax_relation->where('product_id', $id)->pluck('tax_class_id')->toArray();
            $showagent = $product->show_agent;
            $showProductQuantity = $product->show_product_quantity;
            $canModifyAgent = $product->can_modify_agent;
            $canModifyQuantity = $product->can_modify_quantity;
            $githubStatus = StatusSetting::pluck('github_status')->first();

            return view(
                'themes.default1.product.product.edit',
                compact(
                    'product',
                    'periods',
                    'type',
                    'subscription',
                    'currency',
                    'group',
                    'cartUrl',
                    'products',
                    'taxes',
                    'saved_taxes',
                    'savedTaxes',
                    'selectedCategory',
                    'selectedGroup',
                    'showagent',
                    'showProductQuantity',
                    'canModifyAgent',
                    'canModifyQuantity',
                    'checkowner',
                    'githubStatus'
                )
            );
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function update($id, Request $request)
    {
        $input = $request->all();
        $v = \Validator::make($input, [
                        'name'       => 'required',
                        'type'       => 'required',
                        'description'=> 'required',
                        'image'      => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
                        'product_sku'=> 'required',
                        'group'      => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->back()->with('errors', $v->errors());
        }

        try {
            $licenseStatus = StatusSetting::pluck('license_status')->first();
            if ($licenseStatus == 1) {
                $addProductInLicensing = $this->licensing->editProduct($input['name'], $input['product_sku']);
            }
            $product = $this->product->where('id', $id)->first();
            if ($request->hasFile('image')) {
                $image = $request->file('image')->getClientOriginalName();
                $imagedestinationPath = 'common/images';
                $request->file('image')->move($imagedestinationPath, $image);
                $product->image = $image;
            }
            if ($request->hasFile('file')) {
                $file = $request->file('file')->getClientOriginalName();
                $filedestinationPath = storage_path().'/products';
                $request->file('file')->move($filedestinationPath, $file);
                $product->file = $file;
            }
            $product->fill($request->except('image', 'file', 'cartquantity', 'product_multiple_qty', 'agent_multiple_qty'))->save();
            $this->saveCartDetailsWhileUpdating($input, $request, $product);

            //$this->saveCartValues($input,$can_modify_agent,$can_modify_quantity);
            $this->updateVersionFromGithub($product->id);

            $product_id = $product->id;
            $subscription = $request->input('subscription');
            $cost = $request->input('price');
            $sales_price = $request->input('sales_price');
            $currencies = $request->input('currency');

            //add tax class to tax_product_relation table
            $taxes = $request->input('tax');
            $newTax = $this->saveTax($taxes, $product_id);

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
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
                    $product = $this->product->where('id', $id)->first();
                    if ($product) {
                        $licenseStatus = StatusSetting::pluck('license_status')->first();
                        if ($licenseStatus == 1) {
                            $this->licensing->deleteProductFromAPL($product);
                        }
                        $product->delete();
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
                    echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */
                        \Lang::get('message.alert').'!</b> './* @scrutinizer ignore-type */ \Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.deleted-successfully').'
                </div>';
                }
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
            $lastActivity = Activity::all()->last();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function fileDestroy(Request $request)
    {
        try {
            $ids = $request->input('select');
            $storagePath = Setting::find(1)->value('file_storage');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $product = $this->product_upload->where('id', $id)->first();
                    if ($product) {
                        $file = $product->file;
                        unlink($storagePath.'/'.$file);
                        $product->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.no-record').'
                </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                    echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.deleted-successfully').'
                </div>';
                }
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

    /*
    *  Download Files from Filesystem/Github
    */
    public function downloadProduct($uploadid, $id, $invoice_id, $version_id = '')
    {
        try {
            $product = $this->product->findOrFail($uploadid);
            $type = $product->type;
            $owner = $product->github_owner;
            $repository = $product->github_repository;
            $file = $this->product_upload
                ->where('product_id', '=', $uploadid)
                ->where('id', $version_id)->select('file')->first();
            $order = Order::where('invoice_id', '=', $invoice_id)->first();
            $order_id = $order->id;
            $relese = $this->getRelease($owner, $repository, $order_id, $file);

            return $relese;
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function getSubscriptionCheckScript()
    {
        $response = "<script>
        function getPrice(val) {
            var user = document.getElementsByName('user')[0].value;
            var plan = '';
            if ($('#plan').length > 0) {
                var plan = document.getElementsByName('plan')[0].value;
            }
            //var plan = document.getElementsByName('plan')[0].value;
            //alert(user);

            $.ajax({
                type: 'POST',
                url: ".url('get-price').",
                data: {'product': val, 'user': user,'plan':plan},
                //data: 'product=' + val+'user='+user,
                success: function (data) {
                    var price = data['price'];
                    var field = data['field'];
                    $('#price').val(price);
                    $('#fields').append(field);
                }
            });
        }

    </script>";
    }
}
