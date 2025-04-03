<div class="auction-details-section mb-120">
    <div class="container">
        <div class="row gy-5">
            <div class="col-md-9 ">
                <div class="auction-details-img d-flex justify-content-center align-items-center flex-column"
                    wire:ignore>


                    <section aria-label="My Awesome Gallery" class=" w-100">
                        <div id="main-carousel" class="splide mb-2">
                            <div class="splide__track">
                                <ul class="splide__list" id='thumb-gallery'>
                                    @foreach ($imagesGallery as $galleryItem)
                                        <li class="splide__slide" data-splide-interval="3000"
                                            data-mfp-src="{{ asset($galleryItem->image) }}">
                                            <img src="{{ asset($galleryItem->image) }}" alt="" loading="lazy">
                                        </li>
                                    @endforeach


                                </ul>
                                <a href="#thumb-gallery" class="btn-large-view btn-danger btn-gallery-popup">
                                    View Larger
                                    <i class="fa fa-search-plus"></i>
                                </a>
                            </div>
                        </div>
                        @if (count($imagesGallery) > 1)
                            <div id="thumbnails" class="splide">
                                <div class="splide__track">
                                    <ul class="splide__list">
                                        @foreach ($imagesGallery as $galleryItem)
                                            <li class="splide__slide thumbnail">
                                                <img src="{{ asset($galleryItem->image) }}" class="thumbnail-image"
                                                    alt="" loading="lazy">
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </section>



                    {{-- <ul id="thumbnails" class="thumbnails">
                        @foreach ($imagesGallery as $galleryItem)
                            <li class="thumbnail">
                                <img src="{{ asset($galleryItem->image) }}" class="thumbnail-image" alt=""
                                    loading="lazy">
                            </li>
                        @endforeach
                    </ul> --}}
                </div>
            </div>
            <div class="col-md-3 wow animate fadeInRight" data-wow-delay="200ms" data-wow-duration="1500ms">
                <div class="auction-details-content">
                    <div class="batch">
                        <h6>
                            @if (isVendorActivated())
                                {{ $vendor }}
                                <br>
                                <br>
                            @endif
                            <span style="font-size: 14px; font-weight: 400">
                                SKU: <strong>{{ $selectedSku }}</strong>
                            </span>
                        </h6>
                    </div>
                    <h3 class="my-3">
                        {{ $product->name }}
                    </h3>
                    @if (auth()->guard('representative')->check() || auth()->guard('dealer')->check() || auth('web')->check())
                        @auth('dealer')
                            @if (auth('dealer')->user()->is_customer_mode_active)
                                @php
                                    $dealerPercentage = auth('dealer')->user()->fake_sale_percentage;
                                    $compare_at_price = (float) $compare_at_price * (float) $dealerPercentage;
                                    $price = (float) $price * (float) $dealerPercentage;
                                @endphp
                            @endif
                        @endauth
                        <ul class="artist-info">
                            <li><span>Price :</span>
                                @if ($compare_at_price && $compare_at_price > $price && $compare_at_price > 0)
                                    <span style="text-decoration: line-through; color: #999999">
                                        ${{ $compare_at_price }}
                                    </span>
                                    <strong>
                                        ${{ $price }}
                                    </strong>
                                @else
                                    <strong>${{ $price }}</strong>
                                @endif
                            </li>
                        </ul>
                    @endif

                    @if (isset($groupedAttributes) && count($groupedAttributes))
                        <div class="mt-3 quantity-area position-relative">
                            @foreach ($groupedAttributes as $attribute => $values)
                                @php
                                    $attribute_id = $values['id'];
                                    $values = $values['values'];
                                @endphp

                                <h6 class="mt-4 mb-0">{{ $attribute }}</h6>
                                <div class="mb-3 values-container">
                                    @forelse($values as $value)
                                        <span @class(['active' => in_array($value['id'], $selectedAttributeValues)])
                                            wire:click='selectAttributeValue("{{ $attribute_id }}", "{{ $value['id'] }}")'>
                                            {{ $value['value'] }}
                                        </span>
                                    @empty
                                        <p>No values found</p>
                                    @endforelse
                                </div>
                            @endforeach
                            {{-- loading spinner --}}
                            <div wire:loading>
                                <div class="loading-spinner"
                                    style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; justify-content: center; align-items: center; background-color: rgba(255, 255, 255, 0.8);">
                                    <div class="spinner-border text-secondary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($variantNotFound)
                        <div class="alert alert-warning" style="text-align: center;" wire:tranistion>
                            No variant found for the selected attributes. Please try different combinations.
                            <br>
                            <button wire:click="setDefaultAttributeValues" class="mt-2 btn btn-sm btn-secondary">
                                Reset
                            </button>
                        </div>
                    @endif

                    @if (auth()->guard('representative')->check() || auth()->guard('dealer')->check() || auth('web')->check())
                        {{-- ========== Add To Cart ========= --}}
                        <div class="add-to-cart">
                            @if (!$variantNotFound)
                                <div style="display: flex; gap: 10px; align-items: center;" wire:tranistion>
                                    <button class="btn" wire:click="decreaseQuantity"
                                        style="background-color: #f5f5f5; color: #000;">-</button>
                                    <input type="number" style="min-width: max-content; padding: 5px; border: none"
                                        wire:model="quantity" min="1">
                                    <button class="btn" wire:click="increaseQuantity"
                                        style="background-color: #f5f5f5; color: #000;">+</button>
                                </div>
                                <button class="mt-3 btn" wire:click="addToCart" @disabled($variantNotFound)
                                    style="background-color: #000; color: #fff; max-width: fit-content; padding: 10px 20px;">
                                    Add to Cart
                                </button>
                            @endif
                        </div>
                        {{-- ========== End Add To Cart ========= --}}
                    @endif

                </div>
            </div>
        </div>
        <div class="mt-5">
            {!! str_replace(
                'ðŸ‡ºðŸ‡',
                '<img src="' . asset('assets/america-flag.webp') . '" alt="" style="height: 30px; object-fit: contain;">',
                $description,
            ) !!}
        </div>
    </div>


    {{-- @if (isset($relatedProducts) && count($relatedProducts)) --}}
    {{-- =========== Related Products Section ========= --}}
    {{-- <div class="container pt-5" wire:ignore>
            <hr>
            <div class="flex-wrap gap-3 row mb-60 align-items-center justify-content-between wow animate fadeInDown"
                data-wow-delay="200ms" data-wow-duration="1500ms">
                <div class="col-lg-8 col-md-9">
                    <div class="section-title">
                        <h3>
                            Related Products
                        </h3>
                        <p>
                            Check out some of our related products
                        </p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 d-flex justify-content-md-end">
                    <a href="{{ route('frontend.shop') }}" class="view-all-btn">View All</a>
                </div>
            </div>
            <div class="general-art-slider-wrap wow animate fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
                <div class="row">
                    <div class="col-lg-12">

                        <section id="related-products" class="splide" aria-label="My Awesome Gallery">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    @foreach ($relatedProducts as $product)
                                        <li class="splide__slide">
                                            <div class="auction-card general-art">
                                                <div class="auction-card-img-wrap"
                                                    style="height: 200px; overflow: hidden; border-radius: 10px;">
                                                    <a href="{{ route('frontend.product', $product->handle) }}"
                                                        class="card-img">
                                                        <img src="{{ $product->image_src }}" loading="lazy"
                                                            style="object-fit: contain; width: 100%; height: 100%;"
                                                            alt="{{ $product->image_alt_text ?? $product->title }}">
                                                    </a>
                                                </div>
                                                <div class="auction-card-content">
                                                    <h6>
                                                        <a href="{{ route('frontend.product', $product->handle) }}">
                                                            {{ $product->title }}
                                                        </a>
                                                    </h6>
                                                    <ul>
                                                        <li>
                                                            <span>
                                                                Vendor :
                                                            </span>
                                                            {{ $product->vendor }}
                                                        </li>
                                                        @auth('dealer')
                                                            <li><span>Price : </span>
                                                                <strong>${{ $product->variant_price }}</strong>
                                                            </li>
                                                        @endauth
                                                    </ul>
                                                    <a href="{{ route('frontend.product', $product->handle) }}"
                                                        class="bid-btn btn-hover">
                                                        <span>
                                                            View Details
                                                        </span>
                                                        <strong></strong>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div> --}}
    {{-- @endif --}}
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>

    <script>
        // make sure the qunatity is always a number and more than 1 with every keyup or change
        $(document).ready(function() {
            $('input[type="number"]').on('change keyup', function() {
                if ($(this).val() < 1) {
                    $(this).val(1);
                }
            });


            // Magnific popup
            var galleryBtnPopup = $(".btn-gallery-popup");
            galleryBtnPopup.on('click', function(event) {
                event.preventDefault();

                var gallery = $(this).attr('href');
                console.log(gallery);

                $(gallery).magnificPopup({
                    delegate: '[data-mfp-src]',
                    type: 'image',
                    closeOnContentClick: false,
                    closeBtnInside: false,
                    mainClass: 'ht-mfp zoom-animate mfp-img-mobile',
                    removalDelay: 800,
                    image: {
                        verticalFit: true
                    },
                    gallery: {
                        enabled: true
                    }
                }).magnificPopup('open');
            });
        });
    </script>
