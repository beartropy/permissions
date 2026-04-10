# Permissions Examples - Beartropy Permissions

## Basic Setup

### 1. Install

```bash
composer require beartropy/permissions
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### 2. Add HasRoles to User Model

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

### 3. Define the Gate

```php
// app/Providers/AppServiceProvider.php
use Illuminate\Support\Facades\Gate;

public function boot(): void
{
    Gate::define('manage-permissions', function ($user) {
        return $user->hasRole('admin');
    });
}
```

### 4. Embed the Manager

```blade
{{-- In your admin view --}}
<div class="max-w-7xl mx-auto py-6">
    @livewire('beartropy-permissions::permissions-manager')
</div>
```

## Seeding Roles and Permissions

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions with dot notation grouping
        $permissions = [
            'users.create', 'users.edit', 'users.delete', 'users.view',
            'posts.create', 'posts.edit', 'posts.delete', 'posts.publish',
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $editor = Role::firstOrCreate(['name' => 'editor']);
        $editor->givePermissionTo([
            'posts.create', 'posts.edit', 'posts.publish',
        ]);
    }
}
```

## Custom Layout

```php
// config/beartropy-permissions.php
'layout' => 'layouts.admin',
```

## Custom Route Prefix

```php
// config/beartropy-permissions.php
'prefix' => 'admin/roles-and-permissions',
'middleware' => ['web', 'auth', 'role:admin'],
```
