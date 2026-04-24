<?php

namespace App\Livewire\Components\Manager\Managers;

use App\Exceptions\Manager\ManagerException;
use App\Http\Dto\Request\Manager\GetManager;
use App\Http\Dto\Request\Manager\UpdatePermissions as UpdatePermissionsDto;
use App\Services\AccessControlService;
use Livewire\Attributes\On;
use Livewire\Component;

class CurrentManagerPermissionsModal extends Component
{
    public ?array $currentPermissions;
    public ?int $selectedUserId = null;

    public function loadAllPermissions(): array
    {
        return app(AccessControlService::class)->getAllPermissions();
    }

    #[On('get-manager-permissions')]
    public function loadManagerPermissions(int $id): void
    {
        $this->selectedUserId = $id;
        $accessControlService = app(AccessControlService::class);
        $dto = GetManager::makeDto(['id' => $id]);
        $this->currentPermissions = $accessControlService->getManagerPermissionsName($dto);

        $this->dispatch('stop-load');
    }

    public function resetData(): void
    {
        $this->reset('currentPermissions', 'selectedUserId');
    }

    public function render()
    {
        $permissions = $this->loadAllPermissions();
        return view('components.manager.managers.current-manager-permissions-modal', compact('permissions'));
    }
}
