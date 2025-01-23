<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create
                            {firstname? : The fistname of the user (optional)}
                            {lastname? : The lastname of the user (optional)}
                            {email? : The email of the user}
                            {--password= : The password of the user (optional)}
                            {--admin : Specify if the user is an administrator (boolean)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user using provided parameters or using CLI input';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Collect parameters or ask for input
        $firstname = $this->argument('firstname') ?? $this->ask('What is the user\'s firstname? (optional)');
        $lastname = $this->argument('lastname') ?? $this->ask('What is the user\'s lastname? (optional)');
        $email = $this->argument('email') ?? $this->ask('What is the user\'s email?');
        $password = $this->option('password') ?? $this->secret('What is the user\'s password?');
        $admin = $this->option('admin') ?? $this->confirm('Is the user an administrator?', false);

        // Validate input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address.');
            return Command::FAILURE;
        }

        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters.');
            return Command::FAILURE;
        }

        // Create the user
        $user = User::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => $admin,
        ]);

        $this->info("User {$user->firstname} {$user->lastname} created successfully with ID {$user->id}!");

        return Command::SUCCESS;
    }
}
