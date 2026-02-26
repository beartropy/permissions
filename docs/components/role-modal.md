# Role Modal

Modal dialog for creating and editing Spatie roles. Includes name input, guard selector, and validation.

## Basic Usage

```blade
<livewire:beartropy-permissions::role-modal />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `roleId` | `int|null` | `null` | Role ID for edit mode, null for create |
| `showModal` | `bool` | `false` | Controls modal visibility |
| `name` | `string` | `''` | Role name |
| `guard_name` | `string` | `'web'` | Selected guard |

## Events

| Event | Direction | Description |
|-------|-----------|-------------|
| `createRole` | Listens | Opens in create mode |
| `editRole` | Listens | Opens in edit mode with role data |
| `refresh` | Dispatches | Emitted after successful save |

## Examples

### With roles table
```blade
<livewire:beartropy-permissions::roles-table />
<livewire:beartropy-permissions::role-modal />
```

## Configuration

| Key | Default | Description |
|-----|---------|-------------|
| `guards` | `null` | Available guards for the dropdown (null = auto-detect) |
| `gate` | `'manage-permissions'` | Gate checked before save |
