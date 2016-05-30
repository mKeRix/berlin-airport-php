<?php

namespace AirportApi;


abstract class Api
{
    public $language = 'de';

    public $fbbAirport = 'all';

    const DATE_FORMAT = 'Y.m.d H:i:s';
    
    const USER_AGENT = 'Dalvik/2.1.0 (Linux; U; Android 5.1.1; SM-J500FN Build/LMY48B)';

    /**
     * Sets language.
     *
     * @param string $language
     * @return $this
     */
    public function language($language) {
        // don't continue if language is invalid
        if (!in_array($language, ['en', 'de'])) {
            throw new \InvalidArgumentException('Language must be en or de');
        }

        $this->language = $language;

        return $this;
    }

    /**
     * Sets airport.
     *
     * @param string $airport
     * @return $this
     */
    public function airport($airport) {
        // don't continue if airport is invalid
        if (!in_array($airport, ['all', 'txl', 'sxf'])) {
            throw new \InvalidArgumentException('Airport must be a valid code');
        }

        $this->fbbAirport = $airport;

        return $this;
    }
}