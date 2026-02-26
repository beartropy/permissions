# User Assignments Modal

Modal for managing a user's role and permission assignments. Shows roles and direct permissions side-by-side, with inherited permissions indicated separately.

## Basic Usage

```blade
<livewire:beartropy-permissions::user-assignments-modal />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `showModal` | `bool` | `false` | Controls modal visibility |
| `userId` | `int|null` | `null` | ID of the user being managed |
| `selectedRoles` | `array` | `[]` | Currently selected role IDs |
| `selectedPermissions` | `array` | `[]` | Currently selected direct permission IDs |
| `roleSearch` | `string` | `''` | Filter roles by name |
| `permissionSearch` | `string` | `''` | Filter permissions by name |

## Events

| Event | Direction | Description |
|-------|-----------|-------------|
| `manageUserAssignments` | Listens | Opens modal for the given user ID |
| `refresh` | Dispatches | Emitted after successful save |

## Features

- Dual-panel interface: roles on one side, permissions on the other
- Search filtering for both roles and permissions
- Shows which permissions are inherited from roles vs directly assigned
- Syncs both roles and permissions in a single save
- User display name configurable via `user_display_field` config

## Examples

### With users table
```blade
<livewire:beartropy-permissions::users-table />
<livewire:beartropy-permissions::user-assignments-modal />
```

## Configuration

| Key | Default | Description |
|-----|---------|-------------|
| `user_model` | `'App\\Models\\User'` | User model class |
| `user_display_field` | `'name'` | Attribute shown as modal title |
| `gate` | `'manage-permissions'` | Gate checked before save |
