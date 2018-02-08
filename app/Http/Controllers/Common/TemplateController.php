<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Product\ProductController;
use App\Model\Common\Setting;
use App\Model\Common\Template;
use App\Model\Common\TemplateType;
use App\Model\licence\Licence;
use App\Model\Payment\Currency;
use App\Model\Payment\Plan;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxClass;
use App\Model\Payment\TaxOption;
use App\Model\Payment\TaxProductRelation;
use App\Model\Product\Price;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use Config;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public $template;
    public $type;
    public $product;
    public $price;
    public $subscription;
    public $plan;
    public $licence;
    public $tax_relation;
    public $tax;
    public $tax_class;
    public $tax_rule;
    public $currency;

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
        // $this->middleware('admin', ['except' => ['show']]);

        $template = new Template();
        $this->template = $template;

        $type = new TemplateType();
        $this->type = $type;

        $product = new Product();
        $this->product = $product;

        $price = new Price();
        $this->price = $price;

        $subscription = new Subscription();
        $this->subscription = $subscription;

        $plan = new Plan();
        $this->plan = $plan;

        $licence = new Licence();
        $this->licence = $licence;

        $tax_relation = new TaxProductRelation();
        $this->tax_relation = $tax_relation;

        $tax = new Tax();
        $this->tax = $tax;

        $tax_class = new TaxClass();
        $this->tax_class = $tax_class;

        $tax_rule = new TaxOption();
        $this->tax_rule = $tax_rule;

        $currency = new Currency();
        $this->currency = $currency;
        // $this->smtp();
    }

    public function smtp()
    {
        $settings = new Setting();
        $fields = $settings->find(1);
        $driver = '';
        $port = '';
        $host = '';
        $enc = '';
        $email = '';
        $password = '';
        $name = '';
        if ($fields) {
            $driver = $fields->driver;
            $port = $fields->port;
            $host = $fields->host;
            $enc = $fields->encryption;
            $email = $fields->email;
            $password = $fields->password;
            $name = $fields->company;
        }

        return $this->smtpConfig($driver, $port, $host, $enc, $email, $password, $name);
    }

    public function smtpConfig($driver, $port, $host, $enc, $email, $password, $name)
    {
        Config::set('mail.driver', $driver);
        Config::set('mail.password', $password);
        Config::set('mail.username', $email);
        Config::set('mail.encryption', $enc);
        Config::set('mail.from', ['address' => $email, 'name' => $name]);
        Config::set('mail.port', intval($port));
        Config::set('mail.host', $host);
        dump(Config::get('mail'));

        return 'success';
    }

    public function index()
    {
        try {
            return view('themes.default1.common.template.inbox');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getTemplates()
    {
        return \DataTables::of($this->template->select('id', 'name', 'type')->get())
                        ->addColumn('#', function ($model) {
                            return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
                        })

                         ->addColumn('name', function ($model) {
                             return $model->name;
                         })
                        ->addColumn('type', function ($model) {
                            return $this->type->where('id', $model->type)->first()->name;
                        })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('templates/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
                        })
                        ->rawColumns(['name', 'type', 'action'])
                        ->make(true);
        // ->searchColumns('name')
                        // ->orderColumns('name')
                        // ->make();
    }

    public function create()
    {
        try {
            $controller = new ProductController();
            $url = $controller->GetMyUrl();
            $i = $this->template->orderBy('created_at', 'desc')->first()->id + 1;
            $cartUrl = $url.'/'.$i;
            $type = $this->type->pluck('name', 'id')->toArray();

            return view('themes.default1.common.template.create', compact('type', 'cartUrl'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'data' => 'required',
            'type' => 'required',
        ]);

        try {
            //dd($request);
            $this->template->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $controller = new ProductController();
            $url = $controller->GetMyUrl();

            $i = $this->template->orderBy('created_at', 'desc')->first()->id + 1;
            $cartUrl = $url.'/'.$i;
            //dd($cartUrl);
            $template = $this->template->where('id', $id)->first();
            $type = $this->type->pluck('name', 'id')->toArray();

            return view('themes.default1.common.template.edit', compact('type', 'template', 'cartUrl'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'data' => 'required',
            'type' => 'required',
        ]);

        try {
            //dd($request);
            $template = $this->template->where('id', $id)->first();
            $template->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
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
                    $template = $this->template->where('id', $id)->first();
                    if ($template) {
                        $template->delete();
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

    public function mailing($from, $to, $data, $subject, $replace = [], $type = '', $fromname = '', $toname = '', $cc = [], $attach = [])
    {
        try {
            $page_controller = new \App\Http\Controllers\Front\PageController();
            $transform[0] = $replace;
            $data = $page_controller->transform($type, $data, $transform);
            $settings = \App\Model\Common\Setting::find(1);
            $fromname = $settings->company;

            /*Mail config*/

            // // // Set the mailer

        $fields = Setting::where('id', '=', 1)->first();
        $driver = '';
        $port = '';
        $host = '';
        $enc = '';
        $email = '';
        $mail_password = '';
        $name = '';
        if ($fields) {
            $driver = $fields->driver;
            $port = $fields->port;
            $host = $fields->host;
            $enc = $fields->encryption;
            $email = $fields->email;
            $mail_password = $fields->password;
            $name = $fields->company;
        }



            $https['ssl']['verify_peer'] = false;
            $https['ssl']['verify_peer_name'] = false;
            $transport = new \Swift_SmtpTransport($host, $port, $enc);
            $transport->setUsername($email);
            $transport->setPassword($mail_password);
            $transport->setStreamOptions($https);
            $set = new \Swift_Mailer($transport);

            // // Set the mailer
            \Mail::setSwiftMailer($set);

            /*Mail config ends*/

            \Mail::send('emails.mail', ['data' => $data], function ($m) use ($from, $to, $subject, $fromname, $toname, $cc, $attach) {
                $m->from($from, $fromname);

                $m->to($to, $toname)->subject($subject);

                /* if cc is need  */
                if (!empty($cc)) {
                    foreach ($cc as $address) {
                        $m->cc($address['address'], $address['name']);
                    }
                }

                /*  if attachment is need */
                if (!empty($attach)) {
                    foreach ($attach as $file) {
                        $m->attach($file['path'], $options = []);
                    }
                }
            });

            return 'success';
        } catch (\Exception $ex) {
            dd($ex);
            if ($ex instanceof \Swift_TransportException) {
                throw new \Exception('We can not reach to this email address');
            }

            throw new \Exception('mailing problem');
        }
    }

    public function mailtest($id)
    {
        $from = 'vijaysebastian111@gmail.com';
        $to = 'vijay.sebastian@ladybirdweb.com';
        $subject = 'Tsting the mailer';
        $template = Template::where('id', $id)->whereBetween('type', [1, 8])->first();
        if ($template) {
            $data = $template->data;
        } else {
            return 'Select valid template';
        }
        $cc = [
            0 => [
                'name'    => 'vijay',
                'address' => 'vijaysebastian111@gmail.com',
            ],
            1 => [
                'name'    => 'vijay sebastian',
                'address' => 'vijaysebastian23@gmail.com',
            ],
        ];
        $attachments = [
            0 => [
                'path' => public_path('dist/img/avatar.png'),
            ],
        ];
        $replace = [
            'name'     => 'vijay sebastian',
            'usernmae' => 'vijay',
            'password' => 'jfdvhd',
            'address'  => 'dshbcvhjdsbvchdff',
        ];
        $this->Mailing($from, $to, $data, $subject, $replace, 'from', 'to', $cc, $attachments);
    }

    public function popup($title, $body, $width = '897', $name = '', $modelid = '', $class = 'null', $trigger = false)
    {
        try {
            if ($modelid == '') {
                $modelid = $title;
            }
            if ($trigger == true) {
                $trigger = "<a href=# class=$class  data-toggle='modal' data-target=#edit".$modelid.'>'.$name.'</a>';
            } else {
                $trigger = '';
            }

            return $trigger."
                        <div class='modal fade' id=edit".$modelid.">
                            <div class='modal-dialog' style='width: ".$width."px;'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                        <h4 class='modal-title'>".$title."</h4>
                                    </div>
                                    <div class='modal-body'>
                                    ".$body."
                                    </div>
                                    <div class='modal-footer'>
                                        <button type=button id=close class='btn btn-default pull-left' data-dismiss=modal>Close</button>
                                        <input type=submit class='btn btn-primary' value=".\Lang::get('message.save').'>
                                    </div>
                                    '.\Form::close().'
                                </div>
                            </div>
                        </div>';
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function checkPriceWithTaxClass($productid, $currency)
    {
        try {
            $product = $this->product->findOrFail($productid);
            //dd($product);
            if ($product->tax_apply == 1) {
                $price = $this->checkTax($product->id, $currency);
            } else {
                $price = $product->price()->where('currency', $currency)->first()->sales_price;
                if (!$price) {
                    $price = $product->price()->where('currency', $currency)->first()->price;
                }
            }
            //dd($price);
            return $price;
        } catch (\Exception $ex) {
            dd($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function checkTax($productid, $price, $cart = 0, $cart1 = 0, $shop = 0)
    {
        try {
            // dd($productid, $price);
            $product = $this->product->findOrFail($productid);

            // dd( $product);

            // dd($product);
            $controller = new \App\Http\Controllers\Front\CartController();

            $currency = $controller->currency();
            // $price = $controller->cost($productid);
            //           $price = $product->price()->where('currency', $currency)->first()->sales_price;
            //           if (!$price) {
            //               $price = $product->price()->where('currency', $currency)->first()->price;
            //           }
            //
            $tax_relation = $this->tax_relation->where('product_id', $productid)->first();
            // dd(!$tax_relation);
            if (!$tax_relation) {
                return $this->withoutTaxRelation($productid, $currency);
            }
            // dd($taxes);
            $taxes = $this->tax->where('tax_classes_id', $tax_relation->tax_class_id)->where('active', 1)->orderBy('created_at', 'asc')->get();
            // dd($taxes);
            // dd(count($taxes) == 0);
            if (count($taxes) == 0) {
                throw new \Exception('No taxes is avalable');
            }
            // dd($cart == 1);
            if ($cart == 1) {
                $tax_amount = $this->taxProcess($taxes, $price, $cart1, $shop);
            // dd($tax_amount);
            } else {
                $rate = '';
                // dd($rate);
                foreach ($taxes as $tax) {
                    if ($tax->compound != 1) {
                        $rate += $tax->rate;
                    // dd($rate);
                    } else {
                        $rate = $tax->rate;
                        // dd($rate);
                        $price = $this->calculateTotal($rate, $price);
                    }
                    // dd($price);
                    $tax_amount = $this->calculateTotal($rate, $price);
                    // dd($tax_amount);
                }
            }

            return $tax_amount;
        } catch (\Exception $ex) {
            dd($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function taxProcess($taxes, $price, $cart, $shop)
    {
        try {
            $rate = '';
            foreach ($taxes as $tax) {
                if ($tax->compound != 1) {
                    $rate += $tax->rate;
                } else {
                    $rate = $tax->rate;
                }
                // dd($rate);

                $tax_amount = $this->ifStatement($rate, $price, $cart, $shop, $tax->country, $tax->state);
            }
            // dd($tax_amount);
            return $tax_amount;
        } catch (\Exception $ex) {
            // dd($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function ifStatement($rate, $price, $cart1, $shop1, $country = '', $state = '')
    {
        try {
            // dd($rate);
            $tax_rule = $this->tax_rule->find(1);
            // dd($tax_rule);
            $product = $tax_rule->inclusive;
            // dd($product);
            $shop = $tax_rule->shop_inclusive;
            // dd($shop);
            $cart = $tax_rule->cart_inclusive;
            $result = $price;
            // dd($result);

            // $location = \GeoIP::getLocation();
            //           $location = ['ip'   => '::1',
            // 'isoCode'                     => 'IN',
            // 'country'                     => 'India',
            // 'city'                        => 'Bengaluru',
            // 'state'                       => 'KA',
            // 'postal_code'                 => 560076,
            // 'lat'                         => 12.9833,
            // 'lon'                         => 77.5833,
            // 'timezone'                    => 'Asia/Kolkata',
            // 'continent'                   => 'AS',
            // 'default'                     => false, ];
            //           $counrty_iso = $location['isoCode'];

            //           $state_code = $location['isoCode'].'-'.$location['state'];
            $location = json_decode(file_get_contents('http://ip-api.com/json'), true);

            $country = \App\Http\Controllers\Front\CartController::findCountryByGeoip($location['countryCode']);
            //$states = \App\Http\Controllers\Front\CartController::findStateByRegionId($location['isoCode']);
            $states = \App\Model\Common\State::pluck('state_subdivision_name', 'state_subdivision_code')->toArray();
            $state_code = $location['countryCode'].'-'.$location['region'];
            $state = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
            $mobile_code = \App\Http\Controllers\Front\CartController::getMobileCodeByIso($location['countryCode']);
            $country_iso = $location['countryCode'];

            $geoip_country = '';
            $geoip_state = '';
            if (\Auth::user()) {
                $geoip_country = \Auth::user()->country;
                $geoip_state = \Auth::user()->state;
            }
            if ($geoip_country == '') {
                $geoip_country = \App\Http\Controllers\Front\CartController::findCountryByGeoip($country_iso);
            }
            $geoip_state_array = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
            if ($geoip_state == '') {
                // dd(array_key_exists('id', $geoip_state_array));
                if (array_key_exists('id', $geoip_state_array)) {
                    $geoip_state = $geoip_state_array['id'];
                }
            }

            // dd($product);
            if ($country == $geoip_country || $state == $geoip_state || ($country == '' && $state == '')) {
                if ($product == 1 && $shop == 1 && $cart == 1) {
                    $result = $this->calculateTotalcart($rate, $price, $cart = 1, $shop = 1);
                }
                if ($product == 1 && $shop == 0 && $cart == 0) {
                    $result = $this->calculateSub($rate, $price, $cart1 = 1, $shop1 = 1);
                }
                if ($product == 1 && $shop == 1 && $cart == 0) {
                    $result = $this->calculateSub($rate, $price, $cart1, $shop1 = 0);
                }
                if ($product == 1 && $shop == 0 && $cart == 1) {
                    $result = $this->calculateSub($rate, $price, $cart1 = 0, $shop1);
                }
                if ($product == 0 && $shop == 0 && $cart == 0) {
                    $result = $this->calculateTotalcart($rate, $price, $cart1 = 0, $shop1 = 0);
                }
                if ($product == 0 && $shop == 1 && $cart == 1) {
                    $result = $this->calculateTotalcart($rate, $price, $cart1, $shop1);
                }
                if ($product == 0 && $shop == 1 && $cart == 0) {
                    $result = $this->calculateTotalcart($rate, $price, $cart1 = 0, $shop1);
                }
                if ($product == 0 && $shop == 0 && $cart == 1) {
                    $result = $this->calculateTotalcart($rate, $price, $cart = 1, $shop = 1);
                }
            }

            return $result;
        } catch (\Exception $ex) {
            dd($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function withoutTaxRelation($productid, $currency)
    {
        try {
            $product = $this->product->findOrFail($productid);
            $controller = new \App\Http\Controllers\Front\CartController();
            // dd($price);
            $price = $controller->cost($productid);
            // dd($price);

            return $price;
        } catch (\Exception $ex) {
            dd($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function calculateTotal($rate, $price)
    {
        try {
            $tax_amount = $price * ($rate / 100);
            $total = $price + $tax_amount;
            //dd($total);
            return $total;
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function calculateSub($rate, $price, $cart, $shop)
    {
        try {
            if (($cart == 1 && $shop == 1) || ($cart == 1 && $shop == 0) || ($cart == 0 && $shop == 1)) {
                $total = $price / (($rate / 100) + 1);

                return $total;
            }

            return $price;
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function calculateTotalcart($rate, $price, $cart, $shop)
    {
        try {
            // dd($rate, $price, $cart, $shop);

            if (($cart == 1 && $shop == 1) || ($cart == 1 && $shop == 0) || ($cart == 0 && $shop == 1)) {
                $tax_amount = $price * ($rate / 100);
                // dd($tax_amount);
                $total = $price + $tax_amount;
                // dd($total);
                return $total;
            }

            return $price;
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function plans($url, $id)
    {
        $plan = new Plan();
        // dd($plan);
        $plan_form = 'No subscription';
        $plans = $plan->where('product', '=', $id)->pluck('name', 'id')->toArray();
        // dd($plans);
        $plans = $this->prices($id);
        // dd($plans);
        // dd((count($plans) > 0));
        if (count($plans) > 0) {
            $plan_form = \Form::select('subscription', ['Plans' => $plans], null);
            // dd($plan_form);
        }
        $form = \Form::open(['method' => 'get', 'url' => $url]).
        $plan_form.
        \Form::hidden('id', $id);

        return $form;
    }

    public function prices($id)
    {
        $plan = new Plan();
        $plans = $plan->where('product', $id)->get();
        // dd($plans);
        $price = [];
        $cart_controller = new \App\Http\Controllers\Front\CartController();
        $currency = $cart_controller->currency();

        foreach ($plans as $value) {
            $cost = $value->planPrice()->where('currency', $currency)->first()->add_price;

            $cost = \App\Http\Controllers\Front\CartController::rounding($cost);
            $months = round($value->days / 30 / 12);
            // dd($months);
            $price[$value->id] = $months.' Year at '.$currency.' '.$cost.'/year';
        }
        // dd($price);
        $this->leastAmount($id);

        return $price;
    }

    public function leastAmount($id)
    {
        // dd($id);
        $cost = 'Free';
        $plan = new Plan();
        $plans = $plan->where('product', $id)->get();
        // dd($plans);
        $cart_controller = new \App\Http\Controllers\Front\CartController();
        $currency = $cart_controller->currency();
        // dd($plans->count() > 0);
        if ($plans->count() > 0) {
            foreach ($plans as $value) {
                $days = $value->min('days');

                $month = round($days / 30);
                $price = $value->planPrice()->where('currency', $currency)->min('add_price');

                // $price = \App\Http\Controllers\Front\CartController::calculateTax($id, $price, 1, 0, 1);

                $price = \App\Http\Controllers\Front\CartController::rounding($price);
                // dd($price);
            }
            $cost = "$currency $price /year";
        } else {
            $price = $cart_controller->productCost($id);
            $product_cost = \App\Http\Controllers\Front\CartController::calculateTax($id, $price, 1, 0, 1);
            $product_cost = \App\Http\Controllers\Front\CartController::rounding($product_cost);
            if ($product_cost != 0) {
                $cost = $currency.' '.$product_cost;
            }
        }

        return $cost;
    }
}
