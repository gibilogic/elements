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

use Symfony\Component\HttpFoundation\Request;
use Gibilogic\Elements\Controller\ParametersExtractorTrait;

/**
 * Unit tests for the ParametersExtractorTrait.
 *
 * @author Matteo Guindani https://github.com/Ingannatore
 * @see ParametersExtractorTrait
 * @see \PHPUnit_Framework_TestCase
 */
class ParametersExtractorTraitTest extends \PHPUnit_Framework_TestCase
{
    use ParametersExtractorTrait;

    /**
     * Test for a simple extraction (ie, without prefix) from "query" (GET) source.
     */
    public function testSimpleExtractionFromQuery()
    {
        $queryData = ['a' => 1, 'b' => 2];
        $requestData = ['a' => 2, 'b' => 4, 'c' => 8];
        $contentData = ['b' => 3, 'c' => 6, 'd' => 9];
        $request = $this->createRequest($queryData, $requestData, $contentData);

        $this->assertEquals($queryData, $this->extractParameters($request, null, 'query'));
    }

    /**
     * Test for a simple extraction (ie, without prefix) from "request" (POST) source.
     */
    public function testSimpleExtractionFromRequest()
    {
        $queryData = ['a' => 1, 'b' => 2];
        $requestData = ['a' => 2, 'b' => 4, 'c' => 8];
        $contentData = ['b' => 3, 'c' => 6, 'd' => 9];
        $request = $this->createRequest($queryData, $requestData, $contentData);

        $this->assertEquals($requestData, $this->extractParameters($request, null, 'request'));
    }

    /**
     * Test for a simple extraction (ie, without prefix) from "request" (POST) source.
     */
    public function testSimpleExtractionFromContent()
    {
        $queryData = ['a' => 1, 'b' => 2];
        $requestData = ['a' => 2, 'b' => 4, 'c' => 8];
        $contentData = ['b' => 3, 'c' => 6, 'd' => 9];
        $request = $this->createRequest($queryData, $requestData, $contentData);

        $this->assertEquals($contentData, $this->extractParameters($request, null, 'content'));
    }

    /**
     * Test for a simple extraction (ie, without prefix) from all sources.
     */
    public function testSimpleExtractionFromAllSources()
    {
        $queryData = ['a' => 1, 'b' => 2];
        $requestData = ['a' => 2, 'b' => 4, 'c' => 8];
        $contentData = ['b' => 3, 'c' => 6, 'd' => 9];
        $request = $this->createRequest($queryData, $requestData, $contentData);

        $this->assertEquals(
            ['a' => 2, 'b' => 3, 'c' => 6, 'd' => 9],
            $this->extractParameters($request)
        );
    }

    /**
     * Test a prefixed extraction from all sources.
     */
    public function testPrefixedExtraction()
    {
        $queryData = ['prefix_a' => 1, 'b' => 2];
        $requestData = ['a' => 2, 'prefix_b' => 4, 'c' => 8];
        $contentData = ['b' => 3, 'prefix_c' => 6, 'prefix_d' => 9];
        $request = $this->createRequest($queryData, $requestData, $contentData);

        $this->assertEquals(
            ['a' => 1, 'b' => 4, 'c' => 6, 'd' => 9],
            $this->extractParameters($request, 'prefix_')
        );
    }

    /**
     * Creates a mock-up request instance.
     *
     * @param array $query
     * @param array $request
     * @param array $content
     * @return Request
     */
    protected function createRequest(array $query, array $request, array $content)
    {
        return new Request($query, $request, [], [], [], [], json_encode($content));
    }
}
