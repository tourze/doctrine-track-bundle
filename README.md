# Doctrine Track Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-track-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-track-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/doctrine-track-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-track-bundle)
[![License](https://img.shields.io/packagist/l/tourze/doctrine-track-bundle.svg?style=flat-square)](LICENSE)

[![Build Status](https://img.shields.io/github/actions/workflow/status/tourze/php-monorepo/ci.yml?style=flat-square)](https://github.com/tourze/php-monorepo/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/tourze/php-monorepo?style=flat-square)](https://codecov.io/gh/tourze/php-monorepo)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/doctrine-track-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-track-bundle)

A Symfony bundle for tracking and auditing entity changes with automatic logging of create, update, 
and delete operations to help maintain data integrity and compliance requirements.

## Table of Contents

- [Features](#features)
- [Dependencies](#dependencies)
- [Required Bundles](#required-bundles)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Configuration](#configuration)
  - [Environment Variables](#environment-variables)
  - [Service Configuration](#service-configuration)
- [Advanced Usage](#advanced-usage)
  - [Custom Entity Requirements](#custom-entity-requirements)
  - [Understanding Log Data](#understanding-log-data)
  - [Performance Considerations](#performance-considerations)
- [Contributing](#contributing)
- [License](#license)
- [Changelog](#changelog)

## Features

- **Selective Tracking**: Only tracks fields explicitly marked with `#[TrackColumn]` attribute
- **Complete Audit Trail**: Records entity class, ID, action type, changed data, user, IP, and request ID
- **Asynchronous Logging**: Non-blocking log persistence for better performance
- **Automatic Cleanup**: Built-in scheduled cleanup with configurable retention period
- **User Context**: Automatically captures user information when available
- **Request Tracking**: Associates changes with request IDs for better debugging

## Dependencies

- PHP >= 8.1
- Symfony >= 6.4
- Doctrine ORM >= 3.0
- Doctrine Bundle >= 2.13

## Required Bundles

- `tourze/doctrine-async-insert-bundle`: For asynchronous log insertion
- `tourze/request-id-bundle`: For request ID tracking
- `tourze/doctrine-user-bundle`: For user context tracking
- `tourze/doctrine-ip-bundle`: For IP address tracking
- `tourze/doctrine-timestamp-bundle`: For timestamp management

## Installation

Install via Composer:

```bash
composer require tourze/doctrine-track-bundle
```

If you're not using Symfony Flex, manually register the bundle in `config/bundles.php`:

```php
return [
    // ...
    Tourze\DoctrineTrackBundle\DoctrineTrackBundle::class => ['all' => true],
];
```

Run database migrations to create the tracking table:

```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

## Quick Start

1. **Mark fields to track** with the `#[TrackColumn]` attribute:

```php
<?php

use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[TrackColumn]  // This field will be tracked
    #[ORM\Column]
    private string $email;

    #[TrackColumn]  // This field will be tracked
    #[ORM\Column]
    private string $username;

    #[ORM\Column]   // This field will NOT be tracked
    private \DateTime $lastLogin;
    
    // ... getters and setters
}
```

2. **Use your entities normally** - tracking happens automatically:

```php
// Create
$user = new User();
$user->setEmail('user@example.com');
$user->setUsername('john_doe');
$entityManager->persist($user);
$entityManager->flush(); // Logs: action='create', data=['email' => 'user@example.com', 'username' => 'john_doe']

// Update
$user->setEmail('newemail@example.com');
$entityManager->flush(); // Logs: action='update', data=['email' => ['old' => 'user@example.com', 'new' => 'newemail@example.com']]

// Delete
$entityManager->remove($user);
$entityManager->flush(); // Logs: action='remove', data=['email' => 'newemail@example.com', 'username' => 'john_doe']
```

3. **Query tracking logs**:

```php
use Tourze\DoctrineTrackBundle\Entity\EntityTrackLog;

// Find all changes to a specific entity
$logs = $entityManager->getRepository(EntityTrackLog::class)->findBy([
    'objectClass' => User::class,
    'objectId' => '123'
]);

// Find all changes by a specific action
$createLogs = $entityManager->getRepository(EntityTrackLog::class)->findBy([
    'action' => 'create'
]);
```

## Configuration

## Environment Variables

```env
# Log retention period (default: 180 days)
ENTITY_TRACK_LOG_PERSIST_DAY_NUM=180
```

## Service Configuration

No additional configuration is required. The bundle automatically:
- Registers Doctrine event listeners
- Configures async logging service
- Sets up cleanup scheduling

## Advanced Usage

## Custom Entity Requirements

Your entities must have an `id` property as the primary key for tracking to work:

```php
#[ORM\Entity]
class MyEntity
{
    #[ORM\Id]  // Required for tracking
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    // ... other properties
}
```

## Understanding Log Data

The tracking logs contain:
- `objectClass`: Full class name of the tracked entity
- `objectId`: Primary key value of the entity
- `action`: `create`, `update`, or `remove`
- `data`: JSON containing field changes
- `createdBy`: User who made the change (if available)
- `createdFromIp`: IP address of the request
- `requestId`: Unique request identifier
- `createdAt`: Timestamp of the change

## Performance Considerations

- Logs are written asynchronously to avoid blocking entity operations
- Only marked fields are tracked, reducing overhead
- Automatic cleanup prevents log table growth
- Consider indexing on `objectClass` and `objectId` for query performance

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes and add tests
4. Ensure all tests pass (`./vendor/bin/phpunit`)
5. Check code quality (`./vendor/bin/phpstan analyse`)
6. Commit your changes (`git commit -am 'Add amazing feature'`)
7. Push to the branch (`git push origin feature/amazing-feature`)
8. Open a Pull Request

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Changelog

Please see [Releases](https://github.com/tourze/php-monorepo/releases) for version history and upgrade guides.