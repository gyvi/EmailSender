<?php

namespace EmailSender\Email\Application\Controller;

use EmailSender\Core\Controller\AbstractController;
use EmailSender\Core\Services\ServiceList;
use EmailSender\Email\Application\Service\EmailService;
use EmailSender\Email\Application\Validator\EmailAddRequestValidator;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use InvalidArgumentException;

/**
 * Class EmailQueueController
 *
 * @package EmailSender\EmailQueue
 */
class EmailController extends AbstractController
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \RuntimeException
     */
    public function add(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        /** @var \Closure $view */
        $view = $this->container->get(ServiceList::VIEW);

        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $this->container->get(ServiceList::LOGGER);

        /** @var \Closure $composedEmailReaderService */
        $composedEmailReaderService = $this->container->get(ServiceList::COMPOSED_EMAIL_READER);

        /** @var \Closure $composedEmailWriterService */
        $composedEmailWriterService = $this->container->get(ServiceList::COMPOSED_EMAIL_WRITER);

        /** @var \Closure $emailLogReaderService */
        $emailLogReaderService = $this->container->get(ServiceList::EMAIL_LOG_READER);

        /** @var \Closure $emailLogWriterService */
        $emailLogWriterService = $this->container->get(ServiceList::EMAIL_LOG_WRITER);

        /** @var \Closure $queueService */
        $queueService = $this->container->get(ServiceList::QUEUE);

        /** @var array $queueServiceSettings */
        $queueServiceSettings = $this->container->get('settings')[ServiceList::QUEUE];

        /** @var array $queueServiceSettings */
        $smtpService = $this->container->get(ServiceList::SMTP);

        try {
            (new EmailAddRequestValidator())->validate($request->getParsedBody());
        } catch (InvalidArgumentException $e) { // Invalid request.
            $logger->warning($e->getMessage(), $e->getTrace());

            /** @var \Slim\Http\Response $response */
            $response = $response
                ->withStatus(400)
                ->withJson([
                    'message' => 'Invalid request.',
                    'description' => $e->getMessage(),
                ]);

            return $response;
        }

        $emailService = new EmailService(
            $view,
            $logger,
            $queueService,
            $queueServiceSettings,
            $composedEmailReaderService,
            $composedEmailWriterService,
            $emailLogReaderService,
            $emailLogWriterService,
            $smtpService
        );

        return $emailService->add($request, $response, $getRequest);
    }
}
