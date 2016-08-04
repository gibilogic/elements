<?php

/*
 * This file is part of the GiBilogic Elements package.
 *
 * (c) GiBilogic Srl <info@gibilogic.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gibilogic\Elements\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Generic method to extract parameters from a Request instance.
 *
 * @author Matteo Guindani https://github.com/Ingannatore
 * @see Symfony\Component\HttpFoundation\Request
 */
trait ParametersExtractorTrait
{
    /**
     * Executes the extraction from the given Request instance.
     *
     * The source can be `null` or one of the following:
     *
     * - "query": to extract from the GET parameters
     * - "request": to extract from the POST parameters
     * - "content": to extract from a JSON payload
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $prefix
     * @param string|null $source
     * @return array
     */
    protected function extractParameters(Request $request, $prefix = null, $source = null)
    {
        switch ($source) {
            case 'query':
                $parameters = $request->query->all();
                break;
            case 'request':
                $parameters = $request->request->all();
                break;
            case 'content':
                $parameters = (array)json_decode($request->getContent(), true);
                break;
            default:
                $parameters = array_merge(
                    $request->query->all(),
                    $request->request->all(),
                    (array)json_decode($request->getContent(), true)
                );
                break;
        }

        if (null !== $prefix) {
            $prefix = (string)$prefix;

            // Removes all the parameters whose key does not begin with the given prefix
            $validKeys = array_filter(array_keys($parameters), function($name) use ($prefix) {
                return 0 === strpos($name, $prefix);
            });
            $parameters = array_intersect_key($parameters, array_flip($validKeys));

            // Removes the given prefix from all the keys
            $parameters = array_combine(
                array_map(function($key) use ($prefix) {
                    return str_replace($prefix, '', $key);
                }, array_keys($parameters)), $parameters
            );
        }

        return $parameters;
    }
}
