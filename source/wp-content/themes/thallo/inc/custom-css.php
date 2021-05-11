<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( function_exists( 'G5CORE' ) ) {
	add_action( 'template_redirect', 'thallo_custom_css', 20 );
}

function thallo_custom_css() {
	$custom_css = '';
	$custom_css .= thallo_custom_text_color();
	$custom_css .= thallo_custom_accent_color();
	$custom_css .= thallo_custom_border_color();
	$custom_css .= thallo_custom_heading_color();
	$custom_css .= thallo_custom_caption_color();
	$custom_css .= thallo_custom_placeholder_color();
	$custom_css .= thallo_custom_primary_color();
	$custom_css .= thallo_custom_secondary_color();
	$custom_css .= thallo_custom_dark_color();
	$custom_css .= thallo_custom_light_color();
	$custom_css .= thallo_custom_body_font();
	$custom_css .= thallo_custom_primary_font();

	G5CORE()->custom_css()->addCss( $custom_css );
}

function thallo_custom_text_color() {
	$text_color = G5CORE()->options()->color()->get_option( 'site_text_color' );
	$submenu_text_hover_color = G5CORE()->options()->header()->get_option( 'submenu_text_hover_color' );

	return <<<CSS

@media (min-width: 768px) {
.wpb-js-composer.wpb-js-composer .custom-tab.vc_tta.vc_tta-tabs ul.vc_tta-tabs-list li.vc_tta-tab:not(.vc_active) > a {
   color: {$text_color};
}
}
.text-color,
body,
.custom-color-social-icons.gel-social-icons .si-shape i,
.g5core-site-footer .widget_nav_menu ul li a {
    color: {$text_color};
}

.menu-horizontal .sub-menu {
border-color: {$submenu_text_hover_color};
}

CSS;

}

function thallo_custom_accent_color() {
	$accent_color            = G5CORE()->options()->color()->get_option( 'accent_color' );
	$accent_foreground_color = g5core_color_contrast( $accent_color );
	$accent_color_darken_075 = g5core_color_darken( $accent_color, '7.5%' );
	$accent_color_darken_10  = g5core_color_darken( $accent_color, '10%' );
	$accent_color_lighten_10 = g5core_color_lighten( $accent_color, '10%' );
	$accent_adjust_brightness_15 = g5core_color_adjust_brightness( $accent_color ,'15%');


	return <<<CSS

::-moz-selection {
  background-color: {$accent_color};
  color: {$accent_foreground_color};
}

::selection {
  background-color: {$accent_color};
  color: {$accent_foreground_color};
}

select {
  background-image: linear-gradient(45deg, transparent 50%, {$accent_color} 50%), linear-gradient(135deg, {$accent_color} 50%, transparent 50%) !important;
}

.btn,
button,
input[type=button],
input[type=reset],
input[type=submit] {
  color: {$accent_foreground_color};
  background-color: {$accent_color};
  border-color: {$accent_color};
}

.btn:focus, .btn:hover, .btn:active,
button:focus,
button:hover,
button:active,
input[type=button]:focus,
input[type=button]:hover,
input[type=button]:active,
input[type=reset]:focus,
input[type=reset]:hover,
input[type=reset]:active,
input[type=submit]:focus,
input[type=submit]:hover,
input[type=submit]:active {
  color: {$accent_foreground_color};
  background-color: {$accent_color_darken_075};
  border-color: {$accent_color_darken_10};
}

.btn.btn-outline,
button.btn-outline,
input[type=button].btn-outline,
input[type=reset].btn-outline,
input[type=submit].btn-outline {
  color: {$accent_color};
}

.btn.btn-outline:focus, .btn.btn-outline:hover, .btn.btn-outline:active,
button.btn-outline:focus,
button.btn-outline:hover,
button.btn-outline:active,
input[type=button].btn-outline:focus,
input[type=button].btn-outline:hover,
input[type=button].btn-outline:active,
input[type=reset].btn-outline:focus,
input[type=reset].btn-outline:hover,
input[type=reset].btn-outline:active,
input[type=submit].btn-outline:focus,
input[type=submit].btn-outline:hover,
input[type=submit].btn-outline:active {
  background-color: {$accent_color};
  color: {$accent_foreground_color};
  border-color: {$accent_color};
}

.btn.btn-link,
button.btn-link,
input[type=button].btn-link,
input[type=reset].btn-link,
input[type=submit].btn-link {
  color: {$accent_color};
}

.btn.btn-gradient,
button.btn-gradient,
input[type=button].btn-gradient,
input[type=reset].btn-gradient,
input[type=submit].btn-gradient {
  background-image: -webkit-gradient(linear, left top, right top, from({$accent_color}), color-stop(51%, {$accent_adjust_brightness_15}), to({$accent_color}));
  background-image: linear-gradient(to right, {$accent_color} 0%, {$accent_adjust_brightness_15} 51%, {$accent_color} 100%);
}

.btn.btn-accent {
  color: {$accent_foreground_color};
  background-color: {$accent_color};
  border-color: {$accent_color};
}

.btn.btn-accent.btn-outline {
  color: {$accent_color};
}

.btn.btn-accent.btn-outline:focus, .btn.btn-accent.btn-outline:hover, .btn.btn-accent.btn-outline:active {
  background-color: {$accent_color};
  color: {$accent_foreground_color};
  border-color: {$accent_color};
}
.btn.btn-accent.btn-link {
  color: {$accent_color};
}
.btn.btn-accent.btn-gradient {
  background-image: -webkit-gradient(linear, left top, right top, from({$accent_color}), color-stop(51%, {$accent_adjust_brightness_15}), to({$accent_color}));
  background-image: linear-gradient(to right, {$accent_color} 0%, {$accent_adjust_brightness_15} 51%, {$accent_color} 100%);
}

@media (min-width: 768px) {
  .wpb-js-composer.wpb-js-composer .custom-tab.vc_tta.vc_tta-tabs ul.vc_tta-tabs-list li.vc_tta-tab:before {
    background-color: {$accent_color};
  }
  
    .wpb-js-composer.wpb-js-composer .custom-tab.vc_tta.vc_tta-tabs ul.vc_tta-tabs-list li.vc_tta-tab > a:hover,
.wpb-js-composer.wpb-js-composer .custom-tab.vc_tta.vc_tta-tabs ul.vc_tta-tabs-list li.vc_tta-tab a:focus {
    color: {$accent_color} !important;
  }
}

.g5core-site-footer #ctf .ctf-item .ctf-tweet-meta a {
  color: {$accent_color} !important;
}

