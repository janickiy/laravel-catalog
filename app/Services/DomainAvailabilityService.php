<?php

namespace App\Services;

class DomainAvailabilityService
{
    /**
     * Checks domain availability with a short HTTP request.
     *
     * @param string $domain
     * @param int $timeout
     * @return bool
     */
    public function isAvailable(string $domain, int $timeout = 5): bool
    {
        if (! filter_var($domain, FILTER_VALIDATE_URL)) {
            return false;
        }

        $curl = curl_init($domain);

        if ($curl === false) {
            return false;
        }

        curl_setopt_array($curl, [
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeout,
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return (bool) $response;
    }
}
