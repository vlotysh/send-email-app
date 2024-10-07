<?php

use App\Console\Commands\CheckEmails;
use Illuminate\Support\Facades\Schedule;

Schedule::command(CheckEmails::class)->everyMinute();

