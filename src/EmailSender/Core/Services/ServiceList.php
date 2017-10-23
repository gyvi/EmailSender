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
     * Composed email reader.
     *
     * @var string
     */
    const COMPOSED_EMAIL_READER = 'composedEmailReader';

    /**
     * Composed email writer.
     *
     * @var string
     */
    const COMPOSED_EMAIL_WRITER = 'composedEmailWriter';

    /**
     * Email log reader.
     *
     * @var string
     */
    const EMAIL_LOG_READER =  'emailLogReader';

    /**
     * Email log writer.
     *
     * @var string
     */
    const EMAIL_LOG_WRITER =  'emailLogReader';

    /**
     * Queue writer.
     *
     * @var string
     */
    const QUEUE = 'queue';

    /**
     * PHPMailer's SMTP service.
     *
     * @var string
     */
    const SMTP = 'smtp';

    /**
     * View service - Twig.
     *
     * @var string
     */
    const VIEW = 'view';
}
