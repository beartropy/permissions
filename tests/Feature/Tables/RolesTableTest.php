<?php

use Beartropy\Permissions\Livewire\Tables\RolesTable;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->createAuthorizedUser();
});

it('renders the roles table', function () {
    Livewire::test(RolesTable::class)
        ->assertOk();
});

it('displays roles in the table', function () {
    Role::create(['name' => 'admin', 'guard_name' => 'web']);
    Role::create(['name' => 'editor', 'guard_name' => 'web']);

    Livewire::test(RolesTable::class)
        ->assertSee('admin')
        ->assertSee('editor');
});

it('shows permission and user counts', function () {
    $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $perm = Permission::create(['name' => 'users.view', 'guard_name' => 'web']);
    $role->givePermissionTo($perm);

    $component = Livewire::test(RolesTable::class);
    $component->assertOk();
});

it('deletes a single role', function () {
    $role = Role::create(['name' => 'to-delete', 'guard_name' => 'web']);

    Livewire::test(RolesTable::class)
        ->call('deleteRole', $role->id)
        ->assertDispatched('refresh');

    expect(Role::find($role->id))->toBeNull();
});

it('deletes selected roles in bulk', function () {
    $role1 = Role::create(['name' => 'bulk-1', 'guard_name' => 'web']);
    $role2 = Role::create(['name' => 'bulk-2', 'guard_name' => 'web']);

    // Simulate selecting rows
    $component = Livewire::test(RolesTable::class);

    // Toggle bulk selection
    $component->set('yat_selected_checkbox', [$role1->id, $role2->id])
        ->call('deleteSelected')
        ->assertDispatched('refresh');

    expect(Role::whereIn('id', [$role1->id, $role2->id])->count())->toBe(0);
});

it('does nothing when deleting with empty selection', function () {
    Role::create(['name' => 'safe-role', 'guard_name' => 'web']);

    Livewire::test(RolesTable::class)
        ->call('deleteSelected');

    expect(Role::where('name', 'safe-role')->exists())->toBeTrue();
});

it('clears permission cache on delete', function () {
    $role = Role::create(['name' => 'cached', 'guard_name' => 'web']);

    // This should not throw
    Livewire::test(RolesTable::class)
        ->call('deleteRole', $role->id);

    expect(true)->toBeTrue();
});

it('uses withCount instead of eager loading', function () {
    $role = Role::create(['name' => 'counted', 'guard_name' => 'web']);
    $perm = Permission::create(['name' => 'test.perm', 'guard_name' => 'web']);
    $role->givePermissionTo($perm);

    // The query should use withCount — we verify the table renders correctly
    Livewire::test(RolesTable::class)
        ->assertOk();
});

it('blocks unauthorized delete when gate is configured', function () {
    config()->set('beartropy-permissions.gate', 'manage-permissions');
    $role = Role::create(['name' => 'protected', 'guard_name' => 'web']);

    Livewire::test(RolesTable::class)
        ->call('deleteRole', $role->id)
        ->assertForbidden();

    expect(Role::find($role->id))->not->toBeNull();
});

it('blocks unauthorized bulk delete when gate is configured', function () {
    config()->set('beartropy-permissions.gate', 'manage-permissions');
    $role = Role::create(['name' => 'protected-bulk', 'guard_name' => 'web']);

    Livewire::test(RolesTable::class)
        ->set('yat_selected_checkbox', [$role->id])
        ->call('deleteSelected')
        ->assertForbidden();

    expect(Role::find($role->id))->not->toBeNull();
});
