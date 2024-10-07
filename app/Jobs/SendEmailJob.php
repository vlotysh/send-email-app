<?php

namespace App\Jobs;

use App\Enums\EmailStatus;
use App\Models\Email;
use App\Service\MailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Email $email;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function handle(MailService $mailService)
    {
        try {
            $mailService->sendEmail($this->email);

            $this->email->update(['status' => EmailStatus::SENT()]);
        } catch (Exception $e) {
            $this->handleRetry($e);
        }
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    private function handleRetry(Exception $e): void
    {
        // Log error and retry if necessary
        Log::error(sprintf('Failed to send email: %s', $e->getMessage()));

        if ($this->attempts() >= $this->tries) {
            $this->fail($e);
            $this->email->update(['status' => EmailStatus::FAILED()]);
            Log::error(sprintf('Email to %s failed after maximum retry attempts.', $this->email->recipient));
        }
        $this->release(30);
        return;
    }
}
