<?php

/*
 * This file is part of the GiBilogic Elements package.
 *
 * (c) GiBilogic Srl <info@gibilogic.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gibilogic\Elements\Tests;

use Gibilogic\Elements\Service\SluggerService;

/**
 * Unit tests for the SluggerService.
 *
 * @author Matteo Guindani https://github.com/Ingannatore
 * @see SluggerService
 * @see \PHPUnit_Framework_TestCase
 */
class SluggerServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SluggerService $slugger
     */
    private static $slugger = null;

    /**
     * Test for invalid (non url-friendly) separator.
     */
    public function testInvalidSeparator()
    {
        $this->assertEquals('test-separator', $this->getSlugger()->slugify('test separator', '/'));
    }

    /**
     * Test for lowercase conversion.
     */
    public function testLowercase()
    {
        $this->assertEquals('testlowercase', $this->getSlugger()->slugify('TESTLOWERCASE'));
    }

    /**
     * Test for new lines removal.
     */
    public function testNewLines()
    {
        $this->assertEquals('testnewlines', $this->getSlugger()->slugify("test\nnew\rlines\n\r"));
    }

    /**
     * Test for dot (.) conversion.
     */
    public function testDot()
    {
        $this->assertEquals('test-dot', $this->getSlugger()->slugify('test.dot'));
    }

    /**
     * Test for whitespaces trimming.
     */
    public function testWhitespacesTrimming()
    {
        $this->assertEquals('testtrimming', $this->getSlugger()->slugify(" testtrimming\t "));
    }

    /**
     * Test for whitespaces removal.
     */
    public function testNoWhitespaces()
    {
        $this->assertTrue(strpos($this->getSlugger()->slugify("test white spaces"), ' ') === false);
    }

    /**
     * Test for invalid characters removal.
     */
    public function testInvalidCharacterRemoval()
    {
        $strings = array(
            'à', 'á', 'â', 'ã', 'å', 'À', 'Á', 'Â', 'Ã', 'Å',
            'æ', 'Æ', 'ä', 'Ä',
            '&amp;', '&',
            'ç', 'Ç', '©',
            '∂',
            'è', 'é', 'ê', 'ë', 'È', 'É', 'Ê', 'Ë', '€',
            'ì', 'í', 'î', 'ï', 'Ì', 'Í', 'Î', 'Ï',
            'ñ', 'Ñ',
            'ò', 'ó', 'ô', 'õ', 'ø', 'Ò', 'Ó', 'Ô', 'Õ', 'Ø',
            'œ', 'Œ', 'ö', 'Ö',
            '®',
            '$',
            'ß',
            'ù', 'ú', 'û', 'µ', 'Ù', 'Ú', 'Û',
            'ü', 'Ü',
            'ÿ', 'Ÿ', '¥',
            '™',
            '∏', 'π', 'Π',
            "'", "`", '"'
        );

        foreach ($strings as $string) {
            $slug = $this->getSlugger()->slugify($string);
            $this->assertTrue(
                preg_match('/[^a-z0-9\s-]/', $slug) === 0,
                sprintf("testInvalidCharacterRemoval failed for '%s' from '%s'", $slug, $string)
            );
        }
    }

    /**
     * @return SluggerService
     */
    protected function getSlugger()
    {
        if (null === self::$slugger) {
            self::$slugger = new SluggerService();
        }

        return self::$slugger;
    }
}
