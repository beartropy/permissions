<div class="flex items-center justify-end gap-1">
    <x-bt-tooltip
        :label="__('beartropy-permissions::messages.edit')"
        position="left"
        :delay="500"
    >
        <x-bt-button 
            xs 
            ghost 
            wire:click="$dispatch('editPermission', { id: {{ $row['id'] }} })"
        >
            <x-bt-icon name="pencil" />
        </x-bt-button>
    </x-bt-tooltip>
    <x-bt-tooltip
        :label="__('beartropy-permissions::messages.delete')"
        position="left"
        :delay="500"
    >
        <x-bt-button 
            xs 
            ghost 
            red
            wire:click="$dispatch('deletePermission', { id: {{ $row['id'] }} })"
            wire:confirm="{{ __('beartropy-permissions::messages.confirm_delete_permission', ['name' => $row['name']]) }}"
        >
            <x-bt-icon name="trash" />
        </x-bt-button>
    </x-bt-tooltip>
</div>
