<?php

namespace EmailSender\Email\Application\Contract;

use EmailSender\Email\Domain\Aggregate\Email;

/**
 * Interface EmailServiceInterface
 *
 * @package EmailSender\Email
 */
interface EmailServiceInterface
{
    /**
     * @param array $request
     *
     * @return \EmailSender\Email\Domain\Aggregate\Email
     *
     * @throws \InvalidArgumentException
     */
    public function getEmailFromRequest(array $request): Email;
}
