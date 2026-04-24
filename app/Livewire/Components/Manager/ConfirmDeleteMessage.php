<?php

namespace App\Livewire\Components\Manager;

use Livewire\Attributes\On;
use Livewire\Component;

class ConfirmDeleteMessage extends Component
{

    public bool $visible = false;
    public ?int $id = null;
    public string $model = "";
    public string $keyword = "";
    public string $content = "";

    #[On('show-confirm-delete-message')]
    public function showMessage(
        int $id,
        string $model,
        string $keyword,
    ): void {
        $this->id = $id;
        $this->model = $model;
        $this->keyword = $keyword;
        $this->visible = true;
        $this->getContent($id);
    }

    #[On('hide-confirm-delete-message')]
    public function hideMessage(): void
    {
        $this->visible = false;
        $this->reset();
    }

    public function delete(): void
    {
        $this->dispatch('delete-' . $this->model, $this->id);
        $this->visible = false;
    }

    private function getContent(int $id): void
    {
        $this->content = app('GalleryService')->getImageRelations($id);
    }

    public function render()
    {
        return view('components.manager.confirm-delete-message');
    }
}
