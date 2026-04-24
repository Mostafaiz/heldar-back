<div class="h-full w-[148mm]">
    <div class="w-full h-full border-2 flex flex-col font-shabnam font-[500]">
        <div class="w-full border-b-2 flex flex-col items-center p-2 gap-2">
            <h1 class="font-[500] text-md">کیف داریوش</h1>
            <div class="w-full flex flex-nowrap text-[10px] gap-3">
                <div class="w-full flex flex-col gap-2 shrink-1">
                    <span>
                        آدرس:
                        {{ $siteConfig['admin_address'] ?? '' }}
                    </span>
                </div>
                <div class="w-30 shrink-0 flex flex-col gap-1" style="margin-top: -30px;">
                    <span>
                        شماره فاکتور:
                        {{ $transaction->code }}
                    </span>
                    <span>
                        تاریخ:
                        {{ jalali($transaction->createdAt, 'Y/m/d') }}
                    </span>
                    <span>
                        تلفن:
                        {{ $siteConfig['admin_phone'] ?? '' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="w-ful flex flex-col">
            <div class="w-full flex flex-col gap-2 text-[10px] p-2">
                <h3 class="font-[500]">
                    خریدار:
                    {{ $transaction->user->name }}
                </h3>
                <div class="w-full flex flex-nowrap gap-3">
                    <span class="w-full shrink-1">
                        آدرس:
                        {{ $transaction->address }}
                    </span>
                    <span class="w-25 shrink-0">
                        تلفن:
                        {{ $transaction->user->username }}
                    </span>
                </div>
            </div>
            <div class="w-full flex flex-col text-[10px]">
                <div class="w-full flex flex-nowrap first:border-t border-b h-8">
                    <div class="w-10 border-l flex items-center justify-center shrink-0">ردیف</div>
                    <div class="w-45 border-l flex items-center justify-center">شــــرح کالا</div>
                    <div class="w-14 border-l flex items-center justify-center shrink-0">تعداد</div>
                    <div class="w-15 border-l flex items-center justify-center shrink-0">واحد</div>
                    <div class="w-20 border-l flex items-center justify-center shrink-0">مبلغ فی (تومان)</div>
                    <div class="w-25 flex items-center justify-center shrink-0">مبلغ کل (تومان)</div>
                </div>
                @foreach ($transaction->items as $key => $product)
                    <div class="w-full flex flex-nowrap first:border-t border-b h-8">
                        <div class="w-10 border-l flex items-center justify-center shrink-0">{{ $key + 1 }}</div>
                        <div class="w-45 px-1 py-0.5 border-l flex items-center justify-center">
                            {{ $product['product_name'] . ' - ' . $product['pattern_name'] }}
                        </div>
                        <div class="w-14 border-l flex items-center justify-center shrink-0">{{ $product['quantity'] }}
                        </div>
                        <div class="w-15 border-l flex items-center justify-center shrink-0">عدد</div>
                        <div class="w-20 border-l flex items-center justify-center shrink-0">
                            {{ number_format($product['unit_price']) }}
                        </div>
                        <div class="w-25 flex items-center justify-center shrink-0">
                            {{ number_format($product['total_price']) }}
                        </div>
                    </div>
                @endforeach
                <div class="w-full flex flex-nowrap h-7">
                    <div class="w-full border-l flex pr-3 items-center">توضیحات:</div>
                    <div class="w-14 border-l flex items-center justify-center shrink-0 border-b">جمع تعداد</div>
                    <div class="w-15 border-l flex items-center justify-center shrink-0 border-b">
                        {{ $transaction->total_quantity }}
                    </div>
                    <div class="w-20 border-l flex items-center justify-center shrink-0 pt-[50%]" style="padding-top: 28px;">جمع کل:</div>
                    <div class="w-25 flex items-center justify-center shrink-0 pt-1/2" style="padding-top: 28px;">
                        {{ number_format($transaction->amount) }}
                    </div>
                </div>
                <div class="w-full flex flex-nowrap min-h-7">
                    <div class="w-full border-l flex items-center pr-3 py-2">
                        مبلغ به حروف:
                        {{ $priceInWordsFa }}
                        تومان
                    </div>
                    <div class="w-20 border-l flex items-center justify-center shrink-0"></div>
                    <div class="w-25 flex items-center justify-center shrink-0"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full flex flex-col justify-center items-end gap-2 shrink-0 h-20">
        <span class="flex items-center gap-2 font-shabnam font-[500]">
            {{ $siteConfig['admin_phone'] ?? '' }}
            <div class="size-6 bg-success flex items-center justify-center rounded-full"><i
                    class="fa-brands fa-whatsapp text-white"></i></div>
        </span>
        <span class="flex items-center gap-2 font-shabnam font-[500]">
            https://dhbag.ir
            <div class="size-6 bg-info flex items-center justify-center rounded-full"><i
                    class="fa-solid fa-globe text-white text-[10px] size-fit"></i></div>
        </span>
    </div>
</div>
