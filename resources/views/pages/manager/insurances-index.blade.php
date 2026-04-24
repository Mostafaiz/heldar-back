<div class="opened-panel flex flex-col justify-start items-start" x-data="{ showDeleteInsuranceMessage: false, selectedInsuranceForDelete: null, showInsuranceDetailsModal: false, showEditInsuranceModal: false }">
    <div class="inner-container">
        <x-blade.manager.section class="title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        </x-blade.manager.section>

        <div class="flex w-full flex-nowrap gap-[20px]">
            <x-blade.manager.section class="shrink-1! w-full">
                <x-blade.manager.section-title title="افزوده شده">
                    مشخصه دیگه
                </x-blade.manager.section-title>

                <div class="w-full h-fit flex flex-wrap gap-5 justify-start items-start">
                    @foreach ($insurances as $insurance)
                        <div class="w-[calc(50%-10px)] max-2xl:w-full h-fit border-1 border-gray-200 rounded-xl flex flex-col justify-start items-start p-4 gap-2 font-[shabnam] box-border shadow-md relative"
                            x-data="{ showOptionsMenu: false }">
                            <span class="text-lg font-[500] w-full flex justify-between items-center">
                                {{ $insurance->name }}
                                <i class="fa-solid fa-ellipsis-vertical text-gray-600 text-md hover:bg-gray-200 rounded-full cursor-pointer size-8 leading-8! text-center transition"
                                    x-on:click="showOptionsMenu = true"></i>
                            </span>
                            <span class="text-md text-gray-600 font-[300] w-full flex gap-2 items-center mb-1">
                                <i class="fa-solid fa-building text-sm"></i>
                                {{ $insurance->provider }}
                            </span>
                            <div class="flex gap-2 flex-wrap">
                                <span
                                    class="text-sm font-[300] w-fit h-fit px-3 py-1 gap-3 box-border bg-green-600 rounded-full text-white">
                                    <i class="fa-solid fa-coins text-[13px] mt-1 -mb-1"></i>
                                    <span>{{ number_format($insurance->price) }}</span>
                                </span>
                                @if ($insurance->insurance_code)
                                    <span
                                        class="text-sm font-[300] w-fit h-fit px-3 py-1 gap-3 box-border bg-yellow-500 rounded-full text-white">
                                        <i class="fa-solid fa-barcode text-[12px] mt-1 -mb-1"></i>
                                        <span>{{ $insurance->insurance_code }}</span>
                                    </span>
                                @endif
                                <span
                                    class="text-sm font-[300] w-fit h-fit px-3 py-1 gap-3 box-border bg-blue-500 rounded-full text-white">
                                    <i class="fa-solid fa-clock text-[12px] mt-1 -mb-1"></i>
                                    <span>{{ $insurance->duration }} ماهه</span>
                                </span>
                            </div>

                            <div class="absolute bg-white w-40 h-fit flex flex-col gap-1 overflow-hidden rounded-xl shadow-2xl top-14 left-4 border-1 border-gray-200 box-border p-1.5"
                                x-show="showOptionsMenu" x-on:click="showOptionsMenu = false"
                                x-on:click.outside="showOptionsMenu = false" x-transition x-cloak>
                                <button type="button"
                                    class="group w-full h-10 rounded-lg bg-white text-right px-3 text-md cursor-pointer hover:bg-gray-100 transition"
                                    x-on:click="showInsuranceDetailsModal = true; $dispatch('get-insurance-data', [{{ $insurance->id }}])">
                                    <i class="fa-solid fa-file-alt text-sm text-gray-400 ml-2"></i>
                                    مشاهده
                                </button>
                                <button type="button"
                                    class="group w-full h-10 rounded-lg bg-white text-right px-3 text-md cursor-pointer hover:bg-gray-100 transition"
                                    x-on:click="showEditInsuranceModal = true; $dispatch('get-insurance-data-for-edit', [{{ $insurance->id }}])">
                                    <i class="fa-solid fa-pen-to-square text-sm text-gray-400 ml-2"></i>
                                    ویرایش
                                </button>
                                <button type="button"
                                    class="group w-full h-10 rounded-lg bg-white text-right px-3 text-md cursor-pointer hover:bg-gray-100 transition"
                                    x-on:click="showDeleteInsuranceMessage = true; selectedInsuranceForDelete = {{ $insurance->id }}">
                                    <i class="fa-solid fa-trash-can text-sm text-gray-400 ml-2"></i>
                                    حذف
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-blade.manager.section>

            <x-blade.manager.section class="w-[350px] h-fit sticky! top-5">
                <x-blade.manager.section-title title="افزودن">
                    مقادیر بیمه را وارد کنید
                </x-blade.manager.section-title>

                <form wire:submit.prevent="create" class="flex flex-col w-full gap-[20px] items-end!">
                    <x-blade.manager.input-text title="نام بیمه" name="form.name" />
                    <x-blade.manager.input-text title="نام ارائه دهنده بیمه" name="form.provider" />
                    <x-blade.manager.input-text title="کد بیمه‌نامه" name="form.insuranceCode" />
                    <div class="w-full h-fit flex items-center gap-2.5 font-[shabnam] font-light text-lg">
                        <span class="shrink-0">
                            مدت بیمه:
                        </span>
                        <input type="number" x-on:change="if ($event.target.value < 0) $event.target.value = 0"
                            class="outlined-input shrink-1! grow-0! w-16!" placeholder dir="ltr"
                            wire:model="form.duration" />
                        <span class="shrink-0">
                            ماه
                        </span>
                    </div>
                    @error('form.duration')
                        <span class="error-message -mt-4!">
                            {{ $message }}
                        </span>
                    @enderror
                    <div class="flex gap-2 w-full font-[shabnam] items-center">
                        <x-blade.manager.input-text type="number" title="هزینه" class="hide-arrows" name="form.price"
                            x-on:change="if ($event.target.value < 0) $event.target.value = 0" dir="ltr" />
                        تومان
                    </div>
                    <x-blade.manager.textarea title="توضیحات" name="form.description" class="h-20!" />
                    <x-blade.manager.filled-button type="submit" value="ثبت بیمه" target="create" />
                </form>
            </x-blade.manager.section>

            <x-manager.insurances.delete-message-modal />
            <livewire:components.manager.insurances.update-insurance-modal />
            <livewire:components.manager.insurances.insurance-details-modal />
        </div>
    </div>
</div>
