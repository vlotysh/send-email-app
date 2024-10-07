<?php

namespace App\Models;

use App\Enums\EmailStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $recipient
 * @property string $subject
 * @property string $body
 * @property string $scheduled_time
 * @property string $status
 */
class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient',
        'subject',
        'body',
        'scheduled_time',
        'status',
    ];

    protected $casts = [
        'status' => EmailStatus::class,
    ];
}
