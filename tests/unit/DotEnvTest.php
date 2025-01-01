<?php

declare(strict_types=1);

namespace Jardis\DotEnv\Tests\unit;

use Jardis\DotEnv\DotEnv;
use PHPUnit\Framework\TestCase;

class DotEnvTest extends TestCase
{
    private DotEnv $env;
    private string $fixturesPath;

    protected function setUp(): void
    {
        $this->env = new DotEnv();
        $this->fixturesPath = dirname(__DIR__) . '/fixtures';
        $_ENV['APP_ENV'] = 'test';
    }

    public function testLoadValueProdSuccessful(): void
    {
        $_ENV['APP_ENV'] = 'prod';
        $this->env->load($this->fixturesPath);
        $this->assertEquals('prodHost', $_ENV['DB_HOST']);
        $this->assertEquals('prodName', $_ENV['DB_NAME']);
        $this->assertEquals('mysql://prodHost:prodName@localhost', $_ENV['DATABASE_URL']);
    }

    public function testLoadValueDevSuccessful(): void
    {
        $_ENV['APP_ENV'] = 'dev';
        $this->env->load($this->fixturesPath);
        $this->assertEquals('devHost', $_ENV['DB_HOST']);
        $this->assertEquals('devName', $_ENV['DB_NAME']);
        $this->assertEquals('mysql://devHost:devName@localhost', $_ENV['DATABASE_URL']);
    }

    public function testLoadValueTestSuccessful(): void
    {
        $this->env->load($this->fixturesPath);
        $this->assertEquals('testHost', $_ENV['DB_HOST']);
        $this->assertEquals('testName', $_ENV['DB_NAME']);
        $this->assertEquals('mysql://testHost:testName@localhost', $_ENV['DATABASE_URL']);
    }

    public function testLoadPrivateLoadsCorrectly(): void
    {
        $result = $this->env->load($this->fixturesPath, false);
        $this->assertArrayHasKey('DB_HOST', $result);
        $this->assertEquals('testHost', $result['DB_HOST']);
    }

    public function testLoadPrivateHandlesNonExistentPath(): void
    {
        $result = $this->env->load('/path/to/nonexistent', false);
        $this->assertEmpty($result);
    }

    public function testEnvVarType(): void
    {
        $_ENV['APP_ENV'] = 'dev';
        $this->env->load($this->fixturesPath);
        $this->assertEquals(2, $_ENV['INT_VAR']);
        $this->assertEquals(false, $_ENV['BOOL_VAR']);
    }

    public function testPrivateEnvVarType(): void
    {
        $result = $this->env->load($this->fixturesPath, false);
        $this->assertEquals(3, $result['INT_VAR']);
        $this->assertEquals(false, $result['BOOL_VAR']);
    }

    public function testPrivateEnvVarArrayType(): void
    {
        $result = $this->env->load($this->fixturesPath, false);
        $this->assertIsArray($result['TEST']);
        $this->assertIsBool($result['TEST']['b']);
        $this->assertIsFloat($result['TEST'][1]);
        $this->assertIsString($result['TEST'][3]);
        $this->assertIsArray($result['TEST']['test']);
        $this->assertIsArray($result['TEST']['test']['test2']);
        $this->assertCount(4, $result['TEST']['test']['test2']);
    }

    public function testPrivateEnvVarHOME(): void
    {
        $result = $this->env->load($this->fixturesPath, false);
        $this->assertIsString($result['HOME']);
        $this->assertStringContainsString('/', $result['HOME']);
    }
}
