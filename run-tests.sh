#!/bin/bash

METRICS_FILE="var/test_metrics.json"

XDEBUG_MODE=coverage php bin/phpunit --coverage-html ./coverage "$@"

php bin/performance.php