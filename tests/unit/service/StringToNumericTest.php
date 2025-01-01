<?php

declare(strict_types=1);

namespace Jardis\DotEnv\Tests\unit\service;

use Jardis\DotEnv\service\StringToNumeric;
use PHPUnit\Framework\TestCase;

class StringToNumericTest extends TestCase
{
    private StringToNumeric $stringToNumeric;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stringToNumeric = new StringToNumeric();
    }

    public function testInvokeWithIntegerStringReturnsInteger(): void
    {
        $result = ($this->stringToNumeric)('123');
        $this->assertIsInt($result);
        $this->assertSame(123, $result);
    }

    public function testInvokeWithFloatStringReturnsFloat(): void
    {
        $result = ($this->stringToNumeric)('123.45');
        $this->assertIsFloat($result);
        $this->assertSame(123.45, $result);
    }

    public function testInvokeWithNonNumericStringReturnsSameString(): void
    {
        $result = ($this->stringToNumeric)('hello');
        $this->assertIsString($result);
        $this->assertSame('hello', $result);
    }

    public function testInvokeWithNullReturnsNull(): void
    {
        $result = ($this->stringToNumeric)(null);
        $this->assertNull($result);
    }

    public function testInvokeWithEmptyStringReturnsEmptyString(): void
    {
        $result = ($this->stringToNumeric)('');
        $this->assertIsString($result);
        $this->assertSame('', $result);
    }

    public function testInvokeWithLeadingZerosReturnsNumeric(): void
    {
        $result = ($this->stringToNumeric)('00123');
        $this->assertIsInt($result);
        $this->assertSame(123, $result);
    }

    public function testInvokeWithScientificNotationReturnsFloat(): void
    {
        $result = ($this->stringToNumeric)('1.23e3');
        $this->assertIsFloat($result);
        $this->assertSame(1230.0, $result);
    }
}
