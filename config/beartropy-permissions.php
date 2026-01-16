<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | This is the URI prefix where the permissions UI will be accessible.
    | For example, if set to 'permissions', the UI will be at /permissions.
    |
    */
    'prefix' => 'permissions',

    /*
    |--------------------------------------------------------------------------
    | Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be applied to all routes within this package.
    | By default, we require web and auth middleware.
    |
    */
    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | The layout view that will wrap the permissions UI.
    | Set to null to use the default Livewire layout.
    |
    */
    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The fully qualified class name of your User model.
    |
    */
    'user_model' => App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Guards
    |--------------------------------------------------------------------------
    |
    | The guards that will be available in the UI for creating roles/permissions.
    | Set to null to auto-detect from config/auth.php.
    |
    */
    'guards' => null,

    /*
    |--------------------------------------------------------------------------
    | Default Guard
    |--------------------------------------------------------------------------
    |
    | The default guard to use when creating new roles/permissions.
    |
    */
    'default_guard' => 'web',

    /*
    |--------------------------------------------------------------------------
    | User Display Field
    |--------------------------------------------------------------------------
    |
    | The field from your User model to display in the users table.
    |
    */
    'user_display_field' => 'name',

    /*
    |--------------------------------------------------------------------------
    | User Search Fields
    |--------------------------------------------------------------------------
    |
    | Fields from your User model to search when filtering the users table.
    |
    */
    'user_search_fields' => ['name', 'email'],

    /*
    |--------------------------------------------------------------------------
    | Permission Grouping
    |--------------------------------------------------------------------------
    |
    | Enable grouping of permissions by their prefix (e.g., 'users.' prefix).
    | This helps organize permissions in the assignment modal.
    |
    */
    'group_permissions' => true,

    /*
    |--------------------------------------------------------------------------
    | Permission Group Separator
    |--------------------------------------------------------------------------
    |
    | The character used to separate permission groups (e.g., 'users.create').
    |
    */
    'permission_group_separator' => '.',
];
