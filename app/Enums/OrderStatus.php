<?php

namespace App\Enums;


Enum OrderStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETE = 'complete';
    case FAILED = 'failed';
}