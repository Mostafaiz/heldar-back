<div class="w-full flex flex-col gap-6 mb-20 relative" x-data="{
    orders: {{ $orders }},
    addOrder(id) {
        const item = this.orders.find(o => o.product_variant_id === id);
        if (item) item.quantity++;
        else this.orders.push({ id, count: 1 })
    },
    removeOrder(id) { const item = this.orders.find(o => o.product_variant_id === id); if (item && item.quantity > 0) item.quantity-- },
    getOrder(id) {
        let item = this.orders.find(o => o.product_variant_id === id);
        if (!item) {
            item = { product_variant_id: id, quantity: 0 };
            this.orders.push(item);
        }
        return item;
    },
    requests: [],
    addRequest(id) {
        const item = this.requests.find(r => r.product_variant_id === id);
        if (item) item.quantity++;
        else this.requests.push({ product_variant_id: id, quantity: 1 })
    },
    removeRequest(id) { const item = this.requests.find(r => r.product_variant_id === id); if (item && item.quantity > 0) item.quantity-- },
    getRequest(id) {
        let item = this.requests.find(r => r.product_variant_id === id);
        if (!item) {
            item = { product_variant_id: id, quantity: 0 };
            this.requests.push(item);
        }
        return item;
    },
    showRequestsPanel: false
}" wire:ignore.self>
    <div class="h-100 flex" wire:ignore>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    <img class="swiper-slide h-full object-contain" src="{{ asset('storage/' . $slide->path) }}">
                @endforeach
            </div>

            <div
                class="pagination flex items-center justify-center gap-1.5 z-2 w-fit! rounded-full px-1.5 py-1.5 bg-black/10 absolute bottom-3 right-1/2 translate-x-1/2">
            </div>
            <button
                class="next absolute left-3 z-2 bg-white shadow-md transition opacity-80 rounded-full cursor-pointer border border-gray-200 size-10 top-1/2 -translate-y-1/2">
                <i class="fa-solid fa-angle-left"></i>
            </button>
            <button
                class="prev absolute right-3 z-2 bg-white shadow-md transition opacity-80 rounded-full cursor-pointer border border-gray-200 size-10 top-1/2 -translate-y-1/2">
                <i class="fa-solid fa-angle-right"></i>
            </button>
        </div>
    </div>

    <div class="w-full font-[500] font-shabnam max-md:text-sm px-4 flex items-start gap-3 justify-between">
        <span class="text-primary-dark shrink-0">
            {{ number_format($product->variants[0]->price) }}
            تومان
        </span>
        <span class="font-shabnam-en">{{ $product->name }}</span>
    </div>
    <div class="w-full grid grid-cols-[repeat(auto-fill,_minmax(350px,1fr))] gap-3 px-5">
        @foreach ($product->variants as $variant)
            <div class="border h-20 flex flex-nowrap items-center rounded-lg gap-2 overflow-hidden border-gray-200 shadow-md pl-4"
                x-data="fitNameOnQuantity()" x-init="init()" x-effect="check()">
                <img src="{{ asset('storage/' . $variant->pattern->files[0]->path) }}"
                    class="h-full aspect-square object-cover rounded-r-lg" alt="{{ $variant->pattern->files[0]->alt }}">
                <span class="font-shabnam w-fit shrink-1 text-nowrap" :style="{ fontSize: fontSize + 'px' }"
                    x-ref="name">{{ $variant->pattern->name }}</span>
                <div class="flex flex-nowrap w-full justify-end items-center gap-6">
                    <span
                        class="font-shabnam shrink-0 size-fit bg-gray-200 font-[500] flex items-center justify-center px-2 text-sm text-neutral-dark rounded-full"
                        x-ref="qty">
                        {{ $variant->quantity }}
                        عدد
                    </span>
                    <div class="w-24 h-9 shrink-0 shadow border border-gray-200 rounded-md flex justify-between items-center font-shabnam"
                        x-data="{ max: false }" wire:replace>
                        <button type="button"
                            class="h-full aspect-square active:scale-70 active:bg-primary active:text-white rounded-full transition flex items-center justify-center font-shabnam text-primary cursor-pointer disabled:cursor-default"
                            x-on:click="$event.preventDefault(); if (getOrder({{ $variant->id }}).quantity < {{ $variant->quantity }}) addOrder({{ $variant->id }})">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                        <input type="text" class="w-6 bg-transparent text-center outline-none text-primary-dark"
                            x-model="getOrder({{ $variant->id }}).quantity"
                            x-on:blur="if (getOrder({{ $variant->id }}).quantity > {{ $variant->quantity }}) getOrder({{ $variant->id }}).quantity = {{ $variant->quantity }}"
                            x-init="getOrder({{ $variant->id }})">
                        <button type="button"
                            class=" h-full aspect-square flex items-center active:scale-70 active:bg-primary active:text-white transition justify-center font-shabnam text-primary rounded-full cursor-pointer disabled:cursor-default"
                            x-on:click="$event.preventDefault(); removeOrder({{ $variant->id }})">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
    <div class="w-full flex flex-col gap-5 items-center">
        <button type="button"
            class="bg-primary rounded-md text-white font-shabnam font-[500] w-60 py-3 cursor-pointer hover:bg-primary-dark disabled:cursor-default not-disabled:hover:bg-primary-dark transition"
            wire:click="updateCartItems(orders)" wire:loading.attr="disabled" wire:target="updateCartItems">
            <span wire:loading.remove wire:target="updateCartItems">افزودن به سبد خرید</span>
            <i class="fa-solid fa-spinner animate-spin" wire:loading wire:target="updateCartItems"></i>
        </button>
        <button type="button" x-on:click="showRequestsPanel = true" x-show="requests.length" x-cloak
            class="bg-white border-2 focus:scale-95 border-primary rounded-md text-primary font-shabnam font-[500] w-60 py-3 cursor-pointer transition hover:bg-neutral-light">
            درخواست موجود کردن کالا
        </button>
    </div>
    <hr class="w-full bg-gray-200 border-none h-1.5 mb-6 mt-3 hidden lg:block">
    @if (count($product->attributes))
        <hr class="w-full text-gray-300 lg:hidden">
        <div class="w-full flex flex-col flex-nowrap gap-5 font-shabnam px-5" id="attributes" x-data="{ expanded: false }">
            <h3 class="w-fit font-[500] lg:border-b-3 pb-2 border-primary">ویژگی های
                کالا</h3>
            <div class="w-full flex flex-col overflow-hidden" x-bind:class="!expanded ? 'h-65' : 'h-auto'">
                @foreach ($product->attributes as $attribute)
                    <div
                        class="w-full flex flex-nowrap items-center border-b lg:border-none border-gray-200 last:border-none text-sm text-gray-700">
                        <span
                            class="w-1/3 px-3 bg-gray-100 min-h-13 flex items-center lg:bg-surface">{{ $attribute->key }}</span>
                        <span
                            class="w-2/3 px-3 lg:border-b border-gray-200 lg:text-black min-h-13 h-full flex items-center lg:font-[500]">{{ $attribute->pivot->value }}</span>
                    </div>
                @endforeach
            </div>
            <button class="flex items-center gap-2 text-primary text-sm font-[500] cursor-pointer"
                x-on:click="expanded = !expanded" x-show="$el.previousElementSibling.children.length > 3">
                <span x-text="expanded ? 'بستن' : 'مشاهده بیشتر'"></span>
                <i class="fa-solid fa-angle-left"></i>
            </button>
        </div>
    @endif
    @if ($product->description)
        <div class="w-full flex flex-col flex-nowrap gap-5 px-5 font-shabnam!" x-data="{ expanded: false, needsTruncate: false }">
            <h3 class="w-fit font-[500] lg:border-b-3 pb-2 border-primary">توضیحات کالا</h3>
            <p class="text-gray-700 text-[15px] leading-7 text-justify w-full overflow-hidden transition-all ease-in-out duration-500"
                x-bind:class="expanded ? ' line-clamp-none max-h-[1000px]' : 'line-clamp-3 max-h-20'" x-ref=" desc">
                {{ $product->description }}
            </p>
            <button class="flex items-center gap-2 text-primary text-sm font-[500] cursor-pointer"
                x-on:click="expanded = !expanded" x-show="needsTruncate">
                <span x-text="expanded ? 'بستن' : 'مشاهده بیشتر'"></span>
                <i class="fa-solid fa-angle-left"></i>
            </button>
        </div>
    @endif

    <div class="md:w-full max-w-[1676px] w-full -md:translate-x-12.5 p-5 flex flex-col gap-10 max-md:h-[calc(100%-132px)] max-md:top-18 fixed z-2 bg-white/50 md:bg-primary/50 md:rounded-xl max-md:right-0 overflow-auto"
        x-show="showRequestsPanel" x-transition x-cloak x-on:notify.window="showRequestsPanel = false"
        style="backdrop-filter: blur(30px)">
        <div class="w-full grid grid-cols-[repeat(auto-fill,_minmax(350px,1fr))] gap-5">
            @foreach ($product->variants as $variant)
                @if (!$variant->quantity)
                    <div
                        class="border h-20 bg-white flex flex-nowrap items-center rounded-lg gap-2 overflow-hidden border-gray-200 shadow-md pl-4">
                        <img src="{{ asset('storage/' . $variant->pattern->files[0]->path) }}"
                            class="h-full aspect-square object-cover rounded-r-lg"
                            alt="{{ $variant->pattern->files[0]->alt }}">
                        <span class="font-shabnam w-fit shrink-1 text-nowrap">{{ $variant->pattern->name }}</span>
                        <div class="flex flex-nowrap w-full justify-end items-center gap-6">
                            <div class="w-24 h-9 shrink-0 shadow border border-gray-200 rounded-md flex justify-between items-center font-shabnam"
                                wire:replace>
                                <button type="button"
                                    class="h-full aspect-square active:scale-70 active:bg-primary active:text-white rounded-full transition flex items-center justify-center font-shabnam text-primary cursor-pointer disabled:cursor-default"
                                    x-on:click="$event.preventDefault(); addRequest({{ $variant->id }})">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                                <input type="text"
                                    class="w-6 bg-transparent text-center outline-none text-primary-dark"
                                    x-model="getRequest({{ $variant->id }}).quantity" x-init="console.log(getRequest({{ $variant->id }}))">
                                <button type="button"
                                    class=" h-full aspect-square rounded-full flex items-center active:scale-70 active:bg-primary active:text-white transition justify-center font-shabnam text-primary cursor-pointer disabled:cursor-default"
                                    x-on:click="$event.preventDefault(); removeRequest({{ $variant->id }})">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="w-full flex flex-col gap-5 items-center">
            <button type="button"
                class="bg-primary rounded-md text-white font-shabnam font-[500] w-60 py-3 cursor-pointer disabled:cursor-default not-disabled:hover:bg-primary-dark transition"
                wire:click="addRequests(requests)" wire:loading.attr="disabled" wire:target="addRequests">
                <span wire:loading.remove wire:target="addRequests">ثبت درخواست</span>
                <i class="fa-solid fa-spinner animate-spin" wire:loading wire:target="addRequests"></i>
            </button>
            <button type="button" x-on:click="showRequestsPanel = false"
                class="bg-white border-2 focus:scale-95 border-primary rounded-md text-primary font-shabnam font-[500] w-60 py-3 cursor-pointer transition hover:bg-neutral-light">
                انصراف
            </button>
        </div>
    </div>

    <x-blade.customer.shopping-card-success-message />
