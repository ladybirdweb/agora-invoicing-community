<?php

namespace App\Plugins\Paypal\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\Paypal\Model\Paypal;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    protected $paypal;

    public function __construct()
    {
        $paypal = new Paypal();
        $this->paypal = $paypal;
    }

    public function PassToPayment($requests)
    {
        try {
            //dd($requests);
            $request = $requests['request'];
            $order = $requests['order'];
            $data = [];
            //dd($order);

            if ($request->input('payment_gateway') == 'paypal') {
                if (!\Schema::hasTable('paypal')) {
                    throw new \Exception('Paypal is not configured');
                }
                $paypal = $this->paypal->where('id', 1)->first();
                if (!$paypal) {
                    throw new \Exception('Paypal Fields not given');
                }
                $data = $this->getFields($order);
                $this->postForm($data);
            }
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }

    public function getFields($invoice)
    {
        try {
            //dd($invoice);
            $item = [];
            $data = [];
            $user = \Auth::user();
            if (!$user) {
                throw new \Exception('No autherized user');
            }
            $config = $this->paypal->where('id', 1)->first();
            if ($config) {
                $business = $config->business;
                $cmd = $config->cmd;
                $return = $config->success_url;
                $cancel_return = $config->cancel_url;
                $notify_url = $config->notify_url;
                $image_url = $config->image_url;
                $rm = 1;
                $currency_code = $invoice->currency;
                $invoice_id = $invoice->id;
                $first_name = $user->first_name;
                $last_name = $user->last_name;
                $address1 = $user->address;
                $city = $user->town;
                $zip = $user->zip;
                $email = $user->email;

                $data = [
                    'business'      => $business,
                    'cmd'           => $cmd,
                    'return'        => $return,
                    'cancel_return' => $cancel_return,
                    'notify_url'    => $notify_url,
                    'image_url'     => $image_url,
                    'rm'            => $rm,
                    'currency_code' => $currency_code,
                    'invoice'       => $invoice_id,
                    'first_name'    => $first_name,
                    'last_name'     => $last_name,
                    'address1'      => $address1,
                    'city'          => $city,
                    'zip'           => $zip,
                    'email'         => $email,
                ];

                $items = $invoice->invoiceItem()->get()->toArray();
                //dd($items);
                if (count($items) > 0) {
                    for ($i = 0; $i < count($items); $i++) {
                        $n = $i + 1;
                        $item = [
                            "item_name_$n" => $items[$i]['product_name'],
                            "quantity_$n"  => $items[$i]['quantity'],
                        ];
                    }
                    $data = array_merge($data, $item);
                    $total = ['amount' => $invoice->grand_total];
                    $data = array_merge($data, $total);
                }
            }

            return $data;
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }

    public function postCurl($data)
    {
        try {
            $config = $this->paypal->where('id', 1)->first();
            if (!$config) {
                throw new \Exception('Paypal Fields not given');
            }
            $url = $config->paypal_url;
            $post_data = http_build_query($data);
            echo $url;
            dd($post_data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);
            dd($output);
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }

    public function postForm($data)
    {
        try {
            $config = $this->paypal->where('id', 1)->first();
            if (!$config) {
                throw new \Exception('Paypal Fields not given');
            }
            $url = $config->paypal_url;
            $gif_path = asset('dist/gif/gifloader.gif');
            echo "<img src=$gif_path>";
            echo "<form action=$url id=form name=redirect method=post>";
            foreach ($data as $key => $value) {
                echo "<input type=hidden name=$key value=$value>";
            }
            echo '</form>';
            echo"<script language='javascript'>document.redirect.submit();</script>";
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }
}
