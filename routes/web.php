<?php

use Illuminate\Support\Facades\Route;

Route::middleware(config('beartropy-permissions.middleware', ['web', 'auth']))
    ->prefix(config('beartropy-permissions.prefix', 'permissions'))
    ->name('beartropy-permissions.')
    ->group(function () {
        // Use Route::livewire() when available (Livewire 4+), fall back to Route::get() (Livewire 3)
        if (Route::hasMacro('livewire')) {
            Route::livewire('/', \Beartropy\Permissions\Livewire\PermissionsManager::class)
                ->name('index');
        } else {
            Route::get('/', \Beartropy\Permissions\Livewire\PermissionsManager::class)
                ->name('index');
        }
    });
