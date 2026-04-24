<?php

namespace App\Livewire\Pages\Manager;

use App\Livewire\Forms\CreatePaymentCardForm;
use App\Services\PaymentCardService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.manager')]
class DebitCardsIndex extends Component
{
    public string $pageTitle = 'کارت‌های اعتباری';
    public string $routeName = 'manager.debit-cards.index';
    public CreatePaymentCardForm $form;
    public Collection $cards;

    public function mount()
    {
        $this->loadCards();
    }

    #[On('load-cards')]
    public function loadCards()
    {
        $this->cards = app(PaymentCardService::class)->getAll();
    }

    public function create()
    {
        $this->form->cleanNumber();
        $validated = $this->form->validate();
        try {
            app(PaymentCardService::class)->create($validated);
            $this->dispatch('success', message: 'کارت با موفقیت اضافه شد!');
            $this->reset('form.ownerName', 'form.cardNumber', 'form.bankName');
            $this->loadCards();
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در افزودن کارت!');
        }
    }

    public function updateCardName(int $cardId, string $name)
    {
        try {
            if (trim($name) === '') return;
            app(PaymentCardService::class)->updateCardName($cardId, $name);
            $this->dispatch('success', message: 'نام با موفقیت ویرایش شد!');
            $this->loadCards();
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در ویرایش نام!');
        }
    }

    public function updateCardNumber(int $cardId, string $number)
    {
        try {
            $data = ['number' => str_replace(' ', '', trim($number))];

            Validator::make(
                $data,
                [
                    'number' => ['required', 'digits:24'],
                ],
                [
                    'number.required' => 'شماره کارت نمی‌تواند خالی باشد!',
                    'number.digits'   => 'شماره کارت باید ۱۶ رقم باشد!',
                ]
            )->validate();

            app(PaymentCardService::class)->updateCardNumber($cardId, $data['number']);

            $this->dispatch('success', message: 'شماره کارت با موفقیت ویرایش شد!');
            $this->loadCards();
        } catch (ValidationException $e) {
            $this->dispatch('exception', message: $e->getMessage());
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در ویرایش شماره کارت!');
        }
    }

    public function updateCardStatus(int $cardId, bool $status)
    {
        try {
            app(PaymentCardService::class)->updateCardStatus($cardId, $status);
            $this->dispatch('success', message: $status ? 'کارت با موفقیت فعال شد!' : 'کارت با موفقیت غیرفعال شد!');
            $this->loadCards();
            // dd($status);
        } catch (\Throwable $th) {
            $this->dispatch('exception', message: 'خطا در انجام عملیات!');
        }
    }

    public function removeCard(int $cardId)
    {
        try {
            app(PaymentCardService::class)->delete($cardId);
            $this->dispatch('success', message: 'کارت با موفقیت حذف شد!');
            $this->loadCards();
        } catch (\Throwable $th) {
            $this->dispatch('success', message: 'کارت با موفقیت حذف شد!');
        }
    }

    public function render()
    {
        return view('pages.manager.debit-cards-index');
    }
}
