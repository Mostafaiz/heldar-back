<div class="fixed w-fit rounded-xl shadow-md z-100 border flex flex-col overflow-hidden transition hover:opacity-50"
    x-data="notification()" x-show="show" x-transition x-cloak x-on:notify.window="start($event.detail)"
    x-on:click="stop()"
    :class="[
        type === 'success' ? 'bg-green-100 border-green-300' : '',
        type === 'error' ? 'bg-red-100 border-red-300' : '',
        type === 'warning' ? 'bg-yellow-100 border-yellow-300' : '',
        position === 'top' ? 'top-5 left-5' : '',
        position === 'center' ?
        'top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-fit max-w-80! pt-5 gap-3! hover:opacity-100 bg-white! border-gray-300!' :
        'h-15',
        position === 'center' && buttonText === null ? 'scale-120' : ''
    ]">

    <div class="w-full h-full flex items-center gap-5 px-5" x-bind:class="{ 'flex-col!': position === 'center' }">
        <i class="fa-solid text-2xl max-lg:text-xl min-w-[24px] min-h-[24px]" x-show="position === 'top'"
            x-bind:class="{
                'fa-check-circle text-success': type === 'success',
                'fa-triangle-exclamation text-error': type === 'error',
                'fa-triangle-exclamation text-warning': type === 'warning',
            }">
        </i>

        <i class="fa-light text-6xl min-w-[24px] min-h-[24px]" x-show="position === 'center'"
            x-bind:class="{
                'fa-check-circle text-success': type === 'success',
                'fa-triangle-exclamation text-error': type === 'error',
                'fa-triangle-exclamation text-warning': type === 'warning',
            }">
        </i>

        <span class="font-shabnam max-lg:text-sm"
            x-bind:class="{
                'text-green-600': type === 'success' && position === 'top',
                'text-red-600': type === 'error' && position === 'top',
                'text-yellow-600': type === 'warning' && position === 'top',
                'font-[500]': position === 'center',
            }"
            x-text="message">
        </span>
    </div>

    <!-- Button section -->
    <div class="w-full flex items-center justify-center" x-show="buttonText !== null" @click="storeLocalStorage()">
        <a :href="url ?? '#'" wire:navigate
            class="px-3 py-2 rounded-lg font-[500] text-white text-sm cursor-pointer bg-primary font-shabnam mt-1 mb-2"
            x-text="buttonText">
        </a>
    </div>

    <div class="h-1"
        :class="{
            'bg-success': type === 'success',
            'bg-error': type === 'error',
            'bg-warning': type === 'warning'
        }"
        :style="`width: ${percent}%`">
    </div>
</div>

<script>
    function notification() {
        return {
            show: false,
            message: '',
            type: 'error',
            position: 'top', // default
            buttonText: null,
            url: null,
            localStorageKey: null, // NEW: property to store localStorage key
            timer: 3000,
            percent: 100,
            interval: null,
            timeout: null,

            start({
                message,
                type,
                timer,
                position,
                buttonText,
                url,
                localStorageKey // NEW: accept localStorageKey parameter
            }) {
                this.stop();

                this.message = message;
                this.type = type ?? 'error';
                this.position = position ?? 'top';
                this.buttonText = buttonText ?? null;
                this.url = url ?? null;
                this.localStorageKey = localStorageKey ?? null; // NEW: set localStorageKey
                this.timer = timer === 'infinite' ? 'infinite' : (timer ?? 3) * 1000;
                this.percent = 100;
                this.show = true;

                if (timer !== 'infinite') {
                    this.interval = setInterval(() => {
                        if (this.percent > 0) this.percent--;
                    }, (this.timer / 100) - 1);

                    this.timeout = setTimeout(() => {
                        this.stop();

                        if (this.url !== null && this.buttonText === null) {
                            window.location.href = this.url;
                        }
                    }, this.timer);
                }
            },

            stop() {
                this.show = false;
                clearInterval(this.interval);
                clearTimeout(this.timeout);
            },

            // NEW: method to store in localStorage when button is clicked
            storeLocalStorage() {
                if (this.localStorageKey) {
                    localStorage.setItem(this.localStorageKey, 'true');
                }
            }
        }
    }
</script>
