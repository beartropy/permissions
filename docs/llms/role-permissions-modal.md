# beartropy-permissions::role-permissions-modal — AI Reference

## Livewire Tag
```blade
<livewire:beartropy-permissions::role-permissions-modal />
```

## Architecture
- `RolePermissionsModal` -> extends `Livewire\Component`
- Uses `AuthorizesPermissionsAccess` trait for gate checks on save
- Renders: `beartropy-permissions::modals.role-permissions-modal`
- Uses `#[Computed]` on `filteredPermissions` for caching within request lifecycle
- Permissions grouped by dot-notation prefix via `groupedPermissions` computed property

## Props (public properties)

| Prop | PHP Type | Default | Description |
|------|----------|---------|-------------|
| `$showModal` | `bool` | `false` | Modal visibility |
| `$roleId` | `?int` | `null` | The role being managed |
| `$role` | `?Role` | `null` | The loaded Role model |
| `$selectedPermissions` | `array` | `[]` | Array of selected permission IDs |
| `$search` | `string` | `''` | Search filter for permission names |

## Computed Properties

| Property | Return Type | Description |
|----------|-------------|-------------|
| `filteredPermissions` | `Collection` | Permissions filtered by guard and search, cached via `#[Computed]` |
| `groupedPermissions` | `array` | Permissions grouped by dot-notation prefix. Returns flat if `group_permissions` config is false. |

## Events

| Event | Direction | Payload | Description |
|-------|-----------|---------|-------------|
| `manageRolePermissions` | Listens | `int $id` | Opens modal for the given role |
| `refresh` | Dispatches | — | Triggers table refresh after save |
| `notify` | Dispatches | `type, message` | Dispatched on sync error |

## Methods

| Method | Auth | Description |
|--------|------|-------------|
| `togglePermission(int $id)` | No | Toggle a permission in/out of selection |
| `selectAll()` | No | Select all currently filtered/visible permissions |
| `deselectAll()` | No | Deselect all currently filtered/visible permissions |
| `save()` | Yes | Sync permissions to role, clear cache |
| `close()` | No | Close modal without saving |

## Usage Examples
```blade
<livewire:beartropy-permissions::role-permissions-modal />
```

## Key Notes
- `selectAll` / `deselectAll` only affect the currently filtered set, preserving selections in other groups
- Permissions are filtered by the role's guard_name automatically
- Save wraps `syncPermissions()` in try/catch, dispatches error notification on failure
- `filteredPermissions` uses `#[Computed]` for efficient re-rendering
- Group separator configurable via `permission_group_separator` config
