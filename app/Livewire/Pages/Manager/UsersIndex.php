<?php

namespace App\Livewire\Pages\Manager;

use App\Enums\User\UserExceptionsEnum;
use App\Exceptions\User\UserException;
use App\Http\Dto\Request\Manager\AddManager as AddManagerDto;
use App\Http\Dto\Response\User as UserDto;
use App\Livewire\Forms\AddManagerForm;
use App\Services\AccessControlService;
use App\Services\ManagerService;
use App\Services\TransactionService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.manager')]
class UsersIndex extends Component
{
    public string $pageTitle = 'کاربران';
    public string $routeName = 'manager.users.index';
    public AddManagerForm $form;
    public UserDto $currentManager;
    public Collection $users;
    public string $searchText = '';

    public function mount(): void
    {
        $this->loadCurrentManager();
        $this->loadUsers();
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
            $this->loadUsers();
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

    #[On('load-users')]
    public function loadUsers(): void
    {
        $this->users = app(UserService::class)->getUsersByNameOrUsername($this->searchText);
    }

    public function updatedSearchText()
    {
        $this->loadUsers();
    }

    public function acceptTransaction(int $id)
    {
        try {
            $userId = app(TransactionService::class)->approveTransaction($id)->id;
            $this->dispatch('success', message: 'تراکنش با موفقیت تایید شد!');
            $this->dispatch('get-transaction-data', $id);
            $this->dispatch('load-user-transactions', $userId);
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در انجام عملیات!');
        }
    }

    public function rejectTransaction(int $id)
    {
        try {
            $userId = app(TransactionService::class)->rejectTransaction($id)->id;
            $this->dispatch('success', message: 'تراکنش با موفقیت رد شد!');
            $this->dispatch('get-transaction-data', $id);
            $this->dispatch('load-user-transactions', $userId);
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در انجام عملیات!');
        }
    }

    public function render()
    {
        return view('pages.manager.users-index');
    }
}
