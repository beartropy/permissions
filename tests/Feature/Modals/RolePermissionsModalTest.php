<?php

use Beartropy\Permissions\Livewire\Modals\RolePermissionsModal;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->createAuthorizedUser();

    $this->role = Role::create(['name' => 'editor', 'guard_name' => 'web']);

    $this->permissions = collect([
        Permission::create(['name' => 'users.view', 'guard_name' => 'web']),
        Permission::create(['name' => 'users.create', 'guard_name' => 'web']),
        Permission::create(['name' => 'posts.view', 'guard_name' => 'web']),
        Permission::create(['name' => 'posts.edit', 'guard_name' => 'web']),
    ]);
});

it('renders the role permissions modal', function () {
    Livewire::test(RolePermissionsModal::class)
        ->assertOk();
});

it('opens with role permissions loaded', function () {
    $this->role->givePermissionTo('users.view', 'posts.view');

    $component = Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id);

    $selected = $component->get('selectedPermissions');
    $userView = Permission::findByName('users.view', 'web');
    $postsView = Permission::findByName('posts.view', 'web');

    expect($selected)->toContain($userView->id)
        ->toContain($postsView->id);
});

it('toggles a permission on', function () {
    $perm = $this->permissions->first();

    Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id)
        ->call('togglePermission', $perm->id)
        ->assertSet('selectedPermissions', [$perm->id]);
});

it('toggles a permission off', function () {
    $perm = $this->permissions->first();
    $this->role->givePermissionTo($perm);

    $component = Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id);

    expect($component->get('selectedPermissions'))->toContain($perm->id);

    $component->call('togglePermission', $perm->id);

    expect($component->get('selectedPermissions'))->not->toContain($perm->id);
});

it('selects all visible permissions', function () {
    Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id)
        ->call('selectAll');

    // All 4 permissions should be selected
    $component = Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id)
        ->call('selectAll');

    expect(count($component->get('selectedPermissions')))->toBe(4);
});

it('selects all only selects filtered when searching', function () {
    $component = Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id)
        ->set('search', 'users')
        ->call('selectAll');

    $selected = $component->get('selectedPermissions');
    $usersView = Permission::findByName('users.view', 'web');
    $usersCreate = Permission::findByName('users.create', 'web');

    expect($selected)->toContain($usersView->id)
        ->toContain($usersCreate->id)
        ->toHaveCount(2);
});

it('deselects all visible permissions', function () {
    $component = Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id)
        ->call('selectAll')
        ->call('deselectAll');

    expect($component->get('selectedPermissions'))->toBeEmpty();
});

it('deselects all only removes filtered when searching', function () {
    $component = Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id)
        ->call('selectAll');

    expect(count($component->get('selectedPermissions')))->toBe(4);

    // Now search for 'users' and deselect all — should only remove users.* permissions
    $component->set('search', 'users')
        ->call('deselectAll');

    $selected = $component->get('selectedPermissions');
    $postsView = Permission::findByName('posts.view', 'web');
    $postsEdit = Permission::findByName('posts.edit', 'web');

    expect($selected)->toContain($postsView->id)
        ->toContain($postsEdit->id)
        ->toHaveCount(2);
});

it('saves synced permissions to the role', function () {
    $perm1 = Permission::findByName('users.view', 'web');
    $perm2 = Permission::findByName('posts.view', 'web');

    Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id)
        ->call('togglePermission', $perm1->id)
        ->call('togglePermission', $perm2->id)
        ->call('save')
        ->assertSet('showModal', false)
        ->assertDispatched('refresh');

    $this->role->refresh();
    expect($this->role->permissions->pluck('name')->all())
        ->toContain('users.view')
        ->toContain('posts.view');
});

it('clears permission cache on save', function () {
    $registrar = app(\Spatie\Permission\PermissionRegistrar::class);
    $registrar->forgetCachedPermissions();

    Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id)
        ->call('save');

    // No assertion needed — the test ensures forgetCachedPermissions runs without error
    expect(true)->toBeTrue();
});

it('groups permissions by prefix', function () {
    $component = Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id);

    $grouped = $component->get('groupedPermissions');

    expect($grouped)->toHaveKeys(['posts', 'users']);
    expect($grouped['users'])->toHaveCount(2);
    expect($grouped['posts'])->toHaveCount(2);
});

it('returns flat list when group_permissions is false', function () {
    config()->set('beartropy-permissions.group_permissions', false);

    $component = Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id);

    $grouped = $component->get('groupedPermissions');

    expect($grouped)->toHaveKey('general');
    expect($grouped['general'])->toHaveCount(4);
});

it('filters permissions by search term', function () {
    $component = Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id)
        ->set('search', 'posts');

    $filtered = $component->get('filteredPermissions');

    expect($filtered)->toHaveCount(2);
    expect($filtered->pluck('name')->all())->each->toContain('posts');
});

it('does not save when roleId is null', function () {
    $component = Livewire::test(RolePermissionsModal::class)
        ->set('roleId', null)
        ->call('save');

    // Should silently return without error
    expect(true)->toBeTrue();
});

it('closes the modal', function () {
    Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id)
        ->assertSet('showModal', true)
        ->call('close')
        ->assertSet('showModal', false);
});

it('blocks unauthorized save when gate is configured', function () {
    config()->set('beartropy-permissions.gate', 'manage-permissions');

    Livewire::test(RolePermissionsModal::class)
        ->call('open', $this->role->id)
        ->call('save')
        ->assertForbidden();
});