.pricing-featured-text {
  border-color: {$accent_color_lighten_10};
}


.wp-block-button__link:not(.has-background):not(.has-text-color) {
  color: {$accent_foreground_color};
  background-color: {$accent_color};
  border-color: {$accent_color};
}

.wp-block-button__link:not(.has-background):not(.has-text-color):focus, .wp-block-button__link:not(.has-background):not(.has-text-color):hover, .wp-block-button__link:not(.has-background):not(.has-text-color):active {
  color: {$accent_foreground_color};
  background-color: {$accent_color_darken_075};
  border-color: {$accent_color_darken_10};
}

.wp-block-button.is-style-outline .wp-block-button__link:hover {
  background-color: {$accent_color} !important;
}

.tagcloud a:hover {
  background-image: -webkit-gradient(linear, left top, right top, from({$accent_color}), color-stop(51%, {$accent_adjust_brightness_15}), to({$accent_color}));
  background-image: linear-gradient(to right, {$accent_color} 0%, {$accent_adjust_brightness_15} 51%, {$accent_color} 100%);
}

blockquote cite, .custom-mailchimp .thallo-mailchimp button[type=submit]:before, .white-text-color .thallo-contact .submit input[type=submit], .gel-icon-box .title:hover, .gel-icon-box .btn.btn-link:after, .gel-icon-box .btn.btn-link:hover, .gel-image-box h4.title a:hover, .gel-image-box .btn.btn-link:after, .gel-image-box .btn.btn-link:hover, .gel-our-team h4.gel-our-team-name, .gel-our-team .gel-our-team-social:hover, .slick-arrows .slick-arrow, .custom-color-social-icons.gel-social-icons a:hover i, .gel-testimonial-name, .wpb-js-composer.wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.vc_tta-accordion.custom-accordion .vc_tta-controls-icon.vc_tta-controls-icon-plus, .wpb-js-composer.wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.vc_tta-accordion.custom-accordion.accordion-box-shadown .vc_tta-panel.vc_active .vc_tta-panel-title, div.x-mega-sub-menu .gel-list .gel-list-item:hover, .site-info a, .g5core-site-footer #ctf .ctf-item .ctf-author-box:before, ul.breadcrumbs li.breadcrumb-leaf, .g5core-breadcrumbs li.breadcrumb-leaf, .slick-dots li.slick-active,
.slick-dots li:hover,
.slick-arrow:active,
.slick-dots li:active,
.slick-arrow:focus,
.slick-dots li:focus, .g5core__paging.next-prev > a, .g5core__cate-filer li:hover, .g5core__cate-filer li:active, .g5core__cate-filer li.active, ul.g5core__share-list li a:hover, .wp-block-pullquote cite,
.wp-block-pullquote footer,
.wp-block-pullquote .wp-block-pullquote__citation, .wp-block-quote cite,
.wp-block-quote footer,
.wp-block-quote .wp-block-quote__citation, .wp-block-button.is-style-outline .wp-block-button__link:not(.has-text-color), .wp-block-archives li > a:hover,
.wp-block-categories li > a:hover, .wp-block-archives .current-cat > a,
.wp-block-categories .current-cat > a, .wp-block-latest-posts a:hover,
.wp-block-latest-comments a:hover, .article-post .entry-title a:hover, .article-post .entry-meta a:hover, .article-post .entry-meta .meta-author .title-meta-author, .article-post .btn-read-more i, .author-info-content .name a:hover, .comment-form a:hover, .comment-list li.pingback .comment-body .edit-link a:hover,
.comment-list li.trackback .comment-body .edit-link a:hover, .comment-list .comment-author .fn > a:hover, .comment-list .reply a:after, .comment-list .reply a:hover,
.comment-list .comment-metadata a:hover, .comment-list .comment-reply-title small a:hover, .page-numbers:not(ul).prev, .page-numbers:not(ul).next, ul.g5blog__post-meta li.meta-author span, ul.g5blog__post-meta li a:hover, .g5blog__post-title:hover, .widget_search input:focus + button:before, .widget_rss ul a:hover,
.widget_recent_entries ul a:hover,
.widget_recent_comments ul a:hover,
.widget_meta ul a:hover, .widget_archive ul li > a:hover,
.widget_categories ul li > a:hover,
.widget_nav_menu ul li > a:hover,
.widget_pages ul li > a:hover, .widget_archive ul .current-cat > a,
.widget_categories ul .current-cat > a,
.widget_nav_menu ul .current-cat > a,
.widget_pages ul .current-cat > a, .widget_pages ul .current_page_item > a, .widget_nav_menu ul .current-menu-item > a, .content-404-wrapper h2 {
  color: {$accent_color};
}

