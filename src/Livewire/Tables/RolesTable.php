<?php

namespace Beartropy\Permissions\Livewire\Tables;

use Livewire\Attributes\On;
use Beartropy\Tables\YATBaseTable;
use Beartropy\Tables\Classes\Columns\Column;
use Spatie\Permission\Models\Role;

class RolesTable extends YATBaseTable
{
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
     * Define the columns for this table.
     */
    public function columns(): array
    {
        return [
            Column::make(__('beartropy-permissions::messages.id'), 'id')
                ->styling('w-16')
                ->hideFromSelector(true),

            Column::make(__('beartropy-permissions::messages.name'), 'name')
                ->styling('font-medium'),

            Column::make(__('beartropy-permissions::messages.guard'), 'guard_name')
                ->styling('text-gray-500'),

            Column::make(__('beartropy-permissions::messages.permissions_count'))
                ->customData(function ($row) {
                    return $row->permissions->count();
                })
                ->styling('text-center'),

            Column::make(__('beartropy-permissions::messages.users_count'))
                ->customData(function ($row) {
                    return $row->users->count();
                })
                ->styling('text-center'),

            Column::make('#')
                ->view('beartropy-permissions::partials.role-actions')
                ->sortable(false)
                ->searchable(false)
                ->styling('w-32')
                ->pushRight(true),
        ];
    }

    /**
     * Relationships to eager load.
     */
    public $with = ['permissions', 'users'];

    /**
     * Delete selected roles.
     */
    public function deleteSelected(): void
    {
        $ids = $this->getSelectedRows();
        
        if (empty($ids)) {
            return;
        }

        Role::whereIn('id', $ids)->delete();
        $this->emptySelection();
        $this->dispatch('refresh');
    }

    /**
     * Delete a single role.
     */
    #[On('deleteRole')]
    public function deleteRole(int $id): void
    {
        Role::find($id)?->delete();
        $this->dispatch('refresh');
    }
}
