<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ChangePasswordCommand extends Command
{
    protected $signature = 'user:change-password';

    protected $description = 'Change a user\'s password';

    public function handle(): int
    {
        $email = $this->ask('Enter user email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email '{$email}' not found.");
            return self::FAILURE;
        }

        $this->info("User found: {$user->name} ({$user->email})");

        $password = $this->secret('Enter new password (leave empty for random)');

        $generated = false;

        if (empty($password)) {
            $password = Str::random(16);
            $generated = true;
        }

        $user->update(['password' => $password]);

        $this->info('Password changed successfully!');

        if ($generated) {
            $this->newLine();
            $this->warn("Generated password: {$password}");
            $this->warn("Make sure to save this password - it won't be shown again.");
        }

        return self::SUCCESS;
    }
}
