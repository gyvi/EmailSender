<?php

namespace EmailSender\MessageLog\Application\Service;

use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageLog\Application\Contract\MessageLogServiceInterface;
use EmailSender\MessageLog\Domain\Aggregator\MessageLog;
use EmailSender\MessageLog\Domain\Builder\MessageLogBuilder;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

/**
 * Class MessageLogService
 *
 * @package EmailSender\MessageLog\Application\Service
 */
class MessageLogService implements MessageLogServiceInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     *
     * @ignoreCodeCoverage
     */
    public function listMessagesFromLog(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        $response->getBody()->write('listMessagesFromLog');

        return $response;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message           $message
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @return \EmailSender\MessageLog\Domain\Aggregator\MessageLog
     */
    public function addMessageToMessageLog(Message $message, MessageStore $messageStore): MessageLog
    {
        $messageLogBuilder = new MessageLogBuilder();
        $messageLog        = $messageLogBuilder->buildMessageLogFromMessage($message, $messageStore);

        // TODO: Implement repository store. Return with the messageLogId.
        $messageLog->setMessageLogId(new UnsignedInteger(1));

        return $messageLog;
    }
}
