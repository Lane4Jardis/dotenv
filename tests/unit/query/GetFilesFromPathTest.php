<?php

declare(strict_types=1);

namespace Jardis\DotEnv\Tests\unit\query;

use Jardis\DotEnv\query\GetFilesFromPath;
use PHPUnit\Framework\TestCase;

class GetFilesFromPathTest extends TestCase
{
    private string $basePath = __DIR__ . '/../../fixtures';

    private GetFilesFromPath $getFilesFromPath;
    protected function setUp(): void
    {
        $this->getFilesFromPath = new GetFilesFromPath();
    }

    public function testWithNullEnvironment(): void
    {
        $result = ($this->getFilesFromPath)($this->basePath, null);

        $expected = [
            $this->basePath . '/.env',
            $this->basePath . '/.env.local',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testWithAppEnv(): void
    {
        $result = ($this->getFilesFromPath)($this->basePath, 'dev');

        $expected = [
            $this->basePath . '/.env',
            $this->basePath . '/.env.local',
            $this->basePath . '/.env.dev',
            $this->basePath . '/.env.dev.local',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testWithLoadedEnvironments(): void
    {
        $loadedEnvironments = [$this->basePath . '/.env.local'];

        $result = ($this->getFilesFromPath)($this->basePath, 'dev', $loadedEnvironments);

        $expected = [
            $this->basePath . '/.env',
            $this->basePath . '/.env.dev',
            $this->basePath . '/.env.dev.local',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testWithEmptyPath(): void
    {
        $result = ($this->getFilesFromPath)->__invoke('', 'dev');

        $this->assertEmpty($result);
    }
}
