<?php

namespace Tourze\DoctrineTrackBundle\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;

class TrackColumnTest extends TestCase
{
    public function testTrackColumnAttribute_instanceCreation()
    {
        // 测试TrackColumn属性是否可以正确实例化
        $attribute = new TrackColumn();
        $this->assertInstanceOf(TrackColumn::class, $attribute);
    }

    public function testTrackColumnAttribute_reflectionTarget()
    {
        // 测试TrackColumn的属性用途
        $reflectionClass = new \ReflectionClass(TrackColumn::class);
        $attributes = $reflectionClass->getAttributes();

        // 确认注解存在
        $this->assertGreaterThan(0, count($attributes));

        // 寻找Attribute注解
        $hasAttributeAnnotation = false;
        foreach ($attributes as $attribute) {
            if ($attribute->getName() === 'Attribute') {
                $hasAttributeAnnotation = true;
                break;
            }
        }

        // 验证存在Attribute注解
        $this->assertTrue($hasAttributeAnnotation, 'TrackColumn类必须有Attribute注解');
    }
}
