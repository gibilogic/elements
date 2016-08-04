<?php

/*
 * This file is part of the GiBilogic Elements package.
 *
 * (c) GiBilogic Srl <info@gibilogic.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gibilogic\Elements\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Simple key-based session storage service for arrays made of key-value pairs.
 *
 * @author Matteo Guindani https://github.com/Ingannatore
 * @see SessionInterface
 */
class SessionStorageService
{
    /**
     * @var SessionInterface $session
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Returns the array of key-value pairs stored under the given key.
     *
     * @param string $key
     * @return array
     */
    public function get($key)
    {
        return $this->session->get((string)$key, []);
    }

    /**
     * Stores an array of key-value pairs under the given key.
     *
     * If the `overwrite` flag is `true`, previous values will be ignored and
     * overwritten; if the `overwrite` flag is `false` (default), previous
     * values will be used as a base for an `array_replace` (thus keeping the
     * unmodified ones).
     *
     * @param string $key
     * @param array $values
     * @param bool $overwrite
     * @return \Gibilogic\Elements\Service\SessionStorageService
     */
    public function set($key, array $values, $overwrite = false)
    {
        $key = (string)$key;

        if (!$overwrite) {
            $previousValues = $this->get($key);
            $values = array_replace($previousValues, array_filter($values));
        }

        $this->session->set($key, $values);
        return $this;
    }

    /**
     * Removes the array of key-value pairs stored under the given key.
     *
     * @param string $key
     * @return \Gibilogic\Elements\Service\SessionStorageService
     */
    public function remove($key)
    {
        $this->session->remove((string)$key);
    }
}
