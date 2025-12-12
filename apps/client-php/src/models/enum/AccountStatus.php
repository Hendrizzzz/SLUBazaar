<?php

declare(strict_types=1);

enum AccountStatus : string
{
    case Active = 'active';
    case Unverified = 'unverified';
    case Banned = 'banned';
}
