<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

it('requires authentication for the admin panel and exposes no registration route', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('Selotemna Admin');

    expect(Route::has('filament.admin.auth.register'))->toBeFalse();
});

it('rejects authenticated users who are not approved administrators', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

it('allows approved administrators to open the dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Dashboard');
});

it('creates an approved administrator through the secure console command', function () {
    $this->artisan('selotemna:make-admin')
        ->expectsQuestion('Administrator name', 'Selotemna Administrator')
        ->expectsQuestion('Administrator email', 'admin@selotemna.test')
        ->expectsQuestion('Password (at least 12 characters)', 'SecurePassword123')
        ->expectsQuestion('Confirm password', 'SecurePassword123')
        ->expectsOutputToContain('Admin access granted to admin@selotemna.test.')
        ->assertSuccessful();

    $admin = User::query()->where('email', 'admin@selotemna.test')->firstOrFail();

    expect($admin->is_admin)->toBeTrue()
        ->and($admin->email_verified_at)->not->toBeNull()
        ->and(Hash::check('SecurePassword123', $admin->password))->toBeTrue();
});
