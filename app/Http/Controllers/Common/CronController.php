<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use App\Model\Product\Subscription;
use Carbon\Carbon;
use App\User;
use App\Http\Controllers\Common\TemplateController;
use App\Model\Common\Template;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Invoice;

class CronController extends Controller {

    protected $subscription;
    protected $order;
    protected $user;
    protected $template;
    protected $invoice;

    public function __construct() {
        $subscription = new Subscription();
        $this->sub = $subscription;
        
        $order = new Order();
        $this->order = $order;
        
        $user = new User();
        $this->user = $user;
        
        $template = new Template();
        $this->template = $template;
        
        $invoice = new Invoice();
        $this->invoice = $invoice;
    }

    public function get30DaysExpiryInfo() {
        $plus29days = new Carbon('+29 days');
        $plus31days = new Carbon('+31 days');
        $sub = $this->sub
                ->where('ends_at', '!=', '0000-00-00 00:00:00')
                ->whereNotNull('ends_at')
                ->whereBetween('ends_at', [$plus29days, $plus31days]);
        return $sub;
    }

    public function get15DaysExpiryInfo() {
        $plus14days = new Carbon('+14 days');
        $plus16days = new Carbon('+16 days');
        $sub = $this->sub
                ->where('ends_at', '!=', '0000-00-00 00:00:00')
                ->whereNotNull('ends_at')
                ->whereBetween('ends_at', [$plus14days, $plus16days]);
        return $sub;
    }

    public function getOneDayExpiryInfo() {
        $yesterday = new Carbon('-2 days');
        $today = new Carbon('today');
        $sub = $this->sub
                ->where('ends_at', '!=', '0000-00-00 00:00:00')
                ->whereNotNull('ends_at')
                ->whereBetween('ends_at', [$yesterday, $today]);
        return $sub;
    }

    public function getOnDayExpiryInfo() {
        $yesterday = new Carbon('yesterday');
        $tomorrow = new Carbon('tomorrow');
        $sub = $this->sub
                ->where('ends_at', '!=', '0000-00-00 00:00:00')
                ->whereNotNull('ends_at')
                ->whereBetween('ends_at', [$yesterday, $tomorrow]);
        return $sub;
    }
    public function getExpiredInfo() {
        $yesterday = new Carbon('today');
        $tomorrow = new Carbon('+2 days');
        $sub = $this->sub
                ->where('ends_at', '!=', '0000-00-00 00:00:00')
                ->whereNotNull('ends_at')
                ->whereBetween('ends_at', [$yesterday, $tomorrow]);
        return $sub;
    }
    
    public function getExpiredInfoByOrderId($orderid) {
        $yesterday = new Carbon('today');
        $sub = $this->sub
                ->where('order_id',$orderid)
                ->where('ends_at', '!=', '0000-00-00 00:00:00')
                ->whereNotNull('ends_at')
                ->where('ends_at', '<', $yesterday)
                ->first();
        return $sub;
    }

    public function get30DaysExpiryUsers() {
        $sub = $this->get30DaysExpiryInfo();
        $users = [];
        if ($sub->get()->count() > 0) {
            foreach ($sub->get() as $key => $value) {
                $users[$key]['users'] = $this->sub->find($value->id)->user()->get();
                $users[$key]['orders'] = $this->sub->find($value->id)->order()->get();
                $users[$key]['subscription'] = $this->sub->find($value->id)->first();
            }
        }
        return $users;
    }

    public function get15DaysExpiryUsers() {
        $sub = $this->get15DaysExpiryInfo();
        $users = [];
        if ($sub->get()->count() > 0) {
            foreach ($sub->get() as $key => $value) {
                $users[$key]['users'] = $this->sub->find($value->id)->user()->get();
                $users[$key]['orders'] = $this->sub->find($value->id)->order()->get();
                $users[$key]['subscription'] = $value;
            }
        }
        return $users;
    }

    public function getOneDayExpiryUsers() {
        $sub = $this->getOneDayExpiryInfo();
        $users = [];
        if ($sub->get()->count() > 0) {
            foreach ($sub->get() as $key => $value) {
                $users[$key]['users'] = $this->sub->find($value->id)->user()->get();
                $users[$key]['orders'] = $this->sub->find($value->id)->order()->get();
                $users[$key]['subscription'] = $this->sub->find($value->id)->first();
            }
        }
        return $users;
    }

    public function getOnDayExpiryUsers() {
        $sub = $this->getOnDayExpiryInfo();
        $users = [];
        if ($sub->get()->count() > 0) {
            foreach ($sub->get() as $key => $value) {
                $users[$key]['users'] = $this->sub->find($value->id)->user()->get();
                $users[$key]['orders'] = $this->sub->find($value->id)->order()->get();
                $users[$key]['subscription'] = $this->sub->find($value->id)->first();
            }
        }
        return $users;
    }
    
    public function getExpiredUsers() {
        $sub = $this->getExpiredInfo();
        $users = [];
        if ($sub->get()->count() > 0) {
            foreach ($sub->get() as $key => $value) {
                $users[$key]['users'] = $this->sub->find($value->id)->user()->get();
                $users[$key]['orders'] = $this->sub->find($value->id)->order()->get();
                $users[$key]['subscription'] = $this->sub->find($value->id)->first();
            }
        }
        return $users;
    }

    public function get30DaysUsers() {
        $users = [];
        $users = $this->get30DaysExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['users'];
        }
        return $users;
    }

    public function get15DaysUsers() {
        $users = [];
        $users = $this->get15DaysExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['users'];
        }
        return $users;
    }

    public function get1DaysUsers() {
        $users = [];
        $users = $this->getOneDayExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['users'];
        }
        return $users;
    }

    public function get0DaysUsers() {
        $users = [];
        $users = $this->getOnDayExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['users'];
        }
        return $users;
    }
    
    public function getPlus1Users() {
        $users = [];
        $users = $this->getExpiredUsers();
        if (count($users) > 0) {
            return $users[0]['users'];
        }
        return $users;
    }

    public function get30DaysOrders() {
        $users = [];
        $users = $this->get30DaysExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['orders'];
        }
        return $users;
    }

    public function get15DaysOrders() {
        $users = [];
        $users = $this->get15DaysExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['orders'];
        }
        return $users;
    }

    public function get1DaysOrders() {
        $users = [];
        $users = $this->getOneDayExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['orders'];
        }
        return $users;
    }

    public function get0DaysOrders() {
        $users = [];
        $users = $this->getOnDayExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['orders'];
        }
        return $users;
    }
    public function getPlus1Orders() {
        $users = [];
        $users = $this->getExpiredUsers();
        if (count($users) > 0) {
            return $users[0]['orders'];
        }
        return $users;
    }

    public function get30DaysSubscription() {
        $users = [];
        $users = $this->get30DaysExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['subscription'];
        }
        return $users;
    }

    public function get15DaysSubscription() {
        $users = [];
        $users = $this->get15DaysExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['subscription'];
        }
        return $users;
    }

    public function get1DaysSubscription() {
        $users = [];
        $users = $this->getOneDayExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['subscription'];
        }
        return $users;
    }

    public function get0DaysSubscription() {
        $users = [];
        $users = $this->getOnDayExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['subscription'];
        }
        return $users;
    }
    public function getPlus1Subscription() {
        $users = [];
        $users = $this->getExpiredUsers();
        if (count($users) > 0) {
            return $users[0]['subscription'];
        }
        return $users;
    }

    public function getUsers() {
        $users = [];
        if (count($this->get30DaysUsers())) {
            array_push($users,$this->get30DaysUsers());
        }
        if (count($this->get15DaysUsers())) {
            array_push($users,$this->get15DaysUsers());
            
        }
        if (count($this->get1DaysUsers())) {
            array_push($users,$this->get1DaysUsers());
        }
        if (count($this->get0DaysUsers())) {
            array_push($users,$this->get0DaysUsers());
        }
        if (count($this->getPlus1Users())) {
            array_push($users,$this->getPlus1Users());
        }
        return $users;
    }
    public function getSubscriptions() {
        $sub = [];
        if (count($this->get30DaysSubscription())) {
            array_push($sub,$this->get30DaysSubscription());
        }
        
        if (count($this->get15DaysUsers())) {
            array_push($sub,$this->get15DaysSubscription());
            
        }
        
        if (count($this->get1DaysUsers())) {
            array_push($sub,$this->get1DaysSubscription());
        }
        
        if (count($this->get0DaysUsers())) {
            array_push($sub,$this->get0DaysSubscription());
        }
        if (count($this->getPlus1Users())) {
            array_push($sub,$this->getPlus1Subscription());
        }
        return $sub;
    }
    
    public function getUserById($id){
        $user = $this->user->find($id);
        return $user;
    }
    public function getOrderById($id){
        $order = $this->order->find($id);
        return $order;
    }
    
    public function getInvoiceItemByInvoiceId($invoiceid){
        $invoice = $this->invoice->find($invoiceid);
        $item_id = $invoice->invoiceItem()->first();
        return $item_id;
    }
    
    public function getInvoiceByOrderId($orderid){
        $order = $this->order->find($orderid);
        $invoice = $order->invoice()->first();
        return $invoice;
    }
    
    public function eachSubscription(){
        $sub = $this->getSubscriptions();
        foreach($sub as $value){
            $userid  = $value->user_id;
            $user = $this->getUserById($userid);
            $end = $value->ends_at;
            $order = $this->getOrderById($value->order_id);
            $invoice= $this->getInvoiceByOrderId($value->order_id);
            $item = $this->getInvoiceItemByInvoiceId($invoice->id);
            $product = $item->product_name;
            $this->mail($user, $end,$product,$order);
        }
    }
    
    public function mail($user,$end,$product,$order){
        $data = "";
        $subject = "";
        $replace = [];
        $controller = new TemplateController();
        $settings = \App\Model\Common\Setting::find(1);
        $from = $settings->email;
        $to = $user->email;
        $template = $this->template->where('name','Faveo Expiry')->first();
        if($template){
            $date = date_create($end);
            $end = date_format($date, 'l, F j, Y H:m A');
            $data = $template->data;
            $subject = $template->name;
            $replace = ['name'=>ucfirst($user->first_name).' '.ucfirst($user->last_name),
                'subscription'=>$end,
                'product'=>$product,
                'order'=>$order->number,];
        }
        $controller->Mailing($from, $to, $data, $subject, $replace);
    }

}
