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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RequestParameterProvider
 */
class RequestParameterProvider
{
    /**
     * @var Request
     *
     * Request
     */
    protected $request;

    /**
     * Construct method
     *
     * @param RequestStack $requestStack Request stack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
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
     * @param mixed  $map   Map
     *
     * @return mixed
     */
    public function getParameterValue($value, $map = null)
    {
        if ($this->request instanceof Request) {

            $bag = $this->request->attributes;

            $trimedValue = trim($value, '~');

            if (
                ('~' . $trimedValue . '~' === $value) &&
                $bag->has($trimedValue)
            ) {
                $value = $bag->get($trimedValue);
            }
        }

        return is_array($map) && isset($map[$value])
            ? $map[$value]
            : $value;
    }
}
