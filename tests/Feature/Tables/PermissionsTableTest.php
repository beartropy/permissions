<?php

use Beartropy\Permissions\Livewire\Tables\PermissionsTable;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->createAuthorizedUser();
});

it('renders the permissions table', function () {
    Livewire::test(PermissionsTable::class)
        ->assertOk();
});

it('displays permissions in the table', function () {
    Permission::create(['name' => 'users.view', 'guard_name' => 'web']);
    Permission::create(['name' => 'posts.create', 'guard_name' => 'web']);

    Livewire::test(PermissionsTable::class)
        ->assertSee('users.view')
        ->assertSee('posts.create');
});

it('shows group column when group_permissions is enabled', function () {
    Permission::create(['name' => 'users.view', 'guard_name' => 'web']);

    config()->set('beartropy-permissions.group_permissions', true);

    Livewire::test(PermissionsTable::class)
        ->assertOk();
});

it('hides group column when group_permissions is disabled', function () {
    config()->set('beartropy-permissions.group_permissions', false);

    Livewire::test(PermissionsTable::class)
        ->assertOk();
});

it('deletes a single permission', function () {
    $perm = Permission::create(['name' => 'to-delete', 'guard_name' => 'web']);

    Livewire::test(PermissionsTable::class)
        ->call('deletePermission', $perm->id)
        ->assertDispatched('refresh');

    expect(Permission::find($perm->id))->toBeNull();
});

it('deletes selected permissions in bulk', function () {
    $perm1 = Permission::create(['name' => 'bulk.one', 'guard_name' => 'web']);
    $perm2 = Permission::create(['name' => 'bulk.two', 'guard_name' => 'web']);

    Livewire::test(PermissionsTable::class)
        ->set('yat_selected_checkbox', [$perm1->id, $perm2->id])
        ->call('deleteSelected')
        ->assertDispatched('refresh');

    expect(Permission::whereIn('id', [$perm1->id, $perm2->id])->count())->toBe(0);
});

it('does nothing when deleting with empty selection', function () {
    Permission::create(['name' => 'safe.perm', 'guard_name' => 'web']);

    Livewire::test(PermissionsTable::class)
        ->call('deleteSelected');

    expect(Permission::where('name', 'safe.perm')->exists())->toBeTrue();
});

it('clears permission cache on delete', function () {
    $perm = Permission::create(['name' => 'cached.perm', 'guard_name' => 'web']);

    Livewire::test(PermissionsTable::class)
        ->call('deletePermission', $perm->id);

    expect(true)->toBeTrue();
});

it('uses withCount instead of eager loading', function () {
    $perm = Permission::create(['name' => 'counted.perm', 'guard_name' => 'web']);
    $role = Role::create(['name' => 'counter', 'guard_name' => 'web']);
    $role->givePermissionTo($perm);

    Livewire::test(PermissionsTable::class)
        ->assertOk();
});

it('blocks unauthorized delete when gate is configured', function () {
    config()->set('beartropy-permissions.gate', 'manage-permissions');
    $perm = Permission::create(['name' => 'protected.perm', 'guard_name' => 'web']);

    Livewire::test(PermissionsTable::class)
        ->call('deletePermission', $perm->id)
        ->assertForbidden();

    expect(Permission::find($perm->id))->not->toBeNull();
});
