<?php

namespace Beartropy\Permissions\Mcp\Tools;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[IsReadOnly]
class ProjectContext extends Tool
{
    protected string $name = 'bt-permissions-project-context';

    protected string $description = 'Returns this project\'s Beartropy Permissions configuration: gate, middleware, layout, user model, and installed version.';

    public function schema(\Illuminate\Contracts\JsonSchema\JsonSchema $schema): array
    {
        return [];
    }

    public function handle(Request $request): Response
    {
        $lines = [];

        $lines[] = '# Beartropy Permissions — Project Context';
        $lines[] = '';

        // Package version
        $lines[] = '## Version';
        $lines[] = '';
        $lines[] = $this->packageVersion();
        $lines[] = '';

        // Configuration
        $lines[] = '## Configuration';
        $lines[] = '';
        $gate = config('beartropy-permissions.gate', 'manage-permissions');
        $lines[] = "- Gate: **{$gate}**";
        $layout = config('beartropy-permissions.layout', 'beartropy-permissions::layouts.app');
        $lines[] = "- Layout: **{$layout}**";
        $prefix = config('beartropy-permissions.prefix', 'permissions');
        $lines[] = "- Route prefix: **/{$prefix}**";
        $userModel = config('beartropy-permissions.user_model', 'App\\Models\\User');
        $lines[] = "- User model: **{$userModel}**";
        $groupPermissions = config('beartropy-permissions.group_permissions', true) ? 'enabled' : 'disabled';
        $lines[] = "- Permission grouping: **{$groupPermissions}**";
        $lines[] = '';

        // Middleware
        $middleware = config('beartropy-permissions.middleware', ['web', 'auth', 'can:manage-permissions']);
        $lines[] = '## Middleware';
        $lines[] = '';
        $lines[] = '`'.implode('`, `', $middleware).'`';
        $lines[] = '';

        // Component counts by category
        $lines[] = '## Available Components';
        $lines[] = '';

        $docsPath = dirname(__DIR__, 3).'/docs/llms';
        $docFiles = glob($docsPath.'/*.md') ?: [];
        $docNames = array_map(fn ($f) => basename($f, '.md'), $docFiles);

        $categories = ListComponents::CATEGORIES;
        $total = 0;

        foreach ($categories as $cat => $components) {
            $available = array_intersect($components, $docNames);
            $count = count($available);
            $total += $count;
            $lines[] = "- **{$cat}**: {$count} components";
        }

        $mapped = $categories ? array_merge(...array_values($categories)) : [];
        $uncategorized = array_diff($docNames, $mapped);

        if ($uncategorized !== []) {
            $count = count($uncategorized);
            $total += $count;
            $lines[] = "- **other**: {$count} components";
        }

        $lines[] = "- **total**: {$total} components";
        $lines[] = '';
        $lines[] = '> Use `bt-permissions-list-components` for full names, `bt-permissions-component-docs` for per-component details.';

        return Response::text(implode("\n", $lines));
    }

    protected function packageVersion(): string
    {
        $composerFile = dirname(__DIR__, 3).'/composer.json';

        if (! file_exists($composerFile)) {
            return 'unknown';
        }

        $data = json_decode(file_get_contents($composerFile), true);

        return $data['version'] ?? 'unknown';
    }
}
