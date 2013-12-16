<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * ControllerExtraBundle, an extension of Bundle
 */
class ControllerExtraBundle extends Bundle
{

    /**
     * Boots the Bundle.
     */
    public function boot()
    {
        $kernel = $this->container->get('kernel');

        AnnotationRegistry::registerFile($kernel
            ->locateResource("@ControllerExtraBundle/Annotation/Form.php")
        );

        AnnotationRegistry::registerFile($kernel
            ->locateResource("@ControllerExtraBundle/Annotation/Flush.php")
        );

        AnnotationRegistry::registerFile($kernel
            ->locateResource("@ControllerExtraBundle/Annotation/Paginator.php")
        );
    }
}
