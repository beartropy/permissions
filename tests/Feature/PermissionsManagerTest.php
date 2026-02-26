<?php

use Beartropy\Permissions\Livewire\PermissionsManager;
use Livewire\Livewire;

it('renders the permissions manager component', function () {
    $this->createAuthorizedUser();

    Livewire::test(PermissionsManager::class)
        ->assertOk();
});

it('defaults to the roles tab', function () {
    $this->createAuthorizedUser();

    Livewire::test(PermissionsManager::class)
        ->assertSet('activeTab', 'roles');
});

it('switches to the permissions tab', function () {
    $this->createAuthorizedUser();

    Livewire::test(PermissionsManager::class)
        ->call('setTab', 'permissions')
        ->assertSet('activeTab', 'permissions');
});

it('switches to the users tab', function () {
    $this->createAuthorizedUser();

    Livewire::test(PermissionsManager::class)
        ->call('setTab', 'users')
        ->assertSet('activeTab', 'users');
});

it('ignores invalid tab values', function () {
    $this->createAuthorizedUser();

    Livewire::test(PermissionsManager::class)
        ->call('setTab', 'invalid')
        ->assertSet('activeTab', 'roles');
});
