<?php

/*
 * This file is part of the GiBilogic Elements package.
 *
 * (c) GiBilogic Srl <info@gibilogic.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gibilogic\Elements\Model;

/**
 * Interface for classes that can be geocoded.
 *
 * @author Matteo Guindani https://github.com/Ingannatore
 */
interface GeocodeableInterface
{
    /**
     * @return string The address to be used for geocoding
     */
    public function getAddressForGeocoding();

    /**
     * @return float The current latitude in degrees
     */
    public function getLatitude();

    /**
     * @param float $latitude The new latitude in degrees
     * @return mixed
     */
    public function setLatitude($latitude);

    /**
     * @return float The current longitude in degrees
     */
    public function getLongitude();

    /**
     * @param float $longitude The new longitude in degrees
     * @return mixed
     */
    public function setLongitude($longitude);
}
