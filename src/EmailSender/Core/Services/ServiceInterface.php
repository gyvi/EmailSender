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
    public function getService(): Closure;
}
