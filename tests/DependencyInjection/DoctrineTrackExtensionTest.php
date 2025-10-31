<?php

declare(strict_types=1);

namespace Tourze\DoctrineTrackBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\DoctrineTrackBundle\DependencyInjection\DoctrineTrackExtension;
use Tourze\DoctrineTrackBundle\EventSubscriber\EntityTrackListener;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(DoctrineTrackExtension::class)]
final class DoctrineTrackExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private DoctrineTrackExtension $extension;

    private ContainerBuilder $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new DoctrineTrackExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }

    public function testServicesAreLoaded(): void
    {
        $this->extension->load([], $this->container);

        // 验证容器中是否正确注册了 EntityTrackListener 服务
        $this->assertTrue($this->container->has(EntityTrackListener::class));
        $this->assertTrue($this->container->hasDefinition('Tourze\DoctrineTrackBundle\EventSubscriber\EntityTrackListener'));
    }
}
