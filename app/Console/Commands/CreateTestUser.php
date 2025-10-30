<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:test {--email=test@example.com} {--password=password} {--name="Test User"} {--status=active}';

    /**
     * The console command description.
     */
    protected $description = 'Create or update a test user for logging in';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = (string) $this->option('email');
        $password = (string) $this->option('password');
        $name = (string) $this->option('name');
        $status = (string) $this->option('status');

        $user = null;

        User::withoutSyncingToSearch(function () use ($email, $password, $name, $status, &$user) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make($password),
                    'status' => $status,
                ]
            );
        });

        $this->info('Test user ready. You can log in with:');
        $this->line('  Email:    '.$user->email);
        $this->line('  Password: '.$password);
        $this->line('  Status:   '.$user->status);

        return self::SUCCESS;
    }
}


