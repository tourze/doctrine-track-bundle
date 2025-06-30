<?php

namespace Tourze\DoctrineTrackBundle\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineTrackBundle\Exception\PropertyAccessException;

class PropertyAccessExceptionTest extends TestCase
{
    public function testExceptionInheritance(): void
    {
        $exception = new PropertyAccessException('Test message');
        $this->assertInstanceOf(\RuntimeException::class, $exception);
        $this->assertEquals('Test message', $exception->getMessage());
    }

    public function testExceptionWithCodeAndPrevious(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new PropertyAccessException('Test message', 123, $previous);
        
        $this->assertEquals('Test message', $exception->getMessage());
        $this->assertEquals(123, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
