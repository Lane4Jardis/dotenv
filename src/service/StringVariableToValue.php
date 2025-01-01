<?php

declare(strict_types=1);

namespace Jardis\DotEnv\service;

/**
 * Transforms all string vars to values based on environment values
 */
class StringVariableToValue
{
    public function __invoke(?string $value = null): ?string
    {
        if ($value === null) {
            return null;
        }

        return preg_replace_callback(
            '/\${([^}]+)}/',
            function ($matches) {
                $varName = $matches[1];
                return getenv($varName) ?: $matches[0];
            },
            $value
        );
    }
}
