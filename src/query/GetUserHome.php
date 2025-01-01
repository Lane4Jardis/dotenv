<?php

declare(strict_types=1);

namespace Jardis\DotEnv\query;

use RuntimeException;

/**
 * Return home path of active user
 */
class GetUserHome
{
    public const HOME_DRIVE = 'HOMEDRIVE';
    public const HOME_PATH = 'HOMEPATH';
    public const HOME = 'HOME';

    /** @throws RuntimeException */
    public function __invoke(?string $value = null): ?string
    {
        if (is_string($value) && str_contains($value, '~')) {
            $value = trim($value);

            if (strpos($value, '~') === 0) {
                $homeDir = $this->getHomeDir();

                if (empty($homeDir)) {
                    throw new RuntimeException('HOME environment variable is not set!');
                }

                return str_replace('~', $homeDir, $value);
            }
        }

        return $value;
    }

    protected function getHomeDir(): ?string
    {
        $result = ($this->getOsType() === 'Windows')
            ? getenv(static::HOME_DRIVE) . getenv(static::HOME_PATH)
            : getenv(static::HOME);

        return is_string($result) ? $result : null;
    }

    protected function getOsType(): string
    {
        return PHP_OS_FAMILY;
    }
}
