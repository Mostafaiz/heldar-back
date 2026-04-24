@use('App\Enums\ProductLevelsEnum')

<div class="opened-panel" x-data="{ selectedProductForDelete: ['', ''], showDeleteProductMessage: false }">
    <div class="inner-container flex! flex-col! gap-5 min-h-screen pb-5" x-init="loadProduct" x-data="{
        showGallery: false,
        currentPatternTab: 0,
        selectedImages: [],
        selectedCategories: [],
        product: null,
        selectedAttributeGroup: {
            id: null,
            name: null,
            attributes: null
        },
        loadProduct() {
            this.$wire.loadProduct().then(data => {
                this.selectedCategories = data.categories;
                this.generalInfo.name = data.name;
                this.generalInfo.englishName = data.englishName;
                this.generalInfo.slug = data.slug;
                this.generalInfo.brand = data.brand;
                this.generalInfo.description = data.description;
                this.generalInfo.level = data.level;
                this.generalInfo.price = data.patterns[0].sizes[0].price.toString();
                console.log(this.generalInfo);
                this.selectedAttributeGroup = {
                    id: data.attributeGroup.id,
                    name: data.attributeGroup.name,
                    attributes: data.attributes,
                };
                if (data.patterns[0].name === null) {
                    let pattern = data.patterns[0];
    
                    this.noPattern = {
                        local: false,
                        sizes: [],
                        guarantees: pattern.guarantees,
                        insurances: pattern.insurances,
                        noSizeVariants: {
                            price: '',
                            discount: '',
                            quantity: '',
                            sku: '',
                        },
                        images: pattern.images,
                    };
    
                    if (pattern.sizes[0].size === null) {
                        let size = pattern.sizes[0];
                        this.noPattern.noSizeVariants = {
                            price: size.price.toString(),
                            discount: size.discount ? size.discount.toString() : '',
                            quantity: size.quantity,
                            sku: size.sku,
                        };
                    } else {
                        pattern.sizes.forEach(size => {
                            this.noPattern.sizes.push({
                                id: size.id,
                                name: size.name,
                                price: size.price.toString(),
                                discount: size.discount ? size.discount.toString() : '',
                                quantity: size.quantity,
                                sku: size.sku,
                            });
                        });
                    }
                } else {
                    data.patterns.forEach(pattern => {
                        if (pattern.sizes[0].size === null) {
                            let size = pattern.sizes[0];
    
                            this.patterns.push({
                                local: false,
                                id: pattern.id,
                                name: pattern.name,
                                colors: pattern.colors,
                                sizes: [],
                                noSizeVariants: {
                                    price: size.price.toString(),
                                    discount: size.discount ? size.discount.toString() : '',
                                    quantity: size.quantity,
                                    sku: size.sku,
                                },
                                guarantees: pattern.guarantees,
                                insurances: pattern.insurances,
                                images: pattern.images,
                            });
    
                        } else {
                            let sizes = [];
                            pattern.sizes.forEach(size => {
                                sizes.push({
                                    id: size.id,
                                    name: size.size,
                                    price: size.price.toString(),
                                    discount: size.discount ? size.discount.toString() : '',
                                    quantity: size.quantity,
                                    sku: size.sku,
                                });
                            });
    
                            this.patterns.push({
                                local: false,
                                id: pattern.id,
                                name: pattern.name,
                                colors: pattern.colors,
                                sizes: sizes,
                                guarantees: pattern.guarantees,
                                insurances: pattern.insurances,
                                images: pattern.images,
                            });
                        }
                    });
                }
            });
        },
        changePatternTab(tabIndex) {
            this.currentPatternTab = tabIndex;
            this.updateImages();
        },
        updateImages() {
            this.selectedImages = this.patterns.length && this.patterns[this.currentPatternTab].images.length ? [...this.patterns[this.currentPatternTab].images.map(item => item.id)] : [];
        },
        generalInfo: {
            name: '',
            englishName: '',
            brand: '',
            slug: '',
            description: '',
            level: '',
            price: '',
        },
        sendPatterns: [],
        sendPatterns: [],
        setSendPatterns() {
            this.sendPatterns = [...this.patterns];
            if (this.sendPatterns.length) {
                this.sendPatterns.forEach((pattern, index) => {
                    if (!pattern.sizes.length) {
                        this.sendPatterns[index].sizes.push({
                            id: null,
                            price: pattern.noSizeVariants.price,
                            discount: pattern.noSizeVariants.discount,
                            quantity: pattern.noSizeVariants.quantity,
                            sku: pattern.noSizeVariants.sku,
                        });
                    }
                });
            } else {
                this.sendPatterns.push({
                    id: null,
                    name: null,
                    colors: null,
                    guarantees: this.noPattern.guarantees,
                    insurances: this.noPattern.insurances,
                    sizes: [],
                    images: this.noPattern.images,
                    validated: this.noPattern.validated,
                });
                if (this.noPattern.sizes.length) {
                    this.noPattern.sizes.forEach(size => {
                        this.sendPatterns[0].sizes.push({
                            id: size.id,
                            name: size.name,
                            price: size.price,
                            discount: size.discount,
                            quantity: size.quantity,
                            sku: size.sku,
                        });
                    });
                } else {
                    this.sendPatterns[0].sizes.push({
                        id: null,
                        name: null,
                        price: this.noPattern.noSizeVariants.price,
                        discount: this.noPattern.noSizeVariants.discount,
                        quantity: this.noPattern.noSizeVariants.quantity,
                        sku: this.noPattern.noSizeVariants.sku,
                    });
                }
            }
        },
        patterns: [],
        noPattern: {
            local: false,
            sizes: [],
            guarantees: [],
            insurances: [],
            noSizeVariants: {
                price: '',
                discount: '',
                quantity: '',
                sku: '',
            },
            images: [],
        },
        addPattern() {
            const newPattern = {
                local: true,
                id: this.patterns.length + 1,
                name: '',
                colors: [],
                sizes: [],
                guarantees: [],
                insurances: [],
                noSizeVariants: {
                    price: '',
                    discount: '',
                    quantity: '',
                    sku: ''
                },
                images: this.sharedImages && this.patterns.length ? [...this.patterns[this.currentPatternTab].images] : [],
            };
            this.patterns.push(newPattern);
            this.changePatternTab(this.patterns.length - 1);
        },
        removePattern(id) {
            this.patterns.splice(this.patterns.findIndex(p => p.id === id), 1);
            this.changePatternTab(this.currentPatternTab - 1);
        },
        sharedImages: false,
        updateSharedImages() {
            if (!this.sharedImages) return;
            console.log('hi');
            const images = this.patterns[this.currentPatternTab].images;
            this.patterns.forEach((pattern) => { pattern.images = [...images] });
        },
        allValidated: false,
        sendData() {
            this.$wire.dispatch('validate');
            document.querySelectorAll('[x-price-format]').forEach((el) => {
                const modelName = el.getAttribute('x-model');
                if (!modelName) return;
                const rawValue = el.value.replace(/,/g, '').replace(/[^\d]/g, '');
                Alpine.evaluate(el, `${modelName} = '${rawValue}'`);
            });
            this.setSendPatterns();
            this.allValidated = this.sendPatterns.every(p => p.validated);
            console.log(this.allValidated);
            this.$wire.update(this.generalInfo, this.selectedCategories, this.sendPatterns, this.selectedAttributeGroup ? { id: this.selectedAttributeGroup.id, items: this.selectedAttributeGroup.attributes.filter(attribute => { return attribute['value'] }) } : null, this.allValidated).then(() => {
                this.patterns.forEach(pattern => {
                    if (pattern.sizes[0].id === null) pattern.sizes.pop();
                });
            });
        }
    }">
        <x-blade.manager.section class="title-con h-25" x-init="console.log(selectedAttributeGroup)">
            <x-blade.manager.section-title-large :title="$pageTitle" :route="$routeName" />
            <x-blade.manager.filled-button value="تایید ویرایش" icon="check" target="update" x-on:click="sendData()" />
        </x-blade.manager.section>

        <div class="w-full flex gap-5 flex-nowrap">

            {{-- general info --}}
            <x-blade.manager.section class="w-2/3 h-91 shrink-1!">
                <x-blade.manager.section-title title="اطلاعات اصلی" />
                <div class="w-full flex gap-5">
                    <x-blade.manager.input-text title="نام محصول" name="form.name" x-model="generalInfo.name" required
                        x-on:input="generalInfo.slug = $event.target.value.trim().replace(/ /g,'-')" english />
                    <x-blade.manager.input-text title="نام انگلیسی" name="form.englishName"
                        x-model="generalInfo.englishName" english dir="ltr" />
                    <x-blade.manager.input-text title="قیمت" required name="form.price" label="تومان" dir="ltr"
                        x-model="generalInfo.price" x-price-format="generalInfo.price" />
                </div>
                {{-- <div class="flex gap-5 w-full flex-nowrap">
                    <x-blade.manager.input-text title="نامک (slug)" name="form.slug" x-ref="slugInput"
                        x-model="generalInfo.slug" required english
                        x-on:change="$event.target.value = $event.target.value.replace(/ /g, '_')" />
                    <x-blade.manager.input-text title="نام برند" name="form.brand" x-model="generalInfo.brand" />
                </div> --}}
                <x-blade.manager.textarea title="توضیحات" name="form.description" x-model="generalInfo.description" />
            </x-blade.manager.section>

            {{-- categories --}}
            <div class="w-1/3 flex flex-col gap-5">
                <div class="section w-full h-50 shrink-1! gap-5!" x-data="{
                    showSelectCategoryMenu: false,
                    searchCategories: @entangle('categories')
                }">
                    <x-blade.manager.section-title title="دسته‌بندی‌های محصول" />
                    <div class="w-full relative">
                        <x-blade.manager.input-text title="نام دسته‌بندی"
                            x-on:focus="showSelectCategoryMenu = true; $wire.loadCategories($event.target.value)"
                            x-on:input="$wire.loadCategories($event.target.value)" x-ref="categoryNameInput" />
                        @error('form.categories.*.id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <div class="w-full h-auto max-h-52 z-2 min-h-14 overflow-auto absolute border-1 border-gray-200 shadow-lg bg-white rounded-lg mt-2"
                            x-show="showSelectCategoryMenu"
                            x-on:click.outside="if ($event.target != $refs.categoryNameInput) showSelectCategoryMenu = false"
                            x-transition x-cloak>
                            <template x-if="searchCategories.length > 0">
                                <template x-for="category in searchCategories" :key="category.id">
                                    <label
                                        class="w-full h-14 px-5 flex items-center gap-5 hover:bg-gray-50 cursor-pointer font-[shabnam] transition">
                                        <input type="checkbox"
                                            x-bind:checked="selectedCategories.some(c => c.id === category.id)"
                                            x-on:change="const existingIndex = selectedCategories.findIndex(c => c.id === category.id); existingIndex >= 0 ? selectedCategories.splice(existingIndex, 1) : selectedCategories.push(category)"
                                            class="cursor-pointer size-4.5">
                                        <span class="text-gray-700 select-none" x-text="category.name"></span>
                                    </label>
                                </template>
                            </template>
                            <template x-if="searchCategories.length == 0" wire:loading.remove>
                                <div class="w-full h-14 flex items-center justify-center text-gray-500 font-[shabnam]">
                                    <span>دسته‌بندی‌ای یافت نشد.</span>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="w-full h-48 flex flex-wrap gap-3 overflow-y-auto"
                        x-bind:class="selectedCategories.length == 0 ? 'items-center justify-center' : ''">
                        <template x-if="selectedCategories.length > 0">
                            <template x-for="category in selectedCategories" :key="category.id">
                                <div
                                    class="w-fit h-10 pr-4 pl-2 bg-white box-border flex items-center gap-3 border-1 border-gray-200 shadow rounded-lg font-[shabnam]">
                                    <span class="w-full" x-text="category.name"></span>
                                    <button
                                        class="shrink-0 text-neutral rounded-full size-5 cursor-pointer flex items-center justify-center"
                                        x-on:click="selectedCategories.splice(selectedCategories.findIndex(c => c.id === category.id), 1)">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </template>
                        </template>
                        <template x-if="selectedCategories == 0">
                            <span class="font-[shabnam] text-gray-600">هیچ دسته‌بندی‌ای انتخاب نشده.</span>
                        </template>
                    </div>
                </div>

                {{-- levels --}}
                <div class="w-full h-fit section shrink-0">
                    <x-blade.manager.section-title title="سطح محصول" />
                    <div class="w-full flex flex-nowrap gap-5">
                        <div class="w-full">
                            <input type="radio" class="peer hidden" x-model="generalInfo.level" name="level"
                                value="{{ ProductLevelsEnum::GOLD->value }}">
                            <label
                                class="w-full border-2 border-gray-200 peer-checked:border-blue-700 rounded-lg peer-checked:bg-blue-50 bg-gray-50 h-13 flex items-center justify-center font-shabnam text-lg gap-3 cursor-pointer"
                                x-on:click="generalInfo.level = 'gold'">
                                طلایی
                            </label>
                        </div>
                        <div class="w-full">
                            <input type="radio" class="peer hidden" x-model="generalInfo.level" name="level"
                                value="{{ ProductLevelsEnum::SILVER->value }}">
                            <label
                                class="w-full border-2 border-gray-200 peer-checked:border-blue-700 rounded-lg peer-checked:bg-blue-50 bg-gray-50 h-13 flex items-center justify-center font-shabnam text-lg gap-3 cursor-pointer"
                                x-on:click="generalInfo.level = 'silver'">
                                نقره‌ای
                            </label>
                        </div>
                        <div class="w-full">
                            <input type="radio" class="peer hidden" x-model="generalInfo.level" name="level"
                                value="{{ ProductLevelsEnum::BORONZE->value }}">
                            <label
                                class="w-full border-2 border-gray-200 peer-checked:border-blue-700 rounded-lg peer-checked:bg-blue-50 bg-gray-50 h-13 flex items-center justify-center font-shabnam text-lg gap-3 cursor-pointer"
                                x-on:click="generalInfo.level = 'boronze'">
                                برنزی
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- patterns --}}
        <div class="section h-fit select-none">
            <div class="w-full flex flex-nowrap gap-2">
                <h1 class="title shrink-1! w-fit!">طرح‌های محصول</h1>
                <x-blade.manager.text-button value="افزودن طرح" class="shrink-0" icon="fa-solid fa-plus"
                    x-on:click="addPattern()" />
            </div>
            <div class="w-full h-full flex flex-col gap-6">
                {{-- <template x-if="!patterns.length">
                    <div class="w-full h-auto border-1 p-5 border-yellow-500 bg-yellow-100 rounded-xl font-[shabnam] text-gray-700 flex gap-5 flex-nowrap"
                        x-data="{ showPatternsWarning: true }" x-show="showPatternsWarning">
                        <div class="w-full shrink-1 flex gap-5 flex-nowrap">
                            <i class="fa-solid fa-info-circle text-yellow-500 text-lg h-fit mt-1 shrink-0"></i>
                            <span class="text-justify text-yellow-800">
                                در حال حاضر محصول شما هیچ طرحی ندارد؛ به این معنا که گزینه‌ای برای انتخاب رنگ به کاربر
                                نمایش
                                داده نمی‌شود. در این حالت، تنها می‌توانید مشخصاتی مانند گارانتی، بیمه و سایز را برای
                                محصول
                                تعریف کنید.
                            </span>
                        </div>
                        <i class="fa-solid fa-xmark text-yellow-800 mt-1 text-lg cursor-pointer shrink-0"
                            x-on:click="showPatternsWarning = false"></i>
                    </div>
                </template> --}}
                <template x-if="patterns.length">
                    <div class="flex gap-3 h-12 border-b-1 border-gray-200 overflow-x-auto">
                        <template x-for="(pattern, index) in patterns">
                            <div class="h-full w-fit px-4 shrink-0 bg-gray-50 border-b-3 border-transparent flex items-center justify-center gap-3 box-border font-[shabnam] rounded-t-xl hover:bg-gray-100 transition-[background-color] text-lg cursor-pointer font-light relative"
                                x-init="pattern.validated = true"
                                x-bind:class="{ 'border-b-blue-600': currentPatternTab == index }"
                                x-on:click="changePatternTab(index)">
                                <template x-if="pattern.colors.length != 0">
                                    <div class="flex items-center mr-2">
                                        <template x-for="color in pattern.colors" :key="color.id">
                                            <div class="size-5 rounded-full border-1 border-white outline-1 outline-gray-700 -mr-2"
                                                x-bind:style="`background-color: ${color.code}`"
                                                x-bind:title="color.name">
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="pattern.name">
                                    <span x-text="pattern.name"></span>
                                </template>
                                <template x-if="!pattern.name">
                                    <span class="text-gray-400">بدون نام</span>
                                </template>
                                <div class="absolute size-3 bg-red-600 z-2 rounded-full top-0.5 -left-0.5"
                                    x-show="!pattern.validated"></div>
                            </div>
                        </template>
                    </div>
                </template>
                <template x-if="!patterns.length">
                    <div class="flex gap-5 flex-nowrap" x-init="noPattern.validated = true"
                        x-on:validate.window="noPattern.validated = true">
                        <div class="w-full rounded-lg flex flex-col gap-5">
                            {{-- guarantees --}}
                            {{-- <div class="w-full flex flex-col gap-3"
                                x-data="{ showSelectGuaranteeMenu: false, hideMenu() { this.showSelectGuaranteeMenu = false; this.$refs.guaranteeNameInput.value = ''; }, searchGuarantees: @entangle('guarantees') }">
                                <div class="flex items-center gap-2">
                                    <span class="font-[shabnam] text-lg text-gray-800 select-none">گارانتی‌ها:</span>
                                    <div class="relative">
                                        <x-blade.manager.text-button value="افزودن" icon="plus"
                                            x-on:click="showSelectGuaranteeMenu = true; $wire.loadGuarantees(''); $nextTick(() => $refs.guaranteeNameInput.focus())"
                                            x-show="!showSelectGuaranteeMenu" />
                                        <input type="text" placeholder="جستجو..."
                                            class="h-10 w-40 outline-0 border-2 border-blue-800 font-[shabnam] font-light rounded-md px-2"
                                            x-on:input="$wire.loadGuarantees($event.target.value)"
                                            x-show="showSelectGuaranteeMenu" x-ref="guaranteeNameInput">
                                        <div class="w-100 h-auto max-h-50 min-h-14 overflow-auto absolute border-1 border-gray-200 shadow-lg bg-white rounded-lg mt-2 flex items-center flex-wrap z-10"
                                            x-show="showSelectGuaranteeMenu"
                                            x-on:click.outside="if ($event.target != $refs.guaranteeNameInput) hideMenu()"
                                            x-transition x-cloak>
                                            <template x-if="searchGuarantees.length > 0">
                                                <template x-for="guarantee in searchGuarantees">
                                                    <label
                                                        class="w-full h-fit px-5 py-3 flex items-center gap-5 hover:bg-gray-50 cursor-pointer font-[shabnam] transition">
                                                        <input type="checkbox"
                                                            x-bind:checked="noPattern.guarantees.some(g => g.id === guarantee.id)"
                                                            x-on:change="const existingIndex = noPattern.guarantees.findIndex(c => c.id === guarantee.id); existingIndex >= 0 ? noPattern.guarantees.splice(existingIndex, 1) : noPattern.guarantees.push(guarantee)"
                                                            class="cursor-pointer size-5">
                                                        <div class="w-full flex flex-col select-none">
                                                            <span class="text-gray-700 text-lg"
                                                                x-text="guarantee.name"></span>
                                                            <div class="text-sm text-gray-500">
                                                                <span x-text="guarantee.provider"></span>
                                                                -
                                                                <span x-text="guarantee.duration"></span>
                                                                ماهه
                                                            </div>
                                                        </div>
                                                    </label>
                                                </template>
                                            </template>
                                            <template x-if="searchGuarantees.length == 0">
                                                <span
                                                    class="w-full h-full flex items-center justify-center font-[shabnam] text-gray-500">
                                                    هیچ گارانتی‌ای یافت نشد.
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <template x-if="noPattern.guarantees.length != 0">
                                    <div class="w-full h-fit flex flex-wrap gap-5 items-center">
                                        <template x-for="guarantee in noPattern.guarantees" :key="guarantee.id">
                                            <div
                                                class="w-full max-w-80 h-fit border-1 border-gray-200 shadow rounded-lg font-[shabnam] pr-4 pl-7 py-2 text-lg font-medium flex items-center flex-nowrap">
                                                <div class="flex flex-col gap-1 w-full shrink-1">
                                                    <div class="flex w-full items-center gap-2">
                                                        <span class="w-fit text-black" x-text="guarantee.name"></span>
                                                        <template x-if="guarantee.provider">
                                                            <div class="text-gray-500 text-sm font-light">
                                                                (<span class="" x-text="guarantee.provider"></span>)
                                                            </div>
                                                        </template>
                                                    </div>
                                                    <div
                                                        class="flex items-center gap-4 text-sm text-gray-500 font-normal">
                                                        <span>
                                                            <i class="fa-solid fa-clock"></i>
                                                            <span x-text="guarantee.duration"></span>
                                                            ماهه
                                                        </span>
                                                        <span>
                                                            <i class="fa-solid fa-coins"></i>
                                                            <span
                                                                x-data="{ price: Intl.NumberFormat('fa-IR').format(guarantee.price) }"
                                                                x-text="price"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <i class="fa-solid fa-xmark text-gray-500 shrink-0 cursor-pointer"
                                                    x-on:click="noPattern.guarantees.splice(noPattern.guarantees.findIndex(g => g.id === guarantee.id), 1)"></i>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div> --}}

                            {{-- <div class="w-full h-1 rounded-full border-b-1 border-gray-300 border-dashed"></div>
                            --}}

                            {{-- insurances --}}
                            {{-- <div class="w-full flex flex-col gap-3"
                                x-data="{ showSelectInsuranceMenu: false, hideInsuranceMenu() { this.showSelectInsuranceMenu = false; this.$refs.insuranceNameInput.value = ''; }, searchInsurances: @entangle('insurances') }">
                                <div class="flex items-center gap-2">
                                    <span class="font-[shabnam] text-lg text-gray-800 select-none">بیمه‌ها:</span>
                                    <div class="relative">
                                        <x-blade.manager.text-button value="افزودن" icon="plus"
                                            x-on:click="showSelectInsuranceMenu = true; $wire.loadInsurances(''); $nextTick(() => $refs.insuranceNameInput.focus())"
                                            x-show="!showSelectInsuranceMenu" />
                                        <input type="text" placeholder="جستجو..."
                                            class="h-10 w-40 outline-0 border-2 border-blue-800 font-[shabnam] font-light rounded-md px-2"
                                            x-on:input="$wire.loadInsurances($event.target.value)"
                                            x-show="showSelectInsuranceMenu" x-ref="insuranceNameInput">
                                        <div class="w-100 h-auto max-h-50 min-h-14 overflow-auto absolute border-1 border-gray-200 shadow-lg bg-white rounded-lg mt-2 flex items-center flex-wrap z-10"
                                            x-show="showSelectInsuranceMenu"
                                            x-on:click.outside="if ($event.target != $refs.insuranceNameInput) hideInsuranceMenu()"
                                            x-transition x-cloak>
                                            <template x-if="searchInsurances.length > 0">
                                                <template x-for="insurance in searchInsurances">
                                                    <label
                                                        class="w-full h-fit px-5 py-3 flex items-center gap-5 hover:bg-gray-50 cursor-pointer font-[shabnam] transition">
                                                        <input type="checkbox"
                                                            x-bind:checked="noPattern.insurances && noPattern.insurances.some(i => i.id === insurance.id)"
                                                            x-on:change="if (!noPattern.insurances) noPattern.insurances = []; const existingIndex = noPattern.insurances.findIndex(i => i.id === insurance.id); existingIndex >= 0 ? noPattern.insurances.splice(existingIndex, 1) : noPattern.insurances.push(insurance)"
                                                            class="cursor-pointer size-5">
                                                        <div class="w-full flex flex-col select-none">
                                                            <span class="text-gray-700 text-lg"
                                                                x-text="insurance.name"></span>
                                                            <div class="text-sm text-gray-500">
                                                                <span x-text="insurance.provider"></span>
                                                                -
                                                                <span x-text="insurance.duration"></span>
                                                                ماهه
                                                            </div>
                                                        </div>
                                                    </label>
                                                </template>
                                            </template>
                                            <template x-if="searchInsurances.length == 0">
                                                <span
                                                    class="w-full h-full flex items-center justify-center font-[shabnam] text-gray-500">هیچ
                                                    بیمه‌ای یافت نشد.
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <template x-if="noPattern.insurances.length">
                                    <div class="w-full h-fit flex flex-wrap gap-5 items-center">
                                        <template x-for="insurance in noPattern.insurances" :key="insurance.id">
                                            <div
                                                class="w-full max-w-80 h-fit border-1 border-gray-200 shadow rounded-lg font-[shabnam] pr-4 pl-7 py-2 text-lg font-medium flex items-center flex-nowrap">
                                                <div class="flex flex-col gap-1 w-full shrink-1">
                                                    <div class="flex w-full items-center gap-2">
                                                        <span class="w-fit text-black" x-text="insurance.name"></span>
                                                        <template x-if="insurance.provider">
                                                            <div class="text-gray-500 text-sm font-light">
                                                                (<span class="" x-text="insurance.provider"></span>)
                                                            </div>
                                                        </template>
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <span class="text-sm text-gray-500">
                                                            <i class="fa-solid fa-clock"></i>
                                                            <span x-text="insurance.duration"></span>
                                                            ماهه
                                                        </span>
                                                        <span class="text-sm text-gray-500">
                                                            <i class="fa-solid fa-coins"></i>
                                                            <span
                                                                x-data="{ price: Intl.NumberFormat('fa-IR').format(insurance.price) }"
                                                                x-text="price"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <i class="fa-solid fa-xmark text-gray-500 shrink-0 cursor-pointer"
                                                    x-on:click="noPattern.insurances.splice(noPattern.insurances.findIndex(i => i.id === insurance.id), 1)"></i>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div> --}}

                            {{-- <div class="w-full h-1 rounded-full border-b-1 border-gray-300 border-dashed"></div>
                            --}}

                            {{-- sizes --}}
                            {{-- <div class="w-full h-auto flex flex-col gap-2 flex-nowrap" x-data="{
                                    showSelectSizeMenu: false,
                                    searchSizes: @entangle('sizes'),
                                    showMenu() { this.showSelectSizeMenu = true; $wire.loadSizes($refs.sizeNameInput.value) },
                                    hideMenu() { this.showSelectSizeMenu = false; $refs.sizeNameInput.value = '' },
                                    removeSize(id) { noPattern.sizes.splice(noPattern.sizes.findIndex(c => c.id === id), 1); },
                                }">
                                <div class="w-full h-fit flex gap-2 flex-wrap items-center" x-data="{  }">
                                    <span class="font-[shabnam] text-lg text-gray-800">سایزها:</span>
                                    <template x-for="size in noPattern.sizes" :key="size.id">
                                        <div
                                            class="w-auto h-10 px-2.5 border-1 border-gray-200 shadow flex items-center justify-center gap-2.5 rounded-lg">
                                            <span x-text="size.name" class="font-[shabnam] font-light"></span>
                                            <i class="fa-solid fa-xmark text-gray-500 cursor-pointer"
                                                x-on:click="removeSize(size.id)"></i>
                                        </div>
                                    </template>
                                    <div class="w-fit h-fit relative">
                                        <x-blade.manager.text-button value="افزودن" icon="plus"
                                            x-on:click="showSelectSizeMenu = true; $wire.loadSizes(''); $nextTick(() => $refs.sizeNameInput.focus())"
                                            x-show="!showSelectSizeMenu" />
                                        <input type="text" placeholder="جستجو..."
                                            class="h-10 w-22 outline-0 border-2 border-blue-800 font-[shabnam] font-light rounded-md px-2"
                                            x-on:input="$wire.loadSizes($event.target.value)"
                                            x-on:keydown.enter="const size = searchSizes.find(c => c.name == $event.target.value); if (size !== undefined && noPattern.sizes.findIndex(c => c.name === $event.target.value) == -1) noPattern.sizes.push({ id: size.id, name: size.name, price: '', discount: '', quantity: '', sku: '' }); showSelectSizeMenu = false; $refs.sizeNameInput.value = ''"
                                            x-on:keydown.escape="showSelectSizeMenu = false; $refs.sizeNameInput.value = ''"
                                            x-show="showSelectSizeMenu" x-ref="sizeNameInput">
                                        <div class="w-50 h-auto max-h-30 min-h-14 overflow-auto absolute border-1 border-gray-200 shadow-lg bg-white rounded-lg flex flex-col flex-nowrap box-border mt-2 z-10"
                                            x-show="showSelectSizeMenu"
                                            x-on:click.outside="if ($event.target != $refs.sizeNameInput) { showSelectSizeMenu = false; $refs.sizeNameInput.value = '' }"
                                            x-transition x-cloak>
                                            <template x-if="searchSizes.length != 0">
                                                <template x-for="size in searchSizes" :key="size.id">
                                                    <label
                                                        class="w-full h-14 px-5 flex items-center gap-5 hover:bg-gray-50 cursor-pointer font-[shabnam] transition">
                                                        <input type="checkbox"
                                                            x-bind:checked="noPattern.sizes.some(c => c.id === size.id)"
                                                            x-on:change="const existingIndex = noPattern.sizes.findIndex(c => c.id === size.id); existingIndex >= 0 ? noPattern.sizes.splice(existingIndex, 1) : noPattern.sizes.push({ id: size.id, name: size.name, price: '', discount: '', quantity: '', sku: '' })"
                                                            class="cursor-pointer size-4.5">
                                                        <span class="text-gray-700 select-none"
                                                            x-text="size.name"></span>
                                                    </label>
                                                </template>
                                            </template>
                                            <template x-if="searchSizes.length == 0">
                                                <span
                                                    class="w-full h-14 flex justify-center items-center font-[shabnam] text-gray-500">
                                                    هیچ سایزی یافت نشد.
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <label
                                    class="w-fit flex items-center gap-2 font-[shabnam] text-gray-600 text-sm cursor-pointer"
                                    x-show="noPattern.sizes.length">
                                    <input type="checkbox" class="cursor-pointer">
                                    <span>قیمت فروش یکسان برای سایزها</span>
                                </label>
                            </div> --}}

                            {{-- <div class="w-full h-1 rounded-full border-b-1 border-gray-300 border-dashed"></div>
                            --}}

                            {{-- prices --}}
                            <div class="w-full flex flex-col gap-5 flex-nowrap" x-data="{ currentSizeTab: 0 }">
                                <x-blade.manager.section-title title="مشخصات فروش" class="select-none" />
                                <template x-if="noPattern.sizes.length">
                                    <div class="w-full h-fit font-[shabnam]">
                                        <div class="w-full h-10 flex flex-nowrap gap-2 z-2">
                                            <template x-for="(size, index) in noPattern.sizes">
                                                <div class="w-fit h-full flex items-center justify-center px-3 border-1 border-gray-200 rounded-t-xl cursor-pointer select-none bg-gray-50"
                                                    x-bind:class="currentSizeTab == index ? 'border-b-white bg-white!' : ''"
                                                    x-on:click="currentSizeTab = index" x-text="size.name">
                                                </div>
                                            </template>
                                        </div>
                                        <template x-for="(size, index) in noPattern.sizes">
                                            <div class="border-1 border-gray-200 rounded-lg rounded-tr-none w-full h-auto flex flex-col flex-nowrap p-5 gap-5 -mt-[1px]"
                                                x-show="currentSizeTab == index">
                                                {{-- <div class="w-full flex flex-col gap-5">
                                                    <div class="w-full flex gap-5 flex-nowrap shrink-0">
                                                        <div class="w-full" x-data="{ errorMessage: '' }"
                                                            x-on:validate.window="if (!size.price.trim()) { errorMessage = 'قیمت الزامی می‌باشد.'; noPattern.validated = false } else errorMessage = ''">
                                                            <x-blade.manager.input-text inputmode="numeric"
                                                                title="قیمت" class="hide-arrows" dir="ltr"
                                                                x-model="size.price" required label="تومان"
                                                                x-ref="priceInput" x-price-format="size.price" />
                                                            <span class="error-message" x-text="errorMessage"></span>
                                                        </div>
                                                        <div class="w-full" x-data="{ errorMessage: '' }"
                                                            x-on:validate.window="if (size.discount.replace(/,/g, '').replace(/[^\d]/g, '') > size.price.replace(/,/g, '').replace(/[^\d]/g, '')) { errorMessage = 'تخفیف نمی‌تواند بیشتر از قیمت محصول.'; noPattern.validated = false } else errorMessage = ''">
                                                            <x-blade.manager.input-text inputmode="numeric"
                                                                title="تخفیف" class="hide-arrows" dir="ltr"
                                                                x-model="size.discount" label="تومان"
                                                                x-price-format="size.discount" />
                                                            <span class="error-message" x-text="errorMessage"></span>
                                                        </div>
                                                    </div>
                                                    <div class="font-[shabnam] flex items-center gap-2"
                                                        x-show="size.price && size.discount">
                                                        <span class="text-gray-700">قیمت نهایی:</span>
                                                        <span class="text-xl" x-data="{ finalPrice: 0 }"
                                                            x-init="$watch('size.price', value => finalPrice = Intl.NumberFormat('fa-IR').format(size.price.replace(/,/g, '').replace(/[^\d]/g, '') - size.discount.replace(/,/g, '').replace(/[^\d]/g, ''))); $watch('size.discount', value => finalPrice = Intl.NumberFormat('fa-IR').format(size.price.replace(/,/g, '').replace(/[^\d]/g, '') - size.discount.replace(/,/g, '').replace(/[^\d]/g, '')))"
                                                            x-text="finalPrice"></span>
                                                        <span class="text-gray-400 text-sm">تومان</span>
                                                    </div>
                                                </div> --}}
                                                <div class="w-full flex items-center gap-5 flex-nowrap">
                                                    <div class="w-full" x-data="{ errorMessage: '' }"
                                                        x-on:validate.window="if (!size.quantity.trim()) { errorMessage = 'موجودی الزامی می‌باشد.'; pattern.validated = true }">
                                                        <x-blade.manager.input-text type="text" inputmode="numeric"
                                                            dir="ltr" title="موجودی کالا" min="0"
                                                            x-model="size.quantity" required
                                                            x-only-digits="size.quantity" />
                                                        <span class="error-message" x-ref="errorMessage"
                                                            x-text="errorMessage">hi</span>
                                                    </div>
                                                    <x-blade.manager.input-text title="کد کالا (SKU)"
                                                        x-model="size.sku" dir="ltr" english />
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!noPattern.sizes.length">
                                    <div class="w-full h-fit font-[shabnam]">
                                        <div class="w-full h-auto flex flex-col flex-nowrap gap-5 -mt-[1px]">
                                            {{-- <div class="w-full flex flex-col gap-5">
                                                <div class="w-full flex gap-5 flex-nowrap shrink-0">
                                                    <div class="w-full" x-data="{ errorMessage: '' }"
                                                        x-on:validate.window="if (!noPattern.noSizeVariants.price.trim()) errorMessage = 'قیمت الزامی می‌باشد.'; else errorMessage = ''">
                                                        <x-blade.manager.input-text inputmode="numeric" title="قیمت"
                                                            class="hide-arrows" dir="ltr"
                                                            x-model="noPattern.noSizeVariants.price" required
                                                            label="تومان" x-ref="priceInput"
                                                            x-price-format="noPattern.noSizeVariants.price" />
                                                        <span class="error-message" x-text="errorMessage"></span>
                                                    </div>
                                                    <div class="w-full" x-data="{ errorMessage: '' }"
                                                        x-on:validate.window="if (noPattern.noSizeVariants.discount.replace(/,/g, '').replace(/[^\d]/g, '') > noPattern.noSizeVariants.price.replace(/,/g, '').replace(/[^\d]/g, '')) errorMessage = 'تخفیف نمی‌تواند بیشتر از قیمت محصول.'; else errorMessage = ''">
                                                        <x-blade.manager.input-text inputmode="numeric" title="تخفیف"
                                                            class="hide-arrows" dir="ltr"
                                                            x-model="noPattern.noSizeVariants.discount" label="تومان"
                                                            x-price-format="noPattern.noSizeVariants.discount" />
                                                        <span class="error-message" x-text="errorMessage"></span>
                                                    </div>
                                                </div>
                                                <div class="font-[shabnam] flex items-center gap-2"
                                                    x-show="noPattern.noSizeVariants.price && noPattern.noSizeVariants.discount">
                                                    <span class="text-gray-700">قیمت نهایی:</span>
                                                    <span class="text-xl" x-data="{ finalPrice: 0 }"
                                                        x-init="$watch('noPattern.noSizeVariants.price', value => finalPrice = Intl.NumberFormat('fa-IR').format(noPattern.noSizeVariants.price.replace(/,/g, '').replace(/[^\d]/g, '') - noPattern.noSizeVariants.discount.replace(/,/g, '').replace(/[^\d]/g, ''))); $watch('noPattern.noSizeVariants.discount', value => finalPrice = Intl.NumberFormat('fa-IR').format(noPattern.noSizeVariants.price.replace(/,/g, '').replace(/[^\d]/g, '') - noPattern.noSizeVariants.discount.replace(/,/g, '').replace(/[^\d]/g, '')))"
                                                        x-text="finalPrice"></span>
                                                    <span class="text-gray-400 text-sm">تومان</span>
                                                </div>
                                            </div> --}}
                                            <div class="w-full flex gap-5 flex-nowrap shrink-0">
                                                <div class="w-full" x-data="{ errorMessage: '' }"
                                                    x-on:validate.window="if (!noPattern.noSizeVariants.quantity.trim()) { errorMessage = 'موجودی الزامی می‌باشد.'; noPattern.noSizeVariants.validated = false } else errorMessage = ''">
                                                    <x-blade.manager.input-text type="text" inputmode="numeric"
                                                        dir="ltr" title="موجودی کالا" min="0"
                                                        x-model="noPattern.noSizeVariants.quantity" required
                                                        x-only-digits="noPattern.noSizeVariants.quantity" />
                                                    <span class="error-message" x-text="errorMessage"></span>
                                                </div>
                                                <x-blade.manager.input-text title="کد کالا (SKU)"
                                                    x-model="noPattern.noSizeVariants.sku" dir="ltr" english />
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                        </div>

                        <div class="w-1 h-full rounded-full bg-gray-100"></div>

                        {{-- images --}}
                        <div class="w-full h-auto min-h-32 rounded-lg gap-5 flex flex-nowrap flex-col"
                            x-data="{ showError: false }"
                            x-on:validate.window="if (!noPattern.images.length) { showError = true; noPattern.validated = false } else showError = false">
                            <span class=" font-[shabnam] text-lg text-gray-800">
                                تصاویر:
                                <span class="text-red-700 font-normal">*</span>
                            </span>
                            <span class="error-message -mt-4!" x-show="showError">حداقل یک تصویر الزامی است.</span>
                            <div class="w-full h-auto min-h-50 grid grid-cols-[repeat(auto-fill,_minmax(170px,_1fr))] gap-5 border-1 border-gray-400 bg-gray-50 rounded-xl p-5"
                                x-on:set-selected-images.window="$wire.loadImages(selectedImages).then(images => { noPattern.images = [...images] })">
                                <template x-for="image in noPattern.images" :key="image.id">
                                    <div class="aspect-square relative">
                                        <img x-bind:src="`/storage/${image.path}`"
                                            class="size-full object-contain border-2 rounded-xl border-blue-800 bg-blue-50 gap-2 items-center justify-center" />
                                        <div class="absolute size-6 top-3 right-3 rounded-full bg-red-600 flex items-center justify-center cursor-pointer"
                                            x-on:click="noPattern.images.splice(noPattern.images.findIndex(i => i.id == image.id), 1); console.log(patterns); updateImages(); sharedImages ? updateSharedImages() : null">
                                            <i class="fa-solid fa-xmark text-white"></i>
                                        </div>
                                    </div>
                                </template>
                                <div class="aspect-square border-2 rounded-xl border-blue-800 bg-blue-50 border-dashed flex flex-col gap-2 items-center justify-center cursor-pointer"
                                    x-on:click="showGallery = true">
                                    <i class="fa-solid fa-plus-circle text-6xl text-blue-800"></i>
                                    <span class="text-blue-800 font-[shabnam] select-none">
                                        افزودن تصویر
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-if="patterns.length">
                    <template x-for="(pattern, index) in patterns">
                        <div class="flex flex-col gap-5 flex-nowrap" x-show="currentPatternTab == index" wire:ignore
                            x-on:validate.window="pattern.validated = true">
                            <div class="flex gap-5 flex-nowrap">
                                <div class="w-full rounded-lg flex flex-col gap-5" x-data="{ showErrorPatternName: false }"
                                    x-on:validate.window="if (!pattern.name.length) { showErrorPatternName = true; pattern.validated = false; console.log('name') } else showErrorPatternName = false">
                                    <div class="w-full h-10 flex flex-nowrap justify-between" x-data="{
                                        editing: false,
                                        editPatternName() {
                                            this.editing = true;
                                            $nextTick(() => $refs.patternNameInput.select())
                                        }
                                    }"
                                        x-bind:class="{ 'w-full': editing }">
                                        <div class="flex w-fit h-10 gap-3 flex-nowrap items-center">
                                            <template x-if="pattern.name">
                                                <h2 class="peer font-[shabnam] m-0 p-0 text-2xl font-medium select-none cursor-text"
                                                    x-text="pattern.name" x-on:dblclick="editPatternName"
                                                    x-show="!editing">
                                                </h2>
                                            </template>
                                            <template x-if="!pattern.name">
                                                <h2 class="peer font-[shabnam] m-0 p-0 text-2xl font-medium select-none cursor-text text-gray-300"
                                                    x-on:click="editPatternName" x-show="!editing">
                                                    بدون نام
                                                </h2>
                                            </template>
                                            <span class="text-red-700 font-[shabnam] text-2xl -mr-1"
                                                x-show="!editing">*</span>
                                            <i class="fa-solid fa-pen text-gray-400 text-lg cursor-pointer"
                                                x-on:click="editPatternName" x-show="!editing"></i>
                                            <input type="text"
                                                class="w-full h-full font-[shabnam] text-2xl font-medium outline-0 border-2 border-blue-800 rounded-md box-border"
                                                x-model="pattern.name" x-on:blur="editing = false" x-show="editing"
                                                x-ref="patternNameInput" x-on:keydown.enter="editing = false" />
                                        </div>
                                        <x-blade.manager.text-button value="حذف طرح"
                                            class="text-red-700! hover:bg-red-50!" icon="trash-can"
                                            x-on:click="removePattern(pattern.id)" />
                                    </div>
                                    <span class="error-message" x-show="showErrorPatternName">نام طرح الزامی
                                        می‌باشد.</span>

                                    {{-- colors --}}
                                    <div class="w-full h-auto flex flex-col gap-1 flex-nowrap"
                                        x-data="{ showErrorColors: false }">
                                        <div class="h-fit w-full flex gap-2 flex-wrap items-center"
                                            x-data="{
                                                showSelectColorMenu: false,
                                                showMenu() {
                                                    this.showSelectColorMenu = true;
                                                    $wire.loadColors($refs.colorNameInput.value);
                                                    $nextTick(() => $refs.colorNameInput.focus())
                                                },
                                                hideMenu() {
                                                    this.showSelectColorMenu = false;
                                                    $refs.colorNameInput.value = ''
                                                },
                                                searchColors: @entangle('colors'),
                                                addColor(color) { if (pattern.colors.findIndex(c => c.name === $refs.colorNameInput.value) === -1) pattern.colors.push(color) },
                                                removeColor(id) { pattern.colors.splice(pattern.colors.findIndex(c => c.id === id), 1) }
                                            }"
                                            x-on:validate.window="if (!pattern.colors.length) { showErrorColors = true; pattern.validated = false } else showErrorColors = false;">
                                            <span class="font-[shabnam] text-lg text-gray-800">
                                                رنگ‌ها
                                                <span class="text-red-700">*</span>
                                            </span>
                                            <template x-for="color in pattern.colors">
                                                <div
                                                    class="w-auto h-10 px-2.5 border-1 border-gray-200 shadow flex items-center justify-center gap-2.5 rounded-lg">
                                                    <div class="size-6 rounded-md border-1 border-gray-50"
                                                        x-bind:style="`background-color: ${color.code}`"></div>
                                                    <span x-text="color.name"
                                                        class="font-[shabnam] font-light"></span>
                                                    <i class="fa-solid fa-xmark text-gray-500 cursor-pointer"
                                                        x-on:click="removeColor(color.id)"></i>
                                                </div>
                                            </template>
                                            <div class="w-fit h-fit relative">
                                                <x-blade.manager.text-button value="افزودن" icon="plus"
                                                    x-on:click="showMenu()" x-show="!showSelectColorMenu" />
                                                <input type="text" placeholder="جستجو..."
                                                    class="h-10 w-22 outline-0 border-2 border-blue-800 font-[shabnam] font-light rounded-md px-2"
                                                    x-on:input="$wire.loadColors($event.target.value)"
                                                    x-on:keydown.enter="const color = searchColors.find(c => c.name == $event.target.value); if (color !== undefined) addColor(color); hideMenu()"
                                                    x-on:keydown.escape="hideMenu()" x-show="showSelectColorMenu"
                                                    x-ref="colorNameInput">
                                                <div class="w-60 h-auto max-h-30 min-h-14 overflow-auto absolute border-1 border-gray-200 shadow-lg bg-white rounded-lg mt-2 flex items-center flex-wrap p-4 gap-3 box-border z-10"
                                                    x-on:click.outside="if ($event.target != $refs.colorNameInput) hideMenu()"
                                                    x-show="showSelectColorMenu" x-transition x-cloak>
                                                    <template x-if="searchColors.length != 0">
                                                        <template x-for="color in searchColors"
                                                            :key="color.id">
                                                            <label
                                                                class="group relative size-6 rounded-full border-2 border-white outline-2 outline-gray-700 box-border cursor-pointer"
                                                                x-bind:style="`background-color: ${color.code}`"
                                                                x-bind:title="color.name">
                                                                <input type="checkbox"
                                                                    class="hidden [&:checked+div]:visible"
                                                                    x-bind:checked="pattern.colors.some(c => c.id === color.id)"
                                                                    x-on:change="const existingIndex = pattern.colors.findIndex(c => c.id === color.id); existingIndex >= 0 ? removeColor(color.id) : addColor(color)">
                                                                <div
                                                                    class="absolute invisible bg-blue-600 size-4 rounded-full -right-2 -bottom-2 flex items-center justify-center">
                                                                    <i
                                                                        class="fa-solid fa-check text-white text-[10px]"></i>
                                                                </div>
                                                            </label>
                                                        </template>
                                                    </template>
                                                    <template x-if="searchColors.length == 0">
                                                        <span
                                                            class="w-full h-full flex justify-center items-center font-[shabnam] text-gray-500">
                                                            هیچ رنگی یافت نشد.
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="error-message" x-show="showErrorColors">
                                            حداقل یک رنگ الزامی می‌باشد.
                                        </span>
                                    </div>

                                    <div class="w-full h-1 rounded-full border-b-1 border-gray-300 border-dashed">
                                    </div>

                                    {{-- guarantees --}}
                                    {{-- <div class="w-full flex flex-col gap-3"
                                        x-data="{ showSelectGuaranteeMenu: false, hideMenu() { this.showSelectGuaranteeMenu = false; this.$refs.guaranteeNameInput.value = ''; }, searchGuarantees: @entangle('guarantees') }">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-[shabnam] text-lg text-gray-800 select-none">گارانتی‌ها:</span>
                                            <div class="relative">
                                                <x-blade.manager.text-button value="افزودن" icon="plus"
                                                    x-on:click="showSelectGuaranteeMenu = true; $wire.loadGuarantees(''); $nextTick(() => $refs.guaranteeNameInput.focus())"
                                                    x-show="!showSelectGuaranteeMenu" />
                                                <input type="text" placeholder="جستجو..."
                                                    class="h-10 w-40 outline-0 border-2 border-blue-800 font-[shabnam] font-light rounded-md px-2"
                                                    x-on:input="$wire.loadGuarantees($event.target.value)"
                                                    x-show="showSelectGuaranteeMenu" x-ref="guaranteeNameInput">
                                                <div class="w-100 h-auto max-h-50 min-h-14 overflow-auto absolute border-1 border-gray-200 shadow-lg bg-white rounded-lg mt-2 flex items-center flex-wrap z-10"
                                                    x-show="showSelectGuaranteeMenu"
                                                    x-on:click.outside="if ($event.target != $refs.guaranteeNameInput) hideMenu()"
                                                    x-transition x-cloak>
                                                    <template x-if="searchGuarantees.length > 0">
                                                        <template x-for="guarantee in searchGuarantees">
                                                            <label
                                                                class="w-full h-fit px-5 py-3 flex items-center gap-5 hover:bg-gray-50 cursor-pointer font-[shabnam] transition">
                                                                <input type="checkbox"
                                                                    x-bind:checked="pattern.guarantees.some(g => g.id === guarantee.id)"
                                                                    x-on:change="const existingIndex = pattern.guarantees.findIndex(c => c.id === guarantee.id); existingIndex >= 0 ? pattern.guarantees.splice(existingIndex, 1) : pattern.guarantees.push(guarantee)"
                                                                    class="cursor-pointer size-5">
                                                                <div class="w-full flex flex-col select-none">
                                                                    <span class="text-gray-700 text-lg"
                                                                        x-text="guarantee.name"></span>
                                                                    <div class="text-sm text-gray-500">
                                                                        <span x-text="guarantee.provider"></span>
                                                                        -
                                                                        <span x-text="guarantee.duration"></span>
                                                                        ماهه
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </template>
                                                    </template>
                                                    <template x-if="searchGuarantees.length == 0">
                                                        <span
                                                            class="w-full h-full flex items-center justify-center font-[shabnam] text-gray-500">هیچ
                                                            گارانتی‌ای یافت نشد.</span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <template x-if="pattern.guarantees.length != 0">
                                            <div class="w-full h-fit flex flex-wrap gap-5 items-center">
                                                <template x-for="guarantee in pattern.guarantees" :key="guarantee.id">
                                                    <div
                                                        class="w-full max-w-80 h-fit border-1 border-gray-200 shadow rounded-lg font-[shabnam] pr-4 pl-7 py-2 text-lg font-medium flex items-center flex-nowrap">
                                                        <div class="flex flex-col gap-1 w-full shrink-1">
                                                            <div class="flex w-full items-center gap-2">
                                                                <span class="w-fit text-black"
                                                                    x-text="guarantee.name"></span>
                                                                <template x-if="guarantee.provider">
                                                                    <div class="text-gray-500 text-sm font-light">
                                                                        (<span class=""
                                                                            x-text="guarantee.provider"></span>)
                                                                    </div>
                                                                </template>
                                                            </div>
                                                            <div
                                                                class="flex items-center gap-4 text-sm text-gray-500 font-normal">
                                                                <span>
                                                                    <i class="fa-solid fa-clock"></i>
                                                                    <span x-text="guarantee.duration"></span>
                                                                    ماهه
                                                                </span>
                                                                <span>
                                                                    <i class="fa-solid fa-coins"></i>
                                                                    <span
                                                                        x-data="{ price: Intl.NumberFormat('fa-IR').format(guarantee.price) }"
                                                                        x-text="price"></span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <i class="fa-solid fa-xmark text-gray-500 shrink-0 cursor-pointer"
                                                            x-on:click="pattern.guarantees.splice(pattern.guarantees.findIndex(g => g.id === guarantee.id), 1)"></i>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div> --}}

                                    {{-- <div class="w-full h-1 rounded-full border-b-1 border-gray-300 border-dashed">
                                    </div> --}}

                                    {{-- insurances --}}
                                    {{-- <div class="w-full flex flex-col gap-3"
                                        x-data="{ showSelectInsuranceMenu: false, hideInsuranceMenu() { this.showSelectInsuranceMenu = false; this.$refs.insuranceNameInput.value = ''; }, searchInsurances: @entangle('insurances') }">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-[shabnam] text-lg text-gray-800 select-none">بیمه‌ها:</span>
                                            <div class="relative">
                                                <x-blade.manager.text-button value="افزودن" icon="plus"
                                                    x-on:click="showSelectInsuranceMenu = true; $wire.loadInsurances(''); $nextTick(() => $refs.insuranceNameInput.focus())"
                                                    x-show="!showSelectInsuranceMenu" />
                                                <input type="text" placeholder="جستجو..."
                                                    class="h-10 w-40 outline-0 border-2 border-blue-800 font-[shabnam] font-light rounded-md px-2"
                                                    x-on:input="$wire.loadInsurances($event.target.value)"
                                                    x-show="showSelectInsuranceMenu" x-ref="insuranceNameInput">
                                                <div class="w-100 h-auto max-h-50 min-h-14 overflow-auto absolute border-1 border-gray-200 shadow-lg bg-white rounded-lg mt-2 flex items-center flex-wrap z-10"
                                                    x-show="showSelectInsuranceMenu"
                                                    x-on:click.outside="if ($event.target != $refs.insuranceNameInput) hideInsuranceMenu()"
                                                    x-transition x-cloak>
                                                    <template x-if="searchInsurances.length > 0">
                                                        <template x-for="insurance in searchInsurances">
                                                            <label
                                                                class="w-full h-fit px-5 py-3 flex items-center gap-5 hover:bg-gray-50 cursor-pointer font-[shabnam] transition">
                                                                <input type="checkbox"
                                                                    x-bind:checked="pattern.insurances && pattern.insurances.some(i => i.id === insurance.id)"
                                                                    x-on:change="if (!pattern.insurances) pattern.insurances = []; const existingIndex = pattern.insurances.findIndex(i => i.id === insurance.id); existingIndex >= 0 ? pattern.insurances.splice(existingIndex, 1) : pattern.insurances.push(insurance)"
                                                                    class="cursor-pointer size-5">
                                                                <div class="w-full flex flex-col select-none">
                                                                    <span class="text-gray-700 text-lg"
                                                                        x-text="insurance.name"></span>
                                                                    <div class="text-sm text-gray-500">
                                                                        <span x-text="insurance.provider"></span>
                                                                        -
                                                                        <span x-text="insurance.duration"></span>
                                                                        ماهه
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </template>
                                                    </template>
                                                    <template x-if="searchInsurances.length == 0">
                                                        <span
                                                            class="w-full h-full flex items-center justify-center font-[shabnam] text-gray-500">هیچ
                                                            بیمه‌ای یافت نشد.
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <template x-if="pattern.insurances.length">
                                            <div class="w-full h-fit flex flex-wrap gap-5 items-center">
                                                <template x-for="insurance in pattern.insurances" :key="insurance.id">
                                                    <div
                                                        class="w-full max-w-80 h-fit border-1 border-gray-200 shadow rounded-lg font-[shabnam] pr-4 pl-7 py-2 text-lg font-medium flex items-center flex-nowrap">
                                                        <div class="flex flex-col gap-1 w-full shrink-1">
                                                            <div class="flex w-full items-center gap-2">
                                                                <span class="w-fit text-black"
                                                                    x-text="insurance.name"></span>
                                                                <template x-if="insurance.provider">
                                                                    <div class="text-gray-500 text-sm font-light">
                                                                        (<span class=""
                                                                            x-text="insurance.provider"></span>)
                                                                    </div>
                                                                </template>
                                                            </div>
                                                            <div class="flex items-center gap-4">
                                                                <span class="text-sm text-gray-500">
                                                                    <i class="fa-solid fa-clock"></i>
                                                                    <span x-text="insurance.duration"></span>
                                                                    ماهه
                                                                </span>
                                                                <span class="text-sm text-gray-500">
                                                                    <i class="fa-solid fa-coins"></i>
                                                                    <span
                                                                        x-data="{ price: Intl.NumberFormat('fa-IR').format(insurance.price) }"
                                                                        x-text="price"></span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <i class="fa-solid fa-xmark text-gray-500 shrink-0 cursor-pointer"
                                                            x-on:click="pattern.insurances.splice(pattern.insurances.findIndex(i => i.id === insurance.id), 1)"></i>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div> --}}

                                    {{-- <div class="w-full h-1 rounded-full border-b-1 border-gray-300 border-dashed">
                                    </div> --}}

                                    {{-- sizes --}}
                                    {{-- <div class="w-full h-auto flex flex-col gap-2 flex-nowrap" x-data="{
                                        showSelectSizeMenu: false,
                                        searchSizes: @entangle('sizes'),
                                        showMenu() { this.showSelectSizeMenu = true; $wire.loadSizes($refs.sizeNameInput.value) },
                                        hideMenu() { this.showSelectSizeMenu = false; $refs.sizeNameInput.value = '' },
                                        removeSize(id) { pattern.sizes.splice(pattern.sizes.findIndex(c => c.id === id), 1); },
                                    }">
                                        <div class="w-full h-fit flex gap-2 flex-wrap items-center" x-data="{  }">
                                            <span class="font-[shabnam] text-lg text-gray-800">سایزها:</span>
                                            <template x-for="size in pattern.sizes" :key="size.id">
                                                <div
                                                    class="w-auto h-10 px-2.5 border-1 border-gray-200 shadow flex items-center justify-center gap-2.5 rounded-lg">
                                                    <span x-text="size.name" class="font-[shabnam] font-light"></span>
                                                    <i class="fa-solid fa-xmark text-gray-500 cursor-pointer"
                                                        x-on:click="removeSize(size.id)"></i>
                                                </div>
                                            </template>
                                            <div class="w-fit h-fit relative">
                                                <x-blade.manager.text-button value="افزودن" icon="plus"
                                                    x-on:click="showSelectSizeMenu = true; $wire.loadSizes(''); $nextTick(() => $refs.sizeNameInput.focus())"
                                                    x-show="!showSelectSizeMenu" />
                                                <input type="text" placeholder="جستجو..."
                                                    class="h-10 w-22 outline-0 border-2 border-blue-800 font-[shabnam] font-light rounded-md px-2"
                                                    x-on:input="$wire.loadSizes($event.target.value)"
                                                    x-on:keydown.enter="const size = searchSizes.find(c => c.name == $event.target.value); if (size !== undefined && pattern.sizes.findIndex(c => c.name === $event.target.value) == -1) pattern.sizes.push({ id: size.id, name: size.name, price: '', discount: '', quantity: '', sku: '' }); showSelectSizeMenu = false; $refs.sizeNameInput.value = ''"
                                                    x-on:keydown.escape="showSelectSizeMenu = false; $refs.sizeNameInput.value = ''"
                                                    x-show="showSelectSizeMenu" x-ref="sizeNameInput">
                                                <div class="w-50 h-auto max-h-30 min-h-14 overflow-auto absolute border-1 border-gray-200 shadow-lg bg-white rounded-lg flex flex-col flex-nowrap box-border mt-2 z-10"
                                                    x-show="showSelectSizeMenu"
                                                    x-on:click.outside="if ($event.target != $refs.sizeNameInput) { showSelectSizeMenu = false; $refs.sizeNameInput.value = '' }"
                                                    x-transition x-cloak>
                                                    <template x-if="searchSizes.length != 0">
                                                        <template x-for="size in searchSizes" :key="size.id">
                                                            <label
                                                                class="w-full h-14 px-5 flex items-center gap-5 hover:bg-gray-50 cursor-pointer font-[shabnam] transition">
                                                                <input type="checkbox"
                                                                    x-bind:checked="pattern.sizes.some(c => c.id === size.id)"
                                                                    x-on:change="const existingIndex = pattern.sizes.findIndex(c => c.id === size.id); existingIndex >= 0 ? pattern.sizes.splice(existingIndex, 1) : pattern.sizes.push({ id: size.id, name: size.name, price: '', discount: '', quantity: '', sku: '' })"
                                                                    class="cursor-pointer size-4.5">
                                                                <span class="text-gray-700 select-none"
                                                                    x-text="size.name"></span>
                                                            </label>
                                                        </template>
                                                    </template>
                                                    <template x-if="searchSizes.length == 0">
                                                        <span
                                                            class="w-full h-14 flex justify-center items-center font-[shabnam] text-gray-500">
                                                            هیچ سایزی یافت نشد.
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <label
                                            class="w-fit flex items-center gap-2 font-[shabnam] text-gray-600 text-sm cursor-pointer"
                                            x-show="pattern.sizes.length">
                                            <input type="checkbox" class="cursor-pointer">
                                            <span>قیمت فروش یکسان برای سایزها</span>
                                        </label>
                                    </div> --}}

                                    {{-- <div class="w-full h-1 rounded-full border-b-1 border-gray-300 border-dashed">
                                    </div> --}}

                                    {{-- prices --}}
                                    <div class="w-full flex flex-col gap-5 flex-nowrap" x-data="{ currentSizeTab: 0 }">
                                        <x-blade.manager.section-title title="مشخصات فروش" class="select-none" />
                                        <template x-if="pattern.sizes.length">
                                            <div class="w-full h-fit font-[shabnam]">
                                                <div class="w-full h-10 flex flex-nowrap gap-2 z-2">
                                                    <template x-for="(size, index) in pattern.sizes">
                                                        <div class="w-fit h-full flex items-center justify-center px-3 border-1 border-gray-200 rounded-t-xl cursor-pointer select-none bg-gray-50"
                                                            x-bind:class="currentSizeTab == index ? 'border-b-white bg-white!' : ''"
                                                            x-on:click="currentSizeTab = index" x-text="size.name">
                                                        </div>
                                                    </template>
                                                </div>
                                                <template x-for="(size, index) in pattern.sizes">
                                                    <div class="border-1 border-gray-200 rounded-lg rounded-tr-none w-full h-auto flex flex-col flex-nowrap p-5 gap-5 -mt-[1px]"
                                                        x-show="currentSizeTab == index">
                                                        {{-- <div class="w-full flex flex-col gap-5">
                                                            <div class="w-full flex gap-5 flex-nowrap shrink-0">
                                                                <div class="w-full" x-data="{ errorMessage: '' }"
                                                                    x-on:validate.window="if (!size.price.trim()) { errorMessage = 'قیمت الزامی می‌باشد.'; pattern.validated = false; console.log('price') } else errorMessage = ''">
                                                                    <x-blade.manager.input-text inputmode="numeric"
                                                                        title="قیمت" class="hide-arrows"
                                                                        dir="ltr" x-model="size.price" required
                                                                        label="تومان" x-ref="priceInput"
                                                                        x-price-format="size.price" />
                                                                    <span class="error-message"
                                                                        x-text="errorMessage"></span>
                                                                </div>
                                                                <div class="w-full" x-data="{ errorMessage: '' }"
                                                                    x-on:validate.window="if (Number(size.discount.replace(/,/g, '').replace(/[^\d]/g, '')) > Number(size.price.replace(/,/g, '').replace(/[^\d]/g, ''))) { errorMessage = 'تخفیف نمی‌تواند بیشتر از قیمت محصول.'; pattern.validated = false } else errorMessage = ''">
                                                                    <x-blade.manager.input-text inputmode="numeric"
                                                                        title="تخفیف" class="hide-arrows"
                                                                        dir="ltr" x-model="size.discount"
                                                                        label="تومان"
                                                                        x-price-format="size.discount" />
                                                                    <span class="error-message"
                                                                        x-text="errorMessage"></span>
                                                                </div>
                                                            </div>
                                                            <div class="font-[shabnam] flex items-center gap-2"
                                                                x-show="size.price && size.discount">
                                                                <span class="text-gray-700">قیمت نهایی:</span>
                                                                <span class="text-xl" x-data="{ finalPrice: 0 }"
                                                                    x-init="$watch('size.price', value => finalPrice = Intl.NumberFormat('fa-IR').format(size.price.replace(/,/g, '').replace(/[^\d]/g, '') - size.discount.replace(/,/g, '').replace(/[^\d]/g, ''))); $watch('size.discount', value => finalPrice = Intl.NumberFormat('fa-IR').format(size.price.replace(/,/g, '').replace(/[^\d]/g, '') - size.discount.replace(/,/g, '').replace(/[^\d]/g, '')))"
                                                                    x-text="finalPrice"></span>
                                                                <span class="text-gray-400 text-sm">تومان</span>
                                                            </div>
                                                        </div> --}}
                                                        <div class="w-full flex items-center gap-5 flex-nowrap">
                                                            <div class="w-full" x-data="{ errorMessage: '' }"
                                                                x-on:validate.window="if (!size.quantity.trim()) { errorMessage = 'موجودی الزامی می‌باشد.'; pattern.validated = false; console.log('quantity') } else errorMessage = ''">
                                                                <x-blade.manager.input-text type="text"
                                                                    inputmode="numeric" dir="ltr"
                                                                    title="موجودی کالا" min="0"
                                                                    x-model="size.quantity" required
                                                                    x-only-digits="size.quantity" />
                                                                <span class="error-message" x-ref="errorMessage"
                                                                    x-text="errorMessage"></span>
                                                            </div>
                                                            <div class="w-full">
                                                                <x-blade.manager.input-text title="کد کالا (SKU)"
                                                                    x-model="size.sku" dir="ltr" english />
                                                                <span class="error-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="!pattern.sizes.length">
                                            <div class="w-full h-fit font-[shabnam]">
                                                <div class="w-full h-auto flex flex-col flex-nowrap gap-5 -mt-[1px]">
                                                    {{-- <div class="w-full flex flex-col gap-5">
                                                        <div class="w-full flex gap-5 flex-nowrap shrink-0">
                                                            <div class="w-full" x-data="{ errorMessage: '' }"
                                                                x-on:validate.window="if (!pattern.noSizeVariants.price.trim()) { errorMessage = 'قیمت الزامی می‌باشد.'; pattern.validated = false; console.log('price2') } else errorMessage = ''">
                                                                <x-blade.manager.input-text inputmode="numeric"
                                                                    title="قیمت" class="hide-arrows" dir="ltr"
                                                                    x-model="pattern.noSizeVariants.price" required
                                                                    label="تومان" x-ref="priceInput"
                                                                    x-price-format="pattern.noSizeVariants.price" />
                                                                <span class="error-message"
                                                                    x-text="errorMessage"></span>
                                                            </div>
                                                            <div class="w-full" x-data="{ errorMessage: '' }"
                                                                x-on:validate.window="if (Number(pattern.noSizeVariants.discount.replace(/,/g, '').replace(/[^\d]/g, '')) > Number(pattern.noSizeVariants.price.replace(/,/g, '').replace(/[^\d]/g, ''))) { errorMessage = 'تخفیف نمی‌تواند بیشتر از قیمت محصول.'; pattern.validated = false } else errorMessage = ''">
                                                                <x-blade.manager.input-text inputmode="numeric"
                                                                    title="تخفیف" class="hide-arrows" dir="ltr"
                                                                    x-model="pattern.noSizeVariants.discount"
                                                                    label="تومان"
                                                                    x-price-format="pattern.noSizeVariants.discount" />
                                                                <span class="error-message"
                                                                    x-text="errorMessage"></span>
                                                            </div>
                                                        </div>
                                                        <div class="font-[shabnam] flex items-center gap-2"
                                                            x-show="pattern.noSizeVariants.price && pattern.noSizeVariants.discount">
                                                            <span class="text-gray-700">قیمت نهایی:</span>
                                                            <span class="text-xl" x-data="{ finalPrice: 0 }"
                                                                x-init="$watch('pattern.noSizeVariants.price', value => finalPrice = Intl.NumberFormat('fa-IR').format(pattern.noSizeVariants.price.replace(/,/g, '').replace(/[^\d]/g, '') - pattern.noSizeVariants.discount.replace(/,/g, '').replace(/[^\d]/g, ''))); $watch('pattern.noSizeVariants.discount', value => finalPrice = Intl.NumberFormat('fa-IR').format(pattern.noSizeVariants.price.replace(/,/g, '').replace(/[^\d]/g, '') - pattern.noSizeVariants.discount.replace(/,/g, '').replace(/[^\d]/g, '')))"
                                                                x-text="finalPrice"></span>
                                                            <span class="text-gray-400 text-sm">تومان</span>
                                                        </div>
                                                    </div> --}}
                                                    <div class="w-full flex items-center gap-5 flex-nowrap">
                                                        <div class="w-full" x-data="{ errorMessage: '' }"
                                                            x-on:validate.window="if (!pattern.noSizeVariants.quantity.trim()) { errorMessage = 'موجودی الزامی می‌باشد.'; pattern.validated = false; console.log('quantity2') } else errorMessage = ''">
                                                            <x-blade.manager.input-text type="text"
                                                                inputmode="numeric" dir="ltr"
                                                                title="موجودی کالا" min="0"
                                                                x-model="pattern.noSizeVariants.quantity" required
                                                                x-only-digits="pattern.noSizeVariants.quantity" />
                                                            <span class="error-message" x-ref="errorMessage"
                                                                x-text="errorMessage"></span>
                                                        </div>
                                                        <div class="w-full">
                                                            <x-blade.manager.input-text title="کد کالا (SKU)"
                                                                x-model="pattern.noSizeVariants.sku" dir="ltr"
                                                                english />
                                                            <span class="error-message"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                </div>

                                <div class="w-1 h-full rounded-full bg-gray-100"></div>

                                {{-- images --}}
                                <div class="w-full h-auto min-h-32 rounded-lg gap-5 flex flex-nowrap flex-col"
                                    x-data="{ showError: false }"
                                    x-on:validate.window="if (!pattern.images.length) { showError = true; pattern.validated = false; console.log('images')} else showError = false">
                                    <span class=" font-[shabnam] text-lg text-gray-800">
                                        تصاویر:
                                        <span class="text-red-700 font-normal">*</span>
                                    </span>
                                    <span class="error-message -mt-4!" x-show="showError">
                                        حداقل یک تصویر الزامی است.
                                    </span>
                                    <div class="w-full h-auto min-h-50 grid grid-cols-[repeat(auto-fill,_minmax(170px,_1fr))] gap-5 border-1 border-gray-400 bg-gray-50 rounded-xl p-5"
                                        x-on:set-selected-images.window="currentPatternTab == index && $wire.loadImages(selectedImages).then(images => { pattern.images = [...images]; if (sharedImages) updateSharedImages() })">
                                        <template x-for="image in pattern.images" :key="image.id">
                                            <div class="aspect-square relative">
                                                <img x-bind:src="`/storage/${image.path}`"
                                                    class="size-full object-contain border-2 rounded-xl border-blue-800 bg-blue-50 gap-2 items-center justify-center" />
                                                <div class="absolute size-6 top-3 right-3 rounded-full bg-red-600 flex items-center justify-center cursor-pointer"
                                                    x-on:click="pattern.images.splice(pattern.images.findIndex(i => i.id == image.id), 1); console.log(patterns); updateImages(); sharedImages ? updateSharedImages() : null">
                                                    <i class="fa-solid fa-xmark text-white"></i>
                                                </div>
                                            </div>
                                        </template>
                                        <div class="aspect-square border-2 rounded-xl border-blue-800 bg-blue-50 border-dashed flex flex-col gap-2 items-center justify-center cursor-pointer"
                                            x-on:click="showGallery = true">
                                            <i class="fa-solid fa-plus-circle text-6xl text-blue-800"></i>
                                            <span class="text-blue-800 font-[shabnam] select-none">
                                                افزودن تصویر
                                            </span>
                                        </div>
                                    </div>
                                    <div class="w-full h-auto flex gap-5 flex-nowrap">
                                        <div class="w-full shrink-1 min-w-30 max-w-100 pr-5 pl-2 py-3 font-[shabnam] border-1 border-gray-400 rounded-xl flex gap-5 items-center cursor-pointer bg-gray-50"
                                            x-bind:class="{ 'bg-blue-50! border-blue-700! border-2!': sharedImages }"
                                            x-on:click="sharedImages = true; updateSharedImages()">
                                            <div class="size-4 shrink-0 rounded-full bg-white border-2 border-white outline-1 outline-gray-600"
                                                x-bind:class="{ 'bg-blue-700! border-outline-700! border-blue-50!': sharedImages }">
                                            </div>
                                            <div class="flex flex-col gap-1">
                                                <span>
                                                    تصاویر مشترک برای همه طرح‌ها
                                                </span>
                                                <span class="text-gray-600 text-sm font-light">
                                                    در این حالت برای تمامی رنگ‌ها (طرح‌ها)، تصاویر یکسانی نمایش داده
                                                    می‌شود.
                                                </span>
                                            </div>
                                        </div>
                                        <div class="w-full shrink-1 min-w-30 max-w-100 pr-5 pl-2 py-3 font-[shabnam] border-1 border-gray-400 rounded-xl flex gap-5 items-center cursor-pointer bg-gray-50"
                                            x-bind:class="{ 'bg-blue-50! border-blue-700! border-2!': !sharedImages }"
                                            x-on:click="sharedImages = false; console.log(sharedImages)">
                                            <div class="size-4 shrink-0 rounded-full bg-white border-2 border-white outline-1 outline-gray-600"
                                                x-bind:class="{ 'bg-blue-700! border-outline-700! border-blue-50!': !sharedImages }">
                                            </div>
                                            <div class="flex flex-col gap-1">
                                                <span>
                                                    تصاویر اختصاصی برای هر طرح
                                                </span>
                                                <span class="text-gray-600 text-sm font-light">
                                                    کاربر با کلیک بر روی هر رنگ (طرح) می‌تواند عکس‌های مخصوص به آن رنگ
                                                    را ببیند.
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="w-full h-1 rounded-full border-b-1 border-gray-300 border-dashed">
                                    </div>
                                    <label class="w-fit flex items-center gap-3 font-[shabnam] cursor-pointer">
                                        <input type="checkbox" class="cursor-pointer size-4">
                                        <span>انتخاب به عنوان طرح اصلی</span>
                                    </label>
                                    <div
                                        class="font-[shabnam] text-gray-500 text-sm flex items-center flex-nowrap gap-2 -mt-2">
                                        <i class="fa-solid fa-info-circle"></i>
                                        <span>
                                            با انتخاب این گزینه، تصاویر این طرح به عنوان تصاویر پیش‌فرض محصول قرار
                                            می‌گیرند.
                                        </span>
                                    </div> --}}
                                </div>
                            </div>
                    </template>
                </template>
            </div>
        </div>

        {{-- attributes section --}}
        <x-blade.manager.section class="h-fit" x-data="{ showSelectAttributeGroupMenu: false }">
            <x-blade.manager.section-title title="ویژگی‌ها" />
            <div class="relative">
                <x-blade.manager.input-text title="نام مجموعه ویژگی‌ها" class="w-70!"
                    x-on:focus="showSelectAttributeGroupMenu = true; $wire.loadAttributeGroups($refs.attributeGroupInput.value)"
                    x-ref="attributeGroupInput" x-model="selectedAttributeGroup.name" />
                <div class="absolute mt-2 w-full h-auto min-h-14 max-h-48 rounded-md shadow-lg flex flex-col font-[shabnam] bg-white border-1 border-gray-200 overflow-auto z-3"
                    x-show="showSelectAttributeGroupMenu" x-data="{ searchAttributeGroups: @entangle('attributeGroups') }"
                    x-on:click.outside="if ($event.target != $refs.attributeGroupInput) showSelectAttributeGroupMenu = false"
                    x-transition x-cloak>
                    <template x-if="searchAttributeGroups.length > 0">
                        <template x-for="attributeGroup in searchAttributeGroups" :key="attributeGroup.id">
                            <label
                                class="w-full h-14 px-5 flex items-center gap-5 hover:bg-gray-50 cursor-pointer font-[shabnam] transition text-gray-700"
                                x-bind:class="{
                                    'bg-blue-700! text-white!': (selectedAttributeGroup && selectedAttributeGroup.id ==
                                        attributeGroup.id)
                                }"
                                x-on:click="selectedAttributeGroup = attributeGroup; $refs.attributeGroupInput.value = attributeGroup.name">
                                <span class="select-none" x-text="attributeGroup.name"></span>
                            </label>
                        </template>
                    </template>
                    <template x-if="searchAttributeGroups.length == 0">
                        <span class="w-full h-14 flex items-center justify-center font-[shabnam] text-gray-500">
                            هیچ مجموعه ویژگی‌ای یافت نشد.
                        </span>
                    </template>
                </div>
                <i class="fa-solid fa-xmark absolute top-3 left-3 text-gray-500 cursor-pointer"
                    x-show="selectedAttributeGroup"
                    x-on:click="selectedAttributeGroup = null; $refs.attributeGroupInput.value = ''"></i>
            </div>
            <div class="font-[shabnam] text-gray-500 text-sm flex items-center flex-nowrap gap-2"
                x-show="selectedAttributeGroup">
                <i class="fa-solid fa-info-circle"></i>
                <span>
                    ویژگی‌هایی که مقدار خالی دارند ثبت نمی‌شوند.
                </span>
            </div>
            <template x-if="selectedAttributeGroup">
                <div class="w-full flex flex-col flex-nowrap">
                    <template x-for="(attribute, index) in selectedAttributeGroup.attributes" :key="index">
                        <div class="w-full flex flex-nowrap items-center h-auto border-t-1 border-gray-300 gap-5 py-4 font-[shabnam]"
                            x-data="{ editing: false, previousData: '' }">
                            <span class="w-100 shrink-0 h-auto flex items-center text-gray-600"
                                x-text="attribute.key"></span>
                            <div class="w-full shrink-1 h-auto flex items-center py-2 gap-5"
                                x-show="!editing && attribute.value != ''">
                                <i class="fa-solid fa-pen text-gray-400 text-sm cursor-pointer"
                                    x-on:click="editing = true; $nextTick(() => $refs.valueInput.select())"></i>
                                <span x-text="attribute.value" class="w-full"
                                    x-on:click="editing = true; $nextTick(() => $refs.valueInput.focus())"></span>
                            </div>
                            <div class="w-full shrink-1 h-auto flex items-center gap-4">
                                <i class="fa-solid fa-xmark text-gray-400 cursor-pointer"
                                    x-on:click="editing = false; attribute.value = previousData" x-show="editing"></i>
                                <input type="text" x-show="editing || attribute.value == ''"
                                    class="outlined-input w-full! shrink-1!" x-model="attribute.value"
                                    x-on:click.outside="editing = false; previousData = $refs.valueInput.value"
                                    x-on:keydown.enter="editing = false; previousData = $refs.valueInput.value"
                                    x-on:keydown.escape="editing = false; attribute.value = previousData"
                                    x-ref="valueInput" x-on:input="editing = true" placeholder>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            <template x-if="!selectedAttributeGroup">
                <div class="w-full h-auto border-1 p-5 border-yellow-500 bg-yellow-100 rounded-xl font-[shabnam] text-gray-700 flex gap-5 flex-nowrap"
                    x-data="{ showPatternsWarning: true }" x-show="showPatternsWarning">
                    <div class="w-full shrink-1 flex gap-5 flex-nowrap">
                        <i class="fa-solid fa-info-circle text-yellow-500 text-lg h-fit mt-1 shrink-0"></i>
                        <span class="text-justify text-yellow-800">
                            در حال حاضر محصول شما هیچ ویژگی‌ای ندارد.
                        </span>
                    </div>
                    <i class="fa-solid fa-xmark text-yellow-800 mt-1 text-lg cursor-pointer shrink-0"
                        x-on:click="showPatternsWarning = false"></i>
                </div>
            </template>
        </x-blade.manager.section>

        {{-- variants section --}}
        <x-blade.manager.section>
            <x-blade.manager.section-title title="انبارداری" class="-mb-2!" />
            <div class="w-full h-auto bg-white flex flex-col flex-nowrap font-[shabnam] rounded-xl overflow-y-hidden">
                <div
                    class="w-full grid grid-rows-1 grid-cols-[1fr_1fr_1fr] h-12 bg-[#f9fafb] text-gray-800 font-[500]">
                    <div class="flex items-center px-3">نام طرح</div>
                    {{-- <div class="flex items-center px-3 border-r-1 border-gray-300">نام سایز</div> --}}
                    {{-- <div class="flex items-center px-3 border-r-1 border-gray-300">قیمت</div> --}}
                    {{-- <div class="flex items-center px-3 border-r-1 border-gray-300">تخفیف</div> --}}
                    <div class="flex items-center px-3 border-r-1 border-gray-300">موجودی</div>
                    <div class="flex items-center px-3 border-r-1 border-gray-300">کد کالا</div>
                </div>
                <template x-if="patterns.length">
                    <template x-for="pattern in patterns">
                        <div>
                            <template x-if="pattern.sizes.length">
                                <template x-for="size in pattern.sizes">
                                    <div
                                        class="w-full grid grid-rows-1 grid-cols-[1fr_1fr_1fr] h-14 bg-white border-t-1 border-[#e5e7eb]">
                                        <div class="flex items-center px-3"
                                            x-text="pattern.name.length ? pattern.name : '---'">
                                        </div>
                                        {{-- <div class="flex items-center px-3 border-r-1 border-gray-300"
                                            x-text="size.name"></div> --}}
                                        {{-- <x-manager.products.prices-table-cell model="size.price"
                                            x-price-format="size.price" />
                                        <x-manager.products.prices-table-cell model="size.discount"
                                            x-price-format="size.discount" /> --}}
                                        <x-manager.products.prices-table-cell model="size.quantity" inputType="number"
                                            x-only-digits="size.quantity" min="0" />
                                        <x-manager.products.prices-table-cell model="size.sku" english />
                                    </div>
                                </template>
                            </template>

                            <template x-if="!pattern.sizes.length">
                                <div
                                    class="w-full grid grid-rows-1 grid-cols-[1fr_1fr_1fr] h-14 bg-white border-t-1 border-[#e5e7eb]">
                                    <div class="flex items-center px-3"
                                        x-text="pattern.name.length ? pattern.name : '---'">
                                    </div>
                                    {{-- <div class="flex items-center px-3 text-gray-500 border-r-1 border-gray-300">
                                        بدون
                                        سایز</div> --}}
                                    {{-- <x-manager.products.prices-table-cell model="pattern.noSizeVariants.price"
                                        x-price-format="pattern.noSizeVariants.price" />
                                    <x-manager.products.prices-table-cell model="pattern.noSizeVariants.discount"
                                        x-price-format="pattern.noSizeVariants.discount" /> --}}
                                    <x-manager.products.prices-table-cell model="pattern.noSizeVariants.quantity"
                                        inputType="number" x-only-digits="pattern.noSizeVariants.quantity"
                                        min="0" />
                                    <x-manager.products.prices-table-cell model="pattern.noSizeVariants.sku" english />
                                </div>
                            </template>
                        </div>
                    </template>
                </template>
                <template x-if="!patterns.length">
                    <div>
                        <template x-if="noPattern.sizes.length">
                            <template x-for="size in noPattern.sizes">
                                <div
                                    class="w-full grid grid-rows-1 grid-cols-[1fr_1fr_1fr] h-14 bg-white border-t-1 border-[#e5e7eb]">
                                    <div class="flex items-center px-3 text-gray-500">بدون طرح</div>
                                    {{-- <div class="flex items-center px-3 border-r-1 border-gray-300"
                                        x-text="size.name">
                                    </div> --}}
                                    {{-- <x-manager.products.prices-table-cell model="size.price"
                                        x-price-format="size.price" />
                                    <x-manager.products.prices-table-cell model="size.discount"
                                        x-price-format="size.discount" /> --}}
                                    <x-manager.products.prices-table-cell model="size.quantity" inputType="number"
                                        x-only-digits="size.quantity" min="0" />
                                    <x-manager.products.prices-table-cell model="size.sku" english />
                                </div>
                            </template>
                        </template>

                        <template x-if="!noPattern.sizes.length">
                            <div
                                class="w-full grid grid-rows-1 grid-cols-[1fr_1fr_1fr] h-14 bg-white border-t-1 border-[#e5e7eb]">
                                <div class="flex items-center px-3 text-gray-500">بدون طرح</div>
                                {{-- <div class="flex items-center px-3 text-gray-500 border-r-1 border-gray-300">بدون
                                    سایز
                                </div> --}}
                                {{-- <x-manager.products.prices-table-cell model="noPattern.noSizeVariants.price"
                                    x-price-format="noPattern.noSizeVariants.price" />
                                <x-manager.products.prices-table-cell model="noPattern.noSizeVariants.discount"
                                    x-price-format="noPattern.noSizeVariants.discount" /> --}}
                                <x-manager.products.prices-table-cell model="noPattern.noSizeVariants.quantity"
                                    inputType="number" x-only-digits="noPattern.noSizeVariants.quantity"
                                    min="0" />
                                <x-manager.products.prices-table-cell model="noPattern.noSizeVariants.sku" english />
                            </div>
                        </template>
                    </div>
                </template>
            </div>

        </x-blade.manager.section>

        <div class="w-full flex justify-end gap-5">
            <x-blade.manager.text-button value="حذف محصول" icon="trash-can" target="delete"
                x-on:click="showDeleteProductMessage = true" class="text-red-700! hover:bg-red-50!" />
            <x-blade.manager.filled-button value="تایید ویرایش" icon="check" target="update"
                x-on:click="sendData()" />
        </div>

        <div class="p-0.5 w-full"></div>

        <div class="back-cover fixed! p-10! right-[unset]! left-0! w-[calc(100%-300px)]!" x-show="showGallery"
            x-transition.opacity x-cloak>
            <livewire:components.manager.gallery.index :selectable="true" :multiselect="true" />
        </div>
    </div>

    <x-manager.products.delete-message-modal class="fixed!" />
    <div wire:loading wire:target="loadProduct">
        <div class="w-full h-dvh fixed left-0 top-0 bg-white/60 flex items-center justify-center flex-col gap-5">
            <i class="fa-solid fa-spinner animate-spin text-4xl text-blue-700"></i>
            <span class="text-xl font-[500] font-shabnam text-blue-700">در حال بارگذاری</span>
        </div>
    </div>
</div>

@assets
    @vite(['resources/css/manager/products-create.scss'])
@endassets

@script
    <script>
        window.priceFormat = (element) => {
            IMask(element, {
                mask: Number,
                signed: false,
                scale: 0,
                thousandsSeparator: ',',
                padFractionalZeros: false,
                normalizeZeros: true,
                radix: '.',
            });
        }
    </script>
@endscript
