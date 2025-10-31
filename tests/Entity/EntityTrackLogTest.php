<?php

declare(strict_types=1);

namespace Tourze\DoctrineTrackBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\DoctrineTrackBundle\Entity\EntityTrackLog;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(EntityTrackLog::class)]
final class EntityTrackLogTest extends AbstractEntityTestCase
{
    private EntityTrackLog $entityTrackLog;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entityTrackLog = new EntityTrackLog();
    }

    protected function createEntity(): object
    {
        return new EntityTrackLog();
    }

    /**
     * 提供属性及其样本值的 Data Provider.
     *
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'objectClass' => ['objectClass', 'TestEntity'],
            'objectId' => ['objectId', '123'],
            'action' => ['action', 'create'],
            'data' => ['data', ['field' => 'value']],
            'createdBy' => ['createdBy', 'admin'],
            'createdFromIp' => ['createdFromIp', '127.0.0.1'],
            'requestId' => ['requestId', 'req-123'],
            'createTime' => ['createTime', new \DateTimeImmutable()],
        ];
    }

    public function testEntityTrackLogDefaultValues(): void
    {
        // 测试默认值
        $this->assertNull($this->entityTrackLog->getObjectClass());
        $this->assertNull($this->entityTrackLog->getObjectId());
        $this->assertNull($this->entityTrackLog->getAction());
        $this->assertEquals([], $this->entityTrackLog->getData());
        $this->assertNull($this->entityTrackLog->getCreatedBy());
        $this->assertNull($this->entityTrackLog->getCreatedFromIp());
        $this->assertNull($this->entityTrackLog->getRequestId());
        $this->assertNull($this->entityTrackLog->getCreateTime());
    }

    public function testEntityTrackLogIdGetterSetter(): void
    {
        // 测试 ID 的 getter 和 setter
        $this->entityTrackLog->setId(123);
        $this->assertEquals(123, $this->entityTrackLog->getId());
    }

    public function testEntityTrackLogObjectClassGetterSetter(): void
    {
        // 测试 objectClass 的 getter 和 setter
        $className = 'App\Entity\TestEntity';
        $this->entityTrackLog->setObjectClass($className);
        $this->assertEquals($className, $this->entityTrackLog->getObjectClass());
    }

    public function testEntityTrackLogObjectIdGetterSetter(): void
    {
        // 测试 objectId 的 getter 和 setter
        $objectId = '42';
        $this->entityTrackLog->setObjectId($objectId);
        $this->assertEquals($objectId, $this->entityTrackLog->getObjectId());
    }

    public function testEntityTrackLogActionGetterSetter(): void
    {
        // 测试 action 的 getter 和 setter
        $actions = ['create', 'update', 'remove'];

        foreach ($actions as $action) {
            $this->entityTrackLog->setAction($action);
            $this->assertEquals($action, $this->entityTrackLog->getAction());
        }
    }

    public function testEntityTrackLogDataGetterSetter(): void
    {
        // 测试 data 的 getter 和 setter
        $data = ['name' => 'old name => new name', 'email' => 'old@example.com => new@example.com'];
        $this->entityTrackLog->setData($data);
        $this->assertEquals($data, $this->entityTrackLog->getData());
    }

    public function testEntityTrackLogCreatedByGetterSetter(): void
    {
        // 测试 createdBy 的 getter 和 setter
        $creator = 'admin';
        $this->entityTrackLog->setCreatedBy($creator);
        $this->assertEquals($creator, $this->entityTrackLog->getCreatedBy());

        // 测试 null 值
        $this->entityTrackLog->setCreatedBy(null);
        $this->assertNull($this->entityTrackLog->getCreatedBy());
    }

    public function testEntityTrackLogCreatedFromIpGetterSetter(): void
    {
        // 测试 createdFromIp 的 getter 和 setter
        $ip = '127.0.0.1';
        $this->entityTrackLog->setCreatedFromIp($ip);
        $this->assertEquals($ip, $this->entityTrackLog->getCreatedFromIp());

        // 测试 null 值
        $this->entityTrackLog->setCreatedFromIp(null);
        $this->assertNull($this->entityTrackLog->getCreatedFromIp());
    }

    public function testEntityTrackLogRequestIdGetterSetter(): void
    {
        // 测试 requestId 的 getter 和 setter
        $requestId = 'req-12345-67890';
        $this->entityTrackLog->setRequestId($requestId);
        $this->assertEquals($requestId, $this->entityTrackLog->getRequestId());

        // 测试 null 值
        $this->entityTrackLog->setRequestId(null);
        $this->assertNull($this->entityTrackLog->getRequestId());
    }

    public function testEntityTrackLogCreateTimeGetterSetter(): void
    {
        // 测试 createTime 的 getter 和 setter
        $now = new \DateTimeImmutable();
        $this->entityTrackLog->setCreateTime($now);
        $this->assertEquals($now, $this->entityTrackLog->getCreateTime());

        // 测试 null 值
        $this->entityTrackLog->setCreateTime(null);
        $this->assertNull($this->entityTrackLog->getCreateTime());
    }

    public function testEntityTrackLogFluentInterface(): void
    {
        // 测试setter方法能够正确设置属性值（适配void返回类型）
        $now = new \DateTimeImmutable();
        $data = ['field' => 'value'];

        // setter方法现在返回void，使用独立语句而非链式调用
        $this->entityTrackLog->setObjectClass('TestClass');
        $this->entityTrackLog->setObjectId('123');
        $this->entityTrackLog->setAction('create');
        $this->entityTrackLog->setData($data);
        $this->entityTrackLog->setCreatedBy('admin');
        $this->entityTrackLog->setCreatedFromIp('127.0.0.1');
        $this->entityTrackLog->setRequestId('req-123');
        $this->entityTrackLog->setCreateTime($now);

        // 验证所有的值都被正确设置
        $this->assertEquals('TestClass', $this->entityTrackLog->getObjectClass());
        $this->assertEquals('123', $this->entityTrackLog->getObjectId());
        $this->assertEquals('create', $this->entityTrackLog->getAction());
        $this->assertEquals($data, $this->entityTrackLog->getData());
        $this->assertEquals('admin', $this->entityTrackLog->getCreatedBy());
        $this->assertEquals('127.0.0.1', $this->entityTrackLog->getCreatedFromIp());
        $this->assertEquals('req-123', $this->entityTrackLog->getRequestId());
        $this->assertEquals($now, $this->entityTrackLog->getCreateTime());
    }
}
