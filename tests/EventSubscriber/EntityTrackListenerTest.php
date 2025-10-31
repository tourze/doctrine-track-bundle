<?php

declare(strict_types=1);

namespace Tourze\DoctrineTrackBundle\Tests\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Contracts\Service\ResetInterface;
use Tourze\DoctrineTrackBundle\EventSubscriber\EntityTrackListener;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;

/**
 * 简化版的 EntityTrackListener 测试
 * 专门测试公共方法，避免使用 final 类的 Mock
 *
 * @internal
 */
#[CoversClass(EntityTrackListener::class)]
#[RunTestsInSeparateProcesses]
final class EntityTrackListenerTest extends AbstractEventSubscriberTestCase
{
    protected function onSetUp(): void
    {
        // 不需要额外的设置逻辑
    }

    protected function createListener(): EntityTrackListener
    {
        // 通过反射创建监听器实例，避免直接实例化
        $reflection = new \ReflectionClass(EntityTrackListener::class);

        return $reflection->newInstanceWithoutConstructor();
    }

    public function testImplementsResetInterface(): void
    {
        // 测试类实现了 ResetInterface 接口
        $listener = $this->createListener();
        $reflection = new \ReflectionClass($listener);
        $this->assertTrue($reflection->implementsInterface(ResetInterface::class));
    }

    public function testReset(): void
    {
        // 测试 reset 方法执行后不会抛出异常
        $this->expectNotToPerformAssertions();
        $listener = $this->createListener();
        $listener->reset();
    }

    public function testDoctrineAttributesExist(): void
    {
        // 测试Doctrine事件监听器属性是否存在
        $reflection = new \ReflectionClass(EntityTrackListener::class);

        $doctrineAttributes = $reflection->getAttributes(AsDoctrineListener::class);
        $this->assertGreaterThanOrEqual(4, count($doctrineAttributes), 'EntityTrackListener应该有至少4个AsDoctrineListener属性');

        $eventTypes = [];
        foreach ($doctrineAttributes as $attr) {
            $instance = $attr->newInstance();
            $eventTypes[] = $instance->event;
        }

        $this->assertContains(Events::preRemove, $eventTypes, '应该监听preRemove事件');
        $this->assertContains(Events::postRemove, $eventTypes, '应该监听postRemove事件');
        $this->assertContains(Events::postPersist, $eventTypes, '应该监听postPersist事件');
        $this->assertContains(Events::postUpdate, $eventTypes, '应该监听postUpdate事件');
    }

    public function testHasRequiredMethods(): void
    {
        // 测试是否有所需的事件处理方法
        $listener = $this->createListener();
        $reflection = new \ReflectionClass($listener);

        $this->assertTrue($reflection->hasMethod('preRemove'), '应该有preRemove方法');
        $this->assertTrue($reflection->hasMethod('postRemove'), '应该有postRemove方法');
        $this->assertTrue($reflection->hasMethod('postPersist'), '应该有postPersist方法');
        $this->assertTrue($reflection->hasMethod('postUpdate'), '应该有postUpdate方法');
        $this->assertTrue($reflection->hasMethod('reset'), '应该有reset方法');
    }

    public function testPostPersist(): void
    {
        // 测试postPersist方法存在且为公共方法
        $listener = $this->createListener();
        $reflection = new \ReflectionClass($listener);
        $method = $reflection->getMethod('postPersist');

        $this->assertTrue($method->isPublic(), 'postPersist方法应该是public的');
        $this->assertSame('postPersist', $method->getName(), '方法名应该是postPersist');
    }

    public function testPostRemove(): void
    {
        // 测试postRemove方法存在且为公共方法
        $listener = $this->createListener();
        $reflection = new \ReflectionClass($listener);
        $method = $reflection->getMethod('postRemove');

        $this->assertTrue($method->isPublic(), 'postRemove方法应该是public的');
        $this->assertSame('postRemove', $method->getName(), '方法名应该是postRemove');
    }

    public function testPostUpdate(): void
    {
        // 测试postUpdate方法存在且为公共方法
        $listener = $this->createListener();
        $reflection = new \ReflectionClass($listener);
        $method = $reflection->getMethod('postUpdate');

        $this->assertTrue($method->isPublic(), 'postUpdate方法应该是public的');
        $this->assertSame('postUpdate', $method->getName(), '方法名应该是postUpdate');
    }

    public function testPreRemove(): void
    {
        // 测试preRemove方法存在且为公共方法
        $listener = $this->createListener();
        $reflection = new \ReflectionClass($listener);
        $method = $reflection->getMethod('preRemove');

        $this->assertTrue($method->isPublic(), 'preRemove方法应该是public的');
        $this->assertSame('preRemove', $method->getName(), '方法名应该是preRemove');
    }
}
