<?php
/**
 * SLU BAZAAR - BACKGROUND WORKER
 * 
 * Usage: php console.php auction:worker
 * Purpose: Checks for expired auctions every X seconds and finalizes them.
 */

declare(strict_types=1);

// 1. Load the Application Container
// We reuse the exact same wiring logic as the web app.
/** @var Container $container */
$container = require_once __DIR__ . '/src/bootstrap.php';

// 2. Parse Command Line Arguments
$command = $argv[1] ?? null;

if ($command === 'auction:worker') {
    echo "[" . date('Y-m-d H:i:s') . "] Starting Auction Worker...\n";

    $service = $container->getAuctionService();

    // Infinite Loop (The "Daemon" mode)
    while (true) {
        try {
            $processed = $service->processExpiredAuctions();

            if ($processed > 0)
                echo "[" . date('H:i:s') . "] Processed {$processed} auctions.\n";

            sleep(5);

        } catch (Throwable $e) {
            echo "[ERROR] " . $e->getMessage() . "\n";
            sleep(5);
        }
    }

} else {
    echo "Usage: php console.php auction:worker\n";
    exit(1);
}