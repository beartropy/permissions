<?php

use Beartropy\Permissions\Livewire\Modals\PermissionModal;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    $this->createAuthorizedUser();
});

it('renders the permission modal component', function () {
    Livewire::test(PermissionModal::class)
        ->assertOk();
});

it('opens create modal with reset fields', function () {
    Livewire::test(PermissionModal::class)
        ->call('create')
        ->assertSet('showModal', true)
        ->assertSet('permissionId', null)
        ->assertSet('name', '')
        ->assertSet('guard_name', 'web');
});

it('opens edit modal with permission data', function () {
    $permission = Permission::create(['name' => 'users.view', 'guard_name' => 'web']);

    Livewire::test(PermissionModal::class)
        ->call('edit', $permission->id)
        ->assertSet('showModal', true)
        ->assertSet('permissionId', $permission->id)
        ->assertSet('name', 'users.view')
        ->assertSet('guard_name', 'web');
});

it('creates a new permission on save', function () {
    Livewire::test(PermissionModal::class)
        ->call('create')
        ->set('name', 'posts.create')
        ->call('save')
        ->assertSet('showModal', false)
        ->assertDispatched('refresh');

    expect(Permission::where('name', 'posts.create')->exists())->toBeTrue();
});

it('updates an existing permission on save', function () {
    $permission = Permission::create(['name' => 'users.view', 'guard_name' => 'web']);

    Livewire::test(PermissionModal::class)
        ->call('edit', $permission->id)
        ->set('name', 'users.list')
        ->call('save')
        ->assertSet('showModal', false)
        ->assertDispatched('refresh');

    expect($permission->fresh()->name)->toBe('users.list');
});

it('fails validation with empty name', function () {
    Livewire::test(PermissionModal::class)
        ->call('create')
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

it('fails validation with duplicate name in same guard', function () {
    Permission::create(['name' => 'users.view', 'guard_name' => 'web']);

    Livewire::test(PermissionModal::class)
        ->call('create')
        ->set('name', 'users.view')
        ->set('guard_name', 'web')
        ->call('save')
        ->assertHasErrors(['name']);
});

it('allows duplicate name in different guard', function () {
    Permission::create(['name' => 'users.view', 'guard_name' => 'web']);

    Livewire::test(PermissionModal::class)
        ->call('create')
        ->set('name', 'users.view')
        ->set('guard_name', 'api')
        ->call('save')
        ->assertHasNoErrors();

    expect(Permission::where('name', 'users.view')->count())->toBe(2);
});

it('allows updating permission to keep its own name', function () {
    $permission = Permission::create(['name' => 'users.view', 'guard_name' => 'web']);

    Livewire::test(PermissionModal::class)
        ->call('edit', $permission->id)
        ->call('save')
        ->assertHasNoErrors();
});

it('closes the modal', function () {
    Livewire::test(PermissionModal::class)
        ->call('create')
        ->assertSet('showModal', true)
        ->call('close')
        ->assertSet('showModal', false);
});

it('computes available guards from config', function () {
    config()->set('beartropy-permissions.guards', ['web', 'admin']);

    Livewire::test(PermissionModal::class)
        ->assertSet('guards', ['web', 'admin']);
});

it('blocks unauthorized save when gate is configured', function () {
    config()->set('beartropy-permissions.gate', 'manage-permissions');

    Livewire::test(PermissionModal::class)
        ->call('create')
        ->set('name', 'hacked.permission')
        ->call('save')
        ->assertForbidden();

    expect(Permission::where('name', 'hacked.permission')->exists())->toBeFalse();
});
