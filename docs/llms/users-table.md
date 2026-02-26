# beartropy-permissions::users-table — AI Reference

## Livewire Tag
```blade
<livewire:beartropy-permissions::users-table />
```

## Architecture
- `UsersTable` -> extends `Beartropy\Tables\YATBaseTable`
- Query uses `User::withCount('permissions')` for direct permission count
- Eager loads `roles` relationship for badge display
- User model class configurable via `config('beartropy-permissions.user_model')`
- Search fields configurable via `config('beartropy-permissions.user_search_fields')`

## Props (public properties)

| Prop | PHP Type | Default | Description |
|------|----------|---------|-------------|
| `$tableName` | `string` | `'UsersTable'` | Unique table identifier |
| `$theme` | `string` | `'sky'` | BeartropyTable color theme |
| `$with` | `array` | `['roles']` | Eager-loaded relationships |

## Columns

| Column | Source | Searchable | Description |
|--------|--------|------------|-------------|
| ID | `id` | Default | Hidden from column selector |
| User | Configurable display field | Custom callback using `user_search_fields` | User display name |
| Email | `email` | No | Collapses on mobile |
| Roles | View partial (badges) | No | Shows role badges |
| Direct Permissions | `permissions_count` (aggregate) | No | Count of directly assigned permissions |
| Actions | View partial | No | "Manage" button |

## Events

No custom event listeners. The "Manage" action button dispatches `manageUserAssignments` which is handled by `UserAssignmentsModal`.

## Usage Examples
```blade
<livewire:beartropy-permissions::users-table />
<livewire:beartropy-permissions::user-assignments-modal />
```

## Key Notes
- No bulk actions (read-only table, mutations happen via the modal)
- User display field configurable via `user_display_field` config
- Search uses a custom callback that queries across all `user_search_fields`
- Roles are displayed as colored badges via a Blade partial
