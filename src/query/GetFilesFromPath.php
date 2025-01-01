<?php

declare(strict_types=1);

namespace Jardis\DotEnv\query;

/**
 * Return full qualified fileNames
 */
class GetFilesFromPath
{
    /**
     * @param string $pathToEnvFile
     * @param string|null $appEnv
     * @param array<string|null> $loadedEnvironments
     * @return array<string>
     */
    public function __invoke(string $pathToEnvFile, ?string $appEnv = null, ?array $loadedEnvironments = []): array
    {
        $filesToLoad = [];
        $loadedEnvironments = $loadedEnvironments ?? [];
        $envTypes = ['/.env', '/.env.local'];

        $envTypes = !empty($appEnv)
            ? array_merge($envTypes, ['/.env.' . $appEnv, '/.env.' . $appEnv . '.local'])
            : $envTypes;

        foreach ($envTypes as $envType) {
            $file = $pathToEnvFile . $envType;
            if (!in_array($file, $loadedEnvironments) && file_exists($file)) {
                $filesToLoad[] = $file;
            }
        }

        return $filesToLoad;
    }
}
