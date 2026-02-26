# Permissions Manager

Full tabbed interface for managing roles, permissions, and user assignments. This is the main entry point for the Beartropy Permissions UI.

## Basic Usage

```blade
<livewire:beartropy-permissions::permissions-manager />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `activeTab` | `string` | `'roles'` | The initially active tab |

## Tabs

| Tab | Content |
|-----|---------|
| `roles` | Roles table + role modal + role permissions modal |
| `permissions` | Permissions table + permission modal |
| `users` | Users table + user assignments modal |

## Examples

### As a full-page route
The package automatically registers a route at `/{prefix}` that renders this component with the configured layout.

### Embedded in an existing page
```blade
{{-- In config: 'layout' => null --}}
<div class="my-custom-wrapper">
    <livewire:beartropy-permissions::permissions-manager />
</div>
```

## Configuration

| Key | Default | Description |
|-----|---------|-------------|
| `layout` | `'beartropy-permissions::layouts.app'` | Blade layout for full-page rendering |
| `prefix` | `'permissions'` | Route prefix |
| `middleware` | `['web', 'auth', 'can:manage-permissions']` | Route middleware stack |
