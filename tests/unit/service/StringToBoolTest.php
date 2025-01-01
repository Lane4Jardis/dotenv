<?php

declare(strict_types=1);

namespace Jardis\DotEnv\Tests\unit\service;

use Jardis\DotEnv\service\StringToBool;
use PHPUnit\Framework\TestCase;

class StringToBoolTest extends TestCase
{
    private StringToBool $stringToBool;

    protected function setUp(): void
    {
        $this->stringToBool = new StringToBool();
    }

    /**
     * Test when input is null
     */
    public function testInvokeWithNull(): void
    {
        $result = ($this->stringToBool)(null);
        $this->assertNull($result);
    }

    /**
     * Test when input is "true"
     */
    public function testInvokeWithTrueString(): void
    {
        $result = ($this->stringToBool)("true");
        $this->assertTrue($result);
    }

    /**
     * Test when input is "false"
     */
    public function testInvokeWithFalseString(): void
    {
        $result = ($this->stringToBool)("false");
        $this->assertFalse($result);
    }

    /**
     * Test when input is an invalid boolean string
     */
    public function testInvokeWithInvalidBooleanString(): void
    {
        $input = "not-a-bool";
        $result = ($this->stringToBool)($input);
        $this->assertSame($input, $result);
    }

    /**
     * Test when input is a string representing "1"
     */
    public function testInvokeWithOneAsString(): void
    {
        $result = ($this->stringToBool)("1");
        $this->assertTrue($result);
    }

    /**
     * Test when input is a string representing "0"
     */
    public function testInvokeWithZeroAsString(): void
    {
        $result = ($this->stringToBool)("0");
        $this->assertFalse($result);
    }
}
