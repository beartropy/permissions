<?php

/**
 * Tests for the data layer used by the bt-permissions-project-context MCP tool.
 *
 * Verifies that the data sources the tool relies on are present and consistent.
 *
 * Since `laravel/mcp` is not a dev dependency, we test the raw data sources
 * rather than instantiating the tool class.
 */

$basePath = dirname(__DIR__, 3);
$composerPath = $basePath.'/composer.json';

// --- composer.json version ---

it('has a version field in composer.json', function () use ($composerPath) {
    $data = json_decode(file_get_contents($composerPath), true);

    expect($data)->toHaveKey('version');
    expect($data['version'])->toBeString()->not->toBeEmpty();
});

it('has a valid semver version in composer.json', function () use ($composerPath) {
    $data = json_decode(file_get_contents($composerPath), true);

    expect($data['version'])->toMatch('/^\d+\.\d+\.\d+(-[\w.]+)?$/');
});

// --- config file ---

it('has a config file with expected keys', function () use ($basePath) {
    $configPath = $basePath.'/config/beartropy-permissions.php';

    expect(file_exists($configPath))->toBeTrue();

    $config = require $configPath;

    expect($config)->toBeArray();
    expect($config)->toHaveKeys([
        'prefix',
        'middleware',
        'gate',
        'layout',
        'user_model',
        'user_display_field',
        'guards',
        'group_permissions',
    ]);
});
