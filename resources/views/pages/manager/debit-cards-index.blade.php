<div class="opened-panel">
    <div class="inner-container" x-data="{ showEditPaymentCardModal: false }">
        <x-blade.manager.section class="title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        </x-blade.manager.section>

        <div class="flex w-full flex-nowrap gap-[20px]">
            <x-blade.manager.section class="shrink-1! w-full">
                <x-blade.manager.section-title title="افزوده شده" />
                <div class="w-full h-fit flex-wrap gap-5 grid grid-cols-[repeat(auto-fill,_minmax(300px,1fr))]">
                    @forelse ($cards as $card)
                        <div @class([
                            'aspect-video justify-between rounded-2xl flex flex-col overflow-hidden items-center text-white relative font-shabnam p-4',
                        ]) x-bind:class="{ 'bg-white!': true }" x-data="{ nameEditing: false, numberEditing: false }"
                            wire:key="{{ $card->id }}" wire:replace @style(['background: linear-gradient(to top right, ' . $card->color . 'ff, ' . $card->color . '99);', 'filter: saturate(10%)' => !$card->status])>
                            <div class="absolute size-130 -bottom-100 -left-70 bg-white/5 rounded-full"></div>
                            <div class="absolute size-130 -top-110 -right-80 bg-white/5 rounded-full"></div>
                            <form wire:submit.prevent="updateCardName({{ $card->id }}, $refs.nameInput.value)"
                                class="h-7 right-4 flex items-center gap-2 w-full z-2">
                                <span class="font-[500]" x-show="!nameEditing">{{ $card->owner_name }}</span>
                                <input type="text"
                                    class="font-[500] border-2 border-blue-600 w-60 rounded outline-none -mt-0.5 -mr-1 bg-white text-black font-shabnam shrink-0"
                                    x-ref="nameInput" value="{{ $card->owner_name }}"
                                    x-on:click.outside="nameEditing = false; $nextTick(() => $refs.nameInput.focus())"
                                    x-cloak x-bind:style="`width: ${$el.value.length+4}ch`"
                                    x-on:input="$el.style = `width: ${$el.value.length+4}ch`" x-show="nameEditing">
                                <button x-show="!numberEditing" type="button"
                                    x-on:click="$wire.dispatch('get-payment-card-data', [{{ $card->id }}]); showEditPaymentCardModal = true"
                                    class="flex items-center justify-center gap-2 mr-auto text-sm text-white/70 rounded-full px-2 py-1 transition hover:text-white cursor-pointer hover:bg-white/20">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                    <span>ویرایش</span>
                                </button>
                                <button type="button" wire:click="removeCard({{ $card->id }})"
                                    class="flex items-center justify-center gap-2 text-sm text-white/70 rounded-full px-2 py-1 transition hover:text-white cursor-pointer hover:bg-white/20"
                                    wire:loading.attr="disabled" wire:target="removeCard({{ $card->id }})">
                                    <i class="fa-solid fa-trash-can text-xs" wire:loading.remove
                                        wire:target="removeCard({{ $card->id }})"></i>
                                    <i class="fa-solid fa-spinner animate-spin text-xs" wire:loading
                                        wire:target="removeCard({{ $card->id }})"></i>
                                    <span>حذف</span>
                                </button>
                            </form>
                            <form wire:submit.prevent="updateCardNumber({{ $card->id }}, $refs.numberInput.value)"
                                class="z-2">
                                <input type="text" dir="ltr" x-init="cardNumberFormat($el)"
                                    class="text-2xl font-[500] h-8 text-center w-60 rounded outline-none text-white font-shabnam-en"
                                    disabled value="{{ $card->card_number }}">
                                <div class="flex w-full flex-nowrap gap-1 items-center text-white/80 text-sm">
                                    <input type="text" dir="ltr" x-init="IBANnumberFormat($el)" disabled
                                        class="h-8 text-center w-55 rounded outline-none text-white font-shabnam-en"
                                        disabled value="{{ $card->iban_number }}">
                                    <span class="mt-0.5">
                                        IR
                                    </span>
                                </div>
                            </form>
                            <div class="w-full flex justify-end items-center gap-2 z-2" wire:loading.attr="disabled"
                                wire:target="updateCardStatus({{ $card->id }}, {{ $card->status ? 'false' : 'true' }})">
                                <span class="ml-auto">
                                    {{ $card->bank_name }}
                                </span>
                                <i class="fa-solid fa-spinner animate-spin text-white text-sm" wire:loading
                                    wire:target="updateCardStatus({{ $card->id }}, {{ $card->status ? 'false' : 'true' }})"></i>
                                <div class="h-5 w-10 bg-white/30 border border-white rounded-full flex items-center p-0.5 cursor-pointer"
                                    wire:click="updateCardStatus({{ $card->id }}, {{ $card->status ? 'false' : 'true' }})">
                                    <div @class([
                                        'h-full aspect-square rounded-full bg-white',
                                        'mr-auto' => !$card->status,
                                    ])></div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </x-blade.manager.section>
            <x-blade.manager.section class="w-[400px] h-fit sticky! top-5">
                <x-blade.manager.section-title title="افزودن" />

                <form wire:submit.prevent="create" class="flex flex-col w-full gap-[20px] items-end!">
                    <div class="w-full flex flex-nowrap gap-5">
                        <x-blade.manager.input-text title="نام دارنده کارت" name="form.ownerName" required />
                        <x-blade.manager.input-text title="نام بانک" name="form.bankName" required />
                    </div>
                    <x-blade.manager.input-text title="شماره کارت" name="form.cardNumber" wire:ignore
                        x-init="cardNumberFormat($el)" dir="ltr" required />
                    <x-blade.manager.input-text title="شماره شبا" name="form.IBANnumber" wire:ignore
                        x-init="IBANnumberFormat($el)" dir="ltr" required label="IR" />
                    <x-blade.manager.filled-button type="submit" value="ثبت کارت" target="create" />
                </form>
            </x-blade.manager.section>
        </div>

        <livewire:components.manager.payment-carts.update-payment-card-modal />
    </div>
</div>

@script
    <script>
        window.IBANnumberFormat = (element) => {
            IMask(
                element, {
                    mask: '00 0000 0000 0000 0000 0000 00',
                }
            );
        }

        window.cardNumberFormat = (element) => {
            IMask(
                element, {
                    mask: '0000 0000 0000 0000',
                }
            );
        }
    </script>
@endscript
