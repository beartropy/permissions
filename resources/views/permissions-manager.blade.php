<div>
    <div class="mb-8">
        {{-- Header --}}
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-white flex items-center gap-3">
            <x-bt-icon name="shield-check" class="w-8 h-8 text-indigo-500" />
            {{ __('beartropy-permissions::messages.permissions_management') }}
        </h1>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            {{ __('beartropy-permissions::messages.manage_roles_permissions_users') }}
        </p>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex gap-1 mb-6 border-b border-zinc-200 dark:border-zinc-700 pb-1">
        <x-bt-button 
            wire:click="setTab('roles')"
            :variant="$activeTab === 'roles' ? 'solid' : 'ghost'"
            icon-start="user-group"
            class="rounded-b-none"
            :spinner="false"
        >
            {{ __('beartropy-permissions::messages.roles') }}
        </x-bt-button>
        
        <x-bt-button 
            wire:click="setTab('permissions')"
            :variant="$activeTab === 'permissions' ? 'solid' : 'ghost'"
            icon-start="key"
            class="rounded-b-none"
            :spinner="false"
        >
            {{ __('beartropy-permissions::messages.permissions') }}
        </x-bt-button>
        
        <x-bt-button 
            wire:click="setTab('users')"
            :variant="$activeTab === 'users' ? 'solid' : 'ghost'"
            icon-start="users"
            class="rounded-b-none"
            :spinner="false"
        >
            {{ __('beartropy-permissions::messages.users') }}
        </x-bt-button>
    </div>

    {{-- Tab Content --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        {{-- Roles Tab --}}
        @if($activeTab === 'roles')
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center bg-zinc-50 dark:bg-zinc-800/50">
                <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">
                    {{ __('beartropy-permissions::messages.roles_list') }}
                </h2>
                <x-bt-button 
                    tint 
                    sm 
                    icon-start="plus"
                    wire:click="$dispatch('createRole')"
                >
                    {{ __('beartropy-permissions::messages.new_role') }}
                </x-bt-button>
            </div>
            <div class="p-4">
                <livewire:beartropy-permissions::roles-table />
            </div>
        @endif

        {{-- Permissions Tab --}}
        @if($activeTab === 'permissions')
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center bg-zinc-50 dark:bg-zinc-800/50">
                <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">
                    {{ __('beartropy-permissions::messages.permissions_list') }}
                </h2>
                <x-bt-button 
                    tint 
                    sm 
                    icon-start="plus"
                    wire:click="$dispatch('createPermission')"
                >
                    {{ __('beartropy-permissions::messages.new_permission') }}
                </x-bt-button>
            </div>
            <div class="p-4">
                <livewire:beartropy-permissions::permissions-table />
            </div>
        @endif

        {{-- Users Tab --}}
        @if($activeTab === 'users')
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
                <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">
                    {{ __('beartropy-permissions::messages.users_assignments') }}
                </h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                    {{ __('beartropy-permissions::messages.manage_user_roles_permissions') }}
                </p>
            </div>
            <div class="p-4">
                <livewire:beartropy-permissions::users-table />
            </div>
        @endif
    </div>

    {{-- Modals --}}
    <livewire:beartropy-permissions::role-modal />
    <livewire:beartropy-permissions::permission-modal />
    <livewire:beartropy-permissions::role-permissions-modal />
    <livewire:beartropy-permissions::user-assignments-modal />
</div>
