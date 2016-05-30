<?php

namespace AirportApi;


use AirportApi\Exceptions\ApiException;

class FlightList extends Api
{
    const API_ENDPOINT = 'https://ws.berlin-airport.de/webservices/getFlightList.php';

    public $page = 1;

    public $max = 20;

    public $ad = 'a';

    public $dateFrom;

    public $dateTo;

    public $shortView = 1;

    /**
     * FlightList constructor.
     */
    public function __construct() {
        $this->dateFrom = (new \DateTime())->setTime(0, 0 ,0)->format(self::DATE_FORMAT);
        $this->dateTo = (new \DateTime())->setTime(23,59, 59)->format(self::DATE_FORMAT);
    }

    /**
     * Sets page.
     *
     * @param int $page
     * @return $this
     */
    public function page($page) {
        // don't continue if page is invalid
        if (!is_int($page) || $page <= 0) {
            throw new \InvalidArgumentException('Page must be numeric and greater than 0');
        }

        $this->page = $page;

        return $this;
    }

    /**
     * Sets max.
     *
     * @param string $max
     * @return $this
     */
    public function max($max) {
        // don't continue if max is invalid
        if (!is_int($max) || $max <= 0) {
            throw new \InvalidArgumentException('Max must be numeric and greater than 0');
        }

        $this->max = $max;

        return $this;
    }

    /**
     * Sets arrivals/departures.
     *
     * @param string $ad
     * @return $this
     */
    public function ad($ad) {
        // don't continue if not a or d
        if (!in_array($ad, ['a', 'd'])) {
            throw new \InvalidArgumentException('AD must either be a (arrivals) or d (departures)');
        }
        
        $this->ad = $ad;
        
        return $this;
    }

    /**
     * Shortcut for setting the query to arrivals.
     *
     * @return $this
     */
    public function arrivals() {
        $this->ad('a');

        return $this;
    }

    /**
     * Shortcut for setting the query to departures.
     *
     * @return $this
     */
    public function departures() {
        $this->ad('d');

        return $this;
    }

    /**
     * Sets the from date for the query time window.
     *
     * @param \DateTime $date
     * @return $this
     */
    public function dateFrom($date) {
        // don't continue if not a valid DateTime object
        if (!$date instanceof \DateTime) {
            throw new \InvalidArgumentException('Date from must be a valid DateTime object');
        }

        $this->dateFrom = $date->format(self::DATE_FORMAT);

        return $this;
    }

    /**
     * Sets the to date for the query time window.
     *
     * @param \DateTime $date
     * @return $this
     */
    public function dateTo($date) {
        // don't continue if not a valid DateTime object
        if (!$date instanceof \DateTime) {
            throw new \InvalidArgumentException('Date to must be a valid DateTime object');
        }

        $this->dateTo = $date->format(self::DATE_FORMAT);

        return $this;
    }

    /**
     * Sets the query to short view.
     *
     * @param bool $shortView
     * @return $this
     */
    public function shortView($shortView = true) {
        // don't continue if not boolean
        if (!is_bool($shortView)) {
            throw new \InvalidArgumentException('Short view must be boolean');
        }

        $this->shortView = $shortView ? 1 : 0;

        return $this;
    }

    /**
     * Sends an api request with the current parameters.
     *
     * @return array
     * @throws ApiException
     */
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