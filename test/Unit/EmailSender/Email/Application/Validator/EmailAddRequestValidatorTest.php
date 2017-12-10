<?php

namespace Test\Unit\EmailSender\Email\Application\Validator;

use EmailSender\Email\Application\Catalog\EmailPropertyNameList;
use EmailSender\Email\Application\Validator\EmailAddRequestValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailAddRequestValidatorTest
 *
 * @package Test\Unit\EmailSender\Email
 */
class EmailAddRequestValidatorTest extends TestCase
{
    /**
     * Test validate with empty request.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Empty request.
     */
    public function testValidateWithEmptyRequest()
    {
        $emailAddRequestValidator = new EmailAddRequestValidator();

        $emailAddRequestValidator->validate([]);
    }

    /**
     * Test validate with invalid request.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid request.
     */
    public function testValidateWithInvalidRequest()
    {
        $emailAddRequestValidator = new EmailAddRequestValidator();

        $emailAddRequestValidator->validate(null);
    }

    /**
     * Test validate with invalid request property.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testValidateWithInvalidRequestProperty()
    {
        $emailAddRequestValidator = new EmailAddRequestValidator();

        $emailAddRequestValidator->validate(
            [
                'invalidPropertyName' => 'invalidPropertyValue',
            ]
        );
    }

    /**
     * Test validate with missing request property.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testValidateWithMissingRequestProperty()
    {
        $emailAddRequestValidator = new EmailAddRequestValidator();

        $emailAddRequestValidator->validate(
            [
                EmailPropertyNameList::FROM => 'propertyValue',
            ]
        );
    }

    /**
     * Test validate with valid request.
     */
    public function testValidateWithValidRequest()
    {
        $emailAddRequestValidator = new EmailAddRequestValidator();

        $emailAddRequestValidator->validate(
            [
                EmailPropertyNameList::FROM     => 'propertyValue',
                EmailPropertyNameList::TO       => 'propertyValue',
                EmailPropertyNameList::SUBJECT  => 'propertyValue',
                EmailPropertyNameList::BODY     => 'propertyValue',
                EmailPropertyNameList::CC       => 'propertyValue',
                EmailPropertyNameList::BCC      => 'propertyValue',
                EmailPropertyNameList::DELAY    => 'propertyValue',
                EmailPropertyNameList::REPLY_TO => 'propertyValue',
            ]
        );

        $this->assertTrue(true); // Test void return...
    }
}
