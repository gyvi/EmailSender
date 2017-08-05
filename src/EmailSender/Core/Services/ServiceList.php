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
     * Email composer.
     *
     * @var string
     */
    const EMAIL_COMPOSER = 'emailComposer';

    /**
     * Message store reader.
     *
     * @var string
     */
    const MESSAGE_STORE_READER = 'messageStoreReader';

    /**
     * Message store writer.
     *
     * @var string
     */
    const MESSAGE_STORE_WRITER = 'messageStoreWriter';

    /**
     * Message log reader.
     *
     * @var string
     */
    const MESSAGE_LOG_READER =  'messageLogReader';

    /**
     * Message log writer.
     *
     * @var string
     */
    const MESSAGE_LOG_WRITER =  'messageLogReader';
}