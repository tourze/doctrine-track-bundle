<?php

namespace Tourze\DoctrineTrackBundle;

use RequestIdBundle\RequestIdBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\DoctrineAsyncInsertBundle\DoctrineAsyncInsertBundle;
use Tourze\DoctrineIndexedBundle\DoctrineIndexedBundle;
use Tourze\DoctrineTimestampBundle\DoctrineTimestampBundle;
use Tourze\DoctrineUserBundle\DoctrineUserBundle;
use Tourze\ScheduleEntityCleanBundle\ScheduleEntityCleanBundle;

class DoctrineTrackBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            DoctrineAsyncInsertBundle::class => ['all' => true],
            DoctrineIndexedBundle::class => ['all' => true],
            DoctrineTimestampBundle::class => ['all' => true],
            DoctrineUserBundle::class => ['all' => true],
            RequestIdBundle::class => ['all' => true],
            ScheduleEntityCleanBundle::class => ['all' => true],
        ];
    }
}
