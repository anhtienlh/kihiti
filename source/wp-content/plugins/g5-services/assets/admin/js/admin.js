var G5Services_Admin = G5Services_Admin || {};
(function ($) {
    "use strict";
    G5Services_Admin = {
        onHandle : false,
        init: function () {
            var _that = this;
            $(document).on('click','.g5services-featured > .g5services__featured',function (event) {
                event.preventDefault();
                if (_that.onHandle) return;
                var $this = $(this),
                    data = $this.data('options'),
                    icon = $this.children('span');
                $.ajax({
                    type: 'POST',
                    data: data,
                    url: g5services_var.ajax_url,
                    success: function (response) {
                        if(response.success) {
                            if (!data.status) {
                                icon.attr('class', 'dashicons dashicons-star-filled');
                            } else {
                                icon.attr('class', 'dashicons dashicons-star-empty');
                            }
                            data.status = data.status === 0 ? 1 : 0;
                            $this.data('options',data);
                        }
                        _that.onHandle = false;
                    },
                    error: function (xhr) {
                        _that.onHandle = false;
                    }
                });
            });
        }
    };
    $(document).ready(function () {
        G5Services_Admin.init();
    });
})(jQuery);