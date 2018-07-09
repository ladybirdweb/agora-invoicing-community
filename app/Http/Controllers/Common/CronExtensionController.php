<?php

namespace App\Http\Controllers\Common;

use App\Model\Order\Order;

class CronExtensionController extends CronController
{
    public function getOrderById($id)
    {
        $order = Order::find($id);

        return $order;
    }

    public function getInvoiceByOrderId($orderid)
    {
        $order = Order::find($orderid);
        $invoice = $order->invoice()->first();

        return $invoice;
    }

    public function getInvoiceItemByInvoiceId($invoiceid)
    {
        $invoice = $this->invoice->find($invoiceid);
        $item_id = $invoice->invoiceItem()->first();

        return $item_id;
    }

    public function get30DaysUsers()
    {
        //$users = [];
        $users = $this->get30DaysExpiryUsers();
        //dd($users);
        if (count($users) > 0) {
            return $users[0]['users'];
        }

        return $users;
    }

    public function get15DaysUsers()
    {
        $users = [];
        $users = $this->get15DaysExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['users'];
        }

        return $users;
    }

    public function get1DaysUsers()
    {
        $users = [];
        $users = $this->getOneDayExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['users'];
        }

        return $users;
    }

    public function get0DaysUsers()
    {
        $users = [];
        $users = $this->getOnDayExpiryUsers();
        if (count($users) > 0) {
            return $users[0]['users'];
        }

        return $users;
    }

    public function getPlus1Users()
    {
        $users = [];
        $users = $this->getExpiredUsers();
        if (count($users) > 0) {
            return $users[0]['users'];
        }

        return $users;
    }
}
