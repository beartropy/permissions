<?php

namespace Beartropy\Permissions\Livewire\Modals;

use Beartropy\Permissions\Concerns\AuthorizesPermissionsAccess;
use Beartropy\Permissions\Livewire\Modals\Concerns\ManagesEntity;
use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Permission;

class PermissionModal extends Component
{
    use AuthorizesPermissionsAccess;
    use ManagesEntity;

    public ?int $permissionId = null;

    protected function entityIdProperty(): string
    {
        return 'permissionId';
    }

    protected function modelClass(): string
    {
        return Permission::class;
    }

    protected function viewName(): string
    {
        return 'beartropy-permissions::modals.permission-modal';
    }

    #[On('createPermission')]
    public function create(): void
    {
        $this->initCreate();
    }

    #[On('editPermission')]
    public function edit(int $id): void
    {
        $this->initEdit($id);
    }
}
