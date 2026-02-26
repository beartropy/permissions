# beartropy-permissions::role-modal — AI Reference

## Livewire Tag
```blade
<livewire:beartropy-permissions::role-modal />
```

## Architecture
- `RoleModal` -> extends `Livewire\Component`
- Uses `ManagesEntity` trait (shared create/edit/save/close logic)
- Uses `AuthorizesPermissionsAccess` trait for gate checks on save
- Renders: `beartropy-permissions::modals.role-modal`
- Model: `Spatie\Permission\Models\Role`

## Props (public properties)

| Prop | PHP Type | Default | Description |
|------|----------|---------|-------------|
| `$roleId` | `?int` | `null` | The role being edited (null = create mode) |
| `$showModal` | `bool` | `false` | Modal visibility (from ManagesEntity) |
| `$name` | `string` | `''` | Role name input (from ManagesEntity) |
| `$guard_name` | `string` | `'web'` | Guard selection (from ManagesEntity) |

## Validation

| Field | Rules |
|-------|-------|
| `name` | `required`, `string`, `max:255`, `unique:roles,name` scoped to guard (excludes current on edit) |
| `guard_name` | `required`, `string` |

## Events

| Event | Direction | Payload | Description |
|-------|-----------|---------|-------------|
| `createRole` | Listens | — | Opens modal in create mode |
| `editRole` | Listens | `int $id` | Opens modal in edit mode |
| `refresh` | Dispatches | — | Triggers table refresh after save |

## Usage Examples
```blade
<livewire:beartropy-permissions::role-modal />
```

## Key Notes
- Unique validation scopes name + guard_name combination
- On edit, the unique rule excludes the current role ID
- Save always clears the Spatie permission cache
- Guards dropdown populated from config or auto-detected from auth config
