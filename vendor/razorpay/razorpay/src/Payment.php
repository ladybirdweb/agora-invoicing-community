<?php

namespace Razorpay\Api;

class Payment extends Entity
{
    /**
     * @param $id Payment id
     */
    public function fetch($id)
    {
        return parent::fetch($id);
    }

    public function all($options = array())
    {
        return parent::all($options);
    }

    /**
     * @param $id Payment id
     */
    public function refund($attributes = array())
    {
        $refund = new Refund;

        $attributes = array_merge($attributes, array('payment_id' => $this->id));

        return $refund->create($attributes);
    }

    /**
     * @param $id Payment id
     */
    public function capture($attributes = array())
    {
        $relativeUrl = $this->getEntityUrl() . $this->id . '/capture';

        return $this->request('POST', $relativeUrl, $attributes);
    }

    public function transfer($attributes = array())
    {
        $relativeUrl = $this->getEntityUrl() . $this->id . '/transfers';

        return $this->request('POST', $relativeUrl, $attributes);
    }

    public function refunds()
    {
        $refund = new Refund;

        $options = array('payment_id' => $this->id);

        return $refund->all($options);
    }

    public function transfers()
    {
        $transfer = new Transfer();

        $transfer->payment_id = $this->id;

        return $transfer->all();
    }

    public function bankTransfer()
    {
        $relativeUrl = $this->getEntityUrl() . $this->id . '/bank_transfer';

        return $this->request('GET', $relativeUrl);
    }
}
