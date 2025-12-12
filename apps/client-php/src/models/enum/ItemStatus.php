<?php

declare(strict_types=1);

enum ItemStatus : string 
{
    case Pending = 'Pending';
    case Active = 'Active';
    case Expired = 'Expired';
    case AwaitingMeetup = 'Awaiting Meetup';
    case Sold = 'Sold';
    case Disputed = 'Disputed';
    case CancelledBySeller = 'Cancelled By Seller';
}