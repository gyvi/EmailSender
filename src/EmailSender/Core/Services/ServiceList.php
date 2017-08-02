<?php

namespace EmailSender\Core\Services;

/**
 * Class ServiceList
 *
 * @package EmailSender\Core
 */
class ServiceList
{
    /**
     * Logger.
     *
     * @var string
     */
    const LOGGER = 'logger';

    /**
     * Error handler
     *
     * @var string
     */
    const ERROR_HANDLER = 'errorHandler';

    /**
     * PHP error handler (7.x Error throw)
     *
     * @var string
     */
    const PHP_ERROR_HANDLER = 'phpErrorHandler';

    /**
     * Email builder.
     *
     * @var string
     */
    const EMAIL_BUILDER = 'emailBuilder';
}