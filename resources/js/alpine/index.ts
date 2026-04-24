import { Livewire, Alpine } from "../../../vendor/livewire/livewire/dist/livewire.esm";
import IMask, { InputMaskElement } from 'imask';
import intersect from '@alpinejs/intersect';

window.IMask = IMask;

Alpine.magic("focusTo", (_, { Alpine, evaluate }) => {
    return (subject) => {
        const target = evaluate(`$refs['${subject}']`);
        Alpine.nextTick(() => {
            target.focus();
        });
    };
});

Alpine.directive("middle-ellipsis", (el, { expression }) => {
    const maxLength = parseInt(expression) || 18;

    const applyEllipsis = (text) => {
        if (text.length <= maxLength) return text;
        const keep = Math.floor((maxLength - 3) / 2);
        return text.slice(0, keep) + "..." + text.slice(-keep);
    };

    const setTruncated = () => {
        const original = el.textContent.trim();
        el.textContent = applyEllipsis(original);
        el.setAttribute("title", original);
    };

    setTruncated();
});

Alpine.directive('price-format', (el, { expression }, { evaluateLater, effect }) => {
    const getValue = evaluateLater(expression);

    const mask = IMask(el, {
        mask: Number,
        scale: 0,
        thousandsSeparator: ',',
        padFractionalZeros: false,
        normalizeZeros: true,
        radix: '.',
        signed: false,
        min: 0,
        max: 99999999999
    });

    effect(() => {
        getValue(value => {
            if (value != mask.unmaskedValue) {
                mask.unmaskedValue = value ?? '';
            }
        });
    });

    mask.on('accept', () => {
        el.dispatchEvent(new CustomEvent('input', { bubbles: true }));
    });
});

Alpine.directive('credit-card-format', (el, { expression }, { evaluateLater, effect }) => {
    IMask(
        el as InputMaskElement,
        {
            mask: '0000 0000 0000 0000',
        }
    )
});



Alpine.directive('only-digits', (el, { expression }, { evaluateLater, effect }) => {
    const getValue = evaluateLater(expression);

    const mask = IMask(el, {
        mask: Number,
        signed: false,
        min: 0
    });

    effect(() => {
        getValue(value => {
            if (value != mask.unmaskedValue) {
                mask.unmaskedValue = value ?? '';
            }
        });
    });

    mask.on('accept', () => {
        el.dispatchEvent(new CustomEvent('input', { bubbles: true }));
    });
});

// Alpine.directive('validate', (el, { expression }, { evaluateLater, cleanup }) => {
//     const getRules = evaluateLater(expression)

//     let errorSpan = document.createElement('span')
//     errorSpan.classList.add('error-message')
//     el.parentElement.insertAdjacentElement('afterend', errorSpan)

//     const validate = (value, rules) => {
//         if (rules.required && !value.trim()) return 'This field is required.'
//         if (rules.min && value.length < rules.min) return `Minimum ${rules.min} characters required.`
//         if (rules.pattern && !rules.pattern.test(value)) return 'Invalid format.'
//         return ''
//     }

//     const runValidation = () => {
//         getRules(rules => {
//             const error = validate(el.value, rules)
//             errorSpan.textContent = error
//         })
//         console.log(errorSpan.textContent)
//     }
//     console.log(errorSpan.textContent)

//     el.__validate = runValidation

//     // el.__validate = () => {
//     //     const rules = getRules() || {}
//     //     const error = validate(el.value, rules)
//     //     errorSpan.textContent = error
//     //     console.log('rules =', rules, 'error =', error)
//     // }

//     el.addEventListener('input', runValidation)
//     cleanup(() => el.removeEventListener('input', runValidation))

// })

document.querySelectorAll('[data-id="price-format"]').forEach(element => {
    IMask(
        element as InputMaskElement,
        {
            mask: Number,
            signed: false,
            scale: 0,
            thousandsSeparator: ',',
            padFractionalZeros: false,
            normalizeZeros: true,
            radix: '.',
        }
    )
});

// document.addEventListener('livewire:navigated', () => {
//     document.querySelectorAll('[data-id="credit-card-format"]').forEach(element => {
//         IMask(
//             element as InputMaskElement,
//             {
//                 mask: '00 0000 0000 0000 0000 0000 00',
//             }
//         )
//     });
// });

document.addEventListener('livewire:navigated', () => {
    document.querySelectorAll('[data-id="credit-card-format-with-ir"]').forEach(element => {
        IMask(
            element as InputMaskElement,
            {
                mask: 'IR00 0000 0000 0000 0000 0000 00',
            }
        )
    });
});

Alpine.plugin(intersect);
// Prevent zooming with more than one finger
document.addEventListener(
    "touchstart",
    function (e) {
        if (e.touches.length > 1) {
            e.preventDefault(); // Prevent zoom
        }
    },
    { passive: false }
);

// Prevent pinch zooming with gestures
document.addEventListener(
    "gesturestart",
    function (e) {
        e.preventDefault(); // Prevent zoom gesture
    },
    { passive: false }
);

Livewire.hook('request', ({ fail }) => {

    fail(({ status, preventDefault }) => {

        if (status === 419) {

            preventDefault()

            confirm('این صفحه منقضی شده است. بارگذاری مجدد؟')

        }

    })

})

Livewire.start();
