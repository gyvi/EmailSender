<?php

namespace EmailSender\Email\Domain\Service;

use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\Email\Domain\Factory\EmailFactory;

/**
 * Class GetEmailService
 *
 * @package EmailSender\Email
 */
class GetEmailService
{
    /**
     * @var \EmailSender\Email\Domain\Factory\EmailFactory
     */
    private $emailFactory;

    /**
     * GetEmailService constructor.
     *
     * @param \EmailSender\Email\Domain\Factory\EmailFactory $emailFactory
     */
    public function __construct(EmailFactory $emailFactory)
    {
        $this->emailFactory = $emailFactory;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Email\Domain\Aggregate\Email
     * @throws \InvalidArgumentException
     */
    public function get(array $request): Email
    {
        return $this->emailFactory->create($request);
    }
}
