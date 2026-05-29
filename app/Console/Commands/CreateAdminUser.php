<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create-user {--name=} {--email=} {--password=}';

    protected $description = 'Create an admin user';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Name');
        $email = $this->option('email') ?: $this->ask('Email');
        $password = $this->option('password') ?: $this->secret('Password (leave blank to auto-generate)');

        if (!$password) {
            $password = Str::password(16);
            $this->line('Generated password: '.$password);
        }

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->is_admin = true;
        $user->save();

        $this->info('Admin user saved: '.$user->email);

        return self::SUCCESS;
    }
}

