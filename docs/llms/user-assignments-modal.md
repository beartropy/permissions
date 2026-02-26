# beartropy-permissions::user-assignments-modal — AI Reference

## Livewire Tag
```blade
<livewire:beartropy-permissions::user-assignments-modal />
```

## Architecture
- `UserAssignmentsModal` -> extends `Livewire\Component`
- Uses `AuthorizesPermissionsAccess` trait for gate checks on save
- Renders: `beartropy-permissions::modals.user-assignments-modal`
- Manages both role and permission assignments for a user simultaneously
- Shows inherited permissions (from roles) vs directly assigned permissions

## Props (public properties)

| Prop | PHP Type | Default | Description |
|------|----------|---------|-------------|
| `$showModal` | `bool` | `false` | Modal visibility |
| `$userId` | `?int` | `null` | The user being managed |
| `$user` | `?Model` | `null` | The loaded User model |
| `$selectedRoles` | `array` | `[]` | Array of selected role IDs |
| `$selectedPermissions` | `array` | `[]` | Array of selected direct permission IDs |
| `$roleSearch` | `string` | `''` | Search filter for roles |
| `$permissionSearch` | `string` | `''` | Search filter for permissions |

## Computed Properties

| Property | Return Type | Description |
|----------|-------------|-------------|
| `filteredRoles` | `Collection` | Roles filtered by search, with permissions eager loaded |
| `filteredPermissions` | `Collection` | Permissions filtered by search |
| `inheritedPermissions` | `array` | Permission IDs inherited via roles (not directly assigned) |
| `userDisplayName` | `string` | User display name from configured field, with translation fallback |

## Events

| Event | Direction | Payload | Description |
|-------|-----------|---------|-------------|
| `manageUserAssignments` | Listens | `int $id` | Opens modal for the given user |
| `refresh` | Dispatches | — | Triggers table refresh after save |
| `notify` | Dispatches | `type, message` | Dispatched on sync error |

## Methods

| Method | Auth | Description |
|--------|------|-------------|
| `toggleRole(int $id)` | No | Toggle a role in/out of selection |
| `togglePermission(int $id)` | No | Toggle a direct permission in/out of selection |
| `save()` | Yes | Sync both roles and permissions, clear cache |
| `close()` | No | Close modal, reset `$user` to null |

## Usage Examples
```blade
<livewire:beartropy-permissions::user-assignments-modal />
```

## Key Notes
- Syncs both roles AND direct permissions in a single save operation
- `$user` is reset to null in `close()` to prevent stale model serialization
- Display name falls back to `__('beartropy-permissions::messages.user_fallback', ['id' => $id])`
- Inherited permissions are calculated by diffing `getAllPermissions()` - `getDirectPermissions()`
- Save wraps sync operations in try/catch with error notification
- User model class comes from `config('beartropy-permissions.user_model')`
