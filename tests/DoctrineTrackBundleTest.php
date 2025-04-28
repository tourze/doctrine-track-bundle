<?php

namespace Tourze\DoctrineTrackBundle\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineTrackBundle\DoctrineTrackBundle;

class DoctrineTrackBundleTest extends TestCase
{
    public function testDoctrineTrackBundle_canBeInstantiated()
    {
        // 测试Bundle类可以被实例化
        $bundle = new DoctrineTrackBundle();
        $this->assertInstanceOf(DoctrineTrackBundle::class, $bundle);
    }
}
