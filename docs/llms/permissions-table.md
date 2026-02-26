# beartropy-permissions::permissions-table — AI Reference

## Livewire Tag
```blade
<livewire:beartropy-permissions::permissions-table />
```

## Architecture
- `PermissionsTable` -> extends `Beartropy\Tables\YATBaseTable`
- Uses `AuthorizesPermissionsAccess` trait for gate checks
- Query uses `Permission::withCount('roles')` for N+1 prevention
- Group column conditionally shown based on `config('beartropy-permissions.group_permissions')`

## Props (public properties)

| Prop | PHP Type | Default | Description |
|------|----------|---------|-------------|
| `$tableName` | `string` | `'PermissionsTable'` | Unique table identifier |
| `$theme` | `string` | `'emerald'` | BeartropyTable color theme |

## Columns

| Column | Source | Conditional | Description |
|--------|--------|-------------|-------------|
| ID | `id` | No | Hidden from column selector |
| Group | Computed from name | Yes (`group_permissions` config) | Dot-notation prefix extraction |
| Name | `name` | No | Permission name |
| Guard | `guard_name` | No | Guard name |
| Roles Count | `roles_count` (aggregate) | No | Number of roles with this permission |
| Actions | View partial | No | Edit and delete buttons |

## Events

| Event | Direction | Payload | Description |
|-------|-----------|---------|-------------|
| `deletePermission` | Listens | `int $id` | Delete a single permission |
| `refresh` | Dispatches | — | Triggers table refresh |

## Methods

| Method | Auth | Description |
|--------|------|-------------|
| `deleteSelected()` | Yes | Bulk delete selected permissions |
| `deletePermission(int $id)` | Yes | Delete single permission |

## Usage Examples
```blade
<livewire:beartropy-permissions::permissions-table />
<livewire:beartropy-permissions::permission-modal />
```

## Key Notes
- Group column uses `permission_group_separator` config (default: `.`) to extract prefix
- Group column is hidden entirely when `group_permissions` is false
- All mutations clear the Spatie permission cache
- Authorization checked on every delete
