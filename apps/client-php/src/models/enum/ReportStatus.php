<?php

declare(strict_types=1);


enum ReportStatus : string 
{
    case Pending = 'Pending';
    case InReview = 'In Review';
    case Resolved = 'Resolved';
    case Dismissed = 'Dismissed';
}