<?php

namespace Beartropy\Permissions\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;

class RoleModal extends Component
{
    public bool $showModal = false;
    public ?int $roleId = null;
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
     * Open modal to create a new role.
     */
    #[On('createRole')]
    public function create(): void
    {
        $this->reset(['roleId', 'name', 'guard_name']);
        $this->guard_name = config('beartropy-permissions.default_guard', 'web');
        $this->showModal = true;
    }

    /**
     * Open modal to edit an existing role.
     */
    #[On('editRole')]
    public function edit(int $id): void
    {
        $role = Role::findOrFail($id);
        
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->showModal = true;
    }

    /**
     * Save the role (create or update).
     */
    public function save(): void
    {
        $this->validate();

        if ($this->roleId) {
            $role = Role::findOrFail($this->roleId);
            $role->update([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);
        } else {
            Role::create([
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
            // Auto-detect from auth config
            return array_keys(config('auth.guards', ['web' => []]));
        }

        return $configuredGuards;
    }

    public function render()
    {
        return view('beartropy-permissions::modals.role-modal');
    }
}
