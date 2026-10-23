<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

// php artisan gx:create-admin admin@example.com "Admin Name" [--role=superadmin|admin|staff]
Artisan::command('gx:create-admin {email} {name=Administrator} {--role=superadmin : superadmin, admin or staff}', function (string $email, string $name) {
    $role = (string) $this->option('role');
    if (! in_array($role, User::ROLES, true)) {
        $this->error('The role must be one of: '.implode(', ', User::ROLES).'.');

        return 1;
    }

    $password = $this->secret('Password (min. 12 characters)');

    $validator = Validator::make(
        ['email' => $email, 'password' => $password],
        ['email' => ['required', 'email'], 'password' => ['required', 'string', 'min:12']]
    );

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }

        return 1;
    }

    $user = User::firstOrNew(['email' => strtolower($email)]);
    $user->name = $name;
    $user->password = $password; // hashed by the model cast
    $user->is_admin = true;
    $user->role = $role;
    $user->save();

    $this->info("Account ready: {$user->email} ({$role})");

    return 0;
})->purpose('Create or update a staff-area account (default role: superadmin) - used to create the first superadmin');


Artisan::command('gx:test-mail {to}', function (string $to) {
    $this->line('Mailer: '.config('mail.default').' | Host: '.config('mail.mailers.smtp.host').' | From: '.config('mail.from.address'));
    try {
        \Illuminate\Support\Facades\Mail::raw('GameXtreme test e-mail', fn ($m) => $m->to($to)->subject('GameXtreme test'));
    } catch (\Throwable $e) {
        $this->error('Sending failed: '.$e->getMessage());
        return 1;
    }
    $this->info('Handed to the mail server.');
    return 0;
});