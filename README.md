# Doctrine Track Bundle

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-track-bundle.svg)](https://packagist.org/packages/tourze/doctrine-track-bundle)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

A Symfony bundle for tracking entity changes and logging them to the database. It helps you audit and review changes to important entities in your application.

## Features

- Automatically logs create, update, and delete operations for marked entities
- Tracks only fields annotated with `TrackColumn`
- Records user, IP, request ID, and change details
- Asynchronous log persistence
- Built-in entity log cleanup scheduling

## Installation

- PHP >= 8.1
- Symfony >= 6.4
- Doctrine ORM >= 2.20

Install via Composer:

```bash
composer require tourze/doctrine-track-bundle
```

## Quick Start

1. Mark entity fields you want to track with `#[TrackColumn]`.
2. Register the bundle in your Symfony project (if not using Flex).
3. Ensure your entities use an `id` property as primary key.
4. Run migrations to create the `entity_track_log` table.

**Example:**

```php
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;

class ExampleEntity
{
    #[TrackColumn]
    private string $importantField;
    // ...
}
```

## Detailed Documentation

- The bundle listens to Doctrine events (`postPersist`, `postUpdate`, `preRemove`, `postRemove`).
- Only properties with `#[TrackColumn]` are tracked.
- Logs are saved asynchronously to the `entity_track_log` table.
- Each log contains: entity class, entity id, action (create/update/remove), changed data, user, IP, request ID, and timestamp.
- Log cleanup is scheduled with a default retention (180 days, configurable via `ENTITY_TRACK_LOG_PERSIST_DAY_NUM`).

### Configuration

No mandatory configuration. You may set the retention period via environment variable:

```env
ENTITY_TRACK_LOG_PERSIST_DAY_NUM=180
```

## Contributing

- Please submit issues or pull requests via GitHub.
- Follow PSR coding standards.
- Add tests for new features.

## License

MIT License. See [LICENSE](LICENSE) for details.

## Changelog

See [Releases](https://github.com/tourze/php-monorepo/releases) for version history.
