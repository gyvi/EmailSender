<?php

namespace Test\Helper\EmailSender\Mockery;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Collection\EmailAddressCollection;

/**
 * Trait CollectionMock
 *
 * @package Test\Helper\EmailSender\Mockery
 */
trait CollectionMock
{
    /**
     * @param array $values
     *
     * @return \EmailSender\Core\Collection\EmailAddressCollection|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getEmailAddressCollectionMock(array $values)
    {
        $iterator = [];

        /** @var array $value */
        foreach ($values as $value) {
            $iterator[] = $this->getEmailAddressMock(
                $value[EmailAddressPropertyNameList::ADDRESS],
                $value[EmailAddressPropertyNameList::NAME]
            );
        }

        return $this->getCollectionMock(EmailAddressCollection::class, $iterator);
    }
}