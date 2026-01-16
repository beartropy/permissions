<div class="flex flex-wrap gap-1">
    @php
        $roles = $row['roles'] ?? [];
    @endphp
    @forelse($roles as $role)
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
            {{ is_array($role) ? $role['name'] : $role->name }}
        </span>
    @empty
        <span class="text-sm text-gray-400 italic">{{ __('beartropy-permissions::messages.without_roles') }}</span>
    @endforelse
</div>
