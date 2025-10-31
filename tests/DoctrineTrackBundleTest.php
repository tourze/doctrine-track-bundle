<?php

declare(strict_types=1);

namespace Tourze\DoctrineTrackBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrineTrackBundle\DoctrineTrackBundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(DoctrineTrackBundle::class)]
#[RunTestsInSeparateProcesses]
final class DoctrineTrackBundleTest extends AbstractBundleTestCase
{
}
