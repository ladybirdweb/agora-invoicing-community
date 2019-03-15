<?php

namespace Voerro\Laravel\VisitorTracker\Geoip;

class Ipstack extends Driver
{
    protected function getEndpoint($ip)
    {
        $key = config('visitortracker.ipstack_key');

        return "http://api.ipstack.com/{$ip}?access_key={$key}";
    }

    public function latitude()
    {
        return $this->data->latitude;
    }

    public function longitude()
    {
        return $this->data->longitude;
    }

    public function country()
    {
        return $this->data->country_name;
    }

    public function countryCode()
    {
        return $this->data->country_code;
    }

    public function city()
    {
        return $this->data->city;
    }
}
