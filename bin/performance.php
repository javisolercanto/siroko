<?php

declare(strict_types=1);

$metricsFile = __DIR__ . '/../var/test_metrics.json';
$outputFile = __DIR__ . '/../var/performance_report.html';

if (!file_exists($metricsFile)) {
    exit(1);
}

$metrics = json_decode(file_get_contents($metricsFile), true);

if (empty($metrics)) {
    exit(1);
}

$processedData = processMetrics($metrics);

$html = generateHTML($processedData);

file_put_contents($outputFile, $html);

echo "Performance report generated at $outputFile \n";

function processMetrics(array $metrics): array
{
    $data = [
        'total_tests' => count($metrics),
        'tests' => [],
        'stats' => [
            'avg_time' => 0,
            'max_time' => 0,
            'min_time' => PHP_FLOAT_MAX,
            'avg_memory' => 0,
            'max_memory' => 0,
            'slowest_tests' => [],
            'memory_hungry_tests' => []
        ]
    ];

    $totalTime = 0;
    $totalMemory = 0;

    foreach ($metrics as $metric) {
        $testKey = $metric['test_class'];

        if (!isset($data['tests'][$testKey])) {
            $data['tests'][$testKey] = [
                'class' => basename(str_replace('\\', '/', $metric['test_class'])),
                'executions' => [],
                'avg_time' => 0,
                'avg_memory' => 0,
                'min_time' => PHP_FLOAT_MAX,
                'max_time' => 0
            ];
        }

        $data['tests'][$testKey]['executions'][] = $metric;


        $test = &$data['tests'][$testKey];
        $test['min_time'] = min($test['min_time'], $metric['time_ms']);
        $test['max_time'] = max($test['max_time'], $metric['time_ms']);

        $totalTime += $metric['time_ms'];
        $totalMemory += $metric['memory_bytes'];

        $data['stats']['max_time'] = max($data['stats']['max_time'], $metric['time_ms']);
        $data['stats']['min_time'] = min($data['stats']['min_time'], $metric['time_ms']);
        $data['stats']['max_memory'] = max($data['stats']['max_memory'], $metric['memory_bytes']);
    }


    foreach ($data['tests'] as &$test) {
        $executions = count($test['executions']);
        $test['avg_time'] = round(array_sum(array_column($test['executions'], 'time_ms')) / $executions, 2);
        $test['avg_memory'] = round(array_sum(array_column($test['executions'], 'memory_bytes')) / $executions);
        $test['executions_count'] = $executions;
    }


    $data['stats']['avg_time'] = round($totalTime / count($metrics), 2);
    $data['stats']['avg_memory'] = round($totalMemory / count($metrics));


    uasort($data['tests'], fn($a, $b) => $b['avg_time'] <=> $a['avg_time']);
    $data['stats']['slowest_tests'] = array_slice($data['tests'], 0, 5, true);


    uasort($data['tests'], fn($a, $b) => $b['avg_memory'] <=> $a['avg_memory']);
    $data['stats']['memory_hungry_tests'] = array_slice($data['tests'], 0, 5, true);


    ksort($data['tests']);

    return $data;
}

function generateHTML(array $data): string
{
    $lastUpdate = date('Y-m-d H:i:s');

    $html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Performance - Tests</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .header h1 { color: #2c3e50; margin-bottom: 10px; }
        .header p { color: #7f8c8d; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .stat-number { font-size: 2em; font-weight: bold; color: #3498db; }
        .stat-label { color: #7f8c8d; margin-top: 5px; }
        .section { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .section h2 { color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ecf0f1; }
        th { background: #f8f9fa; font-weight: 600; color: #2c3e50; }
        tr:hover { background: #f8f9fa; }
        .time-good { color: #27ae60; }
        .time-warning { color: #f39c12; }
        .time-danger { color: #e74c3c; }
        .memory { color: #9b59b6; }
        .test-method { font-family: monospace; background: #f8f9fa; padding: 2px 6px; border-radius: 3px; }
        .execution-count { background: #3498db; color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Reporte de Performance - Tests</h1>
            <p>Última actualización: $lastUpdate | Total de ejecuciones: {$data['total_tests']}</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{$data['stats']['avg_time']}ms</div>
                <div class="stat-label">Tiempo Promedio</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{$data['stats']['max_time']}ms</div>
                <div class="stat-label">Tiempo Máximo</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{$data['stats']['min_time']}ms</div>
                <div class="stat-label">Tiempo Mínimo</div>
            </div>
        </div>

        <div class="section">
            <h2>🐌 Tests Más Lentos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Test</th>
                        <th>Tiempo Promedio</th>
                        <th>Ejecuciones</th>
                    </tr>
                </thead>
                <tbody>
HTML;

    foreach ($data['stats']['slowest_tests'] as $test) {
        $timeClass = getTimeClass($test['avg_time']);
        $html .= <<<HTML
<tr>
    <td>{$test['class']}</td>
    <td class="$timeClass">{$test['avg_time']}ms</td>
    <td><span class="execution-count">{$test['executions_count']}</span></td>
</tr>
HTML;
    }

    $html .= <<<HTML
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>🧠 Tests que Más Memoria Consumen</h2>
            <table>
                <thead>
                    <tr>
                        <th>Test</th>
                        <th>Memoria Promedio</th>
                        <th>Ejecuciones</th>
                    </tr>
                </thead>
                <tbody>
HTML;

    foreach ($data['stats']['memory_hungry_tests'] as $test) {
        $formattedMemory = formatBytes($test['avg_memory']);
        $html .= <<<HTML
<tr>
    <td>{$test['class']}</td>
    <td class="memory">{$formattedMemory}</td>
    <td><span class="execution-count">{$test['executions_count']}</span></td>
</tr>
HTML;
    }

    $html .= <<<HTML
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>📋 Todos los Tests</h2>
            <table>
                <thead>
                    <tr>
                        <th>Test</th>
                        <th>Tiempo Promedio</th>
                        <th>Tiempo Min/Max</th>
                        <th>Memoria Promedio</th>
                        <th>Ejecuciones</th>
                    </tr>
                </thead>
                <tbody>
HTML;

    foreach ($data['tests'] as $test) {
        $timeClass = getTimeClass($test['avg_time']);
        $formattedMemory = formatBytes($test['avg_memory']);
        $html .= <<<HTML
<tr>
    <td>{$test['class']}</td>
    <td class="$timeClass">{$test['avg_time']}ms</td>
    <td>{$test['min_time']}ms / {$test['max_time']}ms</td>
    <td class="memory">{$formattedMemory}</td>
    <td><span class="execution-count">{$test['executions_count']}</span></td>
</tr>
HTML;
    }

    $html .= <<<HTML
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
HTML;

    return $html;
}

function formatBytes(float $bytes): string
{
    if (!is_finite($bytes) || $bytes <= 0) {
        return '0 B';
    }

    $units = ['B', 'KB', 'MB', 'GB'];
    $factor = floor(log($bytes, 1024));
    return round($bytes / (1024 ** $factor), 2) . ' ' . $units[$factor];
}

function getTimeClass(float $time): string
{
    if ($time < 10) return 'time-good';
    if ($time < 50) return 'time-warning';
    return 'time-danger';
}
