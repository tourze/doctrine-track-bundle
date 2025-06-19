<?php

namespace Tourze\DoctrineTrackBundle\EventSubscriber;

use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use RequestIdBundle\Service\RequestIdStorage;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Contracts\Service\ResetInterface;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService as DoctrineService;
use Tourze\DoctrineHelper\CacheHelper;
use Tourze\DoctrineHelper\ReflectionHelper;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineTrackBundle\Entity\EntityTrackLog;

/**
 * 保存特定对象的变更日志，方便我们对其进行审查
 */
#[AsDoctrineListener(event: Events::preRemove)]
#[AsDoctrineListener(event: Events::postRemove)]
#[AsDoctrineListener(event: Events::postPersist)]
#[AsDoctrineListener(event: Events::postUpdate)]
#[AutoconfigureTag('as-coroutine')]
class EntityTrackListener implements ResetInterface
{
    /**
     * @var array 暂存 entity => id 的关系，后面可能使用到
     */
    private array $idMap = [];

    public function __construct(
        private readonly DoctrineService $doctrineService,
        private readonly RequestStack $requestStack,
        #[Autowire(service: 'doctrine-track.property-accessor')] private readonly PropertyAccessor $propertyAccessor,
        private readonly LoggerInterface $logger,
        private readonly Security $security,
        #[Autowire(service: 'cache.app')] private readonly AdapterInterface $cache,
        private readonly RequestIdStorage $requestIdStorage,
    ) {
    }

    public function reset(): void
    {
        $this->idMap = [];
    }

    /**
     * 删除记录前，我们做个标记
     */
    public function preRemove(PreRemoveEventArgs $eventArgs): void
    {
        $this->idMap[spl_object_hash($eventArgs->getObject())] = $this->propertyAccessor->getValue($eventArgs->getObject(), 'id');
    }

    /**
     * 删除记录后，记录到数据库
     */
    public function postRemove(PostRemoveEventArgs $eventArgs): void
    {
        $changedValues = $this->getChangedValues($eventArgs->getObject());
        if (empty($changedValues)) {
            return;
        }
        $this->saveLog($eventArgs->getObject(), $changedValues, 'remove');
    }

    /**
     * 记录创建日志日志
     */
    public function postPersist(PostPersistEventArgs $eventArgs): void
    {
        $changedValues = $this->getChangedValues($eventArgs->getObject());
        if (empty($changedValues)) {
            return;
        }
        $this->saveLog($eventArgs->getObject(), $changedValues, 'create');
    }

    /**
     * 更新日志
     */
    public function postUpdate(PostUpdateEventArgs $eventArgs): void
    {
        $changedValues = $this->getChangedValues($eventArgs->getObject());
        if (empty($changedValues)) {
            return;
        }
        $this->saveLog($eventArgs->getObject(), $changedValues, 'update');
    }

    private function getChangedValues(object $entity): array
    {
        $changedValues = [];

        foreach (ReflectionHelper::getClassReflection($entity)->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            $trackColumn = $property->getAttributes(TrackColumn::class);
            if (empty($trackColumn)) {
                continue;
            }
            $trackColumn = $trackColumn[0]->newInstance();
            /* @var TrackColumn $trackColumn */

            try {
                $changedValues[$property->getName()] = $this->propertyAccessor->getValue($entity, $property->getName());
            } catch (\Throwable $exception) {
                $this->logger->error('读取参数时发生错误', [
                    'property' => $property,
                    'entity' => $entity,
                    'trackColumn' => $trackColumn,
                    'exception' => $exception,
                ]);
            }
        }

        return $changedValues;
    }

    /**
     * 保存日志
     */
    private function saveLog(object $entity, array $changedValues, string $action): void
    {
        $id = $this->propertyAccessor->getValue($entity, 'id');
        if (!$id) {
            $id = $this->idMap[spl_object_hash($entity)] ?? null;
        }
        if (!$id) {
            $this->logger->error('记录TrackLog时发生未知错误', [
                'entity' => $entity,
                'values' => $changedValues,
                'action' => $action,
            ]);

            return;
        }

        $objectClass = ClassUtils::getClass($entity);

        // 查找这个对象，上一次的变更日志，如果一样的话我们就不重复插入记录了
        $checkKey = implode('_', [
            $action,
            CacheHelper::getClassTags($objectClass),
            $id,
        ]);
        $checkHash = md5($checkKey . serialize($changedValues));
        $cacheItem = $this->cache->getItem($checkHash);
        if ($cacheItem->isHit() && $cacheItem->get() === $checkHash) {
            return;
        }

        $log = new EntityTrackLog();
        $log->setObjectClass($objectClass);
        $log->setObjectId($id);
        $log->setAction($action);
        $log->setData($changedValues);
        $log->setCreateTime(Carbon::now()->toDateTimeImmutable());
        $log->setCreatedBy($this->security->getUser()?->getUserIdentifier());
        $log->setCreatedFromIp($this->requestStack->getMainRequest() ? $this->requestStack->getMainRequest()->getClientIp() : '');
        $requestId = $this->requestIdStorage->getRequestId();
        $log->setRequestId($requestId ? substr($requestId, 0, 64) : '');
        $this->doctrineService->asyncInsert($log);

        // 一天内不会重复处理
        $cacheItem->set($checkHash);
        $cacheItem->expiresAfter(60 * 60 * 24);
        $this->cache->save($cacheItem);
    }
}
