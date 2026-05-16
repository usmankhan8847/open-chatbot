<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OpenChatBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openchatbot:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the admin account for the Open Chatbot platform';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Welcome to open-chatbot Setup');

        if (User::exists()) {
            $this->warn('Admin already set up.');
            return Command::SUCCESS;
        }

        $email = $this->askForEmail();
        $password = $this->askForPassword();

        User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info('Admin account created. You can now log in.');

        return Command::SUCCESS;
    }

    /**
     * Ask for and validate the admin email.
     *
     * @return string
     */
    private function askForEmail(): string
    {
        $email = $this->ask('Enter admin email:');

        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $this->error('Invalid email format. Please try again.');
            return $this->askForEmail();
        }

        return $email;
    }

    /**
     * Ask for and confirm the admin password.
     *
     * @return string
     */
    private function askForPassword(): string
    {
        $password = $this->secret('Enter admin password:');
        $confirmPassword = $this->secret('Confirm admin password:');

        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match. Please try again.');
            return $this->askForPassword();
        }

        if (empty($password)) {
            $this->error('Password cannot be empty.');
            return $this->askForPassword();
        }

        return $password;
    }
}
