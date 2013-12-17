<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\Tests\EventListener;

/**
 * Tests FormAnnotationEventListener class
 */
class FormAnnotationEventListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests evaluateAnnotation method
     *
     * This case considers that Annotation is a Flush annotation and no manager is defined in it
     */
    public function testEvaluateAnnotationFlushAnnotation()
    {
        $flushAnnotationEventListener = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\EventListener\FormAnnotationEventListener')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getDoctrine',
                'getDefaultManager',
            ))
            ->getMock();
    }
}