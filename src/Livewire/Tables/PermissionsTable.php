<?php

namespace Beartropy\Permissions\Livewire\Tables;

use Livewire\Attributes\On;
use Beartropy\Tables\YATBaseTable;
use Beartropy\Tables\Classes\Columns\Column;
use Spatie\Permission\Models\Permission;

class PermissionsTable extends YATBaseTable
{
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
     * Define the columns for this table.
     */
    public function columns(): array
    {
        $groupSeparator = config('beartropy-permissions.permission_group_separator', '.');
        
        return [
            Column::make(__('beartropy-permissions::messages.id'), 'id')
                ->styling('w-16')
                ->hideFromSelector(true),

            Column::make(__('beartropy-permissions::messages.group'))
                ->customData(function ($row) use ($groupSeparator) {
                    $parts = explode($groupSeparator, $row->name);
                    return count($parts) > 1 ? $parts[0] : '-';
                })
                ->styling('text-gray-500'),

            Column::make(__('beartropy-permissions::messages.name'), 'name')
                ->styling('font-medium'),

            Column::make(__('beartropy-permissions::messages.guard'), 'guard_name')
                ->styling('text-gray-500'),

            Column::make(__('beartropy-permissions::messages.roles_count'))
                ->customData(function ($row) {
                    return $row->roles->count();
                })
                ->styling('text-center'),

            Column::make('#')
                ->view('beartropy-permissions::partials.permission-actions')
                ->sortable(false)
                ->searchable(false)
                ->styling('w-24')
                ->pushRight(true),
        ];
    }

    /**
     * Relationships to eager load.
     */
    public $with = ['roles'];

    /**
     * Delete selected permissions.
     */
    public function deleteSelected(): void
    {
        $ids = $this->getSelectedRows();
        
        if (empty($ids)) {
            return;
        }

        Permission::whereIn('id', $ids)->delete();
        $this->emptySelection();
        $this->dispatch('refresh');
    }

    /**
     * Delete a single permission.
     */
    #[On('deletePermission')]
    public function deletePermission(int $id): void
    {
        Permission::find($id)?->delete();
        $this->dispatch('refresh');
    }
}
