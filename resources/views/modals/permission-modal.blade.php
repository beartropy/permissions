<div>
    <x-bt-modal wire:model="showModal" max-width="lg" styled blur="sm">
        <x-slot name="title">
            <div class="flex items-center gap-2">
                <x-bt-icon name="key" class="w-5 h-5 text-emerald-500" />
                {{ $permissionId ? __('beartropy-permissions::messages.edit_permission') : __('beartropy-permissions::messages.new_permission') }}
            </div>
        </x-slot>

        <form wire:submit="save" class="space-y-4">
            <x-bt-input 
                wire:model="name" 
                :label="__('beartropy-permissions::messages.permission_name')"
                :placeholder="__('beartropy-permissions::messages.permission_name_placeholder')"
                icon-start="key"
                required
            />
            
            <p class="text-xs text-gray-500 dark:text-gray-400">
                💡 {{ __('beartropy-permissions::messages.permission_tip') }}
            </p>

            @if(count($this->guards) > 1)
                <x-bt-select 
                    wire:model="guard_name"
                    :label="__('beartropy-permissions::messages.guard')"
                    :options="collect($this->guards)->mapWithKeys(fn($g) => [$g => $g])->toArray()"
                />
            @else
                <input type="hidden" wire:model="guard_name">
            @endif

            @error('name')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror
        </form>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-bt-button ghost wire:click="close">
                    {{ __('beartropy-permissions::messages.cancel') }}
                </x-bt-button>
                <x-bt-button primary wire:click="save">
                    <x-bt-icon name="check" class="w-4 h-4 mr-1" />
                    {{ __('beartropy-permissions::messages.save') }}
                </x-bt-button>
            </div>
        </x-slot>
    </x-bt-modal>
</div>
