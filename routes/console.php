<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Console\Command\Command;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:setup {--name=} {--email=} {--password=} {--promote-only}', function () {
    $defaultName = (string) env('ADMIN_DEFAULT_NAME', 'Admin User');
    $name = trim((string) ($this->option('name') ?: $defaultName));

    $emailInput = (string) ($this->option('email') ?: env('ADMIN_DEFAULT_EMAIL', ''));
    $email = strtolower(trim($emailInput));

    if ($email === '') {
        $email = strtolower(trim((string) $this->ask('Admin email address')));
    }

    $promoteOnly = (bool) $this->option('promote-only');
    $password = $promoteOnly ? null : (string) ($this->option('password') ?: env('ADMIN_DEFAULT_PASSWORD', ''));

    if (! $promoteOnly && $password === '') {
        $password = (string) $this->secret('New admin password (min 8 characters)');
        $confirmation = (string) $this->secret('Confirm new admin password');

        if ($password !== $confirmation) {
            $this->error('Password confirmation does not match.');
            return Command::FAILURE;
        }
    }

    $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255'],
    ];

    if (! $promoteOnly) {
        $rules['password'] = ['required', Password::defaults()];
    }

    $validator = Validator::make([
        'name' => $name,
        'email' => $email,
        'password' => $password,
    ], $rules);

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }

        return Command::FAILURE;
    }

    $user = User::query()->where('email', $email)->first();

    if (! $user && $promoteOnly) {
        $this->error('No user found for that email. Remove --promote-only to create one.');
        return Command::FAILURE;
    }

    if (! $user) {
        $user = new User();
        $user->email = $email;
        $this->line('No existing user found. Creating a new admin account.');
    } else {
        $this->line('Existing user found. Updating admin credentials.');
    }

    $user->name = $name;
    $user->is_admin = true;

    if (! $promoteOnly && is_string($password) && $password !== '') {
        $user->password = Hash::make($password);
    }

    if (is_null($user->email_verified_at)) {
        $user->email_verified_at = now();
    }

    $user->save();

    $this->info('Admin credentials configured successfully.');
    $this->line('Login URL: '.url('/login'));
    $this->line('Admin URL: '.url('/admin'));

    return Command::SUCCESS;
})->purpose('Create or update admin credentials securely');
