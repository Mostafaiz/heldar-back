<div class="opened-panel" x-data="{ showGallery: false, selectedImage: null }">
    <div class="inner-container grid-rows-[100px_1fr_100%]!" x-data="{
        currentSlide: $wire.entangle('currentSlide'),
        slides: [],
        changeSlide(index) {
            this.currentSlide = index;
            selectedImage = this.slides[this.currentSlide - 1].image?.id ?? null
        },
        changedIndexes: [],
        desktopImage: true
    }" x-init="$wire.getSlides().then(data => {
        slides = data;
        console.log(slides)
    })"
        x-on:set-selected-image.window="$wire.getImage($event.detail[0]).then(data => { if (desktopImage) slides[currentSlide - 1].desktopImage = data; else slides[currentSlide - 1].mobileImage = data }); if (!changedIndexes.includes(slides[currentSlide - 1].id)) changedIndexes.push(slides[currentSlide - 1].id)">
        <x-blade.manager.section class="title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        </x-blade.manager.section>
        <div class="flex gap-5">
            <x-blade.manager.section class="w-full! shrink-1!">
                <template x-for="(slide, index) in slides">
                    <div class="w-full h-auto flex flex-col items-center font-[shabnam] gap-5"
                        x-show="currentSlide == index + 1">
                        <div class="w-full flex justify-between gap-4">
                            <div class="flex gap-4 items-center">
                                <div class="size-4 rounded-full bg-green-500"
                                    x-bind:class="slide.status ? 'bg-green-500' : 'bg-red-600'"></div>
                                <h3 class="text-xl" x-text="'اسلاید ' + (index + 1)"></h3>
                            </div>
                            <div class="flex gap-2 border rounded-xl border-gray-300">
                                <button type="button"
                                    class="size-11 border-gray-300 bg-white transition rounded-xl flex items-center justify-center text-gray-500 cursor-pointer "
                                    x-on:click="changeSlide(currentSlide == 1 ? 10 : currentSlide - 1)">
                                    <i class="fa-solid fa-angle-right"></i>
                                </button>
                                <div class="h-11 text-xl flex items-center justify-center rounded-xl w-15"
                                    x-text="'10' + ' / ' + currentSlide"></div>
                                <button type="button"
                                    class="size-11 border-gray-300 bg-white transition rounded-xl flex items-center justify-center text-gray-500 cursor-pointer "
                                    x-on:click="changeSlide(currentSlide == 10 ? 1 : currentSlide + 1)">
                                    <i class="fa-solid fa-angle-left"></i>
                                </button>
                            </div>
                        </div>
                        <div
                            class="w-full h-100 bg-gray-50 border border-gray-200 rounded-xl flex flex-col items-center justify-center gap-3 cursor-pointer overflow-hidden relative">
                            <template x-if="slide.desktopImage === null">
                                <div class="flex flex-col gap-3 items-center size-full justify-center"
                                    x-on:click="showGallery = true; desktopImage = true; selectedImage = slide.desktopImage?.id ?? null">
                                    <i class="fa-solid fa-plus-circle text-8xl text-gray-300"></i>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-gray-400">برای افزودن تصویر کلیک کنید</span>
                                        <span class="text-gray-400 text-sm">1366 تا 1920 پیکسل در 400 پیکسل</span>
                                    </div>
                                </div>
                            </template>
                            <template x-if="slide.desktopImage !== null">
                                <div class="size-full">
                                    <img x-bind:src="`/storage/${slide.desktopImage.path}`"
                                        class="size-full object-cover">
                                    <div class="absolute bg-red-600 z-2 size-6 rounded-full text-white top-5 left-5 flex items-center justify-center"
                                        x-on:click="slide.desktopImage = null; selectedImage = null; if (!changedIndexes.includes(slide.id)) changedIndexes.push(slide.id); slide.status = false">
                                        <i class="fa-solid fa-xmark"></i>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
                <div class="w-full flex justify-center gap-5 items-center">
                    <div class="w-full flex items-center justify-center gap-3">
                        <button type="button"
                            class="size-10 border-gray-300 shrink-0 border bg-white transition rounded-lg flex items-center justify-center text-gray-500 cursor-pointer hover:bg-gray-100"
                            x-on:click="changeSlide(currentSlide == 1 ? 10 : currentSlide - 1)">
                            <i class="fa-solid fa-angle-right"></i>
                        </button>
                        <template x-for="(slide, index) in slides">
                            <div class="w-full max-w-20 grow-1 h-10 border border-gray-300 rounded-lg bg-gray-50 cursor-pointer flex items-center justify-center font-[shabnam] text-gray-600 overflow-hidden"
                                x-bind:class="{
                                    'border-2 border-primary!': currentSlide == index + 1,
                                    'opacity-40': !slide.status &&
                                        slide.desktopImage !== null
                                }"
                                x-on:click="changeSlide(index + 1)">
                                <template x-if="slide.desktopImage !== null">
                                    <img x-bind:src="`/storage/${slide.desktopImage.path}`"
                                        class="size-full object-cover" />
                                </template>
                            </div>
                        </template>
                        <button type="button"
                            class="size-10 border-gray-300 shrink-0 border bg-white transition rounded-lg flex items-center justify-center text-gray-500 cursor-pointer hover:bg-gray-100"
                            x-on:click="changeSlide(currentSlide == 10 ? 1 : currentSlide + 1)">
                            <i class="fa-solid fa-angle-left"></i>
                        </button>
                    </div>
                </div>
            </x-blade.manager.section>
            <template x-for="(slide, index) in slides">
                <x-blade.manager.section class="w-100 shrink-0! h-fit!" x-show="currentSlide == index + 1"
                    x-data="{ mobileSize: slide.mobileImage !== null && slide.mobileImage?.id !== slide.desktopImage?.id }" x-init="$watch('slide.desktopImage', value => {
                        if (value === null) {
                            mobileSize = false;
                            slide.mobileImage = null
                        }
                    })">
                    <div class="flex justify-between w-full">
                        <x-blade.manager.section-title x-text="'تنظیمات اسلاید'" class="w-full! shrink-1!" />
                        <div class="flex gap-2 items-center justify-between">
                            <span class="font-light text-gray-700 font-[shabnam]">وضعیت:</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer"
                                    x-bind:disabled="slide.desktopImage === null" x-bind:checked="slide.status"
                                    x-on:change="slide.status = !slide.status; if (!changedIndexes.includes(slide.id)) changedIndexes.push(slide.id)">
                                <div
                                    class="w-10 h-6 bg-gray-200 peer-checked:bg-primary rounded-full transition-colors duration-200">
                                </div>
                                <div
                                    class="absolute left-1 top-1 size-4 bg-white rounded-full shadow transition-transform duration-200 transform peer-checked:translate-x-4">
                                </div>
                            </label>
                        </div>
                    </div>
                    <x-blade.manager.input-text title="لینک اسلاید" x-model="slide.link"
                        x-on:input="if (!changedIndexes.includes(slide.id)) changedIndexes.push(slide.id)"
                        dir="ltr" />
                    <span class="font-shabnam">سطح اسلاید</span>
                    <div class="w-full flex flex-nowrap gap-4">
                        <div x-bind:class="{ 'border-primary bg-primary-lighter': slide.level === 'gold' }"
                            x-on:click="slide.level = 'gold'; if (!changedIndexes.includes(slide.id)) changedIndexes.push(slide.id)"
                            class="w-full border-2 cursor-pointer border-gray-300 bg-gray-100 font-shabnam flex items-center justify-center py-2 rounded-md">
                            طلایی
                        </div>
                        <div x-bind:class="{ 'border-primary bg-primary-lighter': slide.level === 'silver' }"
                            x-on:click="slide.level = 'silver'; if (!changedIndexes.includes(slide.id)) changedIndexes.push(slide.id)"
                            class="w-full border-2 cursor-pointer border-gray-300 bg-gray-100 font-shabnam flex items-center justify-center py-2 rounded-md">
                            نقره‌ای
                        </div>
                        <div x-bind:class="{ 'border-primary bg-primary-lighter': slide.level === 'boronze' }"
                            x-on:click="slide.level = 'boronze'; if (!changedIndexes.includes(slide.id)) changedIndexes.push(slide.id)"
                            class="w-full border-2 cursor-pointer border-gray-300 bg-gray-100 font-shabnam flex items-center justify-center py-2 rounded-md">
                            برنزی
                        </div>
                    </div>
                    <label class="w-full flex flex-nowrap gap-2 cursor-pointer font-[shabnam]"
                        x-bind:class="{ 'text-gray-500 cursor-default!': slide.desktopImage == null }">
                        <input type="checkbox" x-bind:checked="mobileSize"
                            x-on:change="mobileSize = $event.target.checked; slide.mobileImage = null; if (!changedIndexes.includes(slide.id)) changedIndexes.push(slide.id)"
                            x-bind:disabled="slide.desktopImage == null">
                        <span>ابعاد موبایل</span>
                    </label>
                    <div class="w-full h-40 bg-gray-50 border border-gray-200 rounded-xl flex flex-col items-center justify-center gap-3 cursor-pointer overflow-hidden relative font-[shabnam]"
                        x-show="mobileSize">
                        <template x-if="slide.mobileImage === null">
                            <div class="flex flex-col gap-3 items-center size-full justify-center"
                                x-on:click="showGallery = true; desktopImage = false; selectedImage = slide.mobileImage?.id ?? null">
                                <i class="fa-solid fa-plus-circle text-5xl text-gray-300"></i>
                                <div class="flex flex-col gap-1">
                                    <span class="text-gray-400">برای افزودن تصویر موبایل کلیک کنید</span>
                                </div>
                            </div>
                        </template>
                        <template x-if="slide.mobileImage !== null">
                            <div class="size-full">
                                <img x-bind:src="`/storage/${slide.mobileImage.path}`" class="size-full object-cover">
                                <div class="absolute bg-red-600 z-2 size-6 rounded-full text-white top-3 left-3 flex items-center justify-center"
                                    x-on:click="slide.mobileImage = null; selectedImage = null; if (!changedIndexes.includes(slide.id)) changedIndexes.push(slide.id)">
                                    <i class="fa-solid fa-xmark"></i>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="w-full flex justify-end items-center gap-3">
                        <div class="font-[shabnam] px-3 py-1 bg-amber-50 rounded-lg text-yellow-700 text-sm"
                            x-show="changedIndexes.includes(slide.id)">
                            ذخیره نشده</div>
                        <x-blade.manager.filled-button value="ذخیره تغییرات" icon="fa-solid fa-paper-plane"
                            x-on:click="$wire.updateSlide(slides[currentSlide - 1]).then(data => { changedIndexes.splice(changedIndexes.indexOf(slide.id), 1) });"
                            x-bind:disabled="!changedIndexes.includes(slide.id) || (mobileSize && slide.mobileImage === null)"
                            target="updateSlide" />
                    </div>
                </x-blade.manager.section>
            </template>
        </div>
    </div>

    <div class="back-cover fixed! z-2 left-0! top-0! p-10!" x-show="showGallery" x-transition.opacity x-cloak>
        <livewire:components.manager.gallery.index :selectable="true" />
    </div>
</div>

@assets
    @vite(['resources/css/manager/home-slider.scss'])
@endassets
