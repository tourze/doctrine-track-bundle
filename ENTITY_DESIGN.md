# Entity Design: entity_track_log

## Table: entity_track_log

| 字段名           | 类型                | 说明           |
|------------------|---------------------|----------------|
| id               | int                 | 主键，自增ID   |
| objectClass      | string(255)         | 实体类名       |
| objectId         | string(40)          | 实体主键       |
| action           | string(20)          | 操作类型       |
| data             | array (json)        | 变更数据       |
| createdBy        | string (nullable)   | 创建人         |
| createdFromIp    | string(45,nullable) | 创建时IP       |
| requestId        | string(64,nullable) | 请求ID         |
| createTime       | datetime            | 创建时间       |

## 设计说明

- 仅记录带有 `#[TrackColumn]` 注解字段的变更。
- 支持追踪创建、更新、删除三类操作。
- 支持异步写入与定期清理。
- 通过扩展属性如 createdBy、createdFromIp、requestId 实现操作溯源。
- createTime 字段支持按天定期清理（默认180天，可配置）。

## 示例数据

```json
{
  "id": 1,
  "objectClass": "App\\Entity\\User",
  "objectId": "42",
  "action": "update",
  "data": {
    "email": "old@example.com => new@example.com"
  },
  "createdBy": "admin",
  "createdFromIp": "127.0.0.1",
  "requestId": "req-123456",
  "createTime": "2025-04-21T05:33:00+08:00"
}
```
