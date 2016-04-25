<?php

namespace App\Http\Controllers;

use App\Model\Product\Product;
use App\Model\Order\Order;
use Illuminate\Http\Request;

class HomeController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Home Controller
      |--------------------------------------------------------------------------
      |
      | This controller renders your application's "dashboard" for users that
      | are authenticated. Of course, you are free to change or remove the
      | controller as you wish. It is just here to get your app started!
      |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth', ['only' => ['index']]);
        $this->middleware('admin', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index() {
        return view('themes.default1.layouts.master');
    }

    public function version(Request $request, Product $product) {

        $url = $request->input('response_url');

        $title = $request->input('title');
        //dd($title);
        $id = $request->input('id');
        if ($id) {
            $product = $product->where('id', $id)->first();
        } else {
            $product = $product->where('name', $title)->first();
        }


        if ($product) {
            $version = str_replace('v','',$product->version);
        } else {
            $version = "Not-Available";
        }
        

        echo "<form action=$url method=post name=redirect >";
        echo "<input type=hidden name=_token value=" . csrf_token() . ">";
        echo "<input type=hidden name=value value=$version />";
        echo "</form>";
        echo"<script language='javascript'>document.redirect.submit();</script>";
    }

    public function getVersion(Request $request, Product $product) {
        $this->validate($request, [
            'title'=>'required',
        ]);
        $title = $request->input('title');
        $product = $product->where('name', $title)->first();
        $version = $product->version;
        return str_replace('v','',$product->version);
    }

    public function versionTest() {
        $url = "http://localhost/billings/agorainvoicing/agorainvoicing/public/version";
        $response = "http://localhost/billings/agorainvoicing/agorainvoicing/public/version-result";
        $name = "faveo helpdesk community";
        echo "<form action=$url method=post name=redirect >";
        echo "<input type=hidden name=_token value=csrf_token() />";
        echo "<input type=hidden name=response_url value=$response />";
        echo "<input type=hidden name=title value='faveo helpdesk community' />";
        echo "</form>";
        echo"<script language='javascript'>document.redirect.submit();</script>";
    }

    public function versionResult(Request $request) {
        dd($request->all());
    }

    public function serial(Request $request, Order $order) {

        $ul = $request->input('url');
        $url = str_replace("serial", "CheckSerial", $ul);
        $domain = $request->input('domain');
        $first = $request->input('first');
        $second = $request->input('second');
        $third = $request->input('third');
        $forth = $request->input('forth');
        $serial = $first . $second . $third . $forth;
        //dd($serial);
        $order_no = $request->input('order_no');
        $order = $order->where('number', $order_no)->first();
        if ($order) {
            if ($domain === $order->domain) {

                $key = $order->serial_key;
                if ($key === $serial) {

                    $id1 = 'true';
                    echo "<form action=$url/$id1 method=post name=redirect>";
                    echo "<input type=hidden name=_token value=csrf_token()/>";
                    echo "</form>";
                    echo"<script language='javascript'>document.redirect.submit();</script>";
                } else {
                    $id = 'false1';
                    echo "<form action=$url/$id method=post name=redirect>";
                    echo "<input type=hidden name=_token value=csrf_token()/>";
                    echo "</form>";
                    echo"<script language='javascript'>document.redirect.submit();</script>";
                }
            } else {
                $id = 'false3';
                echo "<form action=$url/$id method=post name=redirect>";
                echo "<input type=hidden name=_token value=csrf_token()/>";
                echo "</form>";
                echo"<script language='javascript'>document.redirect.submit();</script>";
            }
        } else {
            $id = 'false2';
            echo "<form action=$url/$id method=post name=redirect>";
            echo "<input type=hidden name=_token value=csrf_token()/>";
            echo "</form>";
            echo"<script language='javascript'>document.redirect.submit();</script>";
        }
    }

}
