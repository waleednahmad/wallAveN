/*
 * Storefront behaviour (replaces the template's main.js).
 * Kept: mobile menu, menu dropdowns, back-to-top circle, password show/hide,
 * button hover effect, list/grid toggle, and clearing the template's old dark-mode flag.
 * Dropped with their libraries (none of them is used by any page): Swiper, Slick, CounterUp,
 * WOW, Marquee, Fancybox, Nice Select, countdown timers.
 * Every block checks its elements first, so one missing element can't stop the rest.
 */
(function ($) {
    "use strict";

    // Mobile menu
    $('.sidebar-button').on('click', function () {
        $(this).toggleClass('active');
        $('.main-menu').toggleClass('show-menu');
    });
    $('.menu-close-btn').on('click', function () {
        $('.main-menu').removeClass('show-menu');
    });
    $('.sidebar-btn').on('click', function () {
        $('.sidebar-area').addClass('active');
    });
    $('.sidebar-menu-close').on('click', function () {
        $('.sidebar-area').removeClass('active');
    });

    // Menu dropdowns (mobile)
    $('.dropdown-icon').on('click', function () {
        $(this).toggleClass('active').next('ul, .mega-menu, .mega-menu2').slideToggle();
        $(this).parent().siblings().children('ul, .mega-menu, .mega-menu2').slideUp();
        $(this).parent().siblings().children('.active').removeClass('active');
    });
    $('.dropdown-icon2').on('click', function () {
        $(this).toggleClass('active').next('.submenu-list').slideToggle();
        $(this).parent().siblings().children('.submenu-list').slideUp();
        $(this).parent().siblings().children('.active').removeClass('active');
    });

    // Run now if the page is already parsed (Cloudflare can load scripts after DOMContentLoaded)
    var onReady = function (fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    };

    // Back-to-top circle that fills as you scroll
    onReady(function () {
        var circleContainer = document.querySelector('.circle-container');
        var circlePath = document.querySelector('.circle-container path');
        if (!circleContainer || !circlePath) return;

        var offset = 50;
        var pathLength = circlePath.getTotalLength();
        circlePath.style.transition = 'none';
        circlePath.style.strokeDasharray = pathLength;
        circlePath.style.strokeDashoffset = pathLength;
        circlePath.getBoundingClientRect();
        circlePath.style.transition = 'stroke-dashoffset 10ms linear';

        var update = function () {
            var scrollTop = window.scrollY;
            var height = document.documentElement.scrollHeight - window.innerHeight;
            circlePath.style.strokeDashoffset = Math.max(0, pathLength - (scrollTop * pathLength / Math.max(height, 1)));
            circleContainer.classList.toggle('active', scrollTop > offset);
        };
        circleContainer.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        window.addEventListener('scroll', update, { passive: true });
        update();
    });

    // The storefront is light only: clear the template's old dark-mode flag
    try {
        if (localStorage.getItem('artmart_theme') === 'dark') {
            localStorage.removeItem('artmart_theme');
        }
    } catch (e) {}
    document.body.classList.remove('dark');

    // Password show / hide
    [['#togglePassword', '#password'], ['#togglePassword2', '#password2'], ['#togglePassword3', '#password3']].forEach(function (pair) {
        var toggle = document.querySelector(pair[0]);
        var input = document.querySelector(pair[1]);
        if (!toggle || !input) return;
        toggle.addEventListener('click', function () {
            input.setAttribute('type', input.getAttribute('type') === 'password' ? 'text' : 'password');
            this.classList.toggle('bi-eye');
        });
    });

    // Button hover effect
    $('.btn-hover').on('mouseenter mouseout', function (e) {
        var o = $(this).offset();
        $(this).find('strong').css({ top: e.pageY - o.top, left: e.pageX - o.left });
    });

    // List / grid view switch
    $('.grid-view li').on('click', function () {
        var className = ($(this).attr('class') || '').replace('item-', '').replace(' active', '');
        $('.list-grid-product-wrap').removeClass().addClass('list-grid-product-wrap ' + className + '-wrapper');
        $(this).addClass('active').siblings().removeClass('active');
    });
})(jQuery);
