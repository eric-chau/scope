<?php

namespace URF\Scope\Tests;

use URF\Scope\Scope;

/**
 * Test class for URF\Scope\Scope
 *
 * @author echau <eriic.chau@gmail.com>
 *
 * @coversDefaultClass \URF\Scope\Scope
 */
class ScopeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \URF\Scope\Scope
     */
    private $scope;

    public function setUp()
    {
        $this->scope = new Scope();
    }

    public function tearDown()
    {
        $this->scope = null;
    }

    /**
     * @covers ::__construct
     * @covers ::getAll
     */
    public function test__construct()
    {
        $scope = new Scope();
        $this->assertEquals([], $scope->getAll());
    }

    /**
     * @covers ::add
     * @covers ::has
     */
    public function test_add()
    {
        $this->assertFalse($this->scope->has('foo'));
        $this->scope->add('foo');
        $this->assertTrue($this->scope->has('foo'));
    }

    /**
     * @covers ::add
     * @covers ::has
     */
    public function test_add_and_has_raisesInvalidArgumentException()
    {
        try {
            $this->scope->has(true);
            $this->fail('An expected exception (InvalidArgumentException) has not been raised.');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
            $this->assertEquals('Type of `scope` argument must be string', $e->getMessage());
        }

        try {
            $this->scope->add('foo');
            $this->scope->add('foo');
            $this->fail('An expected exception (InvalidArgumentException) has not been raised.');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
            $this->assertEquals('`foo` already exists in scope list', $e->getMessage());
        }
    }

    /**
     * @covers ::add
     * @covers ::getAll
     */
    public function test_getAll()
    {
        $expected = ['foo', 'bar'];
        $this->assertEquals([], $this->scope->getAll());
        $this->scope->add('foo');
        $this->assertEquals(['foo'], $this->scope->getAll());
        $this->scope->add('bar');
        $this->assertEquals($expected, $this->scope->getAll());
    }

    /**
     * @covers ::add
     * @covers ::disable
     * @covers ::enable
     * @covers ::isActive
     * @covers ::raiseExceptionIfScopeNotFound
     */
    public function test_isActive()
    {
        $this->scope->add('foo');
        $this->assertFalse($this->scope->isActive('foo'));

        $this->scope->enable('foo');
        $this->assertTrue($this->scope->isActive('foo'));

        $this->scope->add('bar', true);
        $this->assertTrue($this->scope->isActive('bar'));

        $this->scope->disable('bar');
        $this->assertFalse($this->scope->isActive('bar'));
    }

    /**
     * @covers ::enable
     * @covers ::isActive
     * @covers ::raiseExceptionIfScopeNotFound
     */
    public function test_isActive_and_enable_raiseInvalidArgumentException()
    {
        try {
            $this->scope->enable('foo');
            $this->fail('An expected exception (InvalidArgumentException) has not been raised.');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
            $this->assertEquals('`foo` is not in scope list', $e->getMessage());
        }

        try {
            $this->scope->isActive('foo');
            $this->fail('An expected exception (InvalidArgumentException) has not been raised.');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
            $this->assertEquals('`foo` is not in scope list', $e->getMessage());
        }
    }

    /**
     * @covers ::add
     * @covers ::disable
     * @covers ::getActives
     */
    public function test_getActives()
    {
        $this->assertEquals([], $this->scope->getActives());
        $this->scope->add('foo');
        $this->scope->add('bar');
        $this->assertEquals([], $this->scope->getActives());
        $this->scope->add('hello', true);
        $this->scope->add('world');
        $this->scope->add('urf');
        $this->scope->enable('urf');
        $this->assertEquals(['hello', 'urf'], $this->scope->getActives());
        $this->scope->disable('hello');
        $this->assertEquals(['urf'], $this->scope->getActives());
    }
}
