<?php

namespace Test\Unit\EmailSender\EmailLog\Application\Validator;

use EmailSender\EmailLog\Application\Catalog\ListEmailLogRequestPropertyNameList;
use EmailSender\EmailLog\Application\Validator\ListEmailLogRequestValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class ListEmailLogRequestValidatorTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class ListEmailLogRequestValidatorTest extends TestCase
{
    /**
     * Test validate with empty request.
     */
    public function testValidateWithEmptyRequest()
    {
        $ListEmailLogRequestValidator = new ListEmailLogRequestValidator();

        $ListEmailLogRequestValidator->validate([]);

        $this->assertTrue(true); // Test void return...
    }

    /**
     * Test validate with invalid request.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid request.
     */
    public function testValidateWithInvalidRequest()
    {
        $ListEmailLogRequestValidator = new ListEmailLogRequestValidator();

        $ListEmailLogRequestValidator->validate(null);
    }

    /**
     * Test validate with invalid request property.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testValidateWithInvalidRequestProperty()
    {
        $ListEmailLogRequestValidator = new ListEmailLogRequestValidator();

        $ListEmailLogRequestValidator->validate(
            [
                'invalidPropertyName' => 'invalidPropertyValue',
            ]
        );
    }

    /**
     * Test validate with valid request.
     */
    public function testValidateWithValidRequest()
    {
        $ListEmailLogRequestValidator = new ListEmailLogRequestValidator();

        $ListEmailLogRequestValidator->validate(
            [
                ListEmailLogRequestPropertyNameList::FROM                   => 'propertyValue',
                ListEmailLogRequestPropertyNameList::PER_PAGE               => 'propertyValue',
                ListEmailLogRequestPropertyNameList::PAGE                   => 'propertyValue',
                ListEmailLogRequestPropertyNameList::LAST_COMPOSED_EMAIL_ID => 'propertyValue',

            ]
        );

        $this->assertTrue(true); // Test void return...
    }
}
