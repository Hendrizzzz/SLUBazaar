<?php

declare(strict_types=1);

enum NotificationType: string
{
    // Bidder Events
    case OUTBID = 'OUTBID';
    case WIN = 'WIN';
    case WATCHLIST = 'WATCHLIST';

    // Seller Events
    case SOLD = 'SOLD';
    case EXPIRED = 'EXPIRED';
    case COMPLETE = 'COMPLETE'; // Transaction finished/Verified

    // System Events
    case SYSTEM = 'SYSTEM';
    case WARNING = 'WARNING'; // Admin actions (bans/removals)
}