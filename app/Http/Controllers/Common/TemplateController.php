<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Product\ProductController;
use App\Model\Common\Template;
use App\Model\Common\TemplateType;
use App\Model\licence\Licence;
use App\Model\Payment\Plan;
use App\Model\Product\Price;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
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

        $licence = new Licence();
        $this->licence = $licence;
    }

    public function index()
    {
        try {
            return view('themes.default1.common.template.inbox');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetTemplates()
    {
        return \Datatable::collection($this->template->select('id', 'name', 'type')->get())
                        ->addColumn('#', function ($model) {
                            return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
                        })
                        ->showColumns('name')
                        ->addColumn('type', function ($model) {
                            return $this->type->where('id', $model->type)->first()->name;
                        })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('templates/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
                        })
                        ->searchColumns('name')
                        ->orderColumns('name')
                        ->make();
    }

    public function create()
    {
        try {
            $controller = new ProductController();
            $url = $controller->GetMyUrl();
            $i = $this->template->orderBy('created_at', 'desc')->first()->id + 1;
            $cartUrl = $url.'/'.$i;
            $type = $this->type->lists('name', 'id')->toArray();

            return view('themes.default1.common.template.create', compact('type', 'cartUrl'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
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
            $type = $this->type->lists('name', 'id')->toArray();

            return view('themes.default1.common.template.edit', compact('type', 'template', 'cartUrl'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function update($id, Request $request)
    {
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

    public function Mailing($from, $to, $data, $subject, $replace = [], $fromname = '', $toname = '', $cc = [], $attach = [])
    {
        try {
            if (!array_key_exists('title', $replace)) {
                $replace['title'] = '';
            }
            if (!array_key_exists('currency', $replace)) {
                $replace['currency'] = '';
            }
            if (!array_key_exists('price', $replace)) {
                $replace['price'] = '';
            }
            if (!array_key_exists('subscription', $replace)) {
                $replace['subscription'] = '';
            }
            if (!array_key_exists('name', $replace)) {
                $replace['name'] = '';
            }
            if (!array_key_exists('url', $replace)) {
                $replace['url'] = '';
            }
            if (!array_key_exists('password', $replace)) {
                $replace['password'] = '';
            }
            if (!array_key_exists('address', $replace)) {
                $replace['address'] = '';
            }
            if (!array_key_exists('username', $replace)) {
                $replace['username'] = '';
            }
            if (!array_key_exists('email', $replace)) {
                $replace['email'] = '';
            }
            $array1 = ['{{title}}', '{{currency}}', '{{price}}', '{{subscription}}', '{{name}}', '{{url}}', '{{password}}', '{{address}}', '{{username}}', '{{email}}'];
            $array2 = [$replace['title'], $replace['currency'], $replace['price'], $replace['subscription'], $replace['name'], $replace['url'], $replace['password'], $replace['address'], $replace['username'], $replace['email']];

            $data = str_replace($array1, $array2, $data);

            \Mail::send('emails.mail', ['data' => $data], function ($m) use ($from, $to, $subject, $fromname, $toname, $cc,$attach) {
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
        } catch (\Exception $ex) {
            //dd($ex);
            throw new \Exception('mailing problem');
        }
    }

    public function mailtest($id)
    {
        $from = 'vijaycodename47@gmail.com';
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
        $this->Mailing($from, $to, $data, $subject, 'from', 'to', $cc, $attachments, $replace);
    }

    public function show($id)
    {
        try {
            if ($this->template->where('type', 3)->where('id', $id)->first()) {
                $data = $this->template->where('type', 3)->where('id', $id)->first()->data;
                //dd($data);

                $products = $this->product->where('id', '!=', 1)->take(4)->get();

                //dd($products);
                if (count($products) > 0) {
                    $template = '';
                    foreach ($products as $product) {
                        $url = $product->shoping_cart_link;
                        $title = $product->name;
                        if ($product->description) {
                            $description = str_replace('</ul>', '', str_replace('<ul>', '', $product->description));
                        } else {
                            $description = '';
                        }

                        $price = $this->price->where('product_id', $product->id)->where('currency', 'USD')->first()->price;

                        $currency = $this->price->where('product_id', $product->id)->where('currency', 'USD')->first()->currency;

                        $subscription = $this->plan->where('id', $this->price->where('product_id', $product->id)->where('currency', 'USD')->first()->subscription)->first()->name;

                        $array1 = ['{{title}}', '{{currency}}', '{{price}}', '{{subscription}}', '<li>{{feature}}</li>', '{{url}}'];
                        $array2 = [$title, $currency, $price, $subscription, $description, $url];
                        $template .= str_replace($array1, $array2, $data);
                    }

                    //dd($template);
                    return view('themes.default1.common.template.shoppingcart', compact('template'));
                } else {
                    $template = '<p>No Products</p>';

                    return view('themes.default1.common.template.shoppingcart', compact('template'));
                }
            } else {
                return redirect('/')->with('fails', 'no such record');
            }
        } catch (\Exception $e) {
            //dd($e);
            return redirect('/')->with('fails', $e->getMessage());
        }
    }

    public function popup($title, $body, $name = '', $modelid = '', $class = 'null', $trigger = false)
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
                            <div class='modal-dialog'>
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
                                </div>
                            </div>
                        </div>';
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