.faq-active-bg-blue div.vc_toggle.vc_toggle_active .vc_toggle_icon, .custom-image-box-style-08 .gel-image-box-style-08 h4.title:before, .image-box-bg-white .gel-image-box:before,
.custom-image-box-style-08 .gel-image-box:before, .slick-arrows .slick-arrow:hover, .wpb-js-composer.wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.vc_tta-accordion.custom-accordion .vc_tta-panel.vc_active .vc_tta-controls-icon.vc_tta-controls-icon-plus, .g5core-back-to-top:focus, .g5core-back-to-top:hover, .g5core__paging.next-prev > a:hover, .post-navigation .nav-links > div:hover, .page-numbers:not(ul).prev:hover, .page-numbers:not(ul).next:hover, .page-links > .post-page-numbers:hover, .page-links > .post-page-numbers.current {
  background-color: {$accent_color};
}

blockquote, .wpb-js-composer.wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.vc_tta-accordion.custom-accordion .vc_tta-controls-icon.vc_tta-controls-icon-plus, .g5core__paging.next-prev > a:hover, .wp-block-quote, .wp-block-quote.has-text-align-right, .wp-block-button.is-style-outline .wp-block-button__link:hover, .post-navigation .nav-links > div:hover, .page-numbers:not(ul).prev:hover, .page-numbers:not(ul).next:hover, .page-links > .post-page-numbers:hover, .page-links > .post-page-numbers.current {
  border-color: {$accent_color};
}


