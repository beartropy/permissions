<?php

namespace Beartropy\Permissions\Livewire\Tables;

use Beartropy\Permissions\Concerns\AuthorizesPermissionsAccess;
use Livewire\Attributes\On;
use Beartropy\Tables\YATBaseTable;
use Beartropy\Tables\Classes\Columns\Column;
use Spatie\Permission\Models\Permission;

class PermissionsTable extends YATBaseTable
{
    use AuthorizesPermissionsAccess;

    public $tableName = 'PermissionsTable';
    public string $theme = 'emerald';

    /**
     * Configure table settings.
     */
    public function settings(): void
    {
        $this->model = Permission::class;
        $this->setTitle(__('beartropy-permissions::messages.permissions'));
        $this->hasBulk(true);
        $this->useStateHandler(false);
        $this->showCounter(false);
        $this->addButtons([
            ['label' => __('beartropy-permissions::messages.delete_selected'), 'action' => 'deleteSelected', 'color' => 'red']
        ]);
    }

    /**
     * Base query with aggregate count instead of eager loading.
     */
    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return Permission::withCount('roles');
    }

    /**
     * Define the columns for this table.
     */
    public function columns(): array
    {
        $groupSeparator = config('beartropy-permissions.permission_group_separator', '.');
        $showGroup = config('beartropy-permissions.group_permissions', true);

        $columns = [
            Column::make(__('beartropy-permissions::messages.id'), 'id')
                ->styling('w-16')
                ->hideFromSelector(true),
        ];

        if ($showGroup) {
            $columns[] = Column::make(__('beartropy-permissions::messages.group'))
                ->customData(function ($row) use ($groupSeparator) {
                    $parts = explode($groupSeparator, $row->name);
                    return count($parts) > 1 ? $parts[0] : '-';
                })
                ->styling('text-gray-500')
                ->centered();
        }

        $columns = array_merge($columns, [
            Column::make(__('beartropy-permissions::messages.name'), 'name')
                ->styling('font-medium')
                ->centered(),

            Column::make(__('beartropy-permissions::messages.guard'), 'guard_name')
                ->styling('text-gray-500')
                ->centered(),

            Column::make(__('beartropy-permissions::messages.roles_count'))
                ->customData(fn ($row) => $row->roles_count)
                ->centered(),

            Column::make('#')
                ->view('beartropy-permissions::partials.permission-actions')
                ->sortable(false)
                ->searchable(false)
                ->styling('w-24')
                ->pushRight(true),
        ]);

        return $columns;
    }

    /**
     * Delete selected permissions.
     */
    public function deleteSelected(): void
    {
        $this->authorizeAccess();

        $ids = $this->getSelectedRows();

        if (empty($ids)) {
            return;
        }

        Permission::whereIn('id', $ids)->delete();

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->emptySelection();
        $this->dispatch('refresh');
    }

    /**
     * Delete a single permission.
     */
    #[On('deletePermission')]
    public function deletePermission(int $id): void
    {
        $this->authorizeAccess();

        Permission::find($id)?->delete();

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->dispatch('refresh');
    }
}
