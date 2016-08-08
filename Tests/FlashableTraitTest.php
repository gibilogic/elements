<?php

/*
 * This file is part of the GiBilogic Elements package.
 *
 * (c) GiBilogic Srl <info@gibilogic.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gibilogic\Elements\Tests;

use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Gibilogic\Elements\Controller\FlashableTrait;

/**
 * Unit tests for the FlashableTrait.
 *
 * @author Matteo Guindani https://github.com/Ingannatore
 * @see FlashableTrait
 * @see \PHPUnit_Framework_TestCase
 */
class FlashableTraitTest extends \PHPUnit_Framework_TestCase
{
    use FlashableTrait;

    /**
     * @var Session $session
     */
    private $session;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->session = new Session(new MockArraySessionStorage(), new AttributeBag(), new FlashBag());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->session = null;
    }

    /**
     * Test for the 'addUserFlash' method.
     */
    public function testAddUserFlash()
    {
        $this->addUserFlash($this->session, 'type', 'message');
        $this->containsMessage($this->session->getFlashBag()->all(), 'type', 'message');
    }

    /**
     * Test for the 'addNoticeFlash' method.
     */
    public function testAddNoticeFlash()
    {
        $this->addNoticeFlash($this->session, 'message');
        $this->containsMessage($this->session->getFlashBag()->all(), 'notice', 'message');
    }

    /**
     * Test for the 'addWarningFlash' method.
     */
    public function testAddWarningFlash()
    {
        $this->addWarningFlash($this->session, 'message');
        $this->containsMessage($this->session->getFlashBag()->all(), 'warning', 'message');
    }

    /**
     * Test for the 'addErrorFlash' method.
     */
    public function testAddErrorFlash()
    {
        $this->addErrorFlash($this->session, 'message');
        $this->containsMessage($this->session->getFlashBag()->all(), 'error', 'message');
    }

    /**
     * @param array $messages
     * @param string $type
     * @param string $message
     */
    protected function containsMessage(array $messages, $type, $message)
    {
        $this->assertCount(1, $messages);
        $this->assertCount(1, $messages[$type]);
        $this->assertEquals($message, $messages[$type][0]);
    }
}
