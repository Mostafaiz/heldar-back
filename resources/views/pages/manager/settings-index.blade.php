<div class="opened-panel">
    <div class="inner-container">
        <x-blade.manager.section class="title-con">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
        </x-blade.manager.section>
        <div class="flex gap-5 flex-nowrap">
            <x-blade.manager.section class="h-fit w-2/3 shrink-1">
                <x-blade.manager.section-title title="اطلاعات نمایش به مشتری" />
                <form wire:submit.prevent="submitPublicData" class="flex w-full flex-col gap-5">
                    <div class="flex flex-nowrap gap-3 items-center">
                        <div class="size-5 flex items-center justify-center">
                            <i class="fa-solid fa-phone size-fit text-neutral"></i>
                        </div>
                        <x-blade.manager.input-text title="شماره تلفن" name="publicDataForm.phone" class="w-50!" />
                    </div>
                    <div class="flex flex-nowrap gap-3 items-center w-full">
                        <div class="size-5 flex items-center justify-center">
                            <i class="fa-solid fa-map-location-dot size-fit text-neutral"></i>
                        </div>
                        <x-blade.manager.input-text title="آدرس" name="publicDataForm.address" class="w-full!" />
                    </div>
                    <div class="flex flex-nowrap gap-3">
                        <div class="size-5 flex items-center justify-center">
                            <i class="fa-solid fa-file-lines size-fit text-neutral mt-2"></i>
                        </div>
                        <x-blade.manager.textarea title="متن صفحه درباره ما" name="publicDataForm.aboutUs"
                            class="h-50! w-150!" />
                    </div>
                    <div class="flex flex-nowrap gap-3 items-center">
                        <div class="size-5 flex items-center justify-center">
                            <i class="fa-solid fa-bookmark size-fit text-neutral"></i>
                        </div>
                        <x-blade.manager.input-text title="کد enamad" name="publicDataForm.enamadCode" class="w-200!" />
                    </div>
                    <x-blade.manager.filled-button type="submit" target="submitPublicData" value="ثــبــت"
                        class="w-fit! mr-auto" />
                </form>
            </x-blade.manager.section>
            <x-blade.manager.section class="h-fit w-[calc(1/3*100%-20px)] shrink-1">
                <x-blade.manager.section-title title="پیامک‌ها" />
                <form wire:submit.prevent="submitSMSPhones" class="flex flex-col gap-5 w-full">
                    <x-blade.manager.input-text title="شماره تلفن اول" name="SMSPhonesForm.firstSMSPhone" />
                    <x-blade.manager.input-text title="شماره تلفن دوم" name="SMSPhonesForm.secondSMSPhone" />
                    <x-blade.manager.filled-button type="submit" target="submitSMSPhones" value="ثــبــت"
                        class="w-fit! mr-auto" />
                </form>
            </x-blade.manager.section>
        </div>
    </div>
</div>
