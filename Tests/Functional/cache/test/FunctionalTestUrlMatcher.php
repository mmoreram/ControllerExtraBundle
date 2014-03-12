<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * FunctionalTestUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class FunctionalTestUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        if (0 === strpos($pathinfo, '/fake')) {
            // FakeBundleControllerEntity
            if ($pathinfo === '/fake/entity') {
                return array (  '_controller' => 'Mmoreram\\ControllerExtraBundle\\Tests\\FakeBundle\\Controller\\FakeController::entityFunctionalAction',  '_route' => 'FakeBundleControllerEntity',);
            }

            // FakeBundleControllerJsonResponse
            if ($pathinfo === '/fake/jsonresponse') {
                return array (  '_controller' => 'Mmoreram\\ControllerExtraBundle\\Tests\\FakeBundle\\Controller\\FakeController::jsonResponseFunctionalAction',  '_route' => 'FakeBundleControllerJsonResponse',);
            }

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
