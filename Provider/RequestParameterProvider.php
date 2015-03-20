<?php

/*
 * This file is part of the ControllerExtraBundle for Symfony2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Mmoreram\ControllerExtraBundle\Provider;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RequestParameterProvider
 */
class RequestParameterProvider
{
    /**
     * @var string
     *
     * Master request
     */
    const MASTER_REQUEST = 'master';

    /**
     * @var string
     *
     * Current request
     */
    const CURRENT_REQUEST = 'current';

    /**
     * @var RequestStack
     *
     * Request Stack
     */
    protected $requestStack;

    /**
     * @var string
     *
     * Request type
     */
    protected $requestType;

    /**
     * Construct method
     *
     * @param RequestStack $requestStack Request stack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Set request type
     *
     * @param string $requestType Request type
     *
     * @return $this Self object
     */
    public function setRequestType($requestType)
    {
        $this->requestType = $requestType;

        return $this;
    }

    /**
     * Checks the value format.
     *
     * If value has parameter format, the referenced parameter will be looked
     * for in the request query parameters bag
     *
     * If found, this will be returned, otherwise plain value will be returned
     *
     * In all cases, if returned value is set as key in the injected map, value
     * of index found will be returned insteadof original value
     *
     * If request is null, return just the value
     *
     * @param string $value Value
     * @param array  $map   Map
     *
     * @return string Value
     */
    public function getParameterValue($value, array $map = null)
    {
        $request = $this->requestType == self::CURRENT_REQUEST
            ? $this
                ->requestStack
                ->getCurrentRequest()
            : $this
                ->requestStack
                ->getMasterRequest();

        if ($request instanceof Request) {

            /**
             * Resolving the elements from the query
             */
            $value = $this->resolveValueFromParameterBag(
                $request->attributes,
                '~',
                $value
            );

            /**
             * Resolving the values from the request ($_POST)
             */
            $value = $this->resolveValueFromParameterBag(
                $request->request,
                '#',
                $value
            );

            /**
             * Resolving the values from the query ($_GET)
             */
            $value = $this->resolveValueFromParameterBag(
                $request->query,
                '?',
                $value
            );
        }

        return is_array($map) && isset($map[$value])
            ? $map[$value]
            : $value;
    }

    /**
     * Given a bag and a delimiter, return the resolved value
     *
     * @param ParameterBag $parameterBag Parameter Bag
     * @param string       $delimiter    Delimiter
     * @param string       $value        Value
     *
     * @return string Resolved value
     */
    protected function resolveValueFromParameterBag(
        ParameterBag $parameterBag,
        $delimiter,
        $value
    )
    {
        $trimmedValue = trim($value, $delimiter);

        if (
            ($delimiter . $trimmedValue . $delimiter === $value) &&
            $parameterBag->has($trimmedValue)
        ) {
            $value = $parameterBag->get($trimmedValue);
        }

        return $value;
    }
}
