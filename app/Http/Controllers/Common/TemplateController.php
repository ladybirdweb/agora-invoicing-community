<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Product\ProductController;
use App\Model\Common\Setting;
use App\Model\Common\Template;
use App\Model\Common\TemplateType;
use App\Model\Payment\Currency;
use App\Model\Payment\Plan;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxClass;
use App\Model\Payment\TaxOption;
use App\Model\Payment\TaxProductRelation;
use App\Model\Product\Price;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use Bugsnag;
use Config;
use Illuminate\Http\Request;

class TemplateController extends BaseTemplateController
{
    public $template;
    public $type;
    public $product;
    public $price;
    public $subscription;
    public $plan;
    public $tax_relation;
    public $tax;
    public $tax_class;
    public $tax_rule;
    public $currency;

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
        $this->middleware('admin', ['except' => ['show']]);

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
        $this->smtp();
    }

    public function smtp()
    {
        $settings = new Setting();
        $fields = $settings->find(1);
        $password = '';
        // $name = '';
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
                        ->addColumn('checkbox', function ($model) {
                            return "<input type='checkbox' class='template_checkbox' 
                            value=".$model->id.' name=select[] id=check>';
                        })

                         ->addColumn('name', function ($model) {
                             return $model->name;
                         })
                        ->addColumn('type', function ($model) {
                            return $this->type->where('id', $model->type)->first()->name;
                        })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('templates/'.$model->id.'/edit').
                            " class='btn btn-sm btn-primary btn-xs'><i class='fa fa-edit'
                                 style='color:white;'> </i>&nbsp;&nbsp;Edit</a>";
                        })
                        ->rawColumns(['checkbox', 'name', 'type', 'action'])
                        ->make(true);
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
            Bugsnag::notifyException($ex);

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
            $this->template->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

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
            $template = $this->template->where('id', $id)->first();
            $type = $this->type->pluck('name', 'id')->toArray();

            return view('themes.default1.common.template.edit', compact('type', 'template', 'cartUrl'));
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

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
            Bugsnag::notifyException($ex);

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
                    $template = $this->template->where('id', $id)->first();
                    if ($template) {
                        $template->delete();
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
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').
                    '!</b> './* @scrutinizer ignore-type */\Lang::get('message.success').'
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

    public function mailing($from, $to, $data, $subject, $replace = [],
     $type = '', $bcc = [], $fromname = '', $toname = '', $cc = [], $attach = [])
    {
        try {
            $transform = [];
            $page_controller = new \App\Http\Controllers\Front\PageController();
            $transform[0] = $replace;
            $data = $page_controller->transform($type, $data, $transform);
            $settings = \App\Model\Common\Setting::find(1);
            $fromname = $settings->company;
            \Mail::send('emails.mail', ['data' => $data], function ($m) use ($from, $to, $subject, $fromname, $toname, $cc, $attach, $bcc) {
                $m->from($from, $fromname);

                $m->to($to, $toname)->subject($subject);

                /* if cc is need  */
                if (!empty($cc)) {
                    foreach ($cc as $address) {
                        $m->cc($address['address'], $address['name']);
                    }
                }

                if (!empty($bcc)) {
                    foreach ($bcc as $address) {
                        $m->bcc($address);
                    }
                }

                /*  if attachment is need */
                if (!empty($attach)) {
                    foreach ($attach as $file) {
                        $m->attach($file['path'], $options = []);
                    }
                }
            });
            \DB::table('email_log')->insert([
            'date'       => date('Y-m-d H:i:s'),
            'from'       => $from,
            'to'         => $to,
             'subject'   => $subject,
            'body'       => $data,
            'status'     => 'success',
          ]);

            return 'success';
        } catch (\Exception $ex) {
            \DB::table('email_log')->insert([
            'date'     => date('Y-m-d H:i:s'),
            'from'     => $from,
            'to'       => $to,
             'subject' => $subject,
            'body'     => $data,
            'status'   => 'failed',
        ]);
            Bugsnag::notifyException($ex);
            if ($ex instanceof \Swift_TransportException) {
                throw new \Exception('We can not reach to this email address');
            }

            throw new \Exception('mailing problem');
        }
    }

    public function checkPriceWithTaxClass($productid, $currency)
    {
        try {
            $product = $this->product->findOrFail($productid);
            // dd($product);
            if ($product->tax_apply == 1) {
                $price = $this->checkTax($product->id, $currency);
            } else {
                $price = $product->price()->where('currency', $currency)->first()->sales_price;
                if (!$price) {
                    $price = $product->price()->where('currency', $currency)->first()->price;
                }
            }

            return $price;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function plans($url, $id)
    {
        $plan = new Plan();
        $plan_form = 'Free'; //No Subscription
        $plans = $plan->where('product', '=', $id)->pluck('name', 'id')->toArray();
        $plans = $this->prices($id);
        if ($plans) {
            $plan_form = \Form::select('subscription', ['Plans' => $plans], null);
        }
        $form = \Form::open(['method' => 'get', 'url' => $url]).
        $plan_form.
        \Form::hidden('id', $id);

        return $form;
    }

    public function leastAmount($id)
    {
        try {
            $cost = 'Free';
            $price = '';
            $plan = new Plan();
            $plans = $plan->where('product', $id)->get();
            $cart_controller = new \App\Http\Controllers\Front\CartController();
            $currencyAndSymbol = $cart_controller->currency();
            $prices = [];
            if ($plans->count() > 0) {
                foreach ($plans as $value) {
                    $prices[] = $value->planPrice()->where('currency', $currencyAndSymbol['currency'])->min('add_price');
                }
                $price = min($prices);
                $format = currency_format($price, $code = $currencyAndSymbol['currency']);
                $finalPrice = str_replace($currencyAndSymbol['symbol'], '', $format);
                $cost = '<span class="price-unit">'.$currencyAndSymbol['symbol'].'</span>'.$finalPrice;
            }

            return $cost;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
