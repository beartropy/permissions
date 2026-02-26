<?php

namespace Beartropy\Permissions\Livewire\Tables;

use Beartropy\Permissions\Concerns\AuthorizesPermissionsAccess;
use Livewire\Attributes\On;
use Beartropy\Tables\YATBaseTable;
use Beartropy\Tables\Classes\Columns\Column;
use Spatie\Permission\Models\Role;

class RolesTable extends YATBaseTable
{
    use AuthorizesPermissionsAccess;

    public $tableName = 'RolesTable';
    public string $theme = 'indigo';

    /**
     * Configure table settings.
     */
    public function settings(): void
    {
        $this->model = Role::class;
        $this->setTitle(__('beartropy-permissions::messages.roles'));
        $this->hasBulk(true);
        $this->useStateHandler(false);
        $this->showCounter(false);
        $this->addButtons([
            ['label' => __('beartropy-permissions::messages.delete_selected'), 'action' => 'deleteSelected', 'color' => 'red']
        ]);
    }

    /**
     * Base query with aggregate counts instead of eager loading.
     */
    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return Role::withCount(['permissions', 'users']);
    }

    /**
     * Define the columns for this table.
     */
    public function columns(): array
    {
        return [
            Column::make(__('beartropy-permissions::messages.id'), 'id')
                ->styling('w-16')
                ->hideFromSelector(true),

            Column::make(__('beartropy-permissions::messages.name'), 'name')
                ->styling('font-medium')
                ->centered(),

            Column::make(__('beartropy-permissions::messages.guard'), 'guard_name')
                ->styling('text-gray-500')
                ->centered(),

            Column::make(__('beartropy-permissions::messages.permissions_count'))
                ->customData(fn ($row) => $row->permissions_count)
                ->centered(),

            Column::make(__('beartropy-permissions::messages.users_count'))
                ->customData(fn ($row) => $row->users_count)
                ->centered(),

            Column::make('#')
                ->view('beartropy-permissions::partials.role-actions')
                ->sortable(false)
                ->searchable(false)
                ->styling('w-32')
                ->pushRight(true),
        ];
    }

    /**
     * Delete selected roles.
     */
    public function deleteSelected(): void
    {
        $this->authorizeAccess();

        $ids = $this->getSelectedRows();

        if (empty($ids)) {
            return;
        }

        Role::whereIn('id', $ids)->delete();

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->emptySelection();
        $this->dispatch('refresh');
    }

    /**
     * Delete a single role.
     */
    #[On('deleteRole')]
    public function deleteRole(int $id): void
    {
        $this->authorizeAccess();

        Role::find($id)?->delete();

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->dispatch('refresh');
    }
}
