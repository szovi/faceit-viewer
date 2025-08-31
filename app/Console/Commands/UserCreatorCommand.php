<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Command\Command as CommandAlias;

class UserCreatorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create
                            {--name= : The name of the user}
                            {--email= : The email of the user}
                            {--password= : The password of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user interactively or via options';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->option('name') ?? $this->ask('Name');
        $email = $this->option('email') ?? $this->ask('Email');
        $password = $this->option('password') ?? $this->secret('Password');

        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists.");
            return CommandAlias::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("✅ User [{$user->email}] created successfully (ID: {$user->id})");

        return CommandAlias::SUCCESS;
    }
}
