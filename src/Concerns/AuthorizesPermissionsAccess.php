<?php

namespace Beartropy\Permissions\Concerns;

use Illuminate\Support\Facades\Gate;

trait AuthorizesPermissionsAccess
{
    protected function authorizeAccess(): void
    {
        $gate = config('beartropy-permissions.gate');

        if ($gate) {
            Gate::authorize($gate);
        }
    }
}
