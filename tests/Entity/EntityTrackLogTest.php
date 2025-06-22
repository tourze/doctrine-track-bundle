<?php

namespace Tourze\DoctrineTrackBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineTrackBundle\Entity\EntityTrackLog;

class EntityTrackLogTest extends TestCase
{
    private EntityTrackLog $entityTrackLog;

    protected function setUp(): void
    {
        $this->entityTrackLog = new EntityTrackLog();
    }

    public function testEntityTrackLog_defaultValues()
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

    public function testEntityTrackLog_idGetterSetter()
    {
        // 测试 ID 的 getter 和 setter
        $this->entityTrackLog->setId(123);
        $this->assertEquals(123, $this->entityTrackLog->getId());
    }

    public function testEntityTrackLog_objectClassGetterSetter()
    {
        // 测试 objectClass 的 getter 和 setter
        $className = 'App\\Entity\\TestEntity';
        $this->entityTrackLog->setObjectClass($className);
        $this->assertEquals($className, $this->entityTrackLog->getObjectClass());
    }

    public function testEntityTrackLog_objectIdGetterSetter()
    {
        // 测试 objectId 的 getter 和 setter
        $objectId = '42';
        $this->entityTrackLog->setObjectId($objectId);
        $this->assertEquals($objectId, $this->entityTrackLog->getObjectId());
    }

    public function testEntityTrackLog_actionGetterSetter()
    {
        // 测试 action 的 getter 和 setter
        $actions = ['create', 'update', 'remove'];

        foreach ($actions as $action) {
            $this->entityTrackLog->setAction($action);
            $this->assertEquals($action, $this->entityTrackLog->getAction());
        }
    }

    public function testEntityTrackLog_dataGetterSetter()
    {
        // 测试 data 的 getter 和 setter
        $data = ['name' => 'old name => new name', 'email' => 'old@example.com => new@example.com'];
        $this->entityTrackLog->setData($data);
        $this->assertEquals($data, $this->entityTrackLog->getData());
    }

    public function testEntityTrackLog_createdByGetterSetter()
    {
        // 测试 createdBy 的 getter 和 setter
        $creator = 'admin';
        $this->entityTrackLog->setCreatedBy($creator);
        $this->assertEquals($creator, $this->entityTrackLog->getCreatedBy());

        // 测试 null 值
        $this->entityTrackLog->setCreatedBy(null);
        $this->assertNull($this->entityTrackLog->getCreatedBy());
    }

    public function testEntityTrackLog_createdFromIpGetterSetter()
    {
        // 测试 createdFromIp 的 getter 和 setter
        $ip = '127.0.0.1';
        $this->entityTrackLog->setCreatedFromIp($ip);
        $this->assertEquals($ip, $this->entityTrackLog->getCreatedFromIp());

        // 测试 null 值
        $this->entityTrackLog->setCreatedFromIp(null);
        $this->assertNull($this->entityTrackLog->getCreatedFromIp());
    }

    public function testEntityTrackLog_requestIdGetterSetter()
    {
        // 测试 requestId 的 getter 和 setter
        $requestId = 'req-12345-67890';
        $this->entityTrackLog->setRequestId($requestId);
        $this->assertEquals($requestId, $this->entityTrackLog->getRequestId());

        // 测试 null 值
        $this->entityTrackLog->setRequestId(null);
        $this->assertNull($this->entityTrackLog->getRequestId());
    }

    public function testEntityTrackLog_createTimeGetterSetter()
    {
        // 测试 createTime 的 getter 和 setter
        $now = new \DateTimeImmutable();
        $this->entityTrackLog->setCreateTime($now);
        $this->assertEquals($now, $this->entityTrackLog->getCreateTime());

        // 测试 null 值
        $this->entityTrackLog->setCreateTime(null);
        $this->assertNull($this->entityTrackLog->getCreateTime());
    }

    public function testEntityTrackLog_fluentInterface()
    {
        // 测试流式接口（fluent interface）
        $this->assertInstanceOf(
            EntityTrackLog::class,
            $this->entityTrackLog->setObjectClass('TestClass')
        );

        $this->assertInstanceOf(
            EntityTrackLog::class,
            $this->entityTrackLog->setObjectId('123')
        );

        $this->assertInstanceOf(
            EntityTrackLog::class,
            $this->entityTrackLog->setAction('create')
        );

        $this->assertInstanceOf(
            EntityTrackLog::class,
            $this->entityTrackLog->setData([])
        );

        $this->assertInstanceOf(
            EntityTrackLog::class,
            $this->entityTrackLog->setCreatedBy('admin')
        );

        $this->assertInstanceOf(
            EntityTrackLog::class,
            $this->entityTrackLog->setCreatedFromIp('127.0.0.1')
        );

        $this->assertInstanceOf(
            EntityTrackLog::class,
            $this->entityTrackLog->setRequestId('req-123')
        );

        $this->assertInstanceOf(
            EntityTrackLog::class,
            $this->entityTrackLog->setCreateTime(new \DateTimeImmutable())
        );
    }
}
