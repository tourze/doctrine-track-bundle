<?php

namespace Tourze\DoctrineTrackBundle;

use RequestIdBundle\RequestIdBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\DoctrineIndexedBundle\DoctrineIndexedBundle;
use Tourze\DoctrineTimestampBundle\DoctrineTimestampBundle;

class DoctrineTrackBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            RequestIdBundle::class => ['all' => true],
            DoctrineIndexedBundle::class => ['all' => true],
            DoctrineTimestampBundle::class => ['all' => true],
        ];
    }
}
