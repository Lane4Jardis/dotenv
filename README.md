# Jardis DotEnv
![Build Status](https://github.com/lane4jardis/dotenv/actions/workflows/ci.yml/badge.svg)

### A support tool for reading .env files for global and protected contexts

## Description

The Jardis DotEnv tool allows reading `.env*` files according to predefined rules for .env files. The read values can be made available in the global `$_ENV` variable or in protected contexts, such as an application domain.

The `.env*` files must be placed in a subdirectory and read using the `load($path, false)` method. This approach allows different settings compared to the global `$_ENV` without causing interactions. This is particularly useful when refactoring a monolithic application and creating protected areas with their own values within an existing application.

## Example Code

```php
use Jardis\DotEnv\DotEnv;

$dotEnv = new DotEnv();
// Load values into $_ENV
$dotEnv->load($appRootPath);

// Do not load into $_ENV and return the result as an array
$domainEnv = $dotEnv->load($domainRootPath, false);
```

## Data Types

The tool recognizes and processes the following data types:

- `string`
- `bool`
- `numerics (int, float)`
- `array (with type casting of values)`

A special feature is the support for both numeric and associative arrays in `.env*` files.

```.env
TYPE_INT=1
TYPE_BOOL=true
TYPE_STRING=teststring
TYPE_ARRAY=[1,2,3,test=>hello,test2=>true,test3=>[1,2,3,4]]

DB_HOST=testHost
DB_NAME=testName
HOME=~
DATABASE_URL=mysql://${DB_HOST}:${DB_NAME}@localhost
```

## Special Features

You can additionally customize three strategies for processing .env files via constructor injection.

```php
    public function __construct(
        ?GetFilesFromPath $fileFinder = null,
        ?GetValuesFromFiles $fileContentReader = null,
        ?CastTypeHandler $castTypeHandler = null
    ) {
        $this->getFilesFromPath = $fileFinder ?? new GetFilesFromPath();
        $this->castTypeHandler = $castTypeHandler ?? new CastTypeHandler();
        $this->getValuesFromFiles = $fileContentReader ?? new GetValuesFromFiles($this->castTypeHandler);
    }
```

This way, you can customize how .env files are searched (`GetFilesFromPath`), how values are read from the files (`GetValuesFromFiles`), and how type conversion is processed (`CastTypeHandler`).

## Quickstart with Composer

```bash
composer require jardis/dotenv
make install
```

## Quickstart via GitHub

```bash
git clone https://github.com/Land4Jardis/dotenv.git
cd dotenv
make test
```

---

## Contents in the GitHub Repository

- **Source Files**:
  - `src/DotEnv.php`
  - `tests/DotEnvTest.php`
- **Support**:
  - Docker Compose
  - `.env`
  - `pre-commit-hook.sh`
  - `Makefile` (Simply run `make` in the console)
- **Documentation**:
  - `README.md`

The Dockerfile setup for creating the PHP image is somewhat more extensive than necessary for this tool because the resulting PHP image is used in various Lane4 tools.

[![Docker Image Version](https://img.shields.io/docker/v/lane4jardis/phpcli?sort=semver)](https://hub.docker.com/r/lane4jardis/phpcli)

We also ensure that our images are as small as possible and leave no unnecessary files on your system even after repeated builds.

---

### Our Principles:
#### Delivering very high software quality
- Analyzability
- Adaptability
- Extensibility
- Modularity
- Maintainability
- Testability
- Scalability
- High performance

Enjoy using it!
