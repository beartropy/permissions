# Beartropy Permissions - Universal AI Assistant Guide

> This guide helps AI assistants generate correct code using Beartropy Permissions for Laravel applications.

## Overview

**Beartropy Permissions** is a beautiful admin UI for `spatie/laravel-permission`, built with Livewire 3, Beartropy UI, and Beartropy Tables. It provides an intuitive interface for managing roles, permissions, and user assignments.

## Quick Start

Embed the full permissions manager in any view:

```blade
@livewire('beartropy-permissions::permissions-manager')
```

This renders a tabbed interface with Roles, Permissions, and Users tables plus all CRUD modals.

## Components

### Main Manager
- `beartropy-permissions::permissions-manager` — Tabbed interface (Roles / Permissions / Users)

### Tables
- `beartropy-permissions::roles-table` — Roles with permission count, user count, actions
- `beartropy-permissions::permissions-table` — Permissions with role count, grouping support
- `beartropy-permissions::users-table` — Users with role badges, direct permission count

### Modals
- `beartropy-permissions::role-modal` — Create/edit roles
- `beartropy-permissions::permission-modal` — Create/edit permissions
- `beartropy-permissions::role-permissions-modal` — Assign permissions to a role (grouped checkboxes, search, select/deselect all)
- `beartropy-permissions::user-assignments-modal` — Assign roles and direct permissions to a user (shows inherited permissions)

## Configuration

```php
// config/beartropy-permissions.php
return [
    'prefix' => 'permissions',                          // Route prefix
    'middleware' => ['web', 'auth', 'can:manage-permissions'],
    'gate' => 'manage-permissions',                     // Authorization gate
    'layout' => null,                                   // Custom Blade layout
    'user_model' => App\Models\User::class,
    'user_display_field' => 'name',
    'user_search_fields' => ['name', 'email'],
    'group_permissions' => true,                        // Group by dot prefix
    'permission_group_separator' => '.',                // e.g., users.create
    'guards' => null,                                   // null = auto-detect
    'default_guard' => 'web',
];
```

## Authorization

Define the gate in `AuthServiceProvider` or a policy:

```php
Gate::define('manage-permissions', function ($user) {
    return $user->hasRole('admin');
});
```

## Permission Grouping

Permissions are auto-grouped by the configured separator:

```
users.create    → group: "users"
users.edit      → group: "users"
posts.publish   → group: "posts"
```

Groups appear as collapsible sections in the role permissions modal.

## Livewire Events

| Event | Description |
|-------|-------------|
| `createRole` / `editRole` | Open role modal |
| `createPermission` / `editPermission` | Open permission modal |
| `manageRolePermissions` | Open role permissions assignment modal |
| `manageUserAssignments` | Open user roles/permissions modal |
| `refresh` | Refresh all tables after CRUD operations |
