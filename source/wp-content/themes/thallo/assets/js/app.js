var CORETHEME = CORETHEME || {};
(function ($) {
	"use strict";

	CORETHEME = {
		init: function () {
			this.search();
			this.mobileEvent();
		},
		isMobile: function () {
			var responsive_breakpoint = 991;
			return window.matchMedia('(max-width: ' + responsive_breakpoint + 'px)').matches;
		},
		search: function () {
			$('.search-form-wrapper .search-icon').on('click', function () {
				$(this).closest('.search-form-wrapper').find('.search-form').toggle();
			});

			$(document).on('click', function (event) {
				if ($(event.target).closest('.search-form-wrapper').length === 0) {
					$('.search-form-wrapper .search-form').hide();
				}
			});
		},
		mobileEvent: function () {
			$('.site-header .menu-toggle-button').on('click', function () {
				var $this = $(this);
				if ($this.hasClass('in')) {
					$this.removeClass('in');
					$('.site-navigation').slideUp();
				}
				else {
					$this.addClass('in');
					$('.site-navigation').slideDown();
				}

			});

			$('.main-menu a').on('click', function (event) {
				if (CORETHEME.isMobile()) {
					if ($(event.target).closest('.caret').length !== 0) {
						event.preventDefault();
					}
				}

			});
			$('.main-menu .menu-item-has-children .caret').on('click', function () {
				if (CORETHEME.isMobile()) {
					var $this = $(this);
					$this.closest('li').find(' > .sub-menu').slideToggle();
					$this.toggleClass('in');
				}
			});
		}
	};

	$(document).ready(function () {
		CORETHEME.init();
	});
	$(window).resize(function () {
		if (!CORETHEME.isMobile()) {
			$('.site-header .menu-toggle-button').removeClass('in');
			$('.main-menu .menu-item-has-children .caret').removeClass('in');
			$('.site-navigation').css('display', '');
			$('.main-menu .menu-item-has-children > .sub-menu').css('display', '');
		}
	});
})(jQuery);