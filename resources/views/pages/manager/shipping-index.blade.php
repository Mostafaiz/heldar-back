<div class="opened-panel">
    <div class="inner-container" x-data="{ showDeleteShippingMessage: false, selectedShippingForDelete: null, showEditShippingModal: false, showShippingDetailsModal: false }">
        <x-blade.manager.section class="title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        </x-blade.manager.section>

        <div class="flex w-full flex-nowrap gap-[20px]">
            <x-blade.manager.section class="shrink-1! w-full">
                <x-blade.manager.section-title title="افزوده شده">
                    مشخصه دیگه
                </x-blade.manager.section-title>

                <div class="w-full h-fit flex-wrap gap-5 grid grid-cols-[repeat(auto-fill,_minmax(300px,1fr))]">
                    @foreach ($shippings as $shipping)
                        <div class="border-1 border-gray-200 rounded-xl flex flex-col justify-start items-start p-4 gap-2 font-[shabnam] box-border shadow-md relative"
                            x-data="{ showOptionsMenu: false }">
                            <span class="text-lg font-[500] w-full flex justify-between items-center">
                                {{ $shipping->name }}
                                <i class="fa-solid fa-ellipsis-vertical text-gray-600 text-md hover:bg-gray-200 rounded-full cursor-pointer size-8 leading-8! text-center transition"
                                    x-on:click="showOptionsMenu = true"></i>
                            </span>
                            <div class="flex gap-2 flex-wrap">
                                <span
                                    class="text-sm font-[500] w-fit h-fit px-3 py-1 gap-3 box-border bg-green-600 rounded-full text-white">
                                    <i class="fa-solid fa-coins text-[13px] mt-1 -mb-1"></i>
                                    <span>{{ $shipping->price ? number_format($shipping->price) : 'رایگان' }}</span>
                                </span>
                            </div>

                            <div class="absolute z-2 bg-white w-40 h-fit flex flex-col gap-1 overflow-hidden rounded-xl shadow-2xl top-14 left-4 border-1 border-gray-200 box-border p-1.5"
                                x-show="showOptionsMenu" x-on:click="showOptionsMenu = false"
                                x-on:click.outside="showOptionsMenu = false" x-transition x-cloak>
                                <button type="button"
                                    class="group w-full h-10 rounded-lg bg-white text-right px-3 text-md cursor-pointer hover:bg-gray-100 transition"
                                    x-on:click="showShippingDetailsModal = true; $dispatch('get-shipping-data', [{{ $shipping->id }}])">
                                    <i class="fa-solid fa-file-alt text-sm text-gray-400 ml-2"></i>
                                    مشاهده
                                </button>
                                <button type="button"
                                    class="group w-full h-10 rounded-lg bg-white text-right px-3 text-md cursor-pointer hover:bg-gray-100 transition"
                                    x-on:click="showEditShippingModal = true; $dispatch('get-shipping-data-for-edit', [{{ $shipping->id }}])">
                                    <i class="fa-solid fa-pen-to-square text-sm text-gray-400 ml-2"></i>
                                    ویرایش
                                </button>
                                <button type="button"
                                    class="group w-full h-10 rounded-lg bg-white text-right px-3 text-md cursor-pointer hover:bg-gray-100 transition"
                                    x-on:click="showDeleteShippingMessage = true; selectedShippingForDelete = {{ $shipping->id }}">
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
                    مقادیر پست را وارد کنید
                </x-blade.manager.section-title>

                <form wire:submit.prevent="create" class="flex flex-col w-full gap-[20px] items-end!">
                    <x-blade.manager.input-text title="نام پست" name="form.name" required />
                    <x-blade.manager.input-text title="قیمت" name="form.price" label="تومان" required />
                    <x-blade.manager.textarea title="توضیحات" name="form.description" class="h-20!" />
                    <x-blade.manager.filled-button type="submit" value="ثبت پست" target="create" />
                </form>
            </x-blade.manager.section>
        </div>

        <x-manager.shipping.delete-message-modal />
        <livewire:components.manager.shipping.update-shipping-modal />
        <livewire:components.manager.shipping.shipping-details-modal />
    </div>
</div>
