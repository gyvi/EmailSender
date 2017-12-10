<?php
/**
 * Created by PhpStorm.
 * User: gyvi
 * Date: 2017. 12. 04.
 * Time: 20:32
 */

namespace Test\Unit\EmailSender\EmailQueue\Application\Catalog;

use EmailSender\EmailQueue\Application\Catalog\EmailQueuePropertyNamesList;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailQueuePropertyNamesListTest
 *
 * @package Test\Unit\EmailSender\EmailQueue
 */
class EmailQueuePropertyNamesListTest extends TestCase
{
    /**
     * Test list elements.
     */
    public function testListElements()
    {
        $this->assertEquals('emailLogId',      EmailQueuePropertyNamesList::EMAIL_LOG_ID);
        $this->assertEquals('composedEmailId', EmailQueuePropertyNamesList::COMPOSED_EMAIL_ID);
        $this->assertEquals('delay',           EmailQueuePropertyNamesList::DELAY);
    }
}
