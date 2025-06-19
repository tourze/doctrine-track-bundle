<?php

namespace Tourze\DoctrineTrackBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\ScheduleEntityCleanBundle\Attribute\AsScheduleClean;

#[AsScheduleClean(expression: '22 5 * * *', defaultKeepDay: 180, keepDayEnv: 'ENTITY_TRACK_LOG_PERSIST_DAY_NUM')]
#[ORM\Entity]
#[ORM\Table(name: 'entity_track_log', options: ['comment' => '数据变更日志'])]
class EntityTrackLog
{
    use CreateTimeAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[IndexColumn]
    #[ORM\Column(length: 255)]
    private ?string $objectClass = null;

    #[IndexColumn]
    #[ORM\Column(length: 40)]
    private ?string $objectId = null;

    #[ORM\Column(length: 20)]
    private ?string $action = null;

    #[ORM\Column]
    private array $data = [];

    #[CreatedByColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '创建人'])]
    private ?string $createdBy = null;

    #[ORM\Column(length: 45, nullable: true, options: ['comment' => '创建时IP'])]
    private ?string $createdFromIp = null;

    #[ORM\Column(length: 64, nullable: true, options: ['comment' => '请求ID'])]
    private ?string $requestId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getObjectClass(): ?string
    {
        return $this->objectClass;
    }

    public function setObjectClass(string $objectClass): static
    {
        $this->objectClass = $objectClass;

        return $this;
    }

    public function getObjectId(): ?string
    {
        return $this->objectId;
    }

    public function setObjectId(string $objectId): static
    {
        $this->objectId = $objectId;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function setCreatedBy(?string $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function getCreatedFromIp(): ?string
    {
        return $this->createdFromIp;
    }

    public function setCreatedFromIp(?string $createdFromIp): static
    {
        $this->createdFromIp = $createdFromIp;

        return $this;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function setRequestId(?string $requestId): static
    {
        $this->requestId = $requestId;

        return $this;
    }
}
