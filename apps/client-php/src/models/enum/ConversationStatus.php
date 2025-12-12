<?php

declare(strict_types=1);

enum ConversationStatus : string
{
    case Active = 'Active';
    case Archived = 'Archived';
}