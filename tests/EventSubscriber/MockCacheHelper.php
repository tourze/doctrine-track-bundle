<?php

declare(strict_types=1);

namespace Tourze\DoctrineTrackBundle\Tests\EventSubscriber;

/**
 * 用于测试的CacheHelper替代品
 */
class MockCacheHelper
{
    public static function getClassTags(string $className): string
    {
        return strtolower(str_replace('\\', '_', $className));
    }
}
