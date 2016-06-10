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

/**
 * A simple slugger service.
 */
class SluggerService
{
    /**
     * Default words separator
     */
    const DEFAULT_SEPARATOR = '-';

    /**
     * Returns the slugified version of the string.
     *
     * @param string $string The string to be slugified
     * @param string $separator The words separator (optional)
     * @return string The slugified string
     */
    public function slugify($string, $separator = self::DEFAULT_SEPARATOR)
    {
        if (null === $string || '' === $string) {
            return '';
        }

        return $this->replaceInvalidCharacters(
            $this->toLowercase(
                $this->replaceExtendedCharacters(
                    $this->removeNewLines($string)
                )
            ),
            $this->sanitizeSeparator($separator)
        );
    }

    /**
     * Removes new lines characters from the given string.
     *
     * @param string $string
     * @return string
     */
    protected function removeNewLines($string)
    {
        return (string)str_replace(array("\r", "\n"), '', $string);
    }

    /**
     * Replaces all the extended characters like 'ä' with their "plain" version.
     *
     * @param string $string
     * @return string
     */
    protected function replaceExtendedCharacters($string)
    {
        $replacements = array(
            'a' => array('à', 'á', 'â', 'ã', 'å', 'À', 'Á', 'Â', 'Ã', 'Å'),
            'ae' => array('æ', 'Æ', 'ä', 'Ä'),
            'and' => array('&amp;', '&'),
            'c' => array('ç', 'Ç', '©'),
            'd' => array('∂'),
            'e' => array('è', 'é', 'ê', 'ë', 'È', 'É', 'Ê', 'Ë', '€'),
            'i' => array('ì', 'í', 'î', 'ï', 'Ì', 'Í', 'Î', 'Ï'),
            'n' => array('ñ', 'Ñ'),
            'o' => array('ò', 'ó', 'ô', 'õ', 'ø', 'Ò', 'Ó', 'Ô', 'Õ', 'Ø'),
            'oe' => array('œ', 'Œ', 'ö', 'Ö'),
            'r' => array('®'),
            's' => array('$'),
            'ss' => array('ß'),
            'u' => array('ù', 'ú', 'û', 'µ', 'Ù', 'Ú', 'Û'),
            'ue' => array('ü', 'Ü'),
            'y' => array('ÿ', 'Ÿ', '¥'),
            'tm' => array('™'),
            'pi' => array('∏', 'π', 'Π'),
            ' ' => array("'", "`"),
        );

        foreach ($replacements as $output => $input) {
            $string = str_replace($input, $output, $string);
        }

        return $string;
    }

    /**
     * Converts the string to lowercase.
     *
     * @param string $string
     * @return string
     */
    protected function toLowercase($string)
    {
        return mb_strtolower($string, mb_internal_encoding());
    }

    /**
     * Replaces all non-alphanumeric characters with the given separator
     * character.
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    protected function replaceInvalidCharacters($string, $separator)
    {
        return preg_replace('#(' . $separator . '+)#', $separator, preg_replace('#[\s]+#', $separator, rtrim(trim(preg_replace('#[^a-z0-9\s]#', ' ', $string)))));
    }

    /**
     * Returns the given separator character if it's URL-friendly, or the
     * default separator otherwise.
     *
     * @param string $separator
     * @return string
     */
    private function sanitizeSeparator($separator)
    {
        if ($separator == self::DEFAULT_SEPARATOR) {
            return $separator;
        }

        if ($separator == rawurlencode($separator)) {
            return $separator;
        }

        return self::DEFAULT_SEPARATOR;
    }
}
