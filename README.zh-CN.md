# Doctrine Track Bundle 实体变更跟踪组件

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-track-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-track-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/doctrine-track-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-track-bundle)
[![License](https://img.shields.io/packagist/l/tourze/doctrine-track-bundle.svg?style=flat-square)](LICENSE)

[![Build Status](https://img.shields.io/github/actions/workflow/status/tourze/php-monorepo/ci.yml?style=flat-square)](https://github.com/tourze/php-monorepo/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/tourze/php-monorepo?style=flat-square)](https://codecov.io/gh/tourze/php-monorepo)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/doctrine-track-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-track-bundle)

一个用于跟踪和审计实体变更的 Symfony 组件，自动记录创建、更新和删除操作，帮助维护数据完整性和满足合规要求。

## 目录

- [功能特性](#功能特性)
- [依赖关系](#依赖关系)
- [必需的组件包](#必需的组件包)
- [安装](#安装)
- [快速开始](#快速开始)
- [配置](#配置)
  - [环境变量](#环境变量)
  - [服务配置](#服务配置)
- [高级用法](#高级用法)
  - [自定义实体要求](#自定义实体要求)
  - [理解日志数据](#理解日志数据)
  - [性能考虑](#性能考虑)
- [贡献指南](#贡献指南)
- [许可证](#许可证)
- [更新日志](#更新日志)

## 功能特性

- **选择性跟踪**：只跟踪显式标记 `#[TrackColumn]` 属性的字段
- **完整审计轨迹**：记录实体类、ID、操作类型、变更数据、用户、IP 和请求 ID
- **异步日志记录**：非阻塞的日志持久化，提升性能
- **自动清理**：内置定时清理，可配置保留期限
- **用户上下文**：自动捕获用户信息（如果可用）
- **请求跟踪**：将变更与请求 ID 关联，便于调试

## 依赖关系

- PHP >= 8.1
- Symfony >= 6.4
- Doctrine ORM >= 3.0
- Doctrine Bundle >= 2.13

## 必需的组件包

- `tourze/doctrine-async-insert-bundle`：用于异步日志插入
- `tourze/request-id-bundle`：用于请求 ID 跟踪
- `tourze/doctrine-user-bundle`：用于用户上下文跟踪
- `tourze/doctrine-ip-bundle`：用于 IP 地址跟踪
- `tourze/doctrine-timestamp-bundle`：用于时间戳管理

## 安装

使用 Composer 安装：

```bash
composer require tourze/doctrine-track-bundle
```

如果您没有使用 Symfony Flex，请在 `config/bundles.php` 中手动注册组件：

```php
return [
    // ...
    Tourze\DoctrineTrackBundle\DoctrineTrackBundle::class => ['all' => true],
];
```

运行数据库迁移创建跟踪表：

```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

## 快速开始

1. **标记要跟踪的字段**，使用 `#[TrackColumn]` 属性：

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

    #[TrackColumn]  // 此字段将被跟踪
    #[ORM\Column]
    private string $email;

    #[TrackColumn]  // 此字段将被跟踪
    #[ORM\Column]
    private string $username;

    #[ORM\Column]   // 此字段不会被跟踪
    private \DateTime $lastLogin;
    
    // ... getter 和 setter 方法
}
```

2. **正常使用您的实体** - 跟踪会自动进行：

```php
// 创建
$user = new User();
$user->setEmail('user@example.com');
$user->setUsername('john_doe');
$entityManager->persist($user);
$entityManager->flush(); // 记录：action='create', data=['email' => 'user@example.com', 'username' => 'john_doe']

// 更新
$user->setEmail('newemail@example.com');
$entityManager->flush(); // 记录：action='update', data=['email' => ['old' => 'user@example.com', 'new' => 'newemail@example.com']]

// 删除
$entityManager->remove($user);
$entityManager->flush(); // 记录：action='remove', data=['email' => 'newemail@example.com', 'username' => 'john_doe']
```

3. **查询跟踪日志**：

```php
use Tourze\DoctrineTrackBundle\Entity\EntityTrackLog;

// 查找特定实体的所有变更
$logs = $entityManager->getRepository(EntityTrackLog::class)->findBy([
    'objectClass' => User::class,
    'objectId' => '123'
]);

// 查找特定操作的所有变更
$createLogs = $entityManager->getRepository(EntityTrackLog::class)->findBy([
    'action' => 'create'
]);
```

## 配置

## 环境变量

```env
# 日志保留期限（默认：180 天）
ENTITY_TRACK_LOG_PERSIST_DAY_NUM=180
```

## 服务配置

无需额外配置。组件会自动：
- 注册 Doctrine 事件监听器
- 配置异步日志服务
- 设置清理计划

## 高级用法

## 自定义实体要求

您的实体必须有一个 `id` 属性作为主键才能进行跟踪：

```php
#[ORM\Entity]
class MyEntity
{
    #[ORM\Id]  // 跟踪功能必需
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    // ... 其他属性
}
```

## 理解日志数据

跟踪日志包含：
- `objectClass`：被跟踪实体的完整类名
- `objectId`：实体的主键值
- `action`：`create`、`update` 或 `remove`
- `data`：包含字段变更的 JSON
- `createdBy`：进行变更的用户（如果可用）
- `createdFromIp`：请求的 IP 地址
- `requestId`：唯一的请求标识符
- `createdAt`：变更的时间戳

## 性能考虑

- 日志异步写入以避免阻塞实体操作
- 只跟踪标记的字段，减少开销
- 自动清理防止日志表增长
- 考虑在 `objectClass` 和 `objectId` 上建立索引以提升查询性能

## 贡献指南

1. Fork 仓库
2. 创建功能分支（`git checkout -b feature/amazing-feature`）
3. 进行更改并添加测试
4. 确保所有测试通过（`./vendor/bin/phpunit`）
5. 检查代码质量（`./vendor/bin/phpstan analyse`）
6. 提交更改（`git commit -am 'Add amazing feature'`）
7. 推送到分支（`git push origin feature/amazing-feature`）
8. 打开 Pull Request

## 许可证

MIT 许可证。更多信息请查看 [许可证文件](LICENSE)。

## 更新日志

版本历史和升级指南请查看 [Releases](https://github.com/tourze/php-monorepo/releases)。