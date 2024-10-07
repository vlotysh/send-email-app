<?php

namespace Tests\Unit;

use App\Enums\EmailStatus;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Email;
use App\Console\Commands\CheckEmails;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendEmailJob;
use Carbon\Carbon;

class CheckEmailsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_queues_emails_that_are_ready_to_be_sent()
    {
        Queue::fake();

        // Create an email that is scheduled to be sent now
        $email = Email::factory()->create([
            'recipient' => 'test@example.com',
            'subject' => 'Test Email',
            'body' => 'This is a test email.',
            'scheduled_time' => Carbon::now()->subMinute(),
            'status' =>  EmailStatus::PENDING(),
        ]);

        // Run the CheckEmails command
        $this->artisan('emails:check')
            ->expectsOutput('Email to test@example.com has been queued for sending.')
            ->assertExitCode(0);

        // Assert that the SendEmailJob was dispatched
        Queue::assertPushed(SendEmailJob::class, function ($job) use ($email) {
            /** @var SendEmailJob $job */
            return $job->getEmail()->id === $email->id;
        });
    }

    /** @test */
    public function it_does_not_queue_emails_that_fail_validation()
    {
        Queue::fake();

        // Create an email with an invalid recipient
        $email = Email::factory()->create([
            'recipient' => 'invalid-email',
            'subject' => 'Test Email',
            'body' => 'This is a test email.',
            'scheduled_time' => Carbon::now()->subMinute(),
            'status' => EmailStatus::QUEUED(),
        ]);


        $this->artisan('emails:check')
            ->assertExitCode(0);

        Queue::assertNotPushed(SendEmailJob::class);
    }
}