</div>

@assets
    @vite(['resources/js/customer/slider.js'])
@endassets

@script
    <script>
        document.addEventListener("livewire:navigated", () => {
            new Swiper(".mySwiper", {
                loop: false,
                pagination: {
                    el: ".pagination",
                    clickable: true,
                    bulletClass: "size-2.5 bg-gray-100/60 rounded-full transition-all cursor-pointer",
                    bulletActiveClass: "w-5 bg-white",
                },
                navigation: {
                    nextEl: ".next",
                    prevEl: ".prev",
                    disabledClass: "opacity-30 cursor-not-allowed text-gray-400 bg-gray-50!",
                },
                watchOverflow: true,
            });
        });

        window.fitNameOnQuantity = () => {
            return {
                fontSize: 16,
                minSize: 11,

                init() {
                    this.$nextTick(() => this.check())
                    window.addEventListener('resize', () => this.reset())
                },

                reset() {
                    this.fontSize = 16
                    this.$nextTick(() => this.check())
                },

                check() {
                    const name = this.$refs.name
                    const qty = this.$refs.qty
                    if (!name || !qty) return

                    const nameLeft = name.getBoundingClientRect().left
                    const qtyRight = qty.getBoundingClientRect().right

                    // RTL collision check
                    if (nameLeft <= qtyRight && this.fontSize > this.minSize) {
                        this.fontSize--
                        this.$nextTick(() => this.check())
                    }
                }
            }
        }
    </script>
@endscript
