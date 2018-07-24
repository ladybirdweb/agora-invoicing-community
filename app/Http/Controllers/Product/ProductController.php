<?php

namespace App\Http\Controllers\Product;

// use Illuminate\Http\Request;
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
    use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Input;
    use Spatie\Activitylog\Models\Activity;

    // use Input;

    class ProductController extends BaseProductController
    {
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

            $type = new Type();
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
                $new_product = Product::select('id', 'name', 'type', 'group')->get();

                return\ DataTables::of($new_product)
            // return \Datatable::collection($this->product->select('id', 'name', 'type', 'group')->where('id', '!=', 1)->get())
                            ->addColumn('checkbox', function ($model) {
                                return "<input type='checkbox' class='product_checkbox' value=".$model->id.' name=select[] id=check>';
                            })
                            ->addColumn('name', function ($model) {
                                return ucfirst($model->name);
                            })
                            ->addColumn('type', function ($model) {
                                //dd($model->type());
                                if ($this->type->where('id', $model->type)->first()) {
                                    return $this->type->where('id', $model->type)->first()->name;
                                } else {
                                    return 'Not available';
                                }
                            })
                            ->addColumn('group', function ($model) {
                                //dd($model->type());
                                if ($this->group->where('id', $model->group)->first()) {
                                    return $this->group->where('id', $model->group)->first()->name;
                                } else {
                                    return 'Not available';
                                }
                            })
                            ->addColumn('price', function ($model) {
                                if ($this->price->where('product_id', $model->id)->first()) {
                                    return $this->price->where('product_id', $model->id)->first()->price;
                                } else {
                                    return 'Not available';
                                }
                            })
                            ->addColumn('currency', function ($model) {
                                if ($this->price->where('product_id', $model->id)->first()) {
                                    return $this->price->where('product_id', $model->id)->first()->currency;
                                } else {
                                    return 'Not available';
                                }
                            })
                            ->addColumn('Action', function ($model) {
                                $url = '';
                                if ($model->type == 2) {
                                    $url = '<a href='.url('product/download/'.$model->id)." class='btn btn-sm btn-primary btn-xs'><i class='fa fa-download' style='color:white;'> </i>&nbsp;&nbsp;Download</a>";
                                }

                                return '<p><a href='.url('products/'.$model->id.'/edit')." class='btn btn-sm btn-primary btn-xs'><i class='fa fa-edit' style='color:white;'> </i>&nbsp;&nbsp;Edit</a>&nbsp;$url</p>";
                            })

                            ->rawColumns(['checkbox', 'name', 'type', 'group', 'price', 'currency', 'Action'])
                            ->make(true);
            } catch (\Exception $e) {
                Bugsnag::notifyException($e);

                return redirect()->back()->with('fails', $e->getMessage());
            }
        }

        public function getUpload($id)
        {
            $new_upload = ProductUpload::where('product_id', '=', $id)
            ->select('id', 'product_id', 'title', 'description', 'version', 'file')
            ->get();

            return \DataTables::of($new_upload)
        ->addColumn('checkbox', function ($model) {
            return "<input type='checkbox' class='upload_checkbox' value=".$model->id.' name=select[] id=checks>';
        })

        ->addColumn('product_id', function ($model) {
            return ucfirst($this->product->where('id', $model->product_id)->first()->name);
        })

        ->addColumn('title', function ($model) {
            return ucfirst($model->title);
        })
        ->addColumn('description', function ($model) {
            return ucfirst($model->description);
        })
        ->addColumn('version', function ($model) {
            return $model->version;
        })

        ->addColumn('file', function ($model) {
            return $model->file;
        })
        ->addColumn('action', function ($model) {
            return '<a href='.('#edit-upload-option/'.$model->id).' 
             class=" btn btn-sm btn-primary " data-title="'.$model->title.'"
              data-description="'.$model->description.'" data-version="'
              .$model->version.'" data-id="'.$model->id.'" onclick="openEditPopup(this)" >Edit</a>';
        })
        ->rawcolumns(['checkbox', 'product_id', 'title', 'description', 'version', 'file', 'action'])
        ->make(true);
        }

        // Save file Info in Modal popup
        public function save(Request $request)
        {
            try {
                $product_id = Product::where('name', '=', $request->input('product'))->select('id')->first();

                $this->product_upload->product_id = $product_id->id;
                $this->product_upload->title = $request->input('title');
                $this->product_upload->description = $request->input('description');
                $this->product_upload->version = $request->input('version');

                if ($request->file) {
                    $file = $request->file('file')->getClientOriginalName();

                    $destination = storage_path().'/products';
                    $request->file('file')->move($destination, $file);
                    $this->product_upload->file = $file;
                }
                $this->product_upload->save();

                return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
            } catch (\Exception $e) {
                Bugsnag::notifyException($e);

                return redirect()->with('fails', $e->getMessage());
            }
        }

        //Update the File Info
        public function uploadUpdate($id, Request $request)
        {
            $file_upload = ProductUpload::find($id);

            $file_upload->title = $request->input('title');
            $file_upload->description = $request->input('description');
            $file_upload->version = $request->input('version');
            if ($request->file) {
                $file = $request->file('file')->getClientOriginalName();

                $destination = storage_path().'/products';
                $request->file('file')->move($destination, $file);
                $file_upload->file = $file;
            }
            $file_upload->save();

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
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
                $url = $this->getMyUrl();
                $i = $this->product->orderBy('created_at', 'desc')->first()->id + 1;
                $cartUrl = $url.'/pricing?id='.$i;
                $type = $this->type->pluck('name', 'id')->toArray();
                $subscription = $this->plan->pluck('name', 'id')->toArray();
                $currency = $this->currency->pluck('name', 'code')->toArray();
                $group = $this->group->pluck('name', 'id')->toArray();
                $products = $this->product->pluck('name', 'id')->toArray();
                $periods = $this->period->pluck('name', 'days')->toArray();
                $taxes = $this->tax_class->pluck('name', 'id')->toArray();

                return view('themes.default1.product.product.create',
                    compact('subscription', 'type', 'periods', 'currency',
                        'group', 'cartUrl', 'products', 'taxes'));
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
            // dd($input);
            $v = \Validator::make($input, [
                        'name'       => 'required|unique:products,name',
                        'type'       => 'required',
                        'group'      => 'required',
                        'description'=> 'required',
                        // 'image'   => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
                        // 'version' => 'required',
            ]);
            if ($v->fails()) {
                //     $currency = $input['currency'];

                return redirect()->back()
                        ->withErrors($v)
                        ->withInput()
                        ->with('currency');
            }

            try {
                if ($request->hasFile('image')) {
                    $image = $request->file('image')->getClientOriginalName();
                    $imagedestinationPath = 'dist/product/images';
                    $request->file('image')->move($imagedestinationPath, $image);
                    $this->product->image = $image;
                }

                $product = $this->product;
                $product->fill($request->except('image', 'file'))->save();
                // Product::where('id',$product->id)->update(['name'=>$request->names]);

                $product_id = $product->id;
                $subscription = $request->input('subscription');

                $price = $request->input('price');
                // $price=

                $sales_price = $request->input('sales_price');
                $currencies = $request->input('currency');
                $taxes = $request->input('tax');
                if ($taxes) {
                    foreach ($taxes as $key=>$value) {
                        $newtax = new TaxProductRelation();
                        $newtax->product_id = $product_id;
                        $newtax->tax_class_id = $value;
                        $newtax->save();
                    }
                }

                return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
            } catch (\Exception $e) {
                Bugsnag::notifyException($e);

                return redirect()->with('fails', $e->getMessage());
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
                $periods = $this->period->pluck('name', 'days')->toArray();
                $url = $this->GetMyUrl();
                $cartUrl = $url.'/cart?id='.$id;
                $product = $this->product->where('id', $id)->first();
                $taxes = $this->tax_class->pluck('name', 'id')->toArray();
                // dd($taxes);
                $saved_taxes = $this->tax_relation->where('product_id', $id)->get();
                $savedTaxes = $this->tax_relation->where('product_id', $id)->pluck('tax_class_id')->toArray();

                return view('themes.default1.product.product.edit',
                    compact('product', 'periods', 'type', 'subscription',
                        'currency', 'group', 'price', 'cartUrl', 'products',
                        'regular', 'sales', 'taxes', 'saved_taxes', 'savedTaxes'));
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
                        'name'    => 'required',
                        'type'    => 'required',
                        'group'   => 'required',
                        'image'   => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
      ]);

            if ($v->fails()) {
                return redirect()->back()->with('errors', $v->errors());
            }

            try {
                $product = $this->product->where('id', $id)->first();
                if ($request->hasFile('image')) {
                    $image = $request->file('image')->getClientOriginalName();
                    $imagedestinationPath = 'dist/product/images';
                    $request->file('image')->move($imagedestinationPath, $image);
                    $product->image = $image;
                }
                if ($request->hasFile('file')) {
                    $file = $request->file('file')->getClientOriginalName();
                    $filedestinationPath = storage_path().'/products';
                    $request->file('file')->move($filedestinationPath, $file);
                    $product->file = $file;
                }
                $product->fill($request->except('image', 'file'))->save();
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

         public function saveTax($taxes, $product_id)
        {
            if ($taxes) {
                    $this->tax_relation->where('product_id', $product_id)->delete();
                    foreach ($taxes as $tax) {
                        $newTax = new TaxProductRelation();
                        $newTax->product_id = $product_id;
                        $newTax->tax_class_id = $tax;
                        $newTax->save();
                    }
                }
             return $newTax;
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
                        if ($id != 1) {
                            $product = $this->product->where('id', $id)->first();
                            if ($product) {
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
                        } else {
                            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */ \Lang::get('message.can-not-delete-default').'
                </div>';
                        }
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
         * @return Response
         */
        public function fileDestroy(Request $request)
        {
            try {
                $ids = $request->input('select');
                if (!empty($ids)) {
                    foreach ($ids as $id) {
                        if ($id != 1) {
                            $product = $this->product_upload->where('id', $id)->first();
                            if ($product) {
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
                        } else {
                            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.can-not-delete-default').'
                </div>';
                        }
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
                if ($type == 2) {
                    $relese = $this->getRelease($owner, $repository, $order_id, $file);

                    return $relese;
                }
            } catch (\Exception $e) {
                Bugsnag::notifyException($e);

                return redirect()->back()->with('fails', $e->getMessage());
            }
        }

        public function adminDownload($id, $invoice = '', $api = false)
        {
            try {
                $role = \Auth::user()->role;
                $release = $this->getLinkToDownload($role, $invoice, $id);

                if (is_array($release) && array_key_exists('type', $release)) {
                    header('Location: '.$release['release']);
                    exit;
                } else {
                    header('Content-type: Zip');
                    header('Content-Description: File Transfer');
                    header('Content-Disposition: attachment; filename=Faveo.zip');
                    header('Content-Length: '.filesize($release));
                    flush();
                    readfile("$release");
                }
            } catch (\Exception $e) {
                if ($api) {
                    return response()->json(['error'=>$e->getMessage()]);
                }
                Bugsnag::notifyException($e);

                return redirect()->back()->with('fails', $e->getMessage());
            }
        }

        public function getProductField($productid)
        {
            try {
                $field = '';
                $product = $this->product->find($productid);
                if ($product) {
                    if ($product->require_domain == 1) {
                        $field .= "<div class='col-md-4 form-group'>
                            <label class='required'>"./* @scrutinizer ignore-type */
                             \Lang::get('message.domain')."</label>
                            <input type='text' name='domain' class='form-control' 
                            id='domain' placeholder='http://example.com'>
                    </div>";
                    }
                }

                return $field;
            } catch (\Exception $ex) {
                Bugsnag::notifyException($ex);

                return $ex->getMessage();
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
