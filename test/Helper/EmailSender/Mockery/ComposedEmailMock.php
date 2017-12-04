<?php

namespace Test\Helper\EmailSender\Mockery;

use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;

/**
 * Trait ComposedEmailMock
 *
 * @package Test\Helper
 */
trait ComposedEmailMock
{
    /**
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getComposedEmail(): ComposedEmail
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $composedEmailMock = $testCase->getMockBuilder(ComposedEmail::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $composedEmailMock;
    }
}
