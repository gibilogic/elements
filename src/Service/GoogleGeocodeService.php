<?php

/*
 * This file is part of the GiBilogic Elements package.
 *
 * (c) GiBilogic Srl <info@gibilogic.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gibilogic\Element\Geocode\Service;

use Gibilogic\Elements\Model\GeocodeableInterface;

/**
 * Wrapper for the Google's geocode service.
 *
 * @see https://developers.google.com/maps/documentation/geocoding/start
 */
class GoogleGeocodeService
{
    /**
     * URL of the Google's geocode service
     */
    const URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    /**
     * Status for a successful response
     */
    const SUCCESSFUL_RESPONSE_STATUS = 'OK';

    /**
     * @var string $apiKey A valid Google API key
     */
    protected $apiKey;

    /**
     * Constructor.
     *
     * @param string $apiKey A valid Google API key (mandatory)
     * @throws \Exception
     */
    public function __construct($apiKey)
    {
        if (empty($apiKey)) {
            throw new \Exception('The Google geocode service needs a valid Google API key');
        }

        $this->apiKey = $apiKey;
    }

    /**
     * Geocodes the given object.
     *
     * @param GeocodeableInterface $object The object to be geocoded
     * @return bool `TRUE` on success, `FALSE` otherwise
     * @see \Gibilogic\Element\Geocode\Model\GeocodedInterface
     */
    public function geocode(GeocodeableInterface $object)
    {
        try {
            list($lat, $lng) = $this->geocodeAddress($object->getAddressForGeocoding());
        } catch (\Exception $ex) {
            return false;
        }

        $object->setLatitude($lat);
        $object->setLongitude($lng);

        return true;
    }

    /**
     * Geocodes the given address.
     *
     * @param string $address The address to geocode
     * @return array The address' coordinates (latitude and longitude)
     */
    public function geocodeAddress($address)
    {
        return $this->getCoordinates($this->execute([
            'key' => $this->apiKey,
            'address' => $address,
        ]));
    }

    /**
     * Extracts and returns the coordinates (latitude and longitude) from the
     * response of the geocode service. If the response does not contain any
     * results, this method will return `NULL`.
     *
     * @param array $response The geocode service response
     * @return array The coordinates (latitude and longitude)
     * @throws \Exception
     * @see https://developers.google.com/maps/documentation/geocoding/intro#GeocodingResponses
     */
    protected function getCoordinates(array $response)
    {
        if (empty($response['results'])) {
            throw new \Exception('Unable to get a result from the geocode service');
        }

        $location = $response['results'][0]['geometry']['location'];
        return [round($location['lat'], 7), round($location['lng'], 7)];
    }

    /**
     * Executes a cURL call towards the geocode service.
     *
     * @param array $params The params of the call
     * @return array The service response as an associative array
     * @throws \Exception
     */
    private function execute(array $params)
    {
        $curlInstance = curl_init();
        if (false === $curlInstance) {
            throw new \Exception('Unable to initialize the cURL service');
        }

        curl_setopt_array($curlInstance, [
            CURLOPT_URL => sprintf('%s?%s', self::URL, http_build_query($params)),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($curlInstance);
        curl_close($curlInstance);

        if (empty($response) || self::SUCCESSFUL_RESPONSE_STATUS != $response['status']) {
            throw new \Exception('Unable to get a valid response from the geocode service');
        }

        return json_decode($response, true);
    }
}
