<?php

namespace EmailSender\Core\Services;

use Closure;

/**
 * Interface ServiceInterface
 *
 * @package EmailSender\Core\Services
 */
interface ServiceInterface
{
    /**
     * @return \Closure
     */
    public function getService(): Closure;
}
