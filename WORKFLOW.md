# Entity Change Tracking Workflow (Mermaid)

```mermaid
flowchart TD
    A[Entity Operation: Create/Update/Delete] --> B{Has #[TrackColumn] fields?}
    B -- No --> Z[No Action]
    B -- Yes --> C[Doctrine Event Triggered]
    C --> D[EntityTrackListener Captures Event]
    D --> E[Extract #[TrackColumn] Field Changes]
    E --> F{Action Type}
    F -- Create --> G[Log as 'create']
    F -- Update --> H[Log as 'update']
    F -- Remove --> I[Log as 'remove']
    G & H & I --> J[Assemble Log Data]
    J --> K[Async Save to entity_track_log]
    K --> L[Log Cleanup by Schedule]
```

该流程图描述了 Doctrine Track Bundle 的实体变更日志记录与清理的整体流程。
