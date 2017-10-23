<?php

namespace EmailSender\Email\Application\Service;

use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Email\Application\Contract\EmailServiceInterface;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\Email\Domain\Factory\EmailFactory;
use EmailSender\Email\Domain\Service\GetEmailService;

/**
 * Class EmailService
 *
 * @package EmailSender\Email
 */
class EmailService implements EmailServiceInterface
{
    /**
     * @param array $request
     *
     * @return \EmailSender\Email\Domain\Aggregate\Email
     * @throws \InvalidArgumentException
     */
    public function getEmailFromRequest(array $request): Email
    {
        $emailAddressFactory           = new EmailAddressFactory();
        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
        $emailFactory                  = new EmailFactory($emailAddressFactory, $emailAddressCollectionFactory);
        $getEmailService               = new GetEmailService($emailFactory);

        return $getEmailService->get($request);
    }
}
