# beartropy-permissions::permission-modal — AI Reference

## Livewire Tag
```blade
<livewire:beartropy-permissions::permission-modal />
```

## Architecture
- `PermissionModal` -> extends `Livewire\Component`
- Uses `ManagesEntity` trait (shared create/edit/save/close logic)
- Uses `AuthorizesPermissionsAccess` trait for gate checks on save
- Renders: `beartropy-permissions::modals.permission-modal`
- Model: `Spatie\Permission\Models\Permission`

## Props (public properties)

| Prop | PHP Type | Default | Description |
|------|----------|---------|-------------|
| `$permissionId` | `?int` | `null` | The permission being edited (null = create mode) |
| `$showModal` | `bool` | `false` | Modal visibility (from ManagesEntity) |
| `$name` | `string` | `''` | Permission name input (from ManagesEntity) |
| `$guard_name` | `string` | `'web'` | Guard selection (from ManagesEntity) |

## Validation

| Field | Rules |
|-------|-------|
| `name` | `required`, `string`, `max:255`, `unique:permissions,name` scoped to guard (excludes current on edit) |
| `guard_name` | `required`, `string` |

## Events

| Event | Direction | Payload | Description |
|-------|-----------|---------|-------------|
| `createPermission` | Listens | — | Opens modal in create mode |
| `editPermission` | Listens | `int $id` | Opens modal in edit mode |
| `refresh` | Dispatches | — | Triggers table refresh after save |

## Usage Examples
```blade
<livewire:beartropy-permissions::permission-modal />
```

## Key Notes
- Identical structure to RoleModal but targets Permission model
- Use dot-notation names for automatic grouping (e.g., `users.create`, `posts.delete`)
- Unique validation scopes name + guard_name combination
- Save clears Spatie permission cache
