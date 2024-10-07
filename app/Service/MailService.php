<?php

namespace App\Service;

use App\Mail\MailSender;
use App\Models\Email;
use Illuminate\Support\Facades\Mail;

class MailService
{
    /**
     * @param Email $email
     * @return void
     */
    public function sendEmail(Email $email): void
    {
        $sender = new MailSender([
            'subject' => $email->subject,
            'body' => $email->body,
        ]);

        Mail::to($email->recipient)->send($sender);
    }
}
