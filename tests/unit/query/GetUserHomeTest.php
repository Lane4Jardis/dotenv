<?php

namespace Jardis\DotEnv\Tests\unit\query;

use Jardis\DotEnv\query\GetUserHome;
use PHPUnit\Framework\TestCase;

class GetUserHomeTest extends TestCase
{
    private GetUserHome $getUserHome;

    protected function setUp(): void
    {
        $this->getUserHome = new GetUserHome();
    }

    public function testReplaceTildeWithHomeDir()
    {
        $input = '~/documents';
        $homeDir = '/home/user';
        putenv("HOME=$homeDir");

        $result = ($this->getUserHome)($input);

        $this->assertEquals('/home/user/documents', $result);
        putenv("HOME"); // Reset HOME environment variable
    }

    public function testNoReplacementForStringsWithoutTilde()
    {
        $input = '/path/to/file';

        $result = ($this->getUserHome)($input);

        $this->assertEquals('/path/to/file', $result);
    }

    public function testExceptionWhenHomeNotSet()
    {
        $input = '~/documents';
        putenv("HOME"); // Clear HOME environment variable

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('HOME environment variable is not set');

        ($this->getUserHome)($input);

    }

    public function testNullInputReturnsNull()
    {
        $result = ($this->getUserHome)(null);

        $this->assertNull($result);
    }

    public function testSimulateWindowsOnLinux()
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            $partialMockGetHomeDir = $this->getMockBuilder(GetUserHome::class)
                ->onlyMethods(['getOsType'])
                ->getMock();

            $partialMockGetHomeDir->expects($this->once())
                ->method('getOsType')
                ->willReturn('Windows');

            putenv('HOMEDRIVE=C:');
            putenv('HOMEPATH=/Users/user');

            $result = $partialMockGetHomeDir('~');

            $this->assertEquals('C:/Users/user', $result);

            putenv('HOMEDRIVE');
            putenv('HOMEPATH');
        }
        else {
            $this->markTestSkipped('Test only valid on non-Windows OS');
        }
    }

    public function testSimulateLinuxOnWindows()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $partialMockGetHomeDir = $this->getMockBuilder(GetUserHome::class)
                ->onlyMethods(['getOsType'])
                ->getMock();

            $partialMockGetHomeDir->expects($this->once())
                ->method('getOsType')
                ->willReturn('Linux');

            putenv('HOME=/Users/user');

            $result = $partialMockGetHomeDir('~');
            $this->assertEquals('/Users/user', $result);
        }
        else {
            $this->markTestSkipped('Test only valid on Windows OS');
        }
    }
}
