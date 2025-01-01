<?php

declare(strict_types=1);

namespace Jardis\DotEnv\Tests\unit\service;

use Jardis\DotEnv\service\CastTypeHandler;
use Jardis\DotEnv\service\StringToArray;
use PHPUnit\Framework\TestCase;


class StringToArrayTest extends TestCase
{
    private CastTypeHandler $mockCastTypeHandler;
    private StringToArray $stringToArray;

    protected function setUp(): void
    {
        $this->mockCastTypeHandler = $this->createMock(CastTypeHandler::class);
        $this->stringToArray = new StringToArray($this->mockCastTypeHandler);
    }

    public function testWithNull(): void
    {
        $result = ($this->stringToArray)(null);
        $this->assertNull($result);
    }

    public function testWithSimpleString(): void
    {
        $input = "simple string";
        $result = ($this->stringToArray)($input);
        $this->assertSame($input, $result);
    }

    public function testWithArrayLikeString(): void
    {
        $input = "[key1=>value1,key2=>value2]";
        $this->mockCastTypeHandler
            ->expects($this->exactly(2))
            ->method('__invoke')
            ->willReturnArgument(0);

        $result = ($this->stringToArray)($input);

        $expected = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        $this->assertSame($expected, $result);
    }

    public function testWithNestedArray(): void
    {
        $input = "[key1=>[nested1=>value1],key2=>value2]";
        $this->mockCastTypeHandler
            ->method('__invoke')
            ->willReturnArgument(0);

        $result = ($this->stringToArray)($input);

        $expected = [
            'key1' => [
                'nested1' => 'value1',
            ],
            'key2' => 'value2',
        ];

        $this->assertSame($expected, $result);
    }

    public function testWithListValues(): void
    {
        $input = "[value1,value2,value3]";
        $this->mockCastTypeHandler
            ->method('__invoke')
            ->willReturnArgument(0);

        $result = ($this->stringToArray)($input);

        $expected = [
            'value1',
            'value2',
            'value3',
        ];

        $this->assertSame($expected, $result);
    }

    public function testWithMixedValues(): void
    {
        $input = "[a=>1,2,b=>true,4.1,5,hallo,7,test=>[1,2,3,4,5,test2=>[1,2,3,4]],rolf=>[a=>true]]";
        $this->mockCastTypeHandler
            ->method('__invoke')
            ->willReturnArgument(0);

        $result = ($this->stringToArray)($input);

        $expected = ['a'=>'1', 0=>'2', 'b'=>'true', 1=>'4.1', 2=>'5', 'hallo', '7', 'test'=>['1','2','3','4','5', 'test2'=>['1','2','3','4']], 'rolf'=>['a'=>'true']];

        $this->assertSame($expected, $result);
    }

    public function testWithEmptyBrackets(): void
    {
        $input = "[]";
        $this->mockCastTypeHandler
            ->expects($this->never())
            ->method('__invoke');

        $result = ($this->stringToArray)($input);

        $this->assertSame([], $result);
    }
}
