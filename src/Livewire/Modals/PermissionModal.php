<?php

namespace Beartropy\Permissions\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Permission;

class PermissionModal extends Component
{
    public bool $showModal = false;
    public ?int $permissionId = null;
    public string $name = '';
    public string $guard_name = 'web';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
        ];
    }

    /**
     * Open modal to create a new permission.
     */
    #[On('createPermission')]
    public function create(): void
    {
        $this->reset(['permissionId', 'name', 'guard_name']);
        $this->guard_name = config('beartropy-permissions.default_guard', 'web');
        $this->showModal = true;
    }

    /**
     * Open modal to edit an existing permission.
     */
    #[On('editPermission')]
    public function edit(int $id): void
    {
        $permission = Permission::findOrFail($id);
        
        $this->permissionId = $permission->id;
        $this->name = $permission->name;
        $this->guard_name = $permission->guard_name;
        $this->showModal = true;
    }

    /**
     * Save the permission (create or update).
     */
    public function save(): void
    {
        $this->validate();

        if ($this->permissionId) {
            $permission = Permission::findOrFail($this->permissionId);
            $permission->update([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);
        } else {
            Permission::create([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);
        }

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
     * Get available guards.
     */
    public function getGuardsProperty(): array
    {
        $configuredGuards = config('beartropy-permissions.guards');
        
        if ($configuredGuards === null) {
            return array_keys(config('auth.guards', ['web' => []]));
        }

        return $configuredGuards;
    }

    public function render()
    {
        return view('beartropy-permissions::modals.permission-modal');
    }
}
