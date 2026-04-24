<?php

namespace App\Livewire\Components\Manager;

use App\Enums\PermissionEnum;
use App\Http\Dto\Request\Manager\GetManager;
use App\Services\AccessControlService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RightMenu extends Component
{
    public array $managerPermissions;

    public function mount(): void
    {
        $this->setManagerPermissions();
    }

    public function logout()
    {
        $authService = app('AuthService');
        $authService->logout();
        $this->redirect(route('login'));
    }

    public function setManagerPermissions(): void
    {
        $dto = GetManager::makeDto(['id' => Auth::id()]);
        $this->managerPermissions = app(AccessControlService::class)->getManagerPermissionsName($dto);
    }

    public function hasPermission(PermissionEnum $permission): bool
    {
        return in_array($permission->value, $this->managerPermissions, true);
    }

    public function render()
    {
        return view('components.manager.right-menu');
    }
}
