<div>
    <x-bt-modal wire:model="showModal" max-width="4xl" styled blur="sm">
        <x-slot name="title">
            <div class="flex items-center gap-2">
                <x-bt-icon name="user" class="w-5 h-5 text-sky-500" />
                <span>{{ __('beartropy-permissions::messages.assignments_of') }}:</span>
                <span class="font-bold text-sky-600 dark:text-sky-400">{{ $this->userDisplayName }}</span>
            </div>
        </x-slot>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Roles Section --}}
            <div class="space-y-3">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <x-bt-icon name="user-group" class="w-5 h-5 text-indigo-500" />
                    {{ __('beartropy-permissions::messages.roles') }}
                </h3>

                <x-bt-input 
                    wire:model.live.debounce.300ms="roleSearch"
                    :placeholder="__('beartropy-permissions::messages.search_roles')"
                    icon-start="magnifying-glass"
                    clearable
                    sm
                />

                <div class="max-h-64 overflow-y-auto beartropy-thin-scrollbar border border-gray-200 dark:border-gray-700 rounded-lg">
                    @forelse($this->filteredRoles as $role)
                        @php $roleId = $role->id; @endphp
                        <button 
                            type="button"
                            class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition-colors border-b border-gray-100 dark:border-gray-800 last:border-b-0 w-full text-left {{ in_array($roleId, $selectedRoles) ? 'bg-indigo-50 dark:bg-indigo-900/30' : '' }}"
                            wire:click="toggleRole({{ $roleId }})"
                        >
                            <span class="flex items-center justify-center w-5 h-5 rounded border {{ in_array($roleId, $selectedRoles) ? 'bg-indigo-500 border-indigo-500 text-white' : 'border-gray-300 dark:border-gray-600' }}">
                                @if(in_array($roleId, $selectedRoles))
                                    <x-bt-icon name="check" class="w-3 h-3" />
                                @endif
                            </span>
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $role->name }}
                                </span>
                                <span class="text-xs text-gray-400 ml-2">
                                    ({{ $role->permissions->count() }} {{ __('beartropy-permissions::messages.permissions') }})
                                </span>
                            </div>
                        </button>
                    @empty
                        <div class="p-4 text-center text-gray-500 text-sm">
                            {{ __('beartropy-permissions::messages.no_roles_found') }}
                        </div>
                    @endforelse
                </div>

                <p class="text-xs text-gray-500">
                    {{ count($selectedRoles) }} {{ __('beartropy-permissions::messages.roles_selected') }}
                </p>
            </div>

            {{-- Direct Permissions Section --}}
            <div class="space-y-3">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <x-bt-icon name="key" class="w-5 h-5 text-emerald-500" />
                    {{ __('beartropy-permissions::messages.direct_permissions') }}
                </h3>

                <x-bt-input 
                    wire:model.live.debounce.300ms="permissionSearch"
                    :placeholder="__('beartropy-permissions::messages.search_permissions')"
                    icon-start="magnifying-glass"
                    clearable
                    sm
                />

                <div class="max-h-64 overflow-y-auto beartropy-thin-scrollbar border border-gray-200 dark:border-gray-700 rounded-lg">
                    @forelse($this->filteredPermissions as $permission)
                        @php
                            $permId = $permission->id;
                            $isInherited = in_array($permId, $this->inheritedPermissions);
                            $isDirectlyAssigned = in_array($permId, $selectedPermissions);
                        @endphp
                        <button 
                            type="button"
                            class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition-colors border-b border-gray-100 dark:border-gray-800 last:border-b-0 w-full text-left {{ $isDirectlyAssigned ? 'bg-emerald-50 dark:bg-emerald-900/30' : '' }} {{ $isInherited && !$isDirectlyAssigned ? 'bg-gray-50 dark:bg-gray-800/50' : '' }}"
                            wire:click="togglePermission({{ $permId }})"
                        >
                            <span class="flex items-center justify-center w-5 h-5 rounded border {{ $isDirectlyAssigned ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-gray-300 dark:border-gray-600' }}">
                                @if($isDirectlyAssigned)
                                    <x-bt-icon name="check" class="w-3 h-3" />
                                @endif
                            </span>
                            <div class="flex-1">
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $permission->name }}
                                </span>
                                @if($isInherited && !$isDirectlyAssigned)
                                    <span class="ml-2 text-xs px-1.5 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">
                                        {{ __('beartropy-permissions::messages.inherited_badge') }}
                                    </span>
                                @endif
                            </div>
                        </button>
                    @empty
                        <div class="p-4 text-center text-gray-500 text-sm">
                            {{ __('beartropy-permissions::messages.no_permissions_found') }}
                        </div>
                    @endforelse
                </div>

                <p class="text-xs text-gray-500">
                    {{ count($selectedPermissions) }} {{ __('beartropy-permissions::messages.direct_permissions_count') }} • 
                    {{ count($this->inheritedPermissions) }} {{ __('beartropy-permissions::messages.inherited') }}
                </p>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-bt-button ghost wire:click="close">
                    {{ __('beartropy-permissions::messages.cancel') }}
                </x-bt-button>
                <x-bt-button primary wire:click="save">
                    <x-bt-icon name="check" class="w-4 h-4 mr-1" />
                    {{ __('beartropy-permissions::messages.save_changes') }}
                </x-bt-button>
            </div>
        </x-slot>
    </x-bt-modal>
</div>
