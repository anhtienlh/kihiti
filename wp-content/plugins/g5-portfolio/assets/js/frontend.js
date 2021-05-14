var G5Portfolio = G5Portfolio || {};
(function ($) {
    "use strict";
    G5Portfolio = {
        ajax_call: false,
        cache: {},
        init: function () {
            this.lightBoxGallery();
            this.categoryDropdown();
        },
        addCache: function (key, value) {
            if (typeof this.cache === 'undefined') {
                this.cache = {};
            }
            if (typeof this.cache[key] !== 'undefined') return;
            this.cache[key] = value;

        },
        getCache: function (key) {
            if ((typeof this.cache !== 'undefined') && (typeof this.cache[key] !== 'undefined')) {
                return this.cache[key];
            }
            return '';
        },
        lightBoxGallery : function () {
            var _self = this;
            $(document).on('click','[data-g5portfolio-light-box]',function (event) {
               event.preventDefault();
                if (_self.ajax_call !== false) {
                    return false;
                }

                var $this = $(this),
                    $wrapper = $this.closest('.g5core__post-featured'),
                    id = parseInt($this.data('id'),10),
                    nonce = $this.data('nonce'),
                    cacheKey = 'g5portfolio__gallery_' + id,
                    cacheData = _self.getCache(cacheKey);
                $wrapper.addClass('active');
                $this.addClass('spinner');

                if (cacheData !== '') {
                    _self.openGallery(cacheData);
                    _self.ajax_call = false;
                    $this.removeClass('spinner');
                    $wrapper.removeClass('active');
                } else {
                    _self.ajax_call = $.ajax({
                        'url': g5portfolio_variable.ajax_url,
                        'data' : {
                            action : 'g5portfolio_light_box_gallery',
                            id : id,
                            _ajax_nonce: nonce
                        },
                        success: function (response) {
                            _self.ajax_call = false;
                            $this.removeClass('spinner');
                            $wrapper.removeClass('active');
                            if (response.success) {
                                _self.addCache(cacheKey, response.data);
                                _self.openGallery(response.data);
                            }
                        },
                        error: function (xhr) {
                            console.log(xhr);
                            _self.ajax_call = false;
                            $this.removeClass('spinner');
                            $wrapper.removeClass('active');
                        }
                    });
                }
            });
        },
        openGallery : function (data) {
            //console.log(data);
            if (typeof ($().magnificPopup) === 'function') {
                var type = data.type === 'video' ? 'iframe' : 'image';
                $.magnificPopup.open({
                    type: type,
                    mainClass: 'mfp-zoom-in',
                    closeOnBgClick: true,
                    closeBtnInside: false,
                    midClick: true,
                    removalDelay: 500,
                    items: data.items,
                    gallery: {
                        enabled: true
                    },
                    callbacks: {
                        beforeOpen: function () {
                            // just a hack that adds mfp-anim class to markup
                            switch (this.st.type) {
                                case 'image':
                                    this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                                    break;
                                case 'iframe' :
                                    this.st.iframe.markup = this.st.iframe.markup.replace('mfp-iframe-scaler', 'mfp-iframe-scaler mfp-with-anim');
                                    break;
                            }
                        },
                        change: function () {
                            var _this = this;
                            if (this.isOpen) {
                                this.wrap.removeClass('mfp-ready');
                                setTimeout(function () {
                                    _this.wrap.addClass('mfp-ready');
                                }, 10);
                            }
                        }
                    }
                });
            }
        },
        categoryDropdown: function () {
            $('.g5portfolio__dropdown_categories').on('change',function () {
               if ($(this).val() !== '') {
                   var this_page = '';
                   var home_url  = g5portfolio_variable.home_url;
                   if ( home_url.indexOf( '?' ) > 0 ) {
                       this_page = home_url + '&portfolio_category=' + $(this).val();
                   } else {
                       this_page = home_url + '?portfolio_category=' + $(this).val();
                   }
                   location.href = this_page;
               } else {
                   location.href = g5portfolio_variable.archive_url;
               }
            });

            if ($().select2) {
                $( '.g5portfolio__dropdown_categories' ).select2( {
                    placeholder: g5portfolio_variable.localization.dropdown_categories_placeholder,
                    minimumResultsForSearch: 5,
                    width: '100%',
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return g5portfolio_variable.localization.dropdown_categories_noResults;
                        }
                    }
                } );
            }
        }
    };
    $(document).ready(function () {
        G5Portfolio.init();
    });
})(jQuery);