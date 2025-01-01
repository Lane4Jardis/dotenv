<?php

declare(strict_types=1);

namespace Jardis\DotEnv;

use Jardis\DotEnv\query\GetFilesFromPath;
use Jardis\DotEnv\query\GetValuesFromFiles;
use Jardis\DotEnv\service\CastTypeHandler;
use Exception;

/**
 * The DotEnv class provides loading and processing environment variables from .env files for public and private
 */
class DotEnv implements DotEnvInterface
{
    private GetFilesFromPath $getFilesFromPath;
    private GetValuesFromFiles $getValuesFromFiles;
    private CastTypeHandler $castTypeHandler;

    /** @var array<string|null> */
    private array $loadedEnvironments = [];

    public function __construct(
        ?GetFilesFromPath $fileFinder = null,
        ?GetValuesFromFiles $fileContentReader = null,
        ?CastTypeHandler $castTypeHandler = null
    ) {
        $this->getFilesFromPath = $fileFinder ?? new GetFilesFromPath();
        $this->castTypeHandler = $castTypeHandler ?? new CastTypeHandler();
        $this->getValuesFromFiles = $fileContentReader ?? new GetValuesFromFiles($this->castTypeHandler);
    }

    /** @throws Exception */
    public function load(string $pathToEnvFiles, ?bool $public = true): ?array
    {
        $appEnv = $_ENV['APP_ENV'] ? strtolower($_ENV['APP_ENV']) : null;

        $filesToLoad = ($this->getFilesFromPath)($pathToEnvFiles, $appEnv, $this->loadedEnvironments);
        $envResult = ($this->getValuesFromFiles)($filesToLoad, $public);
        $this->loadedEnvironments = array_merge($this->loadedEnvironments, $filesToLoad);

        return $public === true ? null : $envResult;
    }
}
