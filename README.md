<div align="center">
    <h1>🛡️ Beartropy Permissions</h1>
    <p><strong>A beautiful UI for <a href="https://github.com/spatie/laravel-permission">spatie/laravel-permission</a></strong></p>
    <p>Manage roles, permissions, and user assignments with ease</p>
</div>

<div align="center">
    <a href="https://packagist.org/packages/beartropy/permissions"><img src="https://img.shields.io/packagist/v/beartropy/permissions.svg?style=flat-square&color=indigo" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/beartropy/permissions"><img src="https://img.shields.io/packagist/dt/beartropy/permissions.svg?style=flat-square&color=blue" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/beartropy/permissions"><img src="https://img.shields.io/packagist/l/beartropy/permissions?style=flat-square&color=slate" alt="License"></a>
</div>

<br>

This package provides an intuitive admin UI for [spatie/laravel-permission](https://github.com/spatie/laravel-permission), the industry-standard package for role and permission management in Laravel. Built with Livewire and designed with modern aesthetics in mind.

> **Note**: This is **not** a replacement for `spatie/laravel-permission`. It's a visual interface that makes it easier to manage the roles and permissions you create with Spatie's excellent package.

## ✨ Key Features

- �️ **Role Management** - Create, edit, and delete roles with an intuitive interface
- 🔑 **Permission Management** - Manage permissions with automatic grouping support
- 👥 **User Assignments** - Assign roles and direct permissions to users
- 📊 **Data Tables** - Searchable, sortable tables with bulk actions
- 🌐 **Internationalization** - Full i18n support (Spanish and English included)
- 🎨 **Dark Mode** - Seamless dark/light mode support
- ⚙️ **Highly Configurable** - Customize routes, middleware, guards, and more

## � Documentation

�👉 **[Read the full documentation at beartropy.com/permissions](https://beartropy.com/permissions)**

## 🚀 Quick Installation

```bash
composer require beartropy/permissions
```

### Setup Spatie Permission (if not installed)

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### Add HasRoles trait to User model

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

### Access the UI

Navigate to `/permissions` in your application.

## ⚙️ Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=beartropy-permissions-config
```

```php
// config/beartropy-permissions.php
return [
    'route_prefix' => 'permissions',
    'route_middleware' => ['web', 'auth'],
    'user_model' => App\Models\User::class,
    'guards' => ['web'],
    'default_guard' => 'web',
    'user_display_field' => 'name',
    'group_permissions' => true,
    'permission_group_separator' => '.',
];
```

## 🎨 Customization

### Publish Views

```bash
php artisan vendor:publish --tag=beartropy-permissions-views
```

### Publish Translations

```bash
php artisan vendor:publish --tag=beartropy-permissions-lang
```

## 📝 Permission Naming Convention

Use dot notation for automatic grouping:

| Permission | Group |
|------------|-------|
| `users.view` | users |
| `users.create` | users |
| `posts.edit` | posts |
| `settings.view` | settings |

## 🌐 Internationalization

The package includes translations for:
- 🇪🇸 Spanish (es)
- 🇺🇸 English (en)

Add more languages by publishing translations and creating new language files.

## 📦 Requirements

- PHP 8.1+
- Laravel 10.x or 11.x
- Livewire 3.x
- spatie/laravel-permission ^6.0
- beartropy/ui ^1.0
- beartropy/tables ^1.0

## 🤝 Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

> [!NOTE]
> **Disclaimer**: This software is provided "as is", without warranty of any kind, express or implied. Use at your own risk.