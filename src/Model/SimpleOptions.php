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

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A wrapper for the OptionsResolver class to ease the creation and management
 * of options and/or parameters lists.
 */
class SimpleOptions extends OptionsResolver
{
    /**
     * Creates an instance of the SimpleOptions class and resolves the given
     * options.
     *
     * @param array $options
     * @return array
     */
    public static function createAndResolve(array $options = [])
    {
        return (new self())->resolve($options);
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setDefaults($this->getDefaults());

        foreach ($this->getAllowedTypes() as $field => $type) {
            $this->setAllowedTypes($field, $type);
        }

        foreach ($this->getAllowedValues() as $field => $type) {
            $this->setAllowedValues($field, $type);
        }
    }

    /**
     * Returns the default values.
     *
     * @return array
     */
    protected function getDefaults()
    {
        return [];
    }

    /**
     * Returns the allowed types.
     *
     * @return array
     */
    protected function getAllowedTypes()
    {
        return [];
    }

    /**
     * Returns the allowed values.
     *
     * @return array
     */
    protected function getAllowedValues()
    {
        return [];
    }
}
