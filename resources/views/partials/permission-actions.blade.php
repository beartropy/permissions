<div class="flex items-center justify-end gap-1">
    <x-bt-button 
        xs 
        ghost 
        icon-start="pencil"
        wire:click="$dispatch('editPermission', { id: {{ $row['id'] }} })"
        :title="__('beartropy-permissions::messages.edit')"
    />
    <x-bt-button 
        xs 
        ghost 
        danger
        icon-start="trash"
        wire:click="$dispatch('deletePermission', { id: {{ $row['id'] }} })"
        wire:confirm="{{ __('beartropy-permissions::messages.confirm_delete_permission', ['name' => $row['name']]) }}"
        :title="__('beartropy-permissions::messages.delete')"
    />
</div>
