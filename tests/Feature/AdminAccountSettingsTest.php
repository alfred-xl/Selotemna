<?php

use App\Filament\Pages\Auth\EditProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

it('exposes account settings to administrators with a read-only login email', function () {
    $administrator = User::factory()->create([
        'is_admin' => true,
        'name' => 'Original Administrator',
        'email' => 'admin@selotemna.test',
    ]);

    $this->actingAs($administrator)
        ->get('/admin/profile')
        ->assertOk()
        ->assertSee('Account settings')
        ->assertSee('Login email');

    Livewire::test(EditProfile::class)
        ->assertFormFieldDisabled('email')
        ->fillForm([
            'name' => 'Updated Administrator',
            'email' => 'changed@selotemna.test',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($administrator->refresh()->name)->toBe('Updated Administrator')
        ->and($administrator->email)->toBe('admin@selotemna.test');
});

it('rejects non-administrators from account settings', function () {
    $this->actingAs(User::factory()->create(['is_admin' => false]))
        ->get('/admin/profile')
        ->assertForbidden();
});

it('requires the current password and the shared strong password policy', function () {
    $administrator = User::factory()->create([
        'is_admin' => true,
        'email' => 'admin@selotemna.test',
        'password' => 'OriginalPassword123',
    ]);
    $this->actingAs($administrator);

    Livewire::test(EditProfile::class)
        ->fillForm([
            'password' => 'NewSecurePassword123',
            'passwordConfirmation' => 'NewSecurePassword123',
            'currentPassword' => 'IncorrectPassword123',
        ])
        ->call('save')
        ->assertHasFormErrors(['currentPassword']);

    Livewire::test(EditProfile::class)
        ->fillForm([
            'password' => 'Short1A',
            'passwordConfirmation' => 'Short1A',
            'currentPassword' => 'OriginalPassword123',
        ])
        ->call('save')
        ->assertHasFormErrors(['password']);

    Livewire::test(EditProfile::class)
        ->fillForm([
            'password' => 'NewSecurePassword123',
            'passwordConfirmation' => 'DifferentPassword123',
            'currentPassword' => 'OriginalPassword123',
        ])
        ->call('save')
        ->assertHasFormErrors(['password']);

    Livewire::test(EditProfile::class)
        ->fillForm([
            'password' => 'NewSecurePassword123',
            'passwordConfirmation' => 'NewSecurePassword123',
            'currentPassword' => 'OriginalPassword123',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Auth::validate([
        'email' => $administrator->email,
        'password' => 'OriginalPassword123',
    ]))->toBeFalse()
        ->and(Auth::validate([
            'email' => $administrator->email,
            'password' => 'NewSecurePassword123',
        ]))->toBeTrue();
});
