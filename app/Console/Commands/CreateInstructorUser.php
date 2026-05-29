<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateInstructorUser extends Command
{
    protected $signature = 'instructor:create-user {--name=} {--email=} {--password=}';

    protected $description = 'Create an instructor user';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Name');
        $email = $this->option('email') ?: $this->ask('Email');
        $password = $this->option('password') ?: $this->secret('Password (leave blank to auto-generate)');

        if (!$password) {
            $password = Str::password(16);
            $this->line('Generated password: ' . $password);
        }

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->is_instructor = true;
        $user->save();

        $this->info('Instructor user saved: ' . $user->email);

        return self::SUCCESS;
    }
}
