# Users Table

Displays all users with their assigned roles (as badges) and direct permission count. Clicking "Manage" opens the user assignments modal.

## Basic Usage

```blade
<livewire:beartropy-permissions::users-table />
<livewire:beartropy-permissions::user-assignments-modal />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `tableName` | `string` | `'UsersTable'` | Unique table identifier |
| `theme` | `string` | `'sky'` | Color theme for the table |

## Features

- Configurable user model and display field
- Multi-field search (searches across all configured fields)
- Role badges rendered inline
- Direct permission count via aggregate query
- Responsive: email column collapses on mobile

## Examples

### Standalone with modal
```blade
<livewire:beartropy-permissions::users-table />
<livewire:beartropy-permissions::user-assignments-modal />
```

## Configuration

| Key | Default | Description |
|-----|---------|-------------|
| `user_model` | `'App\\Models\\User'` | Eloquent model class for users |
| `user_display_field` | `'name'` | Model attribute shown in the "User" column |
| `user_search_fields` | `['name', 'email']` | Fields included in search queries |
