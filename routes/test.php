<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('mail:test {email}', function (string $email) {
    Mail::raw('This is a test email from Therapeak QMS.', function ($message) use ($email) {
        $message->to($email)->subject('QMS Test Email');
    });
    $this->info("Test email sent to {$email}");
});
