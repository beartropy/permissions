<div class="flex items-center justify-end">
    <x-bt-tooltip
        :label="__('beartropy-permissions::messages.edit')"
        position="left"
        :delay="500"
    >
        <x-bt-button 
            xs 
            ghost 
            wire:click="$dispatch('editRole', { id: {{ $row['id'] }} })"
        >
            <x-bt-icon name="pencil" />
        </x-bt-button>
    </x-bt-tooltip>
    <x-bt-tooltip
        :label="__('beartropy-permissions::messages.manage_permissions')"
        position="left"
        :delay="500"
    >
        <x-bt-button 
            xs 
            ghost 
            amber
            wire:click="$dispatch('manageRolePermissions', { id: {{ $row['id'] }} })"
        >
            <x-bt-icon name="key" />
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
            wire:click="$dispatch('deleteRole', { id: {{ $row['id'] }} })"
            wire:confirm="{{ __('beartropy-permissions::messages.confirm_delete_role', ['name' => $row['name']]) }}"
        >
            <x-bt-icon name="trash" />
        </x-bt-button>
    </x-bt-tooltip>
</div>
