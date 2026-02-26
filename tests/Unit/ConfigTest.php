<?php

use Illuminate\Support\Facades\Config;

it('has a default prefix of permissions', function () {
    expect(config('beartropy-permissions.prefix'))->toBe('permissions');
});

it('has a default gate of manage-permissions', function () {
    // In tests, gate is null by default (overridden in TestCase)
    // Check the config file directly
    $config = include __DIR__ . '/../../config/beartropy-permissions.php';
    expect($config['gate'])->toBe('manage-permissions');
});

it('has default middleware including auth and can:manage-permissions', function () {
    $config = include __DIR__ . '/../../config/beartropy-permissions.php';
    expect($config['middleware'])->toContain('web')
        ->toContain('auth')
        ->toContain('can:manage-permissions');
});

it('has group_permissions enabled by default', function () {
    expect(config('beartropy-permissions.group_permissions'))->toBeTrue();
});

it('has dot as default permission group separator', function () {
    expect(config('beartropy-permissions.permission_group_separator'))->toBe('.');
});

it('has default user search fields', function () {
    $config = include __DIR__ . '/../../config/beartropy-permissions.php';
    expect($config['user_search_fields'])->toBe(['name', 'email']);
});
