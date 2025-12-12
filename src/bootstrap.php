<?php

declare(strict_types=1);

require_once __DIR__ . '/Container.php';

$dbConfig = require_once __DIR__ . '/../config/database.php';

$container = new Container($dbConfig);

date_default_timezone_set('Asia/Manila');

return $container;