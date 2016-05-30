<?php

namespace AirportApi;


use AirportApi\Exceptions\ApiException;

class Flight extends Api
{
    const API_ENDPOINT = 'https://ws.berlin-airport.de/webservices/getFlightById.php';

    public $id;

    public function __construct($id) {
        // don't continue if id isn't of type integer
        if (!is_int($id)) {
            throw new \InvalidArgumentException('Flight id must be of type integer');
        }

        $this->id = $id;
    }

    public function get() {
        // prepare request
        $query = http_build_query($this);
        $headers = [
            'User-Agent' => self::USER_AGENT
        ];

        $request = \Requests::get(self::API_ENDPOINT . '?' . $query, $headers);

        // don't continue if the request wasn't successful
        if ($request->status_code != 200) {
            throw new ApiException('Api returned status code ' . $request->status_code);
        }

        $results = json_decode($request->body, true);

        return $results;
    }
}