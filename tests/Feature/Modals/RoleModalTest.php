<?php

use Beartropy\Permissions\Livewire\Modals\RoleModal;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->createAuthorizedUser();
});

it('renders the role modal component', function () {
    Livewire::test(RoleModal::class)
        ->assertOk();
});

it('opens create modal with reset fields', function () {
    Livewire::test(RoleModal::class)
        ->call('create')
        ->assertSet('showModal', true)
        ->assertSet('roleId', null)
        ->assertSet('name', '')
        ->assertSet('guard_name', 'web');
});

it('opens edit modal with role data', function () {
    $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);

    Livewire::test(RoleModal::class)
        ->call('edit', $role->id)
        ->assertSet('showModal', true)
        ->assertSet('roleId', $role->id)
        ->assertSet('name', 'admin')
        ->assertSet('guard_name', 'web');
});

it('creates a new role on save', function () {
    Livewire::test(RoleModal::class)
        ->call('create')
        ->set('name', 'editor')
        ->call('save')
        ->assertSet('showModal', false)
        ->assertDispatched('refresh');

    expect(Role::where('name', 'editor')->exists())->toBeTrue();
});

it('updates an existing role on save', function () {
    $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);

    Livewire::test(RoleModal::class)
        ->call('edit', $role->id)
        ->set('name', 'super-admin')
        ->call('save')
        ->assertSet('showModal', false)
        ->assertDispatched('refresh');

    expect($role->fresh()->name)->toBe('super-admin');
});

it('fails validation with empty name', function () {
    Livewire::test(RoleModal::class)
        ->call('create')
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

it('fails validation with duplicate name in same guard', function () {
    Role::create(['name' => 'admin', 'guard_name' => 'web']);

    Livewire::test(RoleModal::class)
        ->call('create')
        ->set('name', 'admin')
        ->set('guard_name', 'web')
        ->call('save')
        ->assertHasErrors(['name']);
});

it('allows duplicate name in different guard', function () {
    Role::create(['name' => 'admin', 'guard_name' => 'web']);

    Livewire::test(RoleModal::class)
        ->call('create')
        ->set('name', 'admin')
        ->set('guard_name', 'api')
        ->call('save')
        ->assertHasNoErrors();

    expect(Role::where('name', 'admin')->count())->toBe(2);
});

it('allows updating role to keep its own name', function () {
    $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);

    Livewire::test(RoleModal::class)
        ->call('edit', $role->id)
        ->call('save')
        ->assertHasNoErrors();
});

it('closes the modal', function () {
    Livewire::test(RoleModal::class)
        ->call('create')
        ->assertSet('showModal', true)
        ->call('close')
        ->assertSet('showModal', false);
});

it('computes available guards from config', function () {
    config()->set('beartropy-permissions.guards', ['web', 'api']);

    Livewire::test(RoleModal::class)
        ->assertSet('guards', ['web', 'api']);
});

it('auto-detects guards when config is null', function () {
    config()->set('beartropy-permissions.guards', null);

    $component = Livewire::test(RoleModal::class);
    $guards = $component->get('guards');

    expect($guards)->toContain('web');
});

it('blocks unauthorized save when gate is configured', function () {
    config()->set('beartropy-permissions.gate', 'manage-permissions');

    Livewire::test(RoleModal::class)
        ->call('create')
        ->set('name', 'hacker-role')
        ->call('save')
        ->assertForbidden();

    expect(Role::where('name', 'hacker-role')->exists())->toBeFalse();
});
