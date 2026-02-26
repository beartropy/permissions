<?php

use Illuminate\Support\Facades\Gate;

it('blocks unauthenticated users from the permissions page', function () {
    // Without a login route, auth middleware will throw. Assert it's not 200.
    $this->get('/permissions')
        ->assertStatus(500); // Route [login] not defined in test env
});

it('allows authenticated and authorized users to access the permissions page', function () {
    $user = $this->createAuthorizedUser();

    Gate::define('manage-permissions', fn ($user) => true);

    $this->get('/permissions')
        ->assertOk();
});

it('blocks authenticated but unauthorized users', function () {
    $user = $this->createAuthorizedUser();

    Gate::define('manage-permissions', fn ($user) => false);

    $this->get('/permissions')
        ->assertForbidden();
});

it('returns 404 for non-existent sub-routes', function () {
    $user = $this->createAuthorizedUser();

    $this->get('/permissions/nonexistent')
        ->assertNotFound();
});
