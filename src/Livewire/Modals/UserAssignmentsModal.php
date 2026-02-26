<?php

namespace Beartropy\Permissions\Livewire\Modals;

use Beartropy\Permissions\Concerns\AuthorizesPermissionsAccess;
use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class UserAssignmentsModal extends Component
{
    use AuthorizesPermissionsAccess;

    public bool $showModal = false;
    public ?int $userId = null;
    public ?Model $user = null;
    public array $selectedRoles = [];
    public array $selectedPermissions = [];
    public string $roleSearch = '';
    public string $permissionSearch = '';

    /**
     * Open modal to manage user assignments.
     */
    #[On('manageUserAssignments')]
    public function open(int $id): void
    {
        $userModel = config('beartropy-permissions.user_model');
        $this->user = $userModel::with(['roles', 'permissions'])->findOrFail($id);
        $this->userId = $this->user->id;
        $this->selectedRoles = $this->user->roles->pluck('id')->toArray();
        $this->selectedPermissions = $this->user->getDirectPermissions()->pluck('id')->toArray();
        $this->roleSearch = '';
        $this->permissionSearch = '';
        $this->showModal = true;
    }

    /**
     * Toggle a role selection.
     */
    public function toggleRole(int $roleId): void
    {
        if (in_array($roleId, $this->selectedRoles)) {
            $this->selectedRoles = array_values(array_diff($this->selectedRoles, [$roleId]));
        } else {
            $this->selectedRoles[] = $roleId;
        }
    }

    /**
     * Toggle a permission selection.
     */
    public function togglePermission(int $permissionId): void
    {
        if (in_array($permissionId, $this->selectedPermissions)) {
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, [$permissionId]));
        } else {
            $this->selectedPermissions[] = $permissionId;
        }
    }

    /**
     * Save the assignments.
     */
    public function save(): void
    {
        $this->authorizeAccess();

        if ($this->userId === null) {
            return;
        }

        $userModel = config('beartropy-permissions.user_model');
        $user = $userModel::findOrFail($this->userId);

        $roles = Role::whereIn('id', $this->selectedRoles)->get();
        $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();

        try {
            $user->syncRoles($roles);
            $user->syncPermissions($permissions);
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
        $this->user = null;
    }

    /**
     * Get available roles filtered by search.
     */
    public function getFilteredRolesProperty()
    {
        $query = Role::with('permissions');

        if (!empty($this->roleSearch)) {
            $query->where('name', 'like', '%' . $this->roleSearch . '%');
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get available permissions filtered by search.
     */
    public function getFilteredPermissionsProperty()
    {
        $query = Permission::query();

        if (!empty($this->permissionSearch)) {
            $query->where('name', 'like', '%' . $this->permissionSearch . '%');
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get permissions that are inherited from roles (not directly assigned).
     */
    public function getInheritedPermissionsProperty(): array
    {
        if (!$this->user) {
            return [];
        }

        $allPermissions = $this->user->getAllPermissions()->pluck('id')->toArray();
        $directPermissions = $this->user->getDirectPermissions()->pluck('id')->toArray();

        return array_diff($allPermissions, $directPermissions);
    }

    /**
     * Get the user's display name.
     */
    public function getUserDisplayNameProperty(): string
    {
        if (!$this->user) {
            return '';
        }

        $field = config('beartropy-permissions.user_display_field', 'name');
        return $this->user->{$field}
            ?? __('beartropy-permissions::messages.user_fallback', ['id' => $this->userId]);
    }

    public function render()
    {
        return view('beartropy-permissions::modals.user-assignments-modal');
    }
}
