<?php

namespace Tourze\DoctrineTrackBundle\Attribute;

/**
 * 标记这个字段，我们是需要记录他的变化情况的
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class TrackColumn
{
}
