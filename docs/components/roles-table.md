# Roles Table

Displays all Spatie roles with permission and user counts. Supports bulk selection, deletion, and inline action buttons for edit, manage permissions, and delete.

## Basic Usage

```blade
<livewire:beartropy-permissions::roles-table />
<livewire:beartropy-permissions::role-modal />
<livewire:beartropy-permissions::role-permissions-modal />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `tableName` | `string` | `'RolesTable'` | Unique table identifier |
| `theme` | `string` | `'indigo'` | Color theme for the table |

## Features

- Bulk selection with "Delete Selected" action button
- Per-row actions: Edit, Manage Permissions, Delete
- Aggregate counts for permissions and users (no N+1)
- Authorization gate check on all mutations
- Automatic Spatie permission cache clearing on delete

## Examples

### Standalone with modals
```blade
<livewire:beartropy-permissions::roles-table />
<livewire:beartropy-permissions::role-modal />
<livewire:beartropy-permissions::role-permissions-modal />
```

### Inside the full manager
The roles table is automatically included in the "Roles" tab of `permissions-manager`.

## Configuration

| Key | Default | Description |
|-----|---------|-------------|
| `gate` | `'manage-permissions'` | Gate checked before delete operations |
