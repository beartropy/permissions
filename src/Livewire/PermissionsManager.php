<?php

namespace Beartropy\Permissions\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

class PermissionsManager extends Component
{
    /**
     * Currently active tab: 'roles', 'permissions', or 'users'
     */
    public string $activeTab = 'roles';

    /**
     * Switch to a different tab.
     */
    public function setTab(string $tab): void
    {
        if (!in_array($tab, ['roles', 'permissions', 'users'])) {
            return;
        }

        $this->activeTab = $tab;
    }

    /**
     * Get the layout to use for this component.
     */
    public function render()
    {
        $layout = config('beartropy-permissions.layout');
        
        $view = view('beartropy-permissions::permissions-manager');
        
        if ($layout) {
            $view->layout($layout);
        }
        
        return $view;
    }
}
