<?php

namespace Test\Helper\EmailSender;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Test\Helper\EmailSender\Mockery\CollectionMock;
use Test\Helper\EmailSender\Mockery\ComposedEmailMock;
use Test\Helper\EmailSender\Mockery\DateTimeMock;
use Test\Helper\EmailSender\Mockery\EmailLogMock;
use Test\Helper\EmailSender\Mockery\EmailMock;
use Test\Helper\EmailSender\Mockery\EmailQueueMock;
use Test\Helper\EmailSender\Mockery\RecipientsMock;
use Test\Helper\EmailSender\Mockery\ServiceMock;
use Test\Helper\EmailSender\Mockery\ValueObjectMock;
use ArrayIterator;

/**
 * Class Mockery
 *
 * @package Test\Helper
 */
class Mockery
{
    use ValueObjectMock;
    use ServiceMock;
    use EmailLogMock;
    use EmailQueueMock;
    use ComposedEmailMock;
    use EmailMock;
    use CollectionMock;
    use RecipientsMock;
    use DateTimeMock;

    /**
     * @var \PHPUnit\Framework\TestCase
     */
    private $testCase;

    /**
     * Mockery constructor.
     *
     * @param \PHPUnit\Framework\TestCase $testCase
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * @param string $valueObject
     * @param mixed  $value
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getValueObjectMock(string $valueObject, $value): PHPUnit_Framework_MockObject_MockObject
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $valueObjectMock = $this->testCase->getMockBuilder($valueObject)
            ->disableOriginalConstructor()
            ->getMock();

        $valueObjectMock->expects($this->testCase->any())
            ->method('getValue')
            ->willReturn($value);

        return $valueObjectMock;
    }

    /**
     * @param string $collection
     * @param array  $values
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getCollectionMock(string $collection, array $values): PHPUnit_Framework_MockObject_MockObject
    {
        /** @var PHPUnit_Framework_MockObject_MockObject $collectionMock */
        $collectionMock = $this->testCase->getMockBuilder($collection)
            ->disableOriginalConstructor()
            ->getMock();

        $collectionMock->expects($this->testCase->any())
            ->method('getIterator')
            ->willReturn(new ArrayIterator($values));

        return $collectionMock;
    }
}