.accent-foreground-color,
.slick-arrows .slick-arrow:hover,
.pricing-featured-text span,
.g5core-back-to-top:focus,
.g5core-back-to-top:hover,
.g5core__paging.next-prev > a:hover,
.wp-block-button.is-style-outline .wp-block-button__link:hover,
.post-navigation .nav-links > div:hover,
.page-numbers:not(ul).prev:hover,
.page-numbers:not(ul).next:hover,
.page-links > .post-page-numbers:hover,
.page-links > .post-page-numbers.current,
.tagcloud a:hover {
  color: {$accent_foreground_color};
}

.accent-foreground-bg-color,
.slick-arrows .slick-arrow {
  background-color: {$accent_foreground_color};
}

table.g5careers__table a:hover {
  color: {$accent_color};
}

ul.g5careers__single-meta {
  border-color: {$accent_color};
}

.g5portfolio__post-title:hover, .g5portfolio__post-cat a:hover, .g5portfolio__single-navigation .nav-links > div.disabled,
.g5portfolio__single-navigation .nav-links > div a, .g5portfolio__single-meta label {
  color: {$accent_color};
}

.g5portfolio__post-default .g5core__entry-thumbnail:after, .g5portfolio__single-navigation .nav-links > div.disabled:hover,
.g5portfolio__single-navigation .nav-links > div a:hover, .g5portfolio__single-meta:before {
  background-color: {$accent_color};
}

.accent-foreground-color,
.g5portfolio__single-navigation .nav-links > div.disabled:hover,
.g5portfolio__single-navigation .nav-links > div a:hover {
  color: {$accent_foreground_color};
}

.accent-foreground-bg-color,
.g5portfolio__single-navigation .nav-links > div.disabled,
.g5portfolio__single-navigation .nav-links > div a {
  background-color: {$accent_foreground_color};
}


.g5services__loop-view-more:hover, .g5services__loop-view-more i, .g5services__post-cat a:hover, .g5services__post-title:hover, .g5services__single-navigation .nav-links > div.disabled,
.g5services__single-navigation .nav-links > div a {
  color: {$accent_color};
}

.g5services__single-navigation .nav-links > div.disabled:hover,
.g5services__single-navigation .nav-links > div a:hover {
  background-color: {$accent_color};
}

.g5services__post-skin-bordered .g5services__post-inner:before, .custom-services-col-3-skin-bordered .slick-track .slick-slide:before {
  background: {$accent_color};
  /* Old browsers */
  background: -moz-linear-gradient(135deg, {$accent_color} 0%, {$accent_adjust_brightness_15} 100%);
  /* FF3.6-15 */
  background: -webkit-linear-gradient(135deg, {$accent_color} 0%, {$accent_adjust_brightness_15} 100%);
  /* Chrome10-25,Safari5.1-6 */
  background: linear-gradient(135deg, {$accent_color} 0%, {$accent_adjust_brightness_15} 100%);

}

.accent-foreground-color,
.g5services__single-navigation .nav-links > div.disabled:hover,
.g5services__single-navigation .nav-links > div a:hover {
  color: {$accent_foreground_color};
}

.accent-foreground-bg-color,
.g5services__single-navigation .nav-links > div.disabled,
.g5services__single-navigation .nav-links > div a {
  background-color: {$accent_foreground_color};
}

.g5staff__post-title, ul.g5staff__loop-social-profiles li a:hover, .g5staff__single-info .g5staff__loop-job-title {
  color: {$accent_color};
}


.g5works__post-title:hover, .g5works__post-cat:hover, .g5works__single-navigation .nav-links > div.disabled,
.g5works__single-navigation .nav-links > div a {
  color: {$accent_color};
}

.g5works__single-navigation .nav-links > div.disabled:hover,
.g5works__single-navigation .nav-links > div a:hover {
  background-color: {$accent_color};
}

.accent-foreground-color,
.g5works__single-navigation .nav-links > div.disabled:hover,
.g5works__single-navigation .nav-links > div a:hover {
  color: {$accent_foreground_color};
}

