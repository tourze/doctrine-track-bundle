<?php

declare(strict_types=1);

namespace Tourze\DoctrineTrackBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use RequestIdBundle\RequestIdBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
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
            DoctrineBundle::class => ['all' => true],
            DoctrineAsyncInsertBundle::class => ['all' => true],
            DoctrineIndexedBundle::class => ['all' => true],
            DoctrineTimestampBundle::class => ['all' => true],
            DoctrineUserBundle::class => ['all' => true],
            RequestIdBundle::class => ['all' => true],
            ScheduleEntityCleanBundle::class => ['all' => true],
            SecurityBundle::class => ['all' => true],
        ];
    }
}
