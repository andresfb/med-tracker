<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SetupUserCommand extends Command
{
    protected $signature = 'setup:user';

    protected $description = 'Create the Admin user';

    public function handle(): int
    {
        try {
            $email = config('app.admin_email');
            $user = User::where('email', $email)->first();
            if ($user !== null) {
                $this->warn("\nUser already exists.");
                $this->seed();

                return 0;
            }

            $this->line('');
            $this->warn("Creating admin user with email: $email\n");

            $name = '';
            while ($name === '') {
                $name = trim($this->ask('What is your name?'));

                if ($name === '') {
                    $this->error('Name cannot be empty.');
                }
            }

            $password = '';
            while ($password === '') {
                $password = trim($this->secret('What is your password?'));

                if ($password === '') {
                    $this->error('Password cannot be empty.');
                }
            }

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            $this->info("Admin user created.\n");
            $this->seed();

            return 0;
        } catch (\Exception $e) {
            $this->line('');
            $this->error($e->getMessage());
            $this->line('');
            Log::error($e->getMessage());

            return 1;
        }
    }

    private function seed(): void
    {
        if ($this->confirm('Run the DB seeders?', true)) {
            $this->call('db:seed');
        }

        $this->line('');
        $this->info("Done.\n");
    }
}
