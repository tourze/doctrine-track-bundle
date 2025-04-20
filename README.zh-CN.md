# Doctrine Track Bundle 实体变更跟踪组件

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-track-bundle.svg)](https://packagist.org/packages/tourze/doctrine-track-bundle)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

一个用于记录实体变更日志的 Symfony 组件，支持自动记录实体的创建、更新、删除等操作，方便进行审计和追溯。

## 功能特性

- 自动记录带有 `TrackColumn` 注解字段的增删改操作
- 记录操作用户、IP、请求ID及变更详情
- 日志异步写入数据库，性能友好
- 支持定时清理历史日志（默认保留180天，可配置）

## 安装方法

- PHP >= 8.1
- Symfony >= 6.4
- Doctrine ORM >= 2.20

使用 Composer 安装：

```bash
composer require tourze/doctrine-track-bundle
```

## 快速开始

1. 在需要跟踪的实体属性上添加 `#[TrackColumn]` 注解。
2. 确保你的实体主键为 `id`。
3. 运行数据库迁移，生成 `entity_track_log` 表。

**示例：**

```php
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;

class ExampleEntity
{
    #[TrackColumn]
    private string $importantField;
    // ...
}
```

## 详细说明

- 监听 Doctrine 的 `postPersist`、`postUpdate`、`preRemove`、`postRemove` 事件。
- 仅跟踪带有 `#[TrackColumn]` 注解的私有属性。
- 日志异步写入 `entity_track_log` 表，内容包括：实体类、ID、操作类型、变更数据、操作用户、IP、请求ID、操作时间等。
- 日志清理通过定时任务实现，默认保留180天，可通过 `ENTITY_TRACK_LOG_PERSIST_DAY_NUM` 环境变量配置。

### 配置说明

无需强制配置。可通过环境变量设置日志保留天数：

```env
ENTITY_TRACK_LOG_PERSIST_DAY_NUM=180
```

## 贡献指南

- 欢迎通过 GitHub 提交 Issue 或 PR。
- 遵循 PSR 代码规范。
- 新增功能需补充测试。

## 版权信息

MIT 协议，详见 [LICENSE](LICENSE)。

## 更新日志

详见 [Releases](https://github.com/tourze/php-monorepo/releases)。
