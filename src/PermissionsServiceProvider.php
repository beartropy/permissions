<?php

namespace Beartropy\Permissions;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/beartropy-permissions.php',
            'beartropy-permissions'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'beartropy-permissions');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'beartropy-permissions');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/beartropy-permissions.php' => config_path('beartropy-permissions.php'),
        ], 'beartropy-permissions-config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/beartropy-permissions'),
        ], 'beartropy-permissions-views');

        // Publish translations
        $this->publishes([
            __DIR__ . '/../resources/lang' => lang_path('vendor/beartropy-permissions'),
        ], 'beartropy-permissions-lang');

        // Register Livewire components
        $this->registerLivewireComponents();

        // Register MCP tools with Laravel Boost when available
        if (class_exists(\Laravel\Boost\BoostServiceProvider::class)) {
            $this->registerBoostTools();
        }
    }

    /**
     * Register MCP tools with Laravel Boost when available.
     */
    protected function registerBoostTools(): void
    {
        $include = config('boost.mcp.tools.include', []);
        $include[] = \Beartropy\Permissions\Mcp\Tools\ComponentDocs::class;
        $include[] = \Beartropy\Permissions\Mcp\Tools\ListComponents::class;
        $include[] = \Beartropy\Permissions\Mcp\Tools\ProjectContext::class;
        config(['boost.mcp.tools.include' => $include]);
    }

    /**
     * Register all Livewire components for this package.
     */
    protected function registerLivewireComponents(): void
    {
        $components = [
            'beartropy-permissions::permissions-manager' => \Beartropy\Permissions\Livewire\PermissionsManager::class,
            'beartropy-permissions::roles-table' => \Beartropy\Permissions\Livewire\Tables\RolesTable::class,
            'beartropy-permissions::permissions-table' => \Beartropy\Permissions\Livewire\Tables\PermissionsTable::class,
            'beartropy-permissions::users-table' => \Beartropy\Permissions\Livewire\Tables\UsersTable::class,
            'beartropy-permissions::role-modal' => \Beartropy\Permissions\Livewire\Modals\RoleModal::class,
            'beartropy-permissions::permission-modal' => \Beartropy\Permissions\Livewire\Modals\PermissionModal::class,
            'beartropy-permissions::role-permissions-modal' => \Beartropy\Permissions\Livewire\Modals\RolePermissionsModal::class,
            'beartropy-permissions::user-assignments-modal' => \Beartropy\Permissions\Livewire\Modals\UserAssignmentsModal::class,
        ];

        foreach ($components as $name => $class) {
            Livewire::component($name, $class);
        }

        // Livewire 4 treats :: as a namespace separator, so components registered
        // via Livewire::component() with :: in their name won't resolve through the
        // standard finder. Register a fallback resolver to handle this.
        if (class_exists(\Livewire\Finder\Finder::class)) {
            Livewire::resolveMissingComponent(function (string $name) use ($components) {
                return $components[$name] ?? null;
            });
        }
    }
}
