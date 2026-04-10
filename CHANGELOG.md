# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.2.3] - 2026-04-10

### Added
- **Docs**: AI assistant documentation (`docs/ai-assistants/`) with universal guide, cursor rules, and usage examples.
- **Skills**: Added `bt-permissions-component` Claude Code skill header for the `beartropy:skills` installer command.

## [0.2.2] - 2026-04-07

### Added
- Livewire 4 support (`^3.7|^4.0`) with full backward compatibility for Livewire 3
- `Route::livewire()` routing for Livewire 4, with `Route::get()` fallback for Livewire 3
- Missing component resolver for Livewire 4's `::` namespace separator handling

### Changed
- Widened dev dependency constraints for Pest 4, Testbench 10, and Livewire 4

## [0.2.1] - 2026-04-07

### Changed
- Added `spatie/laravel-permission` v7 support (`^6.0|^7.0`)

## [0.2.0] - 2026-02-26

### Added
- Configurable gate authorization on all mutation operations (`gate` config key)
- `AuthorizesPermissionsAccess` trait for shared authorization logic
- `ManagesEntity` trait extracting shared modal create/edit/save/close logic
- Unique validation scoped to guard for role and permission names
- `#[Computed]` attribute on `filteredPermissions` for request-lifecycle caching
- `user_search_fields` config now wired into UsersTable with custom search callback
- `group_permissions` config toggle for PermissionsTable group column and RolePermissionsModal grouping
- Tab input validation in PermissionsManager (rejects invalid tab values)
- Try/catch around sync operations with error toast notifications
- Translation key `user_fallback` for missing user display name
- Full test suite: 120 tests, 219 assertions (Pest + Livewire + Orchestra Testbench)
- AI integration scaffold: MCP tools, skills, component docs, integrity tests
- Laravel Boost MCP tool registration (conditional on Boost presence)

### Fixed
- Hardcoded Spanish fallback `'Usuario #'` replaced with translatable string
- Falsy guard checks (`!$this->roleId`) replaced with strict `=== null`
- Spatie permission cache now cleared on all delete operations (single and bulk)
- N+1 queries: replaced eager loading with `withCount()` on all three tables
- Config key names in README/DOCS (`route_prefix` → `prefix`, `route_middleware` → `middleware`)
- PHP version requirement in docs corrected to 8.2+
- `$user` property reset to null in `UserAssignmentsModal::close()` to prevent stale serialization

### Changed
- `RoleModal` and `PermissionModal` refactored to use `ManagesEntity` trait
- `RolesTable` and `PermissionsTable` override `query()` with aggregate counts
- `UsersTable` uses `withCount('permissions')` instead of `getDirectPermissions()->count()`

## [0.1.2] - 2026-01-16

### Added
- Added `centered()` method to `Column` class for easy content and header alignment in tables.
- Added `beartropy-thin-scrollbar` class to modal scrollable areas for better aesthetics.

## [v0.1.1] - 2026-01-16

### Fixed
- Fixed ID column alignment in Users, Roles, and Permissions tables.

### Changed
- Cleaned up blade views for actions.

## [v0.1.0] - 2026-01-15

### Added
- Initial release
- Role management with CRUD operations
- Permission management with automatic grouping
- User role and permission assignments
- Bulk delete actions for roles and permissions
- Internationalization support (Spanish and English)
- Dark mode support
- Configurable routes, middleware, and guards
- Integration with spatie/laravel-permission
- Data tables powered by beartropy/tables
- UI components from beartropy/ui
