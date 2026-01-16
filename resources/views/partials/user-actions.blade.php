<div class="flex items-center justify-end gap-1">
    <x-bt-button 
        xs 
        ghost 
        icon-start="cog-6-tooth"
        wire:click="$dispatch('manageUserAssignments', { id: {{ $row['id'] }} })"
        :title="__('beartropy-permissions::messages.manage_roles_and_permissions')"
    >
        {{ __('beartropy-permissions::messages.manage') }}
    </x-bt-button>
</div>
