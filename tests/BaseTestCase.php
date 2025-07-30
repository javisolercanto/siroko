<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class BaseTestCase extends KernelTestCase
{
    protected Connection $connection;
    protected EntityManagerInterface $em;

    private float $startTime;
    private int $startMemory;

    private static bool $metricsFileCleared = false;
    private static string $metricsFile = __DIR__ . '/../var/test_metrics.json';

    protected function setUp(): void
    {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage();

        self::bootKernel();

        $this->em = self::getContainer()->get(EntityManagerInterface::class);
        $this->connection = $this->em->getConnection();

        if (!$this->connection->isTransactionActive()) {
            $this->connection->beginTransaction();
        }

        if (!self::$metricsFileCleared) {
            $this->clearMetricsFile();
            self::$metricsFileCleared = true;
        }
    }

    protected function tearDown(): void
    {
        if ($this->connection->isTransactionActive()) {
            $this->connection->rollBack();
        }

        $this->saveMetrics();

        parent::tearDown();
    }

    protected function measure(callable $operation): array
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $result = $operation();

        return [
            'result' => $result,
            'time_ms' => round((microtime(true) - $startTime) * 1000, 2),
            'memory_bytes' => memory_get_usage() - $startMemory,
        ];
    }

    protected function benchmark(callable $operation, int $times = 100): float
    {
        $totalTime = 0;

        for ($i = 0; $i < $times; $i++) {
            $start = microtime(true);
            $operation();
            $totalTime += microtime(true) - $start;
        }

        return round(($totalTime / $times) * 1000, 2);
    }

    private function clearMetricsFile(): void
    {
        $dir = dirname(self::$metricsFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents(self::$metricsFile, json_encode([], JSON_PRETTY_PRINT));
    }

    private function saveMetrics(): void
    {
        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $metrics = [
            'test_class' => static::class,
            'time_ms' => round(($endTime - $this->startTime) * 1000, 2),
            'memory_bytes' => $endMemory - $this->startMemory,
            'peak_memory_bytes' => memory_get_peak_usage(),
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        $existingMetrics = [];
        if (file_exists(self::$metricsFile)) {
            $existingMetrics = json_decode(file_get_contents(self::$metricsFile), true) ?: [];
        }

        $existingMetrics[] = $metrics;
        file_put_contents(self::$metricsFile, json_encode($existingMetrics, JSON_PRETTY_PRINT));
    }
}
