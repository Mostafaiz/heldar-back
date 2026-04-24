import IMask from "imask";

export function initDualRange() {
    function getParsed(fromMask, toMask) {
        return [parseInt(fromMask.unmaskedValue || "0", 10), parseInt(toMask.unmaskedValue || "0", 10)];
    }

    function fillSlider(from, to, sliderColor, rangeColor) {
        if (!from || !to) return;

        const min = parseInt(to.min || "0", 10);
        const max = parseInt(to.max || "100", 10);

        const rangeDistance = max - min;
        const fromPosition = from.value - min;
        const toPosition = to.value - min;

        const el = document.getElementById("test");
        if (el) {
            el.style.background = `linear-gradient(
                to left,
                ${sliderColor} 0%,
                ${sliderColor} ${(fromPosition / rangeDistance) * 100}%,
                ${rangeColor} ${(fromPosition / rangeDistance) * 100}%,
                ${rangeColor} ${(toPosition / rangeDistance) * 100}%,
                ${sliderColor} ${(toPosition / rangeDistance) * 100}%,
                ${sliderColor} 100%)`;
        }
    }

    function setToggleAccessible(target) {
        const toSlider = document.querySelector("#toSlider");
        if (!toSlider) return;

        const min = parseInt(toSlider.min || "0", 10);
        const value = parseInt(target.value, 10);

        toSlider.style.zIndex = value <= min ? 2 : 0;
    }

    function syncFromSlider(fromSlider, toSlider, fromMask, toMask) {
        if (!fromSlider || !toSlider) return;

        let from = parseInt(fromSlider.value, 10);
        let to = parseInt(toSlider.value, 10);

        fillSlider(fromSlider, toSlider, "#C6C6C6", "#f59e0b");
        if (from > to) from = to;

        fromSlider.value = from;
        fromMask.unmaskedValue = String(from);
    }

    function syncToSlider(fromSlider, toSlider, toMask, fromMask) {
        if (!fromSlider || !toSlider) return;

        let from = parseInt(fromSlider.value, 10);
        let to = parseInt(toSlider.value, 10);

        fillSlider(fromSlider, toSlider, "#C6C6C6", "#f59e0b");
        setToggleAccessible(toSlider);
        if (to < from) to = from;

        toSlider.value = to;
        toMask.unmaskedValue = String(to);
    }

    function syncFromInput(fromSlider, toSlider, fromMask, toMask) {
        if (!fromSlider || !toSlider) return;

        const [from, to] = getParsed(fromMask, toMask);
        fillSlider(fromSlider, toSlider, "#C6C6C6", "#f59e0b");
        fromSlider.value = Math.min(from, to);
    }

    function syncToInput(fromSlider, toSlider, fromMask, toMask) {
        if (!fromSlider || !toSlider) return;

        const [from, to] = getParsed(fromMask, toMask);
        fillSlider(fromSlider, toSlider, "#C6C6C6", "#f59e0b");
        setToggleAccessible(toSlider);
        toSlider.value = Math.max(from, to);
    }

    const fromSlider = document.querySelector("#fromSlider");
    const toSlider = document.querySelector("#toSlider");
    const fromInput = document.querySelector("#fromInput");
    const toInput = document.querySelector("#toInput");

    if (!fromSlider || !toSlider || !fromInput || !toInput) {
        console.warn("Dual range slider elements not found.");
        return;
    }

    const maskConfig = { mask: Number, thousandsSeparator: ",", scale: 0 };
    const fromMask = IMask(fromInput, maskConfig);
    const toMask = IMask(toInput, maskConfig);

    fillSlider(fromSlider, toSlider, "#C6C6C6", "#f59e0b");
    setToggleAccessible(toSlider);

    fromSlider.replaceWith(fromSlider.cloneNode(true));
    toSlider.replaceWith(toSlider.cloneNode(true));

    const newFromSlider = document.querySelector("#fromSlider");
    const newToSlider = document.querySelector("#toSlider");

    newFromSlider.addEventListener("input", () => syncFromSlider(newFromSlider, newToSlider, fromMask, toMask));
    newToSlider.addEventListener("input", () => syncToSlider(newFromSlider, newToSlider, toMask, fromMask));
    fromMask.on("accept", () => syncFromInput(newFromSlider, newToSlider, fromMask, toMask));
    toMask.on("accept", () => syncToInput(newFromSlider, newToSlider, fromMask, toMask));
}

document.addEventListener("livewire:navigated", () => {
    initDualRange();
});
