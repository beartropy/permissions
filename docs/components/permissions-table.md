# Permissions Table

Displays all Spatie permissions with optional group column, guard name, and role count. Supports bulk selection and deletion.

## Basic Usage

```blade
<livewire:beartropy-permissions::permissions-table />
<livewire:beartropy-permissions::permission-modal />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `tableName` | `string` | `'PermissionsTable'` | Unique table identifier |
| `theme` | `string` | `'emerald'` | Color theme for the table |

## Features

- Automatic permission grouping by dot-notation prefix (e.g., `users.create` → group `users`)
- Bulk selection with "Delete Selected" action button
- Per-row actions: Edit, Delete
- Aggregate role count (no N+1)
- Authorization gate check on all mutations

## Examples

### Standalone with modal
```blade
<livewire:beartropy-permissions::permissions-table />
<livewire:beartropy-permissions::permission-modal />
```

## Configuration

| Key | Default | Description |
|-----|---------|-------------|
| `group_permissions` | `true` | Show/hide the group column |
| `permission_group_separator` | `'.'` | Character used to split permission name into group + action |
| `gate` | `'manage-permissions'` | Gate checked before mutations |
