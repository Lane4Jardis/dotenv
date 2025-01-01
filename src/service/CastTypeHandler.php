<?php

declare(strict_types=1);

namespace Jardis\DotEnv\service;

use Jardis\DotEnv\query\GetUserHome;
use InvalidArgumentException;
use Exception;

/**
 * This class runs all given castType services in $convertServices
 */
class CastTypeHandler
{
    /** @var array<string|null|object> */
    private array $castTypeClasses = [
        StringVariableToValue::class => null,
        GetUserHome::class => null,
        StringToNumeric::class => null,
        StringToBool::class => null,
        StringToArray::class => null,
    ];

    /**
     * @return string|null
     * @throws Exception
     */
    public function __invoke(?string $value = null)
    {
        if ($value === null) {
            return null;
        }

        foreach ($this->castTypeClasses as $castTypeServiceClass => $castTypeService) {
            $castTypeService = $castTypeService ?? new $castTypeServiceClass($this);
            $this->castTypeClasses[$castTypeServiceClass] = $castTypeService;

            $value = is_callable($castTypeService) ? $castTypeService($value) : $value;

            if (is_array($value) || is_bool($value) || is_int($value) || is_float($value)) {
                break;
            }
        }

        return $value;
    }

    public function setCastTypeClass(string $castTypeClass): void
    {
        if (!class_exists($castTypeClass)) {
            $message = 'Cast type class "' . $castTypeClass . '" does not exist.';
            throw new InvalidArgumentException($message);
        }
        $this->castTypeClasses[$castTypeClass] = null;
    }

    public function removeCastTypeClass(string $castTypeClass): void
    {
        if (array_key_exists($castTypeClass, $this->castTypeClasses)) {
            unset($this->castTypeClasses[$castTypeClass]);
        }
    }
}
