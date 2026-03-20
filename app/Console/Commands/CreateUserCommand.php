<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateUserCommand extends Command
{
    protected $signature = 'user:create';

    protected $description = 'Create a new user';

    public function handle(): int
    {
        $name = $this->ask('Enter name');
        $email = $this->ask('Enter email');

        $role = $this->choice('Select role', User::ROLES, User::ROLE_EDITOR);

        $password = $this->secret('Enter password (leave empty for random)');

        $generated = false;

        if (empty($password)) {
            $password = Str::random(16);
            $generated = true;
        }

        $approved = $this->confirm('Approve user immediately?', true);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'approved' => $approved,
            'email_verified_at' => now(),
        ]);

        $this->info("User created successfully!");
        $this->table(
            ['ID', 'Name', 'Email', 'Role', 'Approved'],
            [[$user->id, $user->name, $user->email, $user->role, $user->approved ? 'Yes' : 'No']],
        );

        if ($generated) {
            $this->newLine();
            $this->warn("Generated password: {$password}");
            $this->warn("Make sure to save this password - it won't be shown again.");
        }

        return self::SUCCESS;
    }
}
