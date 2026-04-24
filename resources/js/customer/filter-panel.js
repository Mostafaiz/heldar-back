// export function initFilterPanel() {
//     const filterPanel = document.querySelector("#filter-panel");
//     let lastScrollTop = 0;
//     let status = "DOWN";

//     window.addEventListener(
//         "scroll",
//         () => {
//             let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

//             if (scrollTop < lastScrollTop) {
//                 if (filterPanel.getBoundingClientRect().top <= 20)
//                     filterPanel.style = "position: sticky !important; top: 20px !important";
//             } else if (scrollTop > lastScrollTop) {
//                 filterPanel.style = "";
//                 if (filterPanel.getBoundingClientRect().bottom <= window.innerHeight - 20)
//                     filterPanel.style = "position: sticky !important; bottom: 20px !important";
//             }

//             lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
//         },
//         false
//     );
// }

// document.addEventListener("livewire:navigated", () => {
//     if (document.querySelector("#filter-panel") != null) initFilterPanel();
// });
