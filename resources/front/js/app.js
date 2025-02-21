import Splide from "@splidejs/splide";

var splide = new Splide("#main-carousel", {
    arrows: false,
    type: 'loop',
    perPage: 1,
    cover: false,
    height: '100%',
    autoplay: true,
});

var thumbnails = document.getElementsByClassName("thumbnail");
var current;

for (var i = 0; i < thumbnails.length; i++) {
    initThumbnail(thumbnails[i], i);
}

function initThumbnail(thumbnail, index) {
    thumbnail.addEventListener("click", function () {
        splide.go(index);
    });
}

splide.on("mounted move", function () {
    var thumbnail = thumbnails[splide.index];

    if (thumbnail) {
        if (current) {
            current.classList.remove("is-active");
        }

        thumbnail.classList.add("is-active");
        current = thumbnail;
    }
});

splide.mount();

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
