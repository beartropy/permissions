<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

it('merges config', function () {
    expect(config('beartropy-permissions.prefix'))->toBe('permissions');
    expect(config('beartropy-permissions.default_guard'))->toBe('web');
});

it('registers views', function () {
    $viewFinder = app('view');
    expect($viewFinder->exists('beartropy-permissions::permissions-manager'))->toBeTrue();
});

it('registers translation strings', function () {
    expect(__('beartropy-permissions::messages.roles'))->toBe('Roles');
    expect(__('beartropy-permissions::messages.permissions'))->toBe('Permissions');
});

it('registers routes', function () {
    $routes = Route::getRoutes();
    $route = $routes->getByName('beartropy-permissions.index');

    expect($route)->not->toBeNull();
});

it('registers livewire components', function () {
    // Verify components can be instantiated via Livewire::test (which internally resolves the component)
    Livewire::test(\Beartropy\Permissions\Livewire\PermissionsManager::class)->assertOk();
    Livewire::test(\Beartropy\Permissions\Livewire\Modals\RoleModal::class)->assertOk();
    Livewire::test(\Beartropy\Permissions\Livewire\Modals\PermissionModal::class)->assertOk();
    Livewire::test(\Beartropy\Permissions\Livewire\Modals\RolePermissionsModal::class)->assertOk();
    Livewire::test(\Beartropy\Permissions\Livewire\Modals\UserAssignmentsModal::class)->assertOk();
});

it('applies configured middleware to routes', function () {
    $routes = Route::getRoutes();
    $route = $routes->getByName('beartropy-permissions.index');

    expect($route->middleware())->toContain('web');
});

it('uses configured route prefix', function () {
    $routes = Route::getRoutes();
    $route = $routes->getByName('beartropy-permissions.index');

    expect($route->uri())->toBe('permissions');
});
