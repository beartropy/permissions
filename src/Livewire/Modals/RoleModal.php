<?php

namespace Beartropy\Permissions\Livewire\Modals;

use Beartropy\Permissions\Concerns\AuthorizesPermissionsAccess;
use Beartropy\Permissions\Livewire\Modals\Concerns\ManagesEntity;
use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;

class RoleModal extends Component
{
    use AuthorizesPermissionsAccess;
    use ManagesEntity;

    public ?int $roleId = null;

    protected function entityIdProperty(): string
    {
        return 'roleId';
    }

    protected function modelClass(): string
    {
        return Role::class;
    }

    protected function viewName(): string
    {
        return 'beartropy-permissions::modals.role-modal';
    }

    #[On('createRole')]
    public function create(): void
    {
        $this->initCreate();
    }

    #[On('editRole')]
    public function edit(int $id): void
    {
        $this->initEdit($id);
    }
}
