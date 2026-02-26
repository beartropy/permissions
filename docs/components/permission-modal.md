# Permission Modal

Modal dialog for creating and editing Spatie permissions. Includes name input, guard selector, and validation.

## Basic Usage

```blade
<livewire:beartropy-permissions::permission-modal />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `permissionId` | `int|null` | `null` | Permission ID for edit mode, null for create |
| `showModal` | `bool` | `false` | Controls modal visibility |
| `name` | `string` | `''` | Permission name |
| `guard_name` | `string` | `'web'` | Selected guard |

## Events

| Event | Direction | Description |
|-------|-----------|-------------|
| `createPermission` | Listens | Opens in create mode |
| `editPermission` | Listens | Opens in edit mode with permission data |
| `refresh` | Dispatches | Emitted after successful save |

## Examples

### With permissions table
```blade
<livewire:beartropy-permissions::permissions-table />
<livewire:beartropy-permissions::permission-modal />
```

## Configuration

| Key | Default | Description |
|-----|---------|-------------|
| `guards` | `null` | Available guards for the dropdown (null = auto-detect) |
| `gate` | `'manage-permissions'` | Gate checked before save |
