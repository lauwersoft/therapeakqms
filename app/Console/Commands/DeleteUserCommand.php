<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteUserCommand extends Command
{
    protected $signature = 'user:delete';

    protected $description = 'Delete a user';

    public function handle(): int
    {
        $email = $this->ask('Enter user email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email '{$email}' not found.");
            return self::FAILURE;
        }

        $this->table(
            ['ID', 'Name', 'Email', 'Role', 'Approved'],
            [[$user->id, $user->name, $user->email, $user->role, $user->approved ? 'Yes' : 'No']],
        );

        if (! $this->confirm('Are you sure you want to delete this user?', false)) {
            $this->info('Cancelled.');
            return self::SUCCESS;
        }

        $user->delete();

        $this->info('User deleted successfully.');

        return self::SUCCESS;
    }
}
