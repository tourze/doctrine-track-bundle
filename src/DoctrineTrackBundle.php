<?php

namespace Tourze\DoctrineTrackBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;

class DoctrineTrackBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            \RequestIdBundle\RequestIdBundle::class => ['all' => true],
            \Tourze\DoctrineAsyncBundle\DoctrineAsyncBundle::class => ['all' => true],
        ];
    }
}