.accent-foreground-bg-color,
.g5works__single-navigation .nav-links > div.disabled,
.g5works__single-navigation .nav-links > div a {
  background-color: {$accent_foreground_color};
}


CSS;

}

function thallo_custom_border_color() {
	$border_color = G5CORE()->options()->color()->get_option( 'border_color' );

	return <<<CSS
 
.g5staff__post-skin-01 .g5staff__post-inner {
  -webkit-box-shadow: 0 0 0 1px $border_color ;
  box-shadow: 0 0 0 1px $border_color ;
}
.g5works__post-skin-bordered .g5works__post-inner {
	box-shadow: 0 0 0 1px $border_color;
  -webkit-box-shadow: 0 0 0 1px $border_color;
}
.border-color,
.g5staff__single-info ul.g5staff__loop-social-profiles,
.g5staff__single-info .g5staff__single-info-content,
body.has-sidebar .g5staff__single-info {
  border-color:$border_color;
}
.border-color,
.sidebar-contact-us .textwidget,
.g5services__post-skin-bordered .g5services__post-inner,
.custom-services-col-3-skin-bordered .slick-track .slick-slide,
.custom-services-col-3-skin-bordered .slick-list,
.g5services__post-skin-05 .g5services__post-featured,
.g5services__single-share-wrap {
   border-color:$border_color;
}
.border-color,
.g5portfolio__single-share-wrap {
   border-color:$border_color;
}
.border-color,
table.g5careers__table thead th,
table.g5careers__table td {
     border-color:$border_color;
}
hr {
  border-top-color:$border_color;
}

table thead th {
  vertical-align: bottom;
  border-bottom-color:$border_color;
}
@media (min-width: 768px) {
  .wpb-js-composer.wpb-js-composer .custom-tab.vc_tta.vc_tta-tabs ul.vc_tta-tabs-list {
    border-bottom-color: $border_color;
  }
 }
.border-color,
.wp-block-table th,
.wp-block-table td,
ul.wp-block-latest-posts.is-grid li,
.article-archive-post,
.article-single-post {
  border-color:$border_color;
}
.gel-pricing-line .pricing-features:before {
  background-color: $border_color;
}
.border-color,
.g5works__single-share-wrap {
  border-color:$border_color;
}

.border-color,
.border-client .wpb_wrapper,
.faq-active-bg-blue div.vc_toggle,
.image-box-border .gel-image-box,
.custom-image-box-style-08 .gel-image-box,
.our-team-hover-border .gel-our-team .gel-our-team-inner,
.custom-color-social-icons.gel-social-icons a:hover,
.gel-pricing-line,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.vc_tta-accordion.custom-accordion.accordion-box-shadown .vc_tta-panel:not(.vc_active),
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.vc_tta-accordion.custom-accordion.accordion-background .vc_tta-panel,
.wp-block-table th,
.wp-block-table td,
ul.wp-block-latest-posts.is-grid li,
.article-archive-post,
.article-single-post,
.post-navigation .nav-links > div,
.g5blog__single,
.g5blog__layout-large-image .g5blog__post-default .g5blog__post-inner,
.g5blog__post-medium-image .g5blog__post-inner,
.g5blog__post-grid .g5blog__post-content,
.g5blog__post-grid-custom-02 .g5blog__post-grid .g5blog__post-inner {
   border-color:$border_color;
}
.border-color,
.wp-block-table th,
.wp-block-table td,
ul.wp-block-latest-posts.is-grid li,
.article-archive-post,
.article-single-post {
  border-color:$border_color;
}


CSS;

}

function thallo_custom_heading_color() {
	$heading_color = G5CORE()->options()->color()->get_option( 'heading_color' );

	return <<<CSS


.heading-color,
.g5staff__single-meta span {
  color: $heading_color;
}
.edit-post-layout__content .editor-post-title__block .editor-post-title__input {
   color: $heading_color;
}
.heading-color,
h1,
h2,
h3,
h4,
h5,
h6,
.h1,
.h2,
.h3,
.h4,
.h5,
.h6,
blockquote p,
.wp-block-archives li > a,
.wp-block-categories li > a {
  color: $heading_color;
}
.heading-color,
.g5services__loop-view-more {
    color: $heading_color;
}
.heading-color,
table.g5careers__table thead th,
table.g5careers__table-responsive tbody td:after,
ul.g5careers__single-meta li {
   color: $heading_color;
}
.site-branding-text .site-title a {
  color: $heading_color;
}
.g5core-site-footer #ctf .ctf-item a.ctf-author-name {
   color: $heading_color !important;
}
.heading-color,
h1,
h2,
h3,
h4,
h5,
h6,
.h1,
.h2,
.h3,
.h4,
.h5,
.h6,
blockquote p,
.gel-counter,
.gel-heading-subtitle,
.gel-testimonial-job,
.pricing-name,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.vc_tta-accordion.custom-accordion.white-text-color .vc_tta-panel.vc_active .vc_tta-controls-icon.vc_tta-controls-icon-plus,
div.x-mega-sub-menu .gel-heading-title,
.g5core-site-footer .opening-time p,
ul.breadcrumbs,
.g5core-breadcrumbs,
.g5core__share-label,
.wp-block-archives li > a,
.wp-block-categories li > a,
.comments-area .comments-title,
.comments-area .comment-reply-title,
.comment-list li.pingback .comment-body a:not(.comment-edit-link),
.comment-list li.trackback .comment-body a:not(.comment-edit-link),
.comment-list .comment-author .fn,
.comment-list .reply,
.post-navigation .nav-links .nav-title,
.page-numbers:not(ul).current,
.page-numbers:not(ul):hover,
.g5blog__block-title,
.widget_search button:before,
.widget_recent_entries ul,
.widget_recent_comments ul,
.widget_meta ul,
.widget_archive ul li > a,
.widget_categories ul li > a,
.widget_nav_menu ul li > a,
.widget_pages ul li > a,
.tagcloud label,
.widget_calendar caption {
    color: $heading_color;
}


CSS;

}

function thallo_custom_caption_color() {
	$caption_color = G5CORE()->options()->color()->get_option( 'caption_color' );

	return <<<CSS
   
.caption-color {
  color: $caption_color;
}
   

CSS;

}

function thallo_custom_placeholder_color() {
	$placeholder_color = G5CORE()->options()->color()->get_option( 'placeholder_color' );

	return <<<CSS
   

textarea:-moz-placeholder,
select:-moz-placeholder,
input[type]:-moz-placeholder {
  color: $placeholder_color;
}
textarea::-moz-placeholder,
select::-moz-placeholder,
input[type]::-moz-placeholder {
  color: $placeholder_color;
}
textarea:-ms-input-placeholder,
select:-ms-input-placeholder,
input[type]:-ms-input-placeholder {
  color: $placeholder_color;
}
textarea::-webkit-input-placeholder,
select::-webkit-input-placeholder,
input[type]::-webkit-input-placeholder {
  color: $placeholder_color;
}


CSS;

}

function thallo_custom_primary_color() {
	$primary_color            = G5CORE()->options()->color()->get_option( 'primary_color' );
	$primary_color_foreground = g5core_color_contrast( $primary_color );
	$primary_color_darken_075 = g5core_color_darken( $primary_color, '7.5%' );
	$primary_color_darken_10  = g5core_color_darken( $primary_color, '10%' );

	return <<<CSS
   
 .btn.btn-primary {
   color: {$primary_color_foreground};
  background-color: {$primary_color};
  border-color: {$primary_color};
}
.btn.btn-primary.btn-outline:focus, .btn.btn-primary.btn-outline:hover, .btn.btn-primary.btn-outline:active {
   background-color: {$primary_color};
  color: {$primary_color_foreground};
  border-color: {$primary_color};
}
.btn.btn-primary.btn-link {
  color:{$primary_color};
}
.btn.btn-primary.btn-outline {
  color:{$primary_color};
}
 
.primary-color {
  color: {$primary_color};
}
.primary-color,
.gel-heading-title,
.pricing-price .pricing-price-currency,
.pricing-price .pricing-price-number,
.pricing-price .pricing-price-duration,
.page-main-title {
   color: {$primary_color};
}

   

CSS;

}

