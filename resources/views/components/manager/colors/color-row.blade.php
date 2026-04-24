<form wire:submit="update" x-on:success.window="editing = false" x-data="{ editing: false }"
    class="border rounded-lg border-gray-300 shadow-md h-20 flex flex-nowrap p-3 box-border gap-3">
    <label
        class="group h-full aspect-square rounded-md border-2 border-white outline outline-gray-400 relative flex items-center justify-center"
        x-bind:style="'background-color: {{ $form->code }}'" x-ref="colorLabel">
        <input type="color" class="hidden" x-on:input="$refs.colorLabel.style.backgroundColor = $el.value"
            wire:model="form.code" x-on:change="$wire.update(); console.log(editing)">
        <div class="absolute size-7 rounded-full bg-neutral-light/50 flex items-center justify-center invisible group-hover:visible transition"
            x-bind:class="{ 'visible!': editing }">
            <i class="fa-solid fa-pen text-xs" x-bind:style="'color: {{ $form->code }}'"></i>
        </div>
    </label>
    <div class="w-full h-full flex flex-col justify-center" x-bind:class="{ 'gap-1': editing }">
        <input type="text" x-show="editing"
            class="w-full border-2 border-blue-600 rounded font-shabnam outline-none -mb-1 -mr-1 box-border pr-1 font-[500]"
            wire:model="form.name" x-ref="inputText" x-on:keydown.escape="editing = false; $wire.resetData()"
            x-on:click.outside="editing = false">
        <span class="font-shabnam font-[500] line-clamp-1 mb-1" x-bind:class="{ 'mb-0!': editing }" x-show="!editing"
            x-on:click="editing = true; $nextTick(() => { $refs.inputText.select() })">{{ $form->name }}</span>
        <span class="font-shabnam-en text-sm text-neutral">{{ $form->code }}</span>
    </div>
    <div class="w-fit flex flex-nowrap gap-1" x-show="!editing">
        <button type="button"
            class="group size-6 flex items-center justify-center rounded-full hover:bg-blue-600 cursor-pointer transition"
            x-on:click="editing = true; $nextTick(() => { $refs.inputText.select() })">
            <i class="fa-solid fa-pen size-fit text-xs text-neutral group-hover:text-white transition"></i>
        </button>
        <button type="button"
            class="group size-6 flex items-center justify-center rounded-full hover:bg-error cursor-pointer transition"
            x-on:click="showDeleteColorModal = true; selectedColorForDelete = {{ $form->color->id }}">
            <i class="fa-solid fa-trash-can size-fit text-xs text-neutral group-hover:text-white transition"></i>
        </button>
    </div>
    <div class="w-fit flex flex-nowrap gap-1" x-show="editing">
        <button type="submit"
            class="group size-6 flex items-center justify-center rounded-full hover:bg-green-600 cursor-pointer transition"
            x-on:click="editing = true; $nextTick(() => { $refs.inputText.select() })">
            <i class="fa-solid fa-check size-fit text-sm text-neutral group-hover:text-white transition"></i>
        </button>
        <button type="button"
            class="group size-6 flex items-center justify-center rounded-full hover:bg-error cursor-pointer transition"
            x-on:click="editing = false; $wire.resetData()">
            <i class="fa-solid fa-xmark size-fit text-sm text-neutral group-hover:text-white transition"></i>
        </button>
    </div>
</form>