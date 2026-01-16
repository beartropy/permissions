# Beartropy Permissions

A comprehensive UI for managing roles, permissions, and user assignments for Laravel applications. Built with Livewire, Blade components from `beartropy/ui`, and data tables from `beartropy/tables`.

## Features

- 🛡️ **Role Management** - Create, edit, and delete roles with an intuitive interface
- 🔑 **Permission Management** - Manage permissions with automatic grouping support
- 👥 **User Assignments** - Assign roles and direct permissions to users
- 📊 **Data Tables** - Searchable, sortable tables with bulk actions
- 🌐 **Internationalization** - Full i18n support (Spanish and English included)
- 🎨 **Dark Mode** - Seamless dark/light mode support
- ⚙️ **Highly Configurable** - Customize routes, middleware, guards, and more

## Requirements

- PHP 8.1+
- Laravel 10.x or 11.x
- Livewire 3.x
- [spatie/laravel-permission](https://github.com/spatie/laravel-permission) ^6.0
- [beartropy/ui](https://github.com/beartropy/ui) ^1.0
- [beartropy/tables](https://github.com/beartropy/tables) ^1.0

## Installation

### 1. Install via Composer

```bash
composer require beartropy/permissions
```

### 2. Install Spatie Permission (if not already installed)

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### 3. Add `HasRoles` trait to your User model

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    
    // ...
}
```

### 4. Publish configuration (optional)

```bash
php artisan vendor:publish --tag=beartropy-permissions-config
```

## Configuration

After publishing, you'll find the configuration file at `config/beartropy-permissions.php`:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    */
    'route_prefix' => 'permissions',
    'route_middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    | The layout to use for the permissions UI. Set to null to use the
    | component without a layout wrapper.
    */
    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    | The fully qualified class name of your User model.
    */
    'user_model' => App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Guards
    |--------------------------------------------------------------------------
    | The guards available for roles and permissions.
    */
    'guards' => ['web'],
    'default_guard' => 'web',

    /*
    |--------------------------------------------------------------------------
    | User Display
    |--------------------------------------------------------------------------
    | Configure how users are displayed in the UI.
    */
    'user_display_field' => 'name',
    'user_search_fields' => ['name', 'email'],

    /*
    |--------------------------------------------------------------------------
    | Permission Grouping
    |--------------------------------------------------------------------------
    | Permissions can be grouped by a separator in their name.
    | E.g., "users.create", "users.edit" will be grouped under "users".
    */
    'group_permissions' => true,
    'permission_group_separator' => '.',
];
```

## Usage

### Accessing the UI

Navigate to `/permissions` (or your configured route prefix) to access the permissions management interface.

### Using in your own views

You can embed the permissions manager component anywhere:

```blade
<livewire:beartropy-permissions::permissions-manager />
```

Or embed individual tables:

```blade
{{-- Roles table only --}}
<livewire:beartropy-permissions::roles-table />

{{-- Permissions table only --}}
<livewire:beartropy-permissions::permissions-table />

{{-- Users table only --}}
<livewire:beartropy-permissions::users-table />
```

### Protecting with Middleware

It's recommended to protect the permissions route with authorization middleware:

```php
// config/beartropy-permissions.php
'route_middleware' => ['web', 'auth', 'can:manage-permissions'],
```

Or create a custom middleware:

```php
// app/Http/Middleware/EnsureUserCanManagePermissions.php
public function handle($request, Closure $next)
{
    if (! $request->user()?->hasRole('super-admin')) {
        abort(403);
    }
    
    return $next($request);
}
```

## Customization

### Publishing Views

```bash
php artisan vendor:publish --tag=beartropy-permissions-views
```

Views will be published to `resources/views/vendor/beartropy-permissions/`.

### Publishing Translations

```bash
php artisan vendor:publish --tag=beartropy-permissions-lang
```

Translations will be published to `lang/vendor/beartropy-permissions/`.

### Adding More Languages

Create a new directory under `lang/vendor/beartropy-permissions/` with the language code (e.g., `fr`, `pt`) and copy the `messages.php` file from an existing language.

### Custom User Model

If your User model is in a different namespace:

```php
// config/beartropy-permissions.php
'user_model' => \App\Domain\Users\Models\User::class,
```

### Multiple Guards

If you use multiple authentication guards:

```php
// config/beartropy-permissions.php
'guards' => ['web', 'api', 'admin'],
'default_guard' => 'web',
```

## Permission Naming Convention

We recommend using a dot notation for permission names to enable automatic grouping:

| Permission | Group | Description |
|------------|-------|-------------|
| `users.view` | users | View users |
| `users.create` | users | Create users |
| `users.edit` | users | Edit users |
| `users.delete` | users | Delete users |
| `posts.view` | posts | View posts |
| `posts.create` | posts | Create posts |
| `settings.view` | settings | View settings |
| `settings.edit` | settings | Edit settings |

## Events

The package dispatches Livewire events that you can listen to:

| Event | Description |
|-------|-------------|
| `refresh` | Triggered after any CRUD operation |
| `createRole` | Triggered when clicking "New Role" |
| `editRole` | Triggered when editing a role |
| `deleteRole` | Triggered when deleting a role |
| `manageRolePermissions` | Triggered when managing role permissions |
| `createPermission` | Triggered when clicking "New Permission" |
| `editPermission` | Triggered when editing a permission |
| `deletePermission` | Triggered when deleting a permission |
| `manageUserAssignments` | Triggered when managing user assignments |

### Listening to Events

```php
// In a Livewire component
#[On('refresh')]
public function refreshData(): void
{
    // Your custom logic
}
```

## Seeding Example

Here's an example seeder to get you started:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'posts.view', 'posts.create', 'posts.edit', 'posts.delete',
            'settings.view', 'settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);

        // Assign permissions to roles
        $admin->syncPermissions(Permission::all());
        $editor->syncPermissions([
            'users.view', 'posts.view', 'posts.create', 'posts.edit'
        ]);
        $viewer->syncPermissions([
            'users.view', 'posts.view', 'settings.view'
        ]);

        // Assign roles to users
        User::first()?->assignRole('admin');
    }
}
```

## Screenshots

### Roles Management
![Roles Table](screenshots/roles-table.png)

### Permission Assignment
![Role Permissions Modal](screenshots/role-permissions-modal.png)

### User Assignments
![User Assignments Modal](screenshots/user-assignments-modal.png)

## Troubleshooting

### Permissions not persisting

Ensure your User model uses the `HasRoles` trait:

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

### Views not updating after changes

Clear the view cache:

```bash
php artisan view:clear
php artisan livewire:discover
```

### Translations not working

Ensure the translations are published or your locale is set correctly:

```php
// config/app.php
'locale' => 'es', // or 'en'
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Credits

- [Beartropy Team](https://github.com/beartropy)
- [Spatie](https://spatie.be) for the excellent [laravel-permission](https://github.com/spatie/laravel-permission) package
