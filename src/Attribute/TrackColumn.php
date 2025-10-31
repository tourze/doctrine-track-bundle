<?php

declare(strict_types=1);

namespace Tourze\DoctrineTrackBundle\Attribute;

/**
 * 标记这个字段，我们是需要记录他的变化情况的
 */
#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class TrackColumn
{
}
