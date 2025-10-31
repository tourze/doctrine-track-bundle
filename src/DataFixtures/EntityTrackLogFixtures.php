<?php

declare(strict_types=1);

namespace Tourze\DoctrineTrackBundle\DataFixtures;

use Carbon\CarbonImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use Tourze\DoctrineTrackBundle\Entity\EntityTrackLog;

/**
 * 实体追踪日志数据填充
 */
#[When(env: 'test')]
class EntityTrackLogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->createTrackLogs($manager);
        $manager->flush();
    }

    private function createTrackLogs(ObjectManager $manager): void
    {
        $actions = ['create', 'update', 'delete'];
        $objectClasses = [
            'App\Entity\User',
            'App\Entity\Product',
            'App\Entity\Order',
        ];

        for ($i = 1; $i <= 10; ++$i) {
            $log = new EntityTrackLog();
            $log->setObjectClass($objectClasses[array_rand($objectClasses)]);
            $log->setObjectId((string) $i);
            $log->setAction($actions[array_rand($actions)]);
            $log->setData($this->generateTestData($actions[array_rand($actions)]));
            $log->setRequestId(uniqid('req_', true));

            $daysAgo = rand(0, 7);
            $createTime = CarbonImmutable::now()->subDays($daysAgo)->subHours(rand(0, 23));
            $log->setCreateTime($createTime);

            $manager->persist($log);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function generateTestData(string $action): array
    {
        return match ($action) {
            'create' => [
                'name' => 'Test Entity ' . rand(1, 100),
                'status' => 'active',
            ],
            'update' => [
                'old' => ['status' => 'inactive'],
                'new' => ['status' => 'active'],
            ],
            'delete' => [
                'reason' => 'User requested deletion',
            ],
            default => [],
        };
    }
}
