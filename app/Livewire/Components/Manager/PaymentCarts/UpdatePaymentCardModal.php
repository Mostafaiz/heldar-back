<?php

namespace App\Livewire\Components\Manager\PaymentCarts;

use App\Livewire\Forms\UpdatePaymentCardForm;
use App\Services\PaymentCardService;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdatePaymentCardModal extends Component
{
    public UpdatePaymentCardForm $form;

    #[On('get-payment-card-data')]
    public function loadData(int $id)
    {
        try {
            $this->form->setData(
                app(PaymentCardService::class)->getById($id)
            );
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در بارگذاری اطلاعات!');
        }
    }

    public function update()
    {
        $this->form->cleanNumber();
        $validated = $this->form->validate();

        try {
            app(PaymentCardService::class)->update($validated);
            $this->dispatch('success', message: 'کارت با موفقیت ویرایش شد!');
            $this->reset('form.ownerName', 'form.cardNumber', 'form.bankName', 'form.IBANnumber');
            $this->dispatch('load-cards');
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در ویرایش کارت!');
        }
    }

    public function resetData()
    {
        $this->reset('form.ownerName', 'form.cardNumber', 'form.bankName', 'form.id');
    }

    public function render()
    {
        return view('components.manager.payment-carts.update-payment-card-modal');
    }
}
