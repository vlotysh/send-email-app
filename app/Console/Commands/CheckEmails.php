<?php

namespace App\Console\Commands;

use App\Enums\EmailStatus;
use Illuminate\Console\Command;
use App\Models\Email;
use App\Jobs\SendEmailJob;
use Carbon\Carbon;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class CheckEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for scheduled emails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();

        // Find all emails that are pending and scheduled to be sent now or earlier
        $emails = Email::where('status', EmailStatus::PENDING())
            ->where('scheduled_time', '<=', $now)
            ->get();

        if ($emails->isEmpty()) {
            $this->info('No emails found on scheduled check.');

            return 0;
        }

        foreach ($emails as $email) {
            SendEmailJob::dispatch($email);

            // Update the status to "queued"
            $email->update(['status' => EmailStatus::QUEUED()]);

            $this->info(sprintf('Email to %s has been queued for sending.', $email->recipient));
        }

        return 0;
    }
}
