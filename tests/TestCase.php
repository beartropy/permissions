<?php

namespace Tests;

use Beartropy\Permissions\PermissionsServiceProvider;
use Beartropy\Tables\BeartropyTableServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Cache::flush();

        // Run Spatie permission migrations
        $migration = include $this->getSpatiePermissionMigrationPath();
        $migration->up();
    }

    protected function tearDown(): void
    {
        \Illuminate\Support\Facades\Schema::dropIfExists('model_has_permissions');
        \Illuminate\Support\Facades\Schema::dropIfExists('model_has_roles');
        \Illuminate\Support\Facades\Schema::dropIfExists('role_has_permissions');
        \Illuminate\Support\Facades\Schema::dropIfExists('permissions');
        \Illuminate\Support\Facades\Schema::dropIfExists('roles');
        \Illuminate\Support\Facades\Schema::dropIfExists('users');

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            \BladeUI\Icons\BladeIconsServiceProvider::class,
            \BladeUI\Heroicons\BladeHeroiconsServiceProvider::class,
            \Beartropy\Ui\BeartropyUiServiceProvider::class,
            BeartropyTableServiceProvider::class,
            PermissionServiceProvider::class,
            PermissionsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', 'base64:Hupx3yAySikrM2/edkZQNQHslgDWYfiBfCuSThJ5SK8=');

        $app['config']->set('view.paths', array_merge(
            [__DIR__ . '/views'],
            config('view.paths', []),
        ));

        // Configure the permissions package to use our test User model
        $app['config']->set('beartropy-permissions.user_model', \Tests\Models\User::class);
        $app['config']->set('beartropy-permissions.gate', null); // Disable gate by default in tests

        // Configure auth guards so Spatie can resolve the guard for the User model
        $app['config']->set('auth.defaults.guard', 'web');
        $app['config']->set('auth.guards.web', [
            'driver' => 'session',
            'provider' => 'users',
        ]);
        $app['config']->set('auth.providers.users', [
            'driver' => 'eloquent',
            'model' => \Tests\Models\User::class,
        ]);

        // Create users table
        \Illuminate\Support\Facades\Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->default('');
            $table->timestamps();
        });
    }

    protected function getSpatiePermissionMigrationPath(): string
    {
        $vendorDir = realpath(__DIR__ . '/../vendor');

        // Look for the migration file
        $migrationDir = $vendorDir . '/spatie/laravel-permission/database/migrations';
        $files = glob($migrationDir . '/*_create_permission_tables.php');

        return $files[0] ?? $migrationDir . '/create_permission_tables.php.stub';
    }

    /**
     * Create a test user.
     */
    protected function createUser(array $attributes = []): \Tests\Models\User
    {
        return \Tests\Models\User::create(array_merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ], $attributes));
    }

    /**
     * Create and authenticate a test user with the manage-permissions gate.
     */
    protected function createAuthorizedUser(array $attributes = []): \Tests\Models\User
    {
        $user = $this->createUser($attributes);
        $this->actingAs($user);

        return $user;
    }
}