@endpush


@script
    <script>
        let splide = new Splide("#main-carousel", {
            arrows: false,
            pagination: false,
            type: "loop",
            perPage: 1,
            cover: false,
            height: "100%",
            autoplay: false,
        });

        let thumbnailsCarousel = new Splide('#thumbnails', {
            fixedWidth: 110,
            fixedHeight: 90,
            gap: 10,
            rewind: true,
            pagination: false,
            cover: false,
            isNavigation: true,
            focus: 'center',
            breakpoints: {
                600: {
                    fixedWidth: 60,
                    fixedHeight: 44,
                },
            },
        });

        let thumbnails = document.getElementsByClassName("thumbnail");
        let current;

        for (let i = 0; i < thumbnails.length; i++) {
            initThumbnail(thumbnails[i], i);
        }

        function initThumbnail(thumbnail, index) {
            thumbnail.addEventListener("click", function() {
                splide.go(index);
            });
        }

        splide.on("mounted move", function() {
            let thumbnail = thumbnails[splide.index];
            console.log({
                thumbnail
            })
            if (thumbnail) {
                if (current) {
                    current.classList.remove("is-active");
                }

                thumbnail.classList.add("is-active");
                current = thumbnail;
            }
        });

        splide.sync(thumbnailsCarousel);
        splide.mount();
        thumbnailsCarousel.mount();

        $wire.on('activateVariantThumbnail', (event) => {
            let variantImage = event[0].image; // the image path of the selected variant
            let variantImageSrc = "{{ asset('') }}" + variantImage; // the full image path

            // Find the index of the variant image in the thumbnails
            let variantIndex = Array.from(thumbnails).findIndex((thumbnail) => {
                let thumbnailImage = thumbnail.querySelector('.thumbnail-image');
                return thumbnailImage.src.includes(
                    variantImage); // Check if the thumbnail image contains the variant image
            });

            console.log({
                variantImage
            })
            if (variantIndex !== -1) {
                splide.go(variantIndex); // Move to the corresponding slide
            }
        });
    </script>
@endscript
