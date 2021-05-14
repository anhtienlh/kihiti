var G5Works = G5Works || {};
(function ($) {
    "use strict";
    G5Works = {
        ajax_call: false,
        cache: {},
        init: function () {
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
        categoryDropdown: function () {
            $('.g5works__dropdown_categories').on('change',function () {
               if ($(this).val() !== '') {
                   var this_page = '';
                   var home_url  = g5works_variable.home_url;
                   if ( home_url.indexOf( '?' ) > 0 ) {
                       this_page = home_url + '&works_category=' + $(this).val();
                   } else {
                       this_page = home_url + '?works_category=' + $(this).val();
                   }
                   location.href = this_page;
               } else {
                   location.href = g5works_variable.archive_url;
               }
            });

            if ($().select2) {
                $( '.g5works__dropdown_categories' ).select2( {
                    placeholder: g5works_variable.localization.dropdown_categories_placeholder,
                    minimumResultsForSearch: 5,
                    width: '100%',
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return g5works_variable.localization.dropdown_categories_noResults;
                        }
                    }
                } );
            }
        }
    };
    $(document).ready(function () {
        G5Works.init();
    });
})(jQuery);