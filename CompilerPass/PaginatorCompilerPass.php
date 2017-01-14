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

namespace Mmoreram\ControllerExtraBundle\CompilerPass;

use Mmoreram\BaseBundle\CompilerPass\TagCompilerPass;

/**
 * Class PaginatorCompilerPass.
 */
final class PaginatorCompilerPass extends TagCompilerPass
{
    /**
     * Get collector service name.
     *
     * @return string
     */
    public function getCollectorServiceName() : string
    {
        return 'controller_extra.collector.paginator_evaluator';
    }

    /**
     * Get collector method name.
     *
     * @return string
     */
    public function getCollectorMethodName() : string
    {
        return 'addPaginatorEvaluator';
    }

    /**
     * Get tag name.
     *
     * @return string
     */
    public function getTagName() : string
    {
        return 'controller_extra.paginator_evaluator';
    }
}
