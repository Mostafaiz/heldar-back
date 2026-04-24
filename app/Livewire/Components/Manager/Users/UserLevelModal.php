<?php

namespace App\Livewire\Components\Manager\Users;

use App\Enums\ProductLevelsEnum;
use App\Livewire\Pages\Manager\UsersIndex;
use App\Services\UserService;
use Livewire\Attributes\On;
use Livewire\Component;

class UserLevelModal extends Component
{
    public ?string $selectedLevel = null;
    public ?int $userId = null;

    #[On('load-user-level')]
    public function getUserLevel(int $id)
    {
        $this->userId = $id;
        $this->selectedLevel = app(UserService::class)->getUserLevelById($id);
    }

    public function updatedSelectedLevel()
    {
        app(UserService::class)->updateUserLevel($this->userId, $this->selectedLevel);
        $this->dispatch('success', message: 'سطح کاربر با موفقیت به‌روزرسانی شد.');
        $this->dispatch('load-users')->to(UsersIndex::class);
    }

    public function resetData()
    {
        $this->selectedLevel = null;
        $this->userId = null;
    }

    public function render()
    {
        return view('components.manager.users.user-level-modal');
    }
}
