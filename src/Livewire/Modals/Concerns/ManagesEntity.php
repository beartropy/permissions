<?php

namespace Beartropy\Permissions\Livewire\Modals\Concerns;

use Illuminate\Validation\Rule;

trait ManagesEntity
{
    public bool $showModal = false;
    public string $name = '';
    public string $guard_name = 'web';

    abstract protected function entityIdProperty(): string;

    abstract protected function modelClass(): string;

    abstract protected function viewName(): string;

    protected function rules(): array
    {
        $entityId = $this->{$this->entityIdProperty()};
        $table = (new ($this->modelClass()))->getTable();

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique($table, 'name')
                    ->where('guard_name', $this->guard_name)
                    ->ignore($entityId),
            ],
            'guard_name' => 'required|string|max:255',
        ];
    }

    protected function initCreate(): void
    {
        $this->reset([$this->entityIdProperty(), 'name', 'guard_name']);
        $this->guard_name = config('beartropy-permissions.default_guard', 'web');
        $this->showModal = true;
    }

    protected function initEdit(int $id): void
    {
        $entity = $this->modelClass()::findOrFail($id);

        $this->{$this->entityIdProperty()} = $entity->id;
        $this->name = $entity->name;
        $this->guard_name = $entity->guard_name;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->authorizeAccess();
        $this->validate();

        $entityId = $this->{$this->entityIdProperty()};

        if ($entityId) {
            $entity = $this->modelClass()::findOrFail($entityId);
            $entity->update([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);
        } else {
            $this->modelClass()::create([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->showModal = false;
        $this->dispatch('refresh');
    }

    public function close(): void
    {
        $this->showModal = false;
    }

    public function getGuardsProperty(): array
    {
        $configuredGuards = config('beartropy-permissions.guards');

        if ($configuredGuards === null) {
            return array_keys(config('auth.guards', ['web' => []]));
        }

        return $configuredGuards;
    }

    public function render()
    {
        return view($this->viewName());
    }
}
