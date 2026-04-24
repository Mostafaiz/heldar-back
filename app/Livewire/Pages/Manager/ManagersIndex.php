<?php

namespace App\Livewire\Pages\Manager;

use App\Enums\User\UserExceptionsEnum;
use App\Exceptions\User\UserException;
use App\Http\Dto\Request\Manager\AddManager as AddManagerDto;
use App\Http\Dto\Response\User as UserDto;
use App\Livewire\Forms\AddManagerForm;
use App\Services\AccessControlService;
use App\Services\ManagerService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.manager')]
class ManagersIndex extends Component
{
    public string $pageTitle = 'مدیران';
    public string $routeName = 'manager.users.managers.index';
    public AddManagerForm $form;
    public UserDto $currentManager;
    public Collection $managers;

    public function mount(): void
    {
        $this->loadCurrentManager();
        $this->loadManagers();
    }

    public function addAdmin(): void
    {
        $this->form->phone = convert_numbers_to_english($this->form->phone);
        $validated = $this->form->validate();
        $managerService = app(ManagerService::class);
        $dto = AddManagerDto::makeDto($validated);

        try {
            $managerService->addManager($dto);
            $this->dispatch('success', message: 'مدیر با موفقیت اضافه شد.');
            $this->loadManagers();
        } catch (UserException $exception) {
            if ($exception->getErrorCode() === UserExceptionsEnum::ALREADY_MANAGER_CODE->value) {
                $this->dispatch('exception', message: 'کاربر مورد نظر در حال حاظر مدیر می‌باشد!');
            } else {
                $this->dispatch('exception', message: 'خطا در افزودن مدیر!');
            }
        }
    }

    public function resetData(): void
    {
        $this->form->reset();
    }

    public function loadCurrentManager(): void
    {
        $this->currentManager = app(AccessControlService::class)->getCurrentUserWithPermissions();
    }

    #[On('load-managers')]
    public function loadManagers(): void
    {
        $this->managers = app(AccessControlService::class)->getOtherManagersWithPermissions();
    }

    public function delete(int $id)
    {
        try {
            app(ManagerService::class)->removeManager($id);
            $this->dispatch('success', message: 'مدیر با موفقیت حذف شد!');
            $this->loadManagers();
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در انجام عملیات!');
        }
    }

    public function render()
    {
        return view('pages.manager.managers-index');
    }
}