function thallo_custom_secondary_color() {
	$secondary_color            = G5CORE()->options()->color()->get_option( 'secondary_color' );
	$secondary_color_foreground = g5core_color_contrast( $secondary_color );
	$secondary_color_darken_075 = g5core_color_darken( $secondary_color, '7.5%' );
	$secondary_color_darken_10  = g5core_color_darken( $secondary_color, '10%' );
	return <<<CSS
   
 
 
 .btn.btn-secondary {
  color: {$secondary_color_foreground};
  background-color: {$secondary_color};
  border-color: {$secondary_color};
}

.btn.btn-secondary.btn-outline:focus, .btn.btn-secondary.btn-outline:hover, .btn.btn-secondary.btn-outline:active {
  background-color: {$secondary_color};
  color: {$secondary_color_foreground};
  border-color: {$secondary_color};
}
.btn.btn-secondary.btn-link {
   color: {$secondary_color};
}
.secondary-color {
  color: {$secondary_color};
}
.secondary-bg-color,
.icon-box-hover-line-bt .gel-icon-box {
    background-color: {$secondary_color};
}
.btn.btn-secondary.btn-outline {
  color: {$secondary_color};
} 
 
   

CSS;

}

function thallo_custom_dark_color() {
	$dark_color            = G5CORE()->options()->color()->get_option( 'dark_color' );
	$dark_color_foreground = g5core_color_contrast( $dark_color );
	$dark_color_darken_075 = g5core_color_darken( $dark_color, '7.5%' );
	$dark_color_darken_10  = g5core_color_darken( $dark_color, '10%' );
	return <<<CSS


.btn.btn-dark {
  color: {$dark_color_foreground};
  background-color: {$dark_color};
  border-color: {$dark_color};
}
.btn.btn-dark.btn-outline {
   color:  {$dark_color};
}
.btn.btn-dark.btn-outline:focus, .btn.btn-dark.btn-outline:hover, .btn.btn-dark.btn-outline:active {
  background-color: {$dark_color};
  color: {$dark_color_foreground};
  border-color: {$dark_color};
}
.btn.btn-dark.btn-link {
  color: {$dark_color};
}



CSS;

}

function thallo_custom_light_color() {
	$light_color            = G5CORE()->options()->color()->get_option( 'light_color' );
	$light_color_foreground = g5core_color_contrast( $light_color );
	$light_color_darken_075 = g5core_color_darken( $light_color, '7.5%' );
	$light_color_darken_10  = g5core_color_darken( $light_color, '10%' );
	return <<<CSS

.btn.btn-light {
  color: {$light_color_foreground};
  background-color: {$light_color};
  border-color: {$light_color};
}
.btn.btn-light:focus, .btn.btn-light:hover, .btn.btn-light:active {
  color: {$light_color_foreground};
  background-color: {$light_color_darken_075};
  border-color: {$light_color_darken_10};
}
.btn.btn-light.btn-outline:focus, .btn.btn-light.btn-outline:hover, .btn.btn-light.btn-outline:active {
  background-color: {$light_color};
  color: {$light_color_foreground};
  border-color: {$light_color};
}
.btn.btn-light.btn-link {
  color: {$light_color};
}

CSS;

}



function thallo_custom_body_font() {
	$font = g5core_process_font( G5CORE()->options()->typography()->get_option( 'body_font' ) );
	return <<<CSS
   

.edit-post-layout__content .editor-post-title__block .editor-post-title__input {
  font-family: {$font['font_family']} !important;
}


.font-body,
body,
.g5core-site-footer h4.widget-title,
.article-post .entry-title,
.g5blog__post-title {
  font-family: {$font['font_family']};
}




CSS;

}

function thallo_custom_primary_font() {
	$font = g5core_process_font( G5CORE()->options()->typography()->get_option( 'primary_font' ) );
	return <<<CSS
	
	
.font-primary,
.g5careers__single-title {
    font-family: {$font['font_family']};
}	

.font-primary,
.g5works__post-skin-08 .g5works__post-title {
  font-family: {$font['font_family']};
}
	
.font-primary,
.gel-heading-title,
.pricing-price-duration,
.site-branding-text .site-title,
.g5core-site-footer.widget-twitter h4.widget-title,
.page-main-title,
.comments-area .comments-title,
.comments-area .comment-reply-title,
.g5blog__block-title,
.widget .widget-title {
   font-family: {$font['font_family']};
}


CSS;

}
