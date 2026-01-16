<div>
    <x-bt-modal wire:model="showModal" max-width="3xl" styled blur="sm">
        <x-slot name="title">
            <div class="flex items-center gap-2">
                <x-bt-icon name="key" class="w-5 h-5 text-indigo-500" />
                <span>{{ __('beartropy-permissions::messages.role_permissions') }}:</span>
                @if($role)
                    <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $role->name }}</span>
                @endif
            </div>
        </x-slot>

        <div class="space-y-4">
            {{-- Search --}}
            <x-bt-input 
                wire:model.live.debounce.300ms="search"
                :placeholder="__('beartropy-permissions::messages.search_permissions')"
                icon-start="magnifying-glass"
                clearable
            />

            {{-- Quick Actions --}}
            <div class="flex gap-2">
                <x-bt-button xs ghost wire:click="selectAll">
                    {{ __('beartropy-permissions::messages.select_all') }}
                </x-bt-button>
                <x-bt-button xs ghost wire:click="deselectAll">
                    {{ __('beartropy-permissions::messages.deselect_all') }}
                </x-bt-button>
                <span class="ml-auto text-sm text-gray-500">
                    {{ count($selectedPermissions) }} {{ __('beartropy-permissions::messages.selected') }}
                </span>
            </div>

            {{-- Grouped Permissions --}}
            <div class="max-h-96 overflow-y-auto beartropy-thin-scrollbar border border-gray-200 dark:border-gray-700 rounded-lg divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($this->groupedPermissions as $group => $permissions)
                    <div class="p-3">
                        {{-- Group Header --}}
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <x-bt-icon name="folder" class="w-4 h-4" />
                            {{ ucfirst($group) }}
                            <span class="text-xs text-gray-400">({{ count($permissions) }})</span>
                        </h4>
                        
                        {{-- Permissions in Group --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                            @foreach($permissions as $permission)
                                @php $permId = $permission->id; @endphp
                                <button 
                                    type="button"
                                    class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition-colors text-left w-full {{ in_array($permId, $selectedPermissions) ? 'bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-800' : 'border border-transparent' }}"
                                    wire:click="togglePermission({{ $permId }})"
                                >
                                    <span class="flex items-center justify-center w-5 h-5 rounded border {{ in_array($permId, $selectedPermissions) ? 'bg-indigo-500 border-indigo-500 text-white' : 'border-gray-300 dark:border-gray-600' }}">
                                        @if(in_array($permId, $selectedPermissions))
                                            <x-bt-icon name="check" class="w-3 h-3" />
                                        @endif
                                    </span>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate">
                                        {{ $permission->name }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <x-bt-icon name="inbox" class="w-12 h-12 mx-auto mb-2 opacity-50" />
                        <p>{{ __('beartropy-permissions::messages.no_permissions_found') }}</p>
                    </div>
                @endforelse
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
