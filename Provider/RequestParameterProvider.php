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

declare(strict_types=1);

namespace Mmoreram\ControllerExtraBundle\Provider;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RequestParameterProvider.
 */
class RequestParameterProvider implements Provider
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
    private $requestStack;

    /**
     * @var string
     *
     * Request type
     */
    private $requestType;

    /**
     * RequestParameterProvider constructor.
     *
     * @param RequestStack $requestStack
     * @param string       $requestType
     */
    public function __construct(
        RequestStack $requestStack,
        string $requestType
    ) {
        $this->requestStack = $requestStack;
        $this->requestType = $requestType;
    }

    /**
     * Provide related value given reference. If not found, return the same
     * reference, treated as a value.
     *
     * A map array is optional in order to have a normalization guide.
     *
     * @param string $reference
     * @param array  $map
     *
     * @return mixed
     */
    public function provide(
        string $reference,
        array $map = []
    ) {
        $request = $this->requestType == self::CURRENT_REQUEST
            ? $this
                ->requestStack
                ->getCurrentRequest()
            : $this
                ->requestStack
                ->getMasterRequest();

        if ($request instanceof Request) {
            foreach ([
                '~' => $request->attributes,
                '#' => $request->request,
                '?' => $request->query,
            ] as $symbol => $parameterBag) {
                $value = $this->resolveValueFromParameterBag(
                    $parameterBag,
                    $symbol,
                    $reference
                );

                if ($value !== $reference) {
                    return is_array($map) && isset($map[$value])
                        ? $map[$value]
                        : $value;
                }
            }
        }

        return $reference;
    }

    /**
     * Given a bag and a delimiter, return the resolved value.
     *
     * @param ParameterBag $parameterBag
     * @param string       $delimiter
     * @param string       $value
     *
     * @return mixed
     */
    protected function resolveValueFromParameterBag(
        ParameterBag $parameterBag,
        string $delimiter,
        string $value
    ) {
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
