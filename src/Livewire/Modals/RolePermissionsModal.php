<?php

namespace Beartropy\Permissions\Livewire\Modals;

use Beartropy\Permissions\Concerns\AuthorizesPermissionsAccess;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionsModal extends Component
{
    use AuthorizesPermissionsAccess;

    public bool $showModal = false;
    public ?int $roleId = null;
    public ?Role $role = null;
    public array $selectedPermissions = [];
    public string $search = '';

    /**
     * Open modal to manage role permissions.
     */
    #[On('manageRolePermissions')]
    public function open(int $id): void
    {
        $this->role = Role::with('permissions')->findOrFail($id);
        $this->roleId = $this->role->id;
        $this->selectedPermissions = $this->role->permissions->pluck('id')->toArray();
        $this->search = '';
        $this->showModal = true;
    }

    /**
     * Toggle a permission selection.
     */
    public function togglePermission(int $permissionId): void
    {
        if (in_array($permissionId, $this->selectedPermissions)) {
            $this->selectedPermissions = array_values(
                array_diff($this->selectedPermissions, [$permissionId])
            );
        } else {
            $this->selectedPermissions[] = $permissionId;
        }
    }

    /**
     * Select all visible permissions.
     */
    public function selectAll(): void
    {
        $permissions = $this->filteredPermissions;
        $this->selectedPermissions = array_unique(
            array_merge($this->selectedPermissions, $permissions->pluck('id')->toArray())
        );
    }

    /**
     * Deselect all visible permissions.
     */
    public function deselectAll(): void
    {
        $permissions = $this->filteredPermissions;
        $idsToRemove = $permissions->pluck('id')->toArray();
        $this->selectedPermissions = array_values(
            array_diff($this->selectedPermissions, $idsToRemove)
        );
    }

    /**
     * Save the permission assignments.
     */
    public function save(): void
    {
        $this->authorizeAccess();

        if ($this->roleId === null) {
            return;
        }

        $role = Role::findOrFail($this->roleId);

        $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();

        try {
            $role->syncPermissions($permissions);
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'error', message: $e->getMessage());
            return;
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->showModal = false;
        $this->dispatch('refresh');
    }

    /**
     * Close the modal without saving.
     */
    public function close(): void
    {
        $this->showModal = false;
    }

    /**
     * Get permissions filtered by search.
     */
    #[Computed]
    public function filteredPermissions()
    {
        $guard = $this->role?->guard_name ?? config('beartropy-permissions.default_guard', 'web');

        $query = Permission::where('guard_name', $guard);

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get permissions grouped by prefix.
     */
    public function getGroupedPermissionsProperty(): array
    {
        $permissions = $this->filteredPermissions;

        if (!config('beartropy-permissions.group_permissions', true)) {
            return ['general' => $permissions->all()];
        }

        $separator = config('beartropy-permissions.permission_group_separator', '.');
        $groups = [];

        foreach ($permissions as $permission) {
            $parts = explode($separator, $permission->name);
            $group = count($parts) > 1 ? $parts[0] : 'general';

            if (!isset($groups[$group])) {
                $groups[$group] = [];
            }

            $groups[$group][] = $permission;
        }

        ksort($groups);
        return $groups;
    }

    public function render()
    {
        return view('beartropy-permissions::modals.role-permissions-modal');
    }
}
