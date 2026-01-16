<?php

namespace Beartropy\Permissions\Livewire\Tables;

use Livewire\Attributes\On;
use Beartropy\Tables\YATBaseTable;
use Beartropy\Tables\Classes\Columns\Column;

class UsersTable extends YATBaseTable
{
    public $tableName = 'UsersTable';
    public string $theme = 'sky';

    /**
     * Configure table settings.
     */
    public function settings(): void
    {
        $this->model = config('beartropy-permissions.user_model');
        $this->setTitle(__('beartropy-permissions::messages.users'));
        $this->hasBulk(false);
        $this->showCounter(false);
        $this->useStateHandler(false);
    }

    /**
     * Define the columns for this table.
     */
    public function columns(): array
    {
        $displayField = config('beartropy-permissions.user_display_field', 'name');
        
        return [
            Column::make(__('beartropy-permissions::messages.id'), 'id')
                ->styling('w-16')
                ->hideFromSelector(true),

            Column::make(__('beartropy-permissions::messages.user'), $displayField)
                ->styling('font-medium'),

            Column::make(__('beartropy-permissions::messages.email'), 'email')
                ->styling('text-gray-500')
                ->collapseOnMobile(true),

            Column::make(__('beartropy-permissions::messages.roles'))
                ->view('beartropy-permissions::partials.user-roles-badges')
                ->sortable(false)
                ->searchable(false),

            Column::make(__('beartropy-permissions::messages.direct_permissions_short'))
                ->customData(function ($row) {
                    return $row->getDirectPermissions()->count();
                })
                ->styling('text-center'),

            Column::make('#')
                ->view('beartropy-permissions::partials.user-actions')
                ->sortable(false)
                ->searchable(false)
                ->styling('w-24')
                ->pushRight(true),
        ];
    }

    /**
     * Relationships to eager load.
     */
    public $with = ['roles', 'permissions'];
}
