<?php

namespace Lane4\DotEnv\Tests\unit\query;

use Jardis\DotEnv\query\GetValuesFromFiles;
use Jardis\DotEnv\service\CastTypeHandler;
use PHPUnit\Framework\TestCase;

class GetValuesFromFilesTest extends TestCase
{
    private $castTypeHandler;
    private $getValuesFromFiles;

    protected function setUp(): void
    {
        $this->castTypeHandler = $this->createMock(CastTypeHandler::class);
        $this->getValuesFromFiles = new GetValuesFromFiles($this->castTypeHandler);
    }

    public function testReturnsMergedValuesNotPublic()
    {
        $this->castTypeHandler
            ->method('__invoke')
            ->willReturnArgument(0); // Rückgabe des gleichen Werts für einfache Tests

        $file = [dirname(__DIR__) . '/../fixtures/.env'];

        $result = ($this->getValuesFromFiles)($file, false);

        $expected = [
            'DB_HOST' => 'prodHost',
            'DB_NAME' => 'prodName',
            'HOME' => '~',
            'DATABASE_URL' => 'mysql://${DB_HOST}:${DB_NAME}@localhost',
            'BOOL_VAR' => 'true',
            'INT_VAR' => '1',
            'TEST' => '[a=>1,2,b=>true,4,5,6,7,test=>[1,2,3,4]]'
        ];

        $this->assertEquals($expected, $result);
    }

    public function testInvokeSkipsUnreadableFiles()
    {
        $this->castTypeHandler
            ->method('__invoke')
            ->willReturnArgument(0); // Rückgabe des gleichen Werts für einfache Tests

        $file = [dirname(__DIR__) . '/../fixtures/.notFoundenv'];

        $result = ($this->getValuesFromFiles)($file, false);

        $this->assertEquals([], $result);
    }

    public function testLoadFileValuesParsesValidRows()
    {
        $this->castTypeHandler
            ->method('__invoke')
            ->willReturnCallback(function ($value) {
                return strtoupper($value);
            });

        $file = [dirname(__DIR__) . '/../fixtures/.env'];

        $result = ($this->getValuesFromFiles)($file);

        $expected = [
            'DB_HOST' => 'PRODHOST',
            'DB_NAME' => 'PRODNAME',
            'HOME' => '~',
            'DATABASE_URL' => 'MYSQL://${DB_HOST}:${DB_NAME}@LOCALHOST',
            'BOOL_VAR' => 'TRUE',
            'INT_VAR' => '1',
            'TEST' => '[A=>1,2,B=>TRUE,4,5,6,7,TEST=>[1,2,3,4]]'
        ];

        $this->assertEquals($expected, $result);
    }
}
