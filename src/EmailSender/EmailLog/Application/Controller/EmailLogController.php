<?php

namespace EmailSender\EmailLog\Application\Controller;

use EmailSender\Core\Controller\AbstractController;
use EmailSender\EmailLog\Application\Service\EmailLogService;
use EmailSender\EmailLog\Application\Validator\ListRequestValidator;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use EmailSender\Core\Services\ServiceList;
use InvalidArgumentException;

/**
 * Class EmailLogController
 *
 * @package EmailSender\EmailLog
 */
class EmailLogController extends AbstractController
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \RuntimeException
     */
    public function list(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        /** @var \Closure $view */
        $view = $this->container->get(ServiceList::VIEW);

        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $this->container->get(ServiceList::LOGGER);

        /** @var \Closure $emailLogReader */
        $emailLogReader = $this->container->get(ServiceList::EMAIL_LOG_READER);

        /** @var \Closure $emailLogWriter */
        $emailLogWriter = $this->container->get(ServiceList::EMAIL_LOG_WRITER);

        try {
            (new ListRequestValidator())->validate($request->getQueryParams());
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

        $emailLogService = new EmailLogService(
            $view,
            $logger,
            $emailLogReader,
            $emailLogWriter
        );

        return $emailLogService->list($request, $response, $getRequest);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function lister(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        /** @var \Closure $view */
        $view = $this->container->get(ServiceList::VIEW);

        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $this->container->get(ServiceList::LOGGER);

        /** @var \Closure $emailLogReader */
        $emailLogReader = $this->container->get(ServiceList::EMAIL_LOG_READER);

        /** @var \Closure $emailLogWriter */
        $emailLogWriter = $this->container->get(ServiceList::EMAIL_LOG_WRITER);

        $emailLogService = new EmailLogService(
            $view,
            $logger,
            $emailLogReader,
            $emailLogWriter
        );

        $logger->info('test');

        return $emailLogService->lister($request, $response, $getRequest);
    }
}
