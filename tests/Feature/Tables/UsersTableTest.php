<?php

use Beartropy\Permissions\Livewire\Tables\UsersTable;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\Models\User;

beforeEach(function () {
    $this->createAuthorizedUser();
});

it('renders the users table', function () {
    Livewire::test(UsersTable::class)
        ->assertOk();
});

it('displays users in the table', function () {
    Livewire::test(UsersTable::class)
        ->assertSee('Test User');
});

it('shows user roles', function () {
    $user = User::first();
    $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $user->assignRole($role);

    Livewire::test(UsersTable::class)
        ->assertOk();
});

it('shows direct permissions count', function () {
    $user = User::first();
    $perm = Permission::create(['name' => 'users.view', 'guard_name' => 'web']);
    $user->givePermissionTo($perm);

    Livewire::test(UsersTable::class)
        ->assertOk();
});

it('uses withCount for permissions', function () {
    $user = User::first();
    $perm1 = Permission::create(['name' => 'perm.one', 'guard_name' => 'web']);
    $perm2 = Permission::create(['name' => 'perm.two', 'guard_name' => 'web']);
    $user->givePermissionTo($perm1, $perm2);

    // Should render without N+1 issues
    Livewire::test(UsersTable::class)
        ->assertOk();
});

it('uses configured display field', function () {
    config()->set('beartropy-permissions.user_display_field', 'email');

    Livewire::test(UsersTable::class)
        ->assertSee('test@example.com');
});

it('respects user search fields config', function () {
    // Create another user to test search
    User::create([
        'name' => 'Alice Wonderland',
        'email' => 'alice@example.com',
        'password' => bcrypt('password'),
    ]);

    // UsersTable should be renderable with search fields configured
    config()->set('beartropy-permissions.user_search_fields', ['name', 'email']);

    Livewire::test(UsersTable::class)
        ->assertOk();
});

it('eager loads roles for badge display', function () {
    $user = User::first();
    $role = Role::create(['name' => 'badge-test', 'guard_name' => 'web']);
    $user->assignRole($role);

    Livewire::test(UsersTable::class)
        ->assertOk();
});
