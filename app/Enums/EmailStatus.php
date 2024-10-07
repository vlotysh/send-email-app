<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method PENDING()
 * @method QUEUED()
 * @method SENT()
 * @method FAILED()
 */
enum EmailStatus: string
{
    use InvokableCases;

    case PENDING = 'pending';
    case QUEUED = 'queued';
    case SENT = 'sent';
    case FAILED = 'failed';
}
