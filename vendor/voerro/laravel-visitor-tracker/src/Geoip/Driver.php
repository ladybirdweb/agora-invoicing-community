<?php

namespace Voerro\Laravel\VisitorTracker\Geoip;

use Voerro\Laravel\VisitorTracker\Models\Visit;
use GuzzleHttp\Client;

abstract class Driver
{
    /**
     * Holds data fetched from a remote geoapi service
     *
     * @var Object
     */
    protected $data;

    /**
     * Fetch data from a remote geoapi service
     *
     * @param Voerro\Laravel\VisitorTracker\Models\Visit $visit
     * @return $this
     */
    public function getDataFor(Visit $visit)
    {
        $client = new Client();

        $response = $client->get($this->getEndpoint($visit->ip));

        if ($response->getStatusCode() == 200) {
            $this->data = json_decode($response->getBody()->getContents());

            return $this;
        }

        return null;
    }

    /**
     * Returns an endpoint to fetch the data from
     *
     * @param string $ip IP address to fetch geolocation data for
     * @return string
     */
    abstract protected function getEndpoint($ip);

    /**
     * Returns latitude from the fetched data
     *
     * @return string
     */
    abstract public function latitude();

    /**
     * Returns longitude from the fetched data
     *
     * @return string
     */
    abstract public function longitude();

    /**
     * Returns country from the fetched data
     *
     * @return string
     */
    abstract public function country();

    /**
     * Returns country code from the fetched data
     *
     * @return string
     */
    abstract public function countryCode();

    /**
     * Returns city from the fetched data
     *
     * @return string
     */
    abstract public function city();
}
