# beartropy-permissions::permissions-manager — AI Reference

## Livewire Tag
```blade
<livewire:beartropy-permissions::permissions-manager />
```

## Architecture
- `PermissionsManager` -> extends `Livewire\Component`
- Renders: `beartropy-permissions::permissions-manager`
- Uses configurable layout via `config('beartropy-permissions.layout')`
- Embeds all three tables and their modals in a tabbed interface

## Props (public properties)

| Prop | PHP Type | Default | Description |
|------|----------|---------|-------------|
| `$activeTab` | `string` | `'roles'` | Currently active tab: `roles`, `permissions`, or `users` |

## Methods

| Method | Parameters | Description |
|--------|-----------|-------------|
| `setTab` | `string $tab` | Switch to a tab. Validates input against allowed values. |

## Events

This component does not listen to or dispatch custom events. It delegates all functionality to its child table and modal components.

## Usage Examples

### Full-page route (uses configured layout)
```blade
{{-- Registered at route: /{prefix} --}}
<livewire:beartropy-permissions::permissions-manager />
```

### Embedded in your own layout
```blade
{{-- Set layout to null in config and embed in your own page --}}
<livewire:beartropy-permissions::permissions-manager />
```

## Integration Notes
- This is the top-level component that combines roles-table, permissions-table, and users-table
- Each tab also includes the corresponding modals (role-modal, permission-modal, role-permissions-modal, user-assignments-modal)
- Tab state is client-side only — not persisted across page loads
- The layout is dynamically applied from `config('beartropy-permissions.layout')`

## Key Notes
- Tab input is validated: only `roles`, `permissions`, `users` are accepted
- If `layout` config is null/false, no layout wrapper is applied (useful for embedding)
