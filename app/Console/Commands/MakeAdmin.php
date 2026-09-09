<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

#[Signature('selotemna:make-admin')]
#[Description('Create a new Selotemna administrator or promote an existing user')]
class MakeAdmin extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $data = [
            'name' => trim((string) $this->ask('Administrator name')),
            'email' => strtolower(trim((string) $this->ask('Administrator email'))),
            'password' => (string) $this->secret('Password (at least 12 characters)'),
            'password_confirmation' => (string) $this->secret('Confirm password'),
        ];

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()],
        ]);

        if ($validator->fails()) {
            $this->components->error(implode(PHP_EOL, $validator->errors()->all()));

            return self::FAILURE;
        }

        $user = User::query()->firstOrNew(['email' => $data['email']]);
        $user->fill([
            'name' => $data['name'],
            'password' => $data['password'],
        ]);
        $user->is_admin = true;
        $user->email_verified_at ??= now();
        $user->save();

        $this->components->info('Admin access granted to '.$user->email.'.');

        return self::SUCCESS;
    }
}
