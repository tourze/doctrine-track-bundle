<?php

namespace Tourze\DoctrineTrackBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\DoctrineTrackBundle\DependencyInjection\DoctrineTrackExtension;
use Tourze\DoctrineTrackBundle\EventSubscriber\EntityTrackListener;

class DoctrineTrackExtensionTest extends TestCase
{
    private DoctrineTrackExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new DoctrineTrackExtension();
        $this->container = new ContainerBuilder();
    }

    public function testDoctrineTrackExtension_servicesAreLoaded()
    {
        // 测试扩展是否正确加载服务
        $this->extension->load([], $this->container);

        // 验证容器中是否正确注册了 EntityTrackListener 服务
        $this->assertTrue($this->container->has(EntityTrackListener::class));
    }

    public function testDoctrineTrackExtension_withEmptyConfig()
    {
        // 测试无配置情况下的加载
        $this->extension->load([], $this->container);

        // 检查服务是否仍然正确加载
        $this->assertTrue($this->container->hasDefinition('Tourze\DoctrineTrackBundle\EventSubscriber\EntityTrackListener'));
    }
}
