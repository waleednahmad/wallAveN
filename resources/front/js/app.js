import Splide from "@splidejs/splide";
import Swal from "sweetalert2";

// Make libraries globally available
window.Splide = Splide;
window.Swal = Swal;

// Debug: Log when the bundle is loaded
console.log('Vite bundle loaded, Swal available:', typeof window.Swal !== 'undefined');

// const { Autoplay } = splide.Components;
// Autoplay.pause();

// Related Products splide, will contain 6 random products, view 4 at one show
// var relatedSplide = new Splide("#related-products", {
//     arrows: false,
//     type: "loop",
//     perPage: 4,
//     autoplay: true,
//     gap: "1em",
//     breakpoints: {
//         768: {
//             perPage: 1,
//         },
//     },
// });

// relatedSplide.mount();
