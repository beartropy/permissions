# beartropy-permissions::roles-table — AI Reference

## Livewire Tag
```blade
<livewire:beartropy-permissions::roles-table />
```

## Architecture
- `RolesTable` -> extends `Beartropy\Tables\YATBaseTable`
- Uses `AuthorizesPermissionsAccess` trait for gate checks on mutations
- Renders via BeartropyTable base (no explicit view override)
- Query uses `Role::withCount(['permissions', 'users'])` for N+1 prevention

## Props (public properties)

| Prop | PHP Type | Default | Description |
|------|----------|---------|-------------|
| `$tableName` | `string` | `'RolesTable'` | Unique table identifier |
| `$theme` | `string` | `'indigo'` | BeartropyTable color theme |

## Columns

| Column | Source | Sortable | Searchable |
|--------|--------|----------|------------|
| ID | `id` | Yes | Yes |
| Name | `name` | Yes | Yes |
| Guard | `guard_name` | Yes | Yes |
| Permissions Count | `permissions_count` (aggregate) | No | No |
| Users Count | `users_count` (aggregate) | No | No |
| Actions | View partial | No | No |

## Events

| Event | Direction | Payload | Description |
|-------|-----------|---------|-------------|
| `deleteRole` | Listens | `int $id` | Delete a single role by ID |
| `refresh` | Dispatches | — | Triggers table refresh after mutations |

## Methods

| Method | Auth | Description |
|--------|------|-------------|
| `deleteSelected()` | Yes | Bulk delete selected roles, clears permission cache |
| `deleteRole(int $id)` | Yes | Delete single role, clears permission cache |

## Usage Examples
```blade
<livewire:beartropy-permissions::roles-table />
<livewire:beartropy-permissions::role-modal />
<livewire:beartropy-permissions::role-permissions-modal />
```

## Key Notes
- All delete operations call `authorizeAccess()` before executing
- Permission cache is cleared after every delete via `PermissionRegistrar::forgetCachedPermissions()`
- Bulk actions button label is translatable
- Actions column renders edit, manage permissions, and delete buttons
