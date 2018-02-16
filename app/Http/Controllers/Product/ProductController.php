<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Model\Payment\Currency;
use App\Model\Payment\Plan;
// use Input;

use App\Model\Payment\Tax;
use App\Model\Payment\TaxClass;
use App\Model\Payment\TaxProductRelation;
use App\Model\Product\Price;
use App\Model\Product\Product;
use App\Model\Product\ProductGroup;
use App\Model\Product\Subscription;
use App\Model\Product\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

// use Input;

class ProductController extends Controller
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

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('admin', ['except' => ['userDownload']]);

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

        $tax_relation = new TaxProductRelation();
        $this->tax_relation = $tax_relation;

        $tax_class = new TaxClass();
        $this->tax_class = $tax_class;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            return view('themes.default1.product.product.index');
        } catch (\Exception $e) {
            return redirect('/')->with('fails', $e->getMessage());
        }
    }

    public function getProducts()
    {

        // try {
        $new_product = Product::select('id', 'name', 'type', 'group')->get();

        return\ DataTables::of($new_product)
        // return \Datatable::collection($this->product->select('id', 'name', 'type', 'group')->where('id', '!=', 1)->get())
                        ->addColumn('#', function ($model) {
                            return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
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
                                $url = '<a href='.url('product/download/'.$model->id)." class='btn btn-sm btn-primary'>Download</a>";
                            }

                            return '<p><a href='.url('products/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>&nbsp;$url</p>";
                        })

                        ->rawColumns(['#', 'name', 'type', 'group', 'price', 'currency', 'Action'])
                        ->make(true);
        // ->searchColumns('name', 'email')
                        // ->orderColumns('name', 'email')
                        // ->make();
//        } catch (\Exception $e) {
//            return redirect()->back()->with('fails', $e->getMessage());
//        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        try {
            /*
             * server url
             */
            $url = $this->GetMyUrl();
            // dd($url);
            $i = $this->product->orderBy('created_at', 'desc')->first()->id + 1;
            // dd($i);
            $cartUrl = $url.'/pricing?id='.$i;
            // dd($cartUrl);
            $type = $this->type->pluck('name', 'id')->toArray();
            $subscription = $this->plan->pluck('name', 'id')->toArray();
            // dd($subscription);
            $currency = $this->currency->pluck('name', 'code')->toArray();
            $group = $this->group->pluck('name', 'id')->toArray();
            $products = $this->product->pluck('name', 'id')->toArray();
            $taxes = $this->tax_class->pluck('name', 'id')->toArray();

            return view('themes.default1.product.product.create', compact('subscription', 'type', 'currency', 'group', 'cartUrl', 'products', 'taxes'));
        } catch (\Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $v = \Validator::make($input, [
                    'name'    => 'required|unique:products,name',
                    'type'    => 'required',
                    'group'   => 'required',
                    // 'version' => 'required',
        ]);
        $v->sometimes(['file', 'image', 'version'], 'required', function ($input) {
            return $input->type == 2 && $input->github_owner == '' && $input->github_repository == '';
        });

        $v->sometimes(['github_owner', 'github_repository'], 'required', function ($input) {
            return $input->type == 2 && $input->file == '' && $input->image == '';
        });
        $v->sometimes(['currency', 'price'], 'required', function ($input) {
            return $input->subscription != 1;
        });
        if ($v->fails()) {
            $currency = $input['currency'];

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
            if ($request->hasFile('file')) {
                $file = $request->file('file')->getClientOriginalName();
                $filedestinationPath = storage_path().'/products';
                $request->file('file')->move($filedestinationPath, $file);
                $this->product->file = $file;
            }

            $product = $this->product;
            // dd($request->except('image', 'file'));
            $product->fill($request->except('image', 'file'))->save();

            $owner = $request->input('github_owner');
            $repo = $request->input('github_repository');

            // $this->updateVersionFromGithub($product->id, $owner,$repo);
            $product_id = $product->id;
            $subscription = $request->input('subscription');

            $price = $request->input('price');
            // $price=

            $sales_price = $request->input('sales_price');
            $currencies = $request->input('currency');
            if (count($currencies) > 0) {
                foreach ($currencies as $key => $currency) {
                    $this->price->create(['product_id' => $product_id, 'currency' => $currency, 'subscription' => $subscription, 'price' => $price[$key], 'sales_price' => $sales_price[$key]]);
                }
            }

            $taxes = $request->input('tax');

            if ($taxes) {
                $this->tax_relation->create(['product_id' => $product_id, 'tax_class_id' => $taxes]);
            }

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $e) {
            // dd($e);

            return redirect()->with('fails', $e->getMessage());
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
            $type = $this->type->pluck('name', 'id')->toArray();

            $subscription = $this->plan->pluck('name', 'id')->toArray();
            $currency = $this->currency->pluck('name', 'code')->toArray();
            $group = $this->group->pluck('name', 'id')->toArray();
            $products = $this->product->pluck('name', 'id')->toArray();
            $url = $this->GetMyUrl();
            $cartUrl = $url.'/cart?id='.$id;
            $product = $this->product->where('id', $id)->first();
            $price = $this->price->where('product_id', $product->id);
            foreach ($currency as $key => $value) {
                if ($this->price->where('product_id', $product->id)->where('currency', $key)->first()) {
                    $regular[$key] = $this->price->where('product_id', $product->id)->where('currency', $key)->first()->price;
                    $sales[$key] = $this->price->where('product_id', $product->id)->where('currency', $key)->first()->sales_price;
                } else {
                    $regular[$key] = '';
                    $sales[$key] = '';
                }
            }

            $taxes = $this->tax_class->pluck('name', 'id')->toArray();
            //dd($taxes);
            $saved_taxes = $this->tax_relation->where('product_id', $id)->get();

            return view('themes.default1.product.product.edit', compact('product', 'type', 'subscription', 'currency', 'group', 'price', 'cartUrl', 'products', 'regular', 'sales', 'taxes', 'saved_taxes'));
        } catch (\Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $input = $request->all();
        // dd($input);
        $v = \Validator::make($input, [
                    'name'  => 'required',
                    'type'  => 'required',
                    'group' => 'required',
//                    'subscription' => 'required',
//                    'currency.*' => 'required',
//                    'price.*' => 'required',
        ]);
        $v->sometimes(['file', 'image', 'version'], 'required', function ($input) {
            return $input->type == 2 && $input->github_owner == '' && $input->github_repository == '';
        });

        $v->sometimes(['github_owner', 'github_repository'], 'required', function ($input) {
            return $input->type == 2 && $input->file == '' && $input->image == '';
        });
        $v->sometimes(['currency', 'price'], 'required', function ($input) {
            return $input->subscription != 1;
        });
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
            // $this->updateVersionFromGithub($product->id, $request);

            $product_id = $product->id;
            $subscription = $request->input('subscription');
            $cost = $request->input('price');
            $sales_price = $request->input('sales_price');
            $currencies = $request->input('currency');

            $prices = $this->price->where('product_id', $product->id)->get();

            if (count($currencies) > 0) {
                foreach ($prices as $price) {
                    $price->delete();
                }

                foreach ($currencies as $key => $currency) {
                    $this->price->create(['product_id' => $product_id, 'currency' => $currency, 'price' => $cost[$key], 'sales_price' => $sales_price[$key]]);
                }
            }
            //add tax class to tax_product_relation table
            $taxes = $request->input('tax');
            //dd($taxes);
            if ($taxes) {
                $saved_taxes = $this->tax_relation->where('product_id', $product_id)->first();
                if ($saved_taxes) {
                    $saved_taxes->tax_class_id = $taxes;
                    $saved_taxes->save();
                } else {
                    $this->tax_relation->create(['product_id' => $product_id, 'tax_class_id' => $taxes]);
                }
            }

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $e) {
            dd($e);

            return redirect()->back()->with('fails', $e->getMessage());
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
                    if ($id != 1) {
                        $product = $this->product->where('id', $id)->first();
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

    public function getMyUrl()
    {
        $server = new Request();
        $url = $_SERVER['REQUEST_URI'];
        $server = parse_url($url);
        $server['path'] = dirname($server['path']);
        $server = parse_url($server['path']);
        $server['path'] = dirname($server['path']);

        $server = 'http://'.$_SERVER['HTTP_HOST'].$server['path'];

        return $server;
    }

    public function downloadProduct($id)
    {
        try {
            $product = $this->product->findOrFail($id);
            //dd($product);
            $type = $product->type;
            $owner = $product->github_owner;
            $repository = $product->github_repository;
            $file = $product->file;

            if ($type == 2) {
                if ($owner && $repository) {
                    $github_controller = new \App\Http\Controllers\Github\GithubController();
                    $relese = $github_controller->listRepositories($owner, $repository);

                    return ['release'=>$relese, 'type'=>'github'];
                } elseif ($file) {
                    $relese = '/home/faveo/products/'.$file;

                    return $relese;
                }
            }
        } catch (\Exception $e) {
            dd($e->getMessage());

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function adminDownload($id, $api = false)
    {
        try {
            $release = $this->downloadProduct($id);
            if (is_array($release) && array_key_exists('type', $release)) {
                header('Location: '.$release['release']);
                exit;
            } else {
                header('Content-type: Zip');
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename=Faveo.zip');
                //header("Content-type: application/zip");
                header('Content-Length: '.filesize($release));
                //ob_clean();
                flush();
                readfile("$release");
                exit;
            }
        } catch (\Exception $e) {
            if ($api) {
                return response()->json(['error'=>$e->getMessage()]);
            }

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function userDownload($userid, $invoice_number)
    {
        try {
            if (\Auth::user()->role != 'admin') {
                if (\Auth::user()->id != $userid) {
                    throw new \Exception('This user has no permission for this action');
                }
            }
            $user = new \App\User();
            $user = $user->findOrFail($userid);
            $invoice = new \App\Model\Order\Invoice();
            $invoice = $invoice->where('number', $invoice_number)->first();

            if ($user && $invoice) {
                if ($user->active == 1) {
                    $order = $invoice->order()->orderBy('id', 'desc')->select('product')->first();
                    $product_id = $order->product;
                    $release = $this->downloadProduct($product_id);
                    if (is_array($release) && array_key_exists('type', $release)) {
                        $release = $release['release'];

                        return view('themes.default1.front.download', compact('release', 'form'));
                    } else {
                        header('Content-type: Zip');
                        header('Content-Description: File Transfer');
                        header('Content-Disposition: attachment; filename=Faveo.zip');
                        //header("Content-type: application/zip");
                        header('Content-Length: '.filesize($release));
                        //ob_clean();
                        flush();
                        readfile("$release");
                        exit;
                    }
                } else {
                    return redirect('auth/login')->with('fails', \Lang::get('activate-your-account'));
                }
            } else {
                return redirect('auth/login')->with('fails', \Lang::get('please-purcahse-a-product'));
            }
        } catch (\Exception $ex) {
            return redirect('auth/login')->with('fails', $ex->getMessage());
        }
    }

    public function getPrice(Request $request)
    {
        try {
            $id = $request->input('product');
            // dd($id);
            $userid = $request->input('user');
            $plan = $request->input('plan');
            $controller = new \App\Http\Controllers\Front\CartController();
            $price = $controller->cost($id, $userid, $plan);
            $field = $this->getProductField($id).$this->getProductQtyCheck($id);

            $result = ['price' => $price, 'field' => $field];

            return response()->json($result);
        } catch (\Exception $ex) {
            $result = ['price' => $ex->getMessage(), 'field' => ''];

            return response()->json($result);
        }
    }

    public function updateVersionFromGithub($productid, $owner, $repo)
    {
        try {
            // if ($request->has('github_owner') && $request->has('github_repository')) {
            if ($owner && $repo) {
                // $owner = $request->input('github_owner');
                // $repo = $request->input('github_repository');
                $product = $this->product->find($productid);
                $github_controller = new \App\Http\Controllers\Github\GithubController();
                $version = $github_controller->findVersion($owner, $repo);
                // dd($version);
                $product->version = $version;
                // dd($product);
                $product->save();
            }
        } catch (\Exception $ex) {
            dd($ex);

            throw new \Exception($ex->getMessage());
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
                        <label class='required'>".\Lang::get('message.domain')."</label>
                        <input type='text' name='domain' class='form-control' id='domain' placeholder='http://example.com'>
                </div>";
                }
            }

            return $field;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function getDescriptionField($productid)
    {
        try {
            $product = $this->product->find($productid);
            $field = '';

            if ($product->retired == 1) {
                $field .= "<div class='col-md-4 form-group'>
                        <label class='required'>".\Lang::get('message.description')."</label>
                        <textarea name='description' class='form-control' id='description' placeholder='Description'></textarea>
                </div>";
            }

            return $field;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function getSubscriptionCheck($productid)
    {
        try {
            $controller = new \App\Http\Controllers\Front\CartController();
            $check = $controller->allowSubscription($productid);
            $field = '';
            $price = '';
            if ($check == true) {
                $plan = new Plan();
                $plans = $plan->pluck('name', 'id')->toArray();
                $script = ''; //$this->getSubscriptionCheckScript();
                $field = "<div class='col-md-4 form-group'>
                        <label class='required'>".\Lang::get('message.subscription').'</label>
                       '.\Form::select('plan', ['' => 'Select', 'Plans' => $plans], null, ['class' => 'form-control', 'id' => 'plan', 'onchange' => 'getPrice(this.value)']).'
                </div>'.$script;
            } else {
                $userid = $request->input('user');
                $price = $controller->productCost($productid, $userid);
            }
            $field .= $this->getDescriptionField($productid);
            $result = ['price' => $price, 'field' => $field];

            return response()->json($result);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function getProductQtyCheck($productid)
    {
        try {
            $check = self::checkMultiProduct($productid);
            if ($check == true) {
                return "<div class='col-md-4 form-group'>
                        <label class='required'>".\Lang::get('message.quantity')."</label>
                        <input type='text' name='quantity' class='form-control' id='quantity' value='1'>
                </div>";
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public static function checkMultiProduct($productid)
    {
        try {
            $product = new Product();
            $product = $product->find($productid);
            if ($product) {
                // dd($product->multiple_qty == 1);
                if ($product->multiple_qty == 1) {
                    return true;
                }
            }

            return false;
        } catch (Exception $ex) {
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
