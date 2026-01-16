<div>
    <x-bt-modal wire:model="showModal" max-width="lg" styled blur="sm">
        <x-slot name="title">
            <div class="flex items-center gap-2">
                <x-bt-icon name="user-group" class="w-5 h-5 text-indigo-500" />
                {{ $roleId ? __('beartropy-permissions::messages.edit_role') : __('beartropy-permissions::messages.new_role') }}
            </div>
        </x-slot>

        <form wire:submit="save" class="space-y-4">
            <x-bt-input 
                wire:model="name" 
                :label="__('beartropy-permissions::messages.role_name')"
                :placeholder="__('beartropy-permissions::messages.role_name_placeholder')"
                icon-start="user-group"
                required
            />
            
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
