# Jardis DotEnv
![Build Status](https://github.com/lane4jardis/dotenv/actions/workflows/ci.yml/badge.svg)

### Ein Support-Tool zum Auslesen von .env-Dateien für globale und geschützte Kontexte

## Beschreibung

Das Jardis DotEnv-Tool ermöglicht das Auslesen von `.env*`-Dateien gemäß der für .env Dateien vordefinierten Regeln. Die ausgelesenen Werte können in der globalen `$_ENV`-Variable oder in geschützten Kontexten, wie einer Anwendungsdomäne, verfügbar gemacht werden.

Die `.env*`-Dateien müssen dafür in einem Unterverzeichnis abgelegt und über die Methode `load($path, false)` ausgelesen werden. Diese Vorgehensweise erlaubt es, abweichende Einstellungen im Vergleich zum globalen `$_ENV` vorzunehmen, ohne Wechselwirkungen zu verursachen. Dies ist besonders nützlich, wenn eine monolithische Anwendung refaktorisiert werden soll und geschützte Bereiche mit eigenen Werten innerhalb einer bestehenden Anwendung entstehen sollen.

## Beispielcode

```php
use Jardis\DotEnv\DotEnv;

$dotEnv = new DotEnv();
// Werte in $_ENV laden
$dotEnv->load($appRootPath);

// Nicht in $_ENV laden und Ergebnis als Array zurückgeben
$domainEnv = $dotEnv->load($domainRootPath, false);
```

## Datentypen

Das Tool erkennt und verarbeitet die folgenden Datentypen:

- `string`
- `bool`
- `numerics (int, float)`
- `array (mit TypeCasting der Werte)`

Eine Besonderheit ist die Unterstützung sowohl numerischer als auch assoziativer Arrays in `.env*`-Dateien.

```.env
TYPE_INT=1
TYPE_BOOL=true
TYPE_STRING=teststring
TYPE_ARRAY=[1,2,3,test=>hallo,test2=>true,test3=>[1,2,3,4]]

DB_HOST=testHost
DB_NAME=testName
HOME=~
DATABASE_URL=mysql://${DB_HOST}:${DB_NAME}@localhost
```

## Special Features

Du hast zusätzlich die Möglichkeit, 3 Strategien für die Verarbeitung von .env-Dateien über Konstruktinjektion anzupassen.

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

Über diesen Weg kannst du anpassen, wie die .env Dateien gesucht werden (`GetFilesFromPath`), wie die Werte aus den Dateien ausgelesen (`GetValuesFromFiles`) und die Typumwandlung (`CastTypeHandler`) verarbeitet wird.

## Quickstart mit Composer

```bash
composer require jardis/dotenv
make install
```

## Quickstart über GitHub

```bash
git clone https://github.com/Land4Jardis/dotenv.git
cd dotenv
make test
```

---

## Lieferumfang im GitHub-Repository

- **Source Files**:
  - `src`
  - `tests`
- **Support**:
  - Docker Compose
  - `.env`
  - `pre-commit-hook.sh`
  - `Makefile` (Einfach `make` in der Konsole aufrufen)
- **Dokumentation**:
  - `README.md`

Der Aufbau des Dockerfiles zum Erstellen des PHP-Images ist etwas umfänglicher gestaltet, als es für dieses Tool notwendig ist, da das resultierende PHP-Image in verschiedenen Lane4-Tools eingesetzt wird.

[![Docker Image Version](https://img.shields.io/docker/v/lane4jardis/phpcli?sort=semver)](https://hub.docker.com/r/lane4jardis/phpcli)

Es wird auch darauf geachtet, dass unsere Images so klein wie möglich sind und auf eurem System durch ggf. wiederholtes Bauen der Images keine unnötigen Dateien verbleiben.

---

### Unsere Leitsätze:
#### Lieferung sehr hoher Softwarequalität
- Analysierbarkeit
- Anpassbarkeit
- Erweiterbarkeit
- Modularität
- Wartbarkeit
- Testbarkeit
- Skalierbarkeit
- Hohe Performance

Viel Freude bei der Nutzung!
