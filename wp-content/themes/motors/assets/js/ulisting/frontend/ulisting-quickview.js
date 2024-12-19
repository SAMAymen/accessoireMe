jQuery(document).ready(function () {
    var big, small, listing_id
    jQuery(document).on("click",".qview", function(event) {
        event.preventDefault()
        listing_id = jQuery(this).data('id')
        let spinner = '<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>'
        quick_fields(this, spinner, "html")

        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                listing_id  : listing_id,
                action      : 'stm_listing_quick_view',
            },
            beforeSend: function() {
                let clean = [
                    ".stm-quickview-title",
                    ".stm-listing-desc",
                    ".listing_price",
                    ".listing-cat",
                    ".content-atribute",
                    ".stm-wishlist"
                ];
                jQuery.each(clean, (key, value) => quick_fields(value, '', "text"));
            },
            success(data) {
                if ( !data ) {
                    no_data()
                    return
                }

                if ( typeof data === "object" && Object.keys(data).length > 0 ) {
                    let attr = {
                        ".stm-quickview-title"  : data.title,
                        ".stm-listing-desc"     : data.description,
                        ".listing_price"        : data.price.price,
                        ".listing-cat"          : data.category,
                        ".stm-wishlist"         : data.stm_wishlist,
                    };

                    jQuery.each(attr, function (key, value) {
                        if ( key === ".listing-cat" ){
                            cat(value);
                        } else {
                            quick_fields(key, value, "html");
                        }
                    });

                    jQuery(".listing-btn-view").attr("href", data.permalink)
                    attributes(data.attribute)

                    const gallery = data.gallery
                    if ( Array.isArray(gallery) ) {
                        let active = true
                        const $wrapper = document.querySelector('#quick-view-gallery')
                        const $thumb = document.querySelector('#quick-view-thumb')
                        gallery.forEach((item, index) => {
                            const $item = render_gallery_items(item, active)
                            const $thumb_item = render_gallery_thumb(item)
                            $wrapper.insertAdjacentHTML('beforeend', $item)
                            $thumb.insertAdjacentHTML('beforeend', $thumb_item)
                            active = false
                        })
                    }

                    jQuery("#centralModalSm").modal('show')
                    spinner = '<i class="fa fa-eye" aria-hidden="true"></i>'
                    quick_fields(".qview", spinner, "html")

                    setTimeout(() => {
                        initSlider()
                    }, 300)
                } else {
                    no_data();
                }
            },
            error: function () {
                    no_data();
            }
        });
    });

    function render_gallery_items(item, active) {
        let [big, ] = item.big
        return `
            <div class="big-item ${active ? 'active' : ''}">
                <div class="big-item-image" style="background-image: url(${big})"></div>
            </div>
        `
    }

    function render_gallery_thumb(item) {
        let [thumbnail] = item.thumbnail
        return `
            <div class="thumb" >
                <div class="thumb-image" style="background-image: url(${thumbnail})"></div>
            </div>
        `
    }


    function no_data(){
        quick_fields(".stm-quickview-title", 'No Data', "text");
        jQuery("#centralModalSm").modal('show');
    }

    function quick_fields(field_id, data, show_tag) {
        if ( show_tag === "text" ) {
            jQuery(field_id).text(data);
        } else {
            jQuery(field_id).html(data);
        }
    }

    function attributes(attributes) {
        if ( typeof attributes !== 'undefined' ) {
            const attribute = Object.values(attributes).map(attribute => `<div class="ulisting-attribute-template stm-attr"><span class="ulisting-attribute-template-icon">${attribute.icon}</span>${attribute.atr_val} ${attribute.attrib_title}</div>`);
            quick_fields(".content-atribute", attribute, "html")
        }
    }

     function cat(categories) {
        const category =  categories?.map(category=>`<span class="inventory_category inventory_category_style_1">${category}</span>`);
        quick_fields(".listing-cat", category, "html")
    }

    function clearSlider() {
        let mainGallery = document.querySelector('#quick-view-gallery')
        let thumbGallery = document.querySelector('#quick-view-thumb')

        mainGallery.classList.remove('owl-carousel', 'owl-theme', 'owl-loaded')
        thumbGallery.classList.remove('owl-carousel', 'owl-theme', 'owl-loaded')

        mainGallery.innerHTML = ''
        thumbGallery.innerHTML = ''
        big.trigger('destroy.owl.carousel')
        small.trigger('destroy.owl.carousel')

    }

    function initSlider() {
        const $ = jQuery
        big = $('.big-wrap');
        small = $('.thumbs-wrap');
        var flag = false;
        var duration = 800;
        big
            .owlCarousel({
                rtl: false,
                items: 1,
                smartSpeed: 800,
                dots: false,
                nav: false,
                margin: 0,
                autoplay: false,
                loop: true,
            })

        small
            .owlCarousel({
                rtl: false,
                items: 5,
                smartSpeed: 800,
                dots: false,
                margin: 0,
                autoplay: false,
                nav: true,
                navElement: 'div',
                loop: true,
                navText: [],
                responsiveRefreshRate: 1000,
                responsive: {
                    500: {
                        items: 3,
                        margin: 4
                    },

                    1000: {
                        items: 4,
                        margin: 10
                    }
                }
            })
            .on('click', '.owl-item', function (event) {
                big.trigger('to.owl.carousel', [$(this).index(), 400, true]);
            })
            .on('changed.owl.carousel', function (e) {
                if (!flag) {
                    flag = true;
                    big.trigger('to.owl.carousel', [e.item.index, duration, true]);
                    flag = false;
                }
            });

        if ($('.thumbs-wrap .thumb').length < 6) {
            $('.stm-single-car-page .owl-controls').hide();
            $('.thumbs-wrap').css({'margin-top': '22px'});
        }
    }

    function init_watchers() {
        document.querySelector('#centralModalSm').addEventListener('click', (e) => {
            const $centralModalSm = document.querySelector('#centralModalSm')
            const $modalCloseButton = document.querySelector('.quickview-close')
            if ( !e.path.indexOf($modalCloseButton) || !e.path.indexOf($centralModalSm)) {
                clearSlider()
            }
        })
    }

    init_watchers()
});