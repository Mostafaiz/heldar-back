<div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 gap-6 bg-white/90 w-70 h-55 z-100 rounded-md border border-gray-200 shadow-lg flex flex-col items-center justify-center"
    x-on:success-card.window="start($event.detail)" x-data="successCard()" x-show="show" x-transition x-cloak
    style="backdrop-filter: blur(10px)">
    <div class="size-24 rounded-full flex justify-center items-center"
        x-bind:style="`background-image: conic-gradient(in oklab, #b9f8cf ${100 - percent}%, #22c55e ${100 - percent}%)`">
        <div class="size-22 bg-white rounded-full flex justify-center items-center relative">
            <div class="h-11 w-1.5 transform rotate-z-45 bg-success absolute right-8.5"></div>
            <div class="h-6 w-1.5 transform -rotate-z-45 bg-success absolute right-14 top-10"></div>
        </div>
    </div>
    <span class="font-shabnam">به سبد خرید افزوده شد.</span>
</div>

<script>
    function successCard() {
        return {
            show: false,
            timer: 3000,
            percent: 100,
            interval: null,
            timeout: null,

            start({
                timer
            }) {
                this.stop();
                this.timer = (timer ?? 3) * 1000;
                this.percent = 100;
                this.show = true;

                this.interval = setInterval(() => {
                    if (this.percent > 0) this.percent--;
                }, (this.timer / 100) - 1);

                this.timeout = setTimeout(() => {
                    this.stop();
                }, this.timer);
            },

            stop() {
                this.show = false;
                clearInterval(this.interval);
                clearTimeout(this.timeout);
            },
        }
    }
</script>
