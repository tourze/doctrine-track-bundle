<?php

declare(strict_types=1);

namespace Tourze\DoctrineTrackBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineIpBundle\Traits\CreatedFromIpAware;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use Tourze\DoctrineUserBundle\Traits\CreatedByAware;
use Tourze\ScheduleEntityCleanBundle\Attribute\AsScheduleClean;

/** @phpstan-type TrackData array<string, mixed> */
#[AsScheduleClean(expression: '22 5 * * *', defaultKeepDay: 180, keepDayEnv: 'ENTITY_TRACK_LOG_PERSIST_DAY_NUM')]
#[ORM\Entity]
#[ORM\Table(name: 'entity_track_log', options: ['comment' => '数据变更日志'])]
class EntityTrackLog implements \Stringable
{
    use CreateTimeAware;
    use CreatedByAware;
    use CreatedFromIpAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[IndexColumn]
    #[ORM\Column(length: 255, options: ['comment' => '对象类名'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $objectClass = null;

    #[IndexColumn]
    #[ORM\Column(length: 40, options: ['comment' => '对象ID'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 40)]
    private ?string $objectId = null;

    #[ORM\Column(length: 20, options: ['comment' => '操作类型'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    private ?string $action = null;

    /** @var TrackData */
    #[ORM\Column(type: Types::JSON, options: ['comment' => '变更数据'])]
    #[Assert\NotNull]
    private array $data = [];

    #[ORM\Column(length: 64, nullable: true, options: ['comment' => '请求ID'])]
    #[Assert\Length(max: 64)]
    private ?string $requestId = null;

    public function __toString(): string
    {
        return sprintf('%s#%s [%s]', $this->objectClass, $this->objectId, $this->action);
    }

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

    public function setObjectClass(string $objectClass): void
    {
        $this->objectClass = $objectClass;
    }

    public function getObjectId(): ?string
    {
        return $this->objectId;
    }

    public function setObjectId(string $objectId): void
    {
        $this->objectId = $objectId;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * @return TrackData
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param TrackData $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function setRequestId(?string $requestId): void
    {
        $this->requestId = $requestId;
    }
}
