# Role Permissions Modal

Modal for assigning permissions to a role. Displays permissions grouped by dot-notation prefix with search, select all, and deselect all.

## Basic Usage

```blade
<livewire:beartropy-permissions::role-permissions-modal />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `showModal` | `bool` | `false` | Controls modal visibility |
| `roleId` | `int|null` | `null` | ID of the role being managed |
| `selectedPermissions` | `array` | `[]` | Currently selected permission IDs |
| `search` | `string` | `''` | Filter permissions by name |

## Events

| Event | Direction | Description |
|-------|-----------|-------------|
| `manageRolePermissions` | Listens | Opens modal for the given role ID |
| `refresh` | Dispatches | Emitted after successful save |

## Features

- Search filters permissions in real-time
- Permissions grouped by dot-notation prefix (e.g., `users.create` → `users` group)
- Select All / Deselect All buttons only affect visible (filtered) permissions
- Error handling with toast notification on sync failure

## Examples

### With roles table
```blade
<livewire:beartropy-permissions::roles-table />
<livewire:beartropy-permissions::role-modal />
<livewire:beartropy-permissions::role-permissions-modal />
```

## Configuration

| Key | Default | Description |
|-----|---------|-------------|
| `group_permissions` | `true` | Group permissions by prefix |
| `permission_group_separator` | `'.'` | Separator character for grouping |
| `gate` | `'manage-permissions'` | Gate checked before save |
