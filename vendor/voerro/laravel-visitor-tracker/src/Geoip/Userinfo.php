<?php

namespace Voerro\Laravel\VisitorTracker\Geoip;

class Userinfo extends Driver
{
    protected function getEndpoint($ip)
    {
        return "https://api.userinfo.io/userinfos?ip_address={$ip}";
    }

    public function latitude()
    {
        return $this->data->position->latitude;
    }

    public function longitude()
    {
        return $this->data->position->longitude;
    }

    public function country()
    {
        return $this->data->country->name;
    }

    public function countryCode()
    {
        return $this->data->country->code;
    }

    public function city()
    {
        return $this->data->city->name;
    }
}
