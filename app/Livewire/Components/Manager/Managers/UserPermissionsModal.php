<?php

namespace App\Livewire\Components\Manager\Managers;

use App\Exceptions\Manager\ManagerException;
use App\Http\Dto\Request\Manager\GetManager;
use App\Http\Dto\Request\Manager\UpdatePermissions as UpdatePermissionsDto;
use App\Services\AccessControlService;
use Livewire\Attributes\On;
use Livewire\Component;

class UserPermissionsModal extends Component
{
    public ?array $currentPermissions;
    public ?int $selectedUserId = null;

    public function togglePermission(string $permissionName, bool $function): void
    {
        if ($function)
            $this->currentPermissions[] = $permissionName;
        else
            $this->currentPermissions = array_filter($this->currentPermissions, fn($item) => $item !== $permissionName);
    }

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

    #[On('reset-permissions-data')]
    public function resetData(): void
    {
        $this->reset('currentPermissions', 'selectedUserId');
    }

    public function update(): void
    {
        $accessControlService = app(AccessControlService::class);
        $dto = UpdatePermissionsDto::makeDto($this->selectedUserId, $this->currentPermissions);

        try {
            $accessControlService->updateManagerPermissions($dto);
            $this->dispatch('success', message: 'دسترسی‌ها با موفقیت به‌روزرسانی شدند.');
            $this->dispatch('load-managers');
            $this->resetData();
        } catch (ManagerException $exception) {
            $this->dispatch('exception', message: 'خطا در تغییر دسترسی‌ها!');
        }

    }

    public function render()
    {
        $permissions = $this->loadAllPermissions();
        return view('components.manager.managers.user-permissions-modal', compact('permissions'));
    }
}
