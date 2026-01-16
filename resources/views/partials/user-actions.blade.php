<div class="flex items-center justify-end gap-1 mr-1">
    <x-bt-tooltip
        :label="__('beartropy-permissions::messages.manage_roles_and_permissions')"
    >
        <x-bt-button 
            xs 
            ghost    
            amber
            wire:click="$dispatch('manageUserAssignments', { id: {{ $row['id'] }} })"
        >
            <x-bt-icon name="cog-6-tooth" />
        </x-bt-button>
    </x-bt-tooltip>
</div>
