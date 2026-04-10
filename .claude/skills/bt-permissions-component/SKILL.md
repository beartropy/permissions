---
name: bt-permissions-component
description: Get detailed information and examples for Beartropy Permissions components
version: 1.0.0
author: Beartropy
tags: [beartropy, permissions, components, documentation, examples]
---

# Beartropy Permissions Component Helper

You are an expert in Beartropy Permissions. Use this guide to help users configure and use the permissions management UI.

---

## Choosing the Right Component

| User says... | Use this | Why |
|---|---|---|
| "permissions manager", "manage roles" | `beartropy-permissions::permissions-manager` | Full tabbed UI with all tables and modals |
| "roles table only" | `beartropy-permissions::roles-table` | Standalone roles table |
| "permissions table only" | `beartropy-permissions::permissions-table` | Standalone permissions table |
| "users table only" | `beartropy-permissions::users-table` | Standalone users table |

## Quick Reference

| Task | How |
|---|---|
| Full manager | `@livewire('beartropy-permissions::permissions-manager')` |
| Auth gate | `Gate::define('manage-permissions', fn($user) => ...)` |
| User model | Must use `Spatie\Permission\Traits\HasRoles` |
| Group permissions | Use dot notation: `users.create`, `posts.edit` |
