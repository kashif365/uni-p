<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('demo:admin', function () {
    $name = trim((string) $this->ask('Administrator name'));
    $email = strtolower(trim((string) $this->ask('Administrator email')));
    $password = $this->secret('Administrator password');

    $validator = Validator::make(compact('name', 'email', 'password'), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'max:72', Password::min(12)],
    ]);

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }

        return 1;
    }

    User::create($validator->validated() + ['role' => 'admin', 'status' => 'active']);
    $this->info('Administrator created.');

    return 0;
})->purpose('Create an administrator for the university demo');
