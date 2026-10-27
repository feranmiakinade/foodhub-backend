<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    protected $signature = 'foodhub:make-admin {email}';

    protected $description = 'Promote an existing user to administrator';

    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error('No user was found with that email address.');

            return self::FAILURE;
        }

        if (! $this->confirm("Promote {$user->email} to administrator?")) {
            $this->info('No changes were made.');

            return self::SUCCESS;
        }

        $user->role = 'admin';
        $user->save();

        $this->info("{$user->email} is now an administrator.");

        return self::SUCCESS;
    }
}