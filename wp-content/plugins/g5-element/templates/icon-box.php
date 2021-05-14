<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $layout_style
 * @var $title
 * @var $content
 * @var $icon_font
 * @var $link
 * @var $description_typography
 * @var $title_typography
 * @var $shape_bg_icon
 * @var $el_class
 * @var $switch_show_button
 * @var $text_button
 * @var $icon_size
 * @var $button_size
 * @var $button_style
 * @var $button_shape
 * @var $button_color
 * @var $$css_animation
 * @var $button_is_3d
 * @var $color_icon
 * @var $css
 * Shortcode class
 * @var $this WPBakeryShortCode_G5Element_Icon_Box
 */
$layout_style = $title = $switch_show_button = $css_animation = $text_button = $description_typography = $title_typography = '';
$shape_bg_icon = $icon_font = $icon_image = $icon_size = $link = $color_icon = '';
$el_class = $button_size = $button_style = $button_is_3d = $button_shape = $button_color = $css = '';
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

G5ELEMENT()->assets()->enqueue_assets_for_shortcode('icon_box');

$wrapper_classes = array(
    'gel-icon-box',
    'gel-icon-box-' . $layout_style,
    $icon_size,
    $shape_bg_icon,
    $this->getExtraClass($el_class),
    $this->getCSSAnimation($css_animation),
    vc_shortcode_custom_css_class($css),
);

if ($color_icon !== '') {
    if (!g5core_is_color($color_icon)) {
        $color_icon = g5core_get_color_from_option($color_icon);
    }
    $class_color_style = uniqid('gel-');
    $icon_custom_css = '';
    $class_circle = 'shape-icon background-icon shape-circle';
    $class_circle_outline = 'shape-icon shape-circle border-not-bg-icon';
    $class_square = 'shape-icon background-icon shape-square';
    $class_square_outline = 'shape-icon shape-square border-not-bg-icon';
    $custom_color_contract = g5core_color_contrast($color_icon);
    if (($shape_bg_icon === $class_circle) || ($shape_bg_icon === $class_square)) {
        $icon_custom_css .= <<<CUSTOM_CSS
	.{$class_color_style} i{
		background: $color_icon !important;
		color: $custom_color_contract !important;
	}
	.{$class_color_style} i:after{
		webkit-box-shadow: 0 0 0 2px $color_icon !important;
        box-shadow: 0 0 0 2px $color_icon !important;
	}
CUSTOM_CSS;
    }
    if (($shape_bg_icon === $class_circle_outline) || ($shape_bg_icon === $class_square_outline)) {
        $icon_custom_css .= <<<CUSTOM_CSS
	.{$class_color_style} i{
	    webkit-box-shadow: 0 0 0 2px $color_icon !important;
        box-shadow: 0 0 0 2px $color_icon !important;
		color: $color_icon !important;
	}
	.{$class_color_style} i:after{
	    webkit-box-shadow: 0 0 0 2px $color_icon !important;
        box-shadow: 0 0 0 2px $color_icon !important;
	}
	.gel-icon-box.{$class_color_style}:hover i{
	   background: $color_icon !important;
		color: $custom_color_contract!important;
	}
	.gel-icon-box.{$class_color_style}:hover i:after{
	    webkit-box-shadow: 0 0 0 2px $color_icon !important;
        box-shadow: 0 0 0 2px $color_icon !important;
	}
CUSTOM_CSS;
    }
    if (($shape_bg_icon === 'shape-default')) {
        $icon_custom_css .= <<<CUSTOM_CSS
	.{$class_color_style} i{
		color: $color_icon !important;
	}
CUSTOM_CSS;
    }
    if ($icon_custom_css !== '') {
        $wrapper_classes[] = $class_color_style;
        G5CORE()->custom_css()->addCss($icon_custom_css);
    }
}
$title_class = array(
    'title',
);
$title_typo_class = g5element_typography_class($title_typography);
if ($title_typo_class !== '') {
    $title_class[] = $title_typo_class;
}
$description_class = array(
    'description',
);
$description_typo_class = g5element_typography_class($description_typography);
if ($description_typo_class !== '') {
    $description_class[] = $description_typo_class;
}
$icon_box_link = g5element_build_link($link);

$icon_html = empty($icon_font) ? '' : '<i class="' . esc_attr($icon_font) . '"></i>';

$class_to_filter = implode(' ', array_filter($wrapper_classes));
$class_to_filter .= vc_shortcode_custom_css_class($css, ' ');
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->getShortcode(), $atts);
?>
<div class="<?php echo esc_attr($css_class) ?>">
    <?php G5ELEMENT()->get_template("icon-box/{$layout_style}.php", array(
        'title_class' => $title_class,
        'title' => $title,
        'content' => $content,
        'description_class' => $description_class,
        'switch_show_button' => $switch_show_button,
        'text_button' => $text_button,
        'icon_html' => $icon_html,
        'link' => $link,
        'icon_box_link' => $icon_box_link,
        'button_color' => $button_color,
        'button_size' => $button_size,
        'button_style' => $button_style,
        'button_shape' => $button_shape,
        'button_is_3d' => $button_is_3d,
    )); ?>
</div>
