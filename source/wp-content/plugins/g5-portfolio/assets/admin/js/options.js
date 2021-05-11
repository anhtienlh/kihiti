var G5Portfolio_Admin_Option = G5Portfolio_Admin_Option || {};
(function ($) {
    "use strict";
    G5Portfolio_Admin_Option = {
        init: function () {
            var _self = this;
            $('[name="post_layout"]').on('click',function () {
               var $this = $(this),
                   layout = $this.val(),
                   currentSkin = '';

                $('[name="item_skin"]').each(function () {
                    $(this).closest('label').show();
                    if ($(this).is(':checked')) {
                        currentSkin = $(this).val();
                    }
                });

               if ((layout !== 'grid')
                   && (layout !== 'masonry')
                   && (layout !== 'masonry-2')) {

                   if ((currentSkin === 'skin-01')
                   || (currentSkin === 'skin-02')) {
                       currentSkin = 'skin-03';
                   }

                    $('[name="item_skin"]').each(function () {
                        if ($(this).val() === currentSkin) {
                            $(this).prop('checked', true);
                        } else {
                            $(this).prop('checked', false);
                        }

                       if ( ($(this).val() === 'skin-01')
                       || ($(this).val() === 'skin-02')) {
                           $(this).closest('label').hide();
                       }
                    });
               }
            });

            $('[name="post_layout"]').each(function () {
                if ($(this).is(':checked')) {
                    $(this).trigger('click');
                }
            });

        }
    };
    $(document).ready(function () {
        G5Portfolio_Admin_Option.init();
    });
})(jQuery);