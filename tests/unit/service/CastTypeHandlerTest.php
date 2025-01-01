<?php

namespace Jardis\DotEnv\Tests\unit\service;

use InvalidArgumentException;
use Jardis\DotEnv\service\CastTypeHandler;
use PHPUnit\Framework\TestCase;

class CastTypeHandlerTest extends TestCase
{
    private CastTypeHandler $castTypeHandler;
    protected function setUp(): void
    {
        $this->castTypeHandler = new CastTypeHandler();
    }

    public function testWithNullValue(): void
    {
        $result = ($this->castTypeHandler)(null);

        $this->assertNull($result);
    }

    public function testWithIntegerValue(): void
    {
        $result = ($this->castTypeHandler)('123');

        $this->assertSame(123, $result);
        $this->assertIsInt($result);
    }

    public function testWithFloatValue(): void
    {
        $result = ($this->castTypeHandler)('123.123');

        $this->assertSame(123.123, $result);
        $this->assertIsFloat($result);
    }

    public function testWithBooleanValue(): void
    {
        $result = ($this->castTypeHandler)('true');

        $this->assertSame(true, $result);
        $this->assertIsBool($result);
    }

    public function testWithStingArrayValue(): void
    {
        $result = ($this->castTypeHandler)('[1,2,3,4,5]');

        $this->assertEquals([1,2,3,4,5], $result);
        $this->assertIsArray($result);
    }

    public function testWithStingValue(): void
    {
        $result = ($this->castTypeHandler)('testValue');

        $this->assertSame('testValue', $result);
    }

    public function testSetCastTypeClassThrowsExceptionForInvalidClass(): void
    {
        $handler = new CastTypeHandler();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cast type class "NonExistentClass" does not exist.');

        $handler->setCastTypeClass('NonExistentClass');
    }

    public function testSetCastTypeClassAddsClassSuccessfully(): void
    {
        $handler = new CastTypeHandler();

        $handler->setCastTypeClass(MockCastTypeService::class);

        $reflector = new \ReflectionClass($handler);
        $property = $reflector->getProperty('castTypeClasses');
        $property->setAccessible(true);

        $castTypeClasses = $property->getValue($handler);

        $this->assertArrayHasKey(MockCastTypeService::class, $castTypeClasses, 'Die Klasse sollte erfolgreich registriert worden sein.');
    }

    public function testRemoveCastTypeClassRemovesSuccessfully(): void
    {
        $handler = new CastTypeHandler();

        $handler->setCastTypeClass(MockCastTypeService::class);
        $handler->removeCastTypeClass(MockCastTypeService::class);

        $reflector = new \ReflectionClass($handler);
        $property = $reflector->getProperty('castTypeClasses');
        $property->setAccessible(true);

        $castTypeClasses = $property->getValue($handler);

        $this->assertArrayNotHasKey(MockCastTypeService::class, $castTypeClasses, 'Die Klasse sollte erfolgreich entfernt worden sein.');
    }

    public function testRemoveCastTypeClassDoesNotFailForNonExistentClass(): void
    {
        $handler = new CastTypeHandler();

        $handler->removeCastTypeClass('NonExistentClass');

        $this->assertTrue(true, 'Das Entfernen einer nicht vorhandenen Klasse sollte keinen Fehler erzeugen.');
    }
}

class MockCastTypeService
{
    public function __invoke($value)
    {
        return $value;
    }
}
