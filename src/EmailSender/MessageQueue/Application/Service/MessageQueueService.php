<?php

namespace EmailSender\MessageQueue\Application\Service;

use EmailSender\Message\Application\Service\MessageService;
use EmailSender\MessageQueue\Application\Contract\MessageQueueServiceInterface;
use EmailSender\MessageQueue\Application\Validator\MessageQueueAddRequestValidator;
use EmailSender\MessageStore\Application\Service\MessageStoreService;
use EmailSender\MessageStore\Domain\Contract\EmailBuilderInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;

/**
 * Class MessageQueueService
 *
 * @package EmailSender\MessageQueue\Application\Service
 */
class MessageQueueService implements MessageQueueServiceInterface
{
    /**
     * @var \EmailSender\MessageStore\Domain\Contract\EmailBuilderInterface
     */
    private $emailBuilder;

    /**
     * MessageQueueService constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\EmailBuilderInterface $emailBuilder
     */
    public function __construct(EmailBuilderInterface $emailBuilder)
    {
        $this->emailBuilder = $emailBuilder;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     *
     * @ignoreCodeCoverage
     */
    public function addMessageToQueue(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        $getRequest = $request->getParsedBody();

        (new MessageQueueAddRequestValidator())->validate($getRequest);

        $messageService = new MessageService();

        $message = $messageService->getMessageFromRequest($getRequest);

        $messageStoreService = new MessageStoreService($this->emailBuilder);

        $messageStore = $messageStoreService->addMessageToMessageStore($message);

        $response
            ->withStatus(400)
            ->withHeader('Content-Type', 'application/json')
            ->getBody()
            ->write(json_encode([
                'status' => 0,
                'statusMessage' => 'Queued.',
                'messageId' => $messageStore->getMessageId()->getValue(),
                'recipients' => $messageStore->getRecipients(),
                'message' => $messageStore->getMessage()->getValue(),
            ]));

        return $response;
    }
}