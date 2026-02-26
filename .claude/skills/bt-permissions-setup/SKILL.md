---
name: bt-permissions-setup
description: Help users install and configure Beartropy Permissions in their Laravel projects
version: 1.0.0
author: Beartropy
tags: [beartropy, permissions, installation, setup, configuration, roles, spatie]
---

# Beartropy Permissions Setup Guide

You are an expert in helping users install and configure Beartropy Permissions in their Laravel applications.

---

## Requirements

- PHP >= 8.2
- Laravel >= 11.x
- Livewire >= 3.x
- beartropy/ui (installed automatically)
- beartropy/tables (installed automatically)
- spatie/laravel-permission ^6.0

---

## Installation

### Step 1: Install via Composer

```bash
composer require beartropy/permissions
```

This also installs `beartropy/ui`, `beartropy/tables`, and `spatie/laravel-permission` as dependencies.

### Step 2: Publish and Run Spatie Migrations

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### Step 3: Add HasRoles Trait to User Model

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

### Step 4: Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag=beartropy-permissions-config
```

### Step 5: Define the Authorization Gate

By default, Beartropy Permissions checks the `manage-permissions` gate. Define it in your `AuthServiceProvider` or a gate file:

```php
Gate::define('manage-permissions', function ($user) {
    return $user->hasRole('admin');
    // Or any custom logic: $user->is_admin, $user->id === 1, etc.
});
```

To disable authorization (e.g., for development), set `gate` to `null` in config:

```php
// config/beartropy-permissions.php
'gate' => null,
```

---

## Configuration

Key configuration options in `config/beartropy-permissions.php`:

| Key | Default | Description |
|-----|---------|-------------|
| `prefix` | `'permissions'` | Route prefix (e.g., `/permissions`) |
| `middleware` | `['web', 'auth', 'can:manage-permissions']` | Route middleware |
| `gate` | `'manage-permissions'` | Gate name for authorization (null to disable) |
| `layout` | `'beartropy-permissions::layouts.app'` | Blade layout for full-page views |
| `user_model` | `'App\\Models\\User'` | User model class |
| `user_display_name` | `'name'` | User model attribute for display |
| `user_search_fields` | `['name', 'email']` | Fields searchable in users table |
| `guards` | `null` | Available guards (null = auto-detect) |
| `group_permissions` | `true` | Group permissions by dot-notation prefix |

---

## Usage

### Full Manager (Tabbed Interface)

```blade
<livewire:beartropy-permissions::permissions-manager />
```

### Standalone Tables

```blade
{{-- Roles with create/edit/delete --}}
<livewire:beartropy-permissions::roles-table />
<livewire:beartropy-permissions::role-modal />
<livewire:beartropy-permissions::role-permissions-modal />

{{-- Permissions with create/edit/delete --}}
<livewire:beartropy-permissions::permissions-table />
<livewire:beartropy-permissions::permission-modal />

{{-- Users with role/permission assignment --}}
<livewire:beartropy-permissions::users-table />
<livewire:beartropy-permissions::user-assignments-modal />
```

---

## AI Coding Skills

Install Beartropy skills for AI assistants:

```bash
php artisan beartropy:skills
```

This discovers and installs skills from all Beartropy packages.

---

## Common Issues & Solutions

### "Gate [manage-permissions] is not defined"
Define the gate in your AuthServiceProvider, or set `'gate' => null` in config for development.

### Permissions not updating after changes
Spatie caches permissions. The package clears cache automatically on mutations, but if issues persist: `php artisan permission:cache-reset`.

### User model not found
Update `user_model` in config to match your application's User model class.
