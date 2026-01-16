<?php

use Illuminate\Support\Facades\Route;

Route::middleware(config('beartropy-permissions.middleware', ['web', 'auth']))
    ->prefix(config('beartropy-permissions.prefix', 'permissions'))
    ->name('beartropy-permissions.')
    ->group(function () {
        Route::get('/', \Beartropy\Permissions\Livewire\PermissionsManager::class)
            ->name('index');
    });
