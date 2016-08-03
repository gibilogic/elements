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
use Gibilogic\Elements\Service\SessionStorageService;

/**
 * Unit tests for the SessionStorageService.
 *
 * @author Matteo Guindani https://github.com/Ingannatore
 * @see SessionStorageService
 * @see \PHPUnit_Framework_TestCase
 */
class SessionStorageServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SessionStorageService $service
     */
    private $service;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->service = new SessionStorageService(
            new Session(new MockArraySessionStorage(), new AttributeBag(), new FlashBag())
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->service = null;
    }

    /**
     * Test the default value for a non-existing key.
     */
    public function testNonExistingKey()
    {
        $values = $this->service->get('nonExisting');

        $this->assertInternalType('array', $values);
        $this->assertEmpty($values);
    }

    /**
     * Test setting a key-values pair with overwrite.
     */
    public function testSetWithOverwrite()
    {
        $this->service->set('myKey', ['value1', 'value2'], true);
        $values = $this->service->get('myKey');

        $this->assertInternalType('array', $values);
        $this->assertEquals('value1', $values[0]);
        $this->assertEquals('value2', $values[1]);

        $this->service->set('myKey', ['value3', 'value4'], true);
        $values = $this->service->get('myKey');

        $this->assertInternalType('array', $values);
        $this->assertEquals('value3', $values[0]);
        $this->assertEquals('value4', $values[1]);
    }

    /**
     * Test setting a key-values pair without overwrite.
     */
    public function testSetWithoutOverwrite()
    {
        $this->service->set('myKey', ['a' => 1, 'b' => 2], true);
        $values = $this->service->get('myKey');

        $this->assertInternalType('array', $values);
        $this->assertEquals(1, $values['a']);
        $this->assertEquals(2, $values['b']);

        $this->service->set('myKey', ['b' => 3]);
        $values = $this->service->get('myKey');

        $this->assertInternalType('array', $values);
        $this->assertEquals(1, $values['a']);
        $this->assertEquals(3, $values['b']);
    }

    /**
     * Test removing a key.
     */
    public function testRemove()
    {
        $this->service->set('myKey', ['a' => 1, 'b' => 2], true);
        $this->service->remove('myKey');
        $values = $this->service->get('myKey');

        $this->assertInternalType('array', $values);
        $this->assertEmpty($values);
    }
}
