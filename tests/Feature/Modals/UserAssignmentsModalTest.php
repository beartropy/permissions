<?php

use Beartropy\Permissions\Livewire\Modals\UserAssignmentsModal;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\Models\User;

beforeEach(function () {
    $this->admin = $this->createAuthorizedUser();

    $this->targetUser = User::create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->role1 = Role::create(['name' => 'editor', 'guard_name' => 'web']);
    $this->role2 = Role::create(['name' => 'viewer', 'guard_name' => 'web']);
    $this->perm1 = Permission::create(['name' => 'users.view', 'guard_name' => 'web']);
    $this->perm2 = Permission::create(['name' => 'posts.create', 'guard_name' => 'web']);
});

it('renders the user assignments modal', function () {
    Livewire::test(UserAssignmentsModal::class)
        ->assertOk();
});

it('opens with user data loaded', function () {
    $this->targetUser->assignRole('editor');
    $this->targetUser->givePermissionTo('users.view');

    $component = Livewire::test(UserAssignmentsModal::class)
        ->call('open', $this->targetUser->id);

    expect($component->get('showModal'))->toBeTrue();
    expect($component->get('userId'))->toBe($this->targetUser->id);
    expect($component->get('selectedRoles'))->toContain($this->role1->id);
    expect($component->get('selectedPermissions'))->toContain($this->perm1->id);
});

it('toggles a role on', function () {
    $component = Livewire::test(UserAssignmentsModal::class)
        ->call('open', $this->targetUser->id)
        ->call('toggleRole', $this->role1->id);

    expect($component->get('selectedRoles'))->toContain($this->role1->id);
});

it('toggles a role off', function () {
    $this->targetUser->assignRole('editor');

    $component = Livewire::test(UserAssignmentsModal::class)
        ->call('open', $this->targetUser->id)
        ->call('toggleRole', $this->role1->id);

    expect($component->get('selectedRoles'))->not->toContain($this->role1->id);
});

it('toggles a permission on', function () {
    $component = Livewire::test(UserAssignmentsModal::class)
        ->call('open', $this->targetUser->id)
        ->call('togglePermission', $this->perm1->id);

    expect($component->get('selectedPermissions'))->toContain($this->perm1->id);
});

it('toggles a permission off', function () {
    $this->targetUser->givePermissionTo('users.view');

    $component = Livewire::test(UserAssignmentsModal::class)
        ->call('open', $this->targetUser->id)
        ->call('togglePermission', $this->perm1->id);

    expect($component->get('selectedPermissions'))->not->toContain($this->perm1->id);
});

it('saves synced roles and permissions', function () {
    $component = Livewire::test(UserAssignmentsModal::class)
        ->call('open', $this->targetUser->id)
        ->call('toggleRole', $this->role1->id)
        ->call('togglePermission', $this->perm1->id)
        ->call('save')
        ->assertSet('showModal', false)
        ->assertDispatched('refresh');

    $this->targetUser->refresh();
    expect($this->targetUser->hasRole('editor'))->toBeTrue();
    expect($this->targetUser->hasDirectPermission('users.view'))->toBeTrue();
});

it('computes inherited permissions correctly', function () {
    // Give the role a permission, assign role to user
    $this->role1->givePermissionTo('users.view');
    $this->targetUser->assignRole('editor');

    $component = Livewire::test(UserAssignmentsModal::class)
        ->call('open', $this->targetUser->id);

    $inherited = $component->get('inheritedPermissions');
    expect($inherited)->toContain($this->perm1->id);
});

it('shows user display name from configured field', function () {
    $component = Livewire::test(UserAssignmentsModal::class)
        ->call('open', $this->targetUser->id);

    expect($component->get('userDisplayName'))->toBe('Jane Doe');
});

it('shows fallback display name when field is null', function () {
    // Create user without the display field value
    $user = User::create([
        'name' => '',
        'email' => 'noname@example.com',
        'password' => bcrypt('password'),
    ]);

    config()->set('beartropy-permissions.user_display_field', 'nonexistent_field');

    $component = Livewire::test(UserAssignmentsModal::class)
        ->call('open', $user->id);

    $displayName = $component->get('userDisplayName');
    expect($displayName)->toContain((string) $user->id);
});

it('filters roles by search', function () {
    $component = Livewire::test(UserAssignmentsModal::class)
        ->call('open', $this->targetUser->id)
        ->set('roleSearch', 'editor');

    $filtered = $component->get('filteredRoles');
    expect($filtered)->toHaveCount(1);
    expect($filtered->first()->name)->toBe('editor');
});

it('filters permissions by search', function () {
    $component = Livewire::test(UserAssignmentsModal::class)
        ->call('open', $this->targetUser->id)
        ->set('permissionSearch', 'users');

    $filtered = $component->get('filteredPermissions');
    expect($filtered)->toHaveCount(1);
    expect($filtered->first()->name)->toBe('users.view');
});

it('closes the modal and resets user', function () {
    Livewire::test(UserAssignmentsModal::class)
        ->call('open', $this->targetUser->id)
        ->assertSet('showModal', true)
        ->call('close')
        ->assertSet('showModal', false)
        ->assertSet('user', null);
});

it('does not save when userId is null', function () {
    Livewire::test(UserAssignmentsModal::class)
        ->set('userId', null)
        ->call('save');

    // Should silently return without error
    expect(true)->toBeTrue();
});

it('blocks unauthorized save when gate is configured', function () {
    config()->set('beartropy-permissions.gate', 'manage-permissions');

    Livewire::test(UserAssignmentsModal::class)
        ->call('open', $this->targetUser->id)
        ->call('save')
        ->assertForbidden();
});
