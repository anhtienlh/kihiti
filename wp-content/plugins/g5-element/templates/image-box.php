<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $layout_style
 * @var $title
 * @var $content
 * @var $icon_image
 * @var $icon_image_hover
 * @var $link
 * @var $description_typography
 * @var $title_typography
 * @var $el_class
 * @var $border_image
 * @var $img_size
 * @var $css
 * @var $switch_show_button
 * @var $button_size
 * @var $button_style
 * @var $button_shape
 * @var $button_color
 * @var $text_button
 * @var $css_animation
 * @var $button_is_3d
 * @var $img_style
 * Shortcode class
 * @var $this WPBakeryShortCode_G5Element_Image_Box
 */

$layout_style = $title = $switch_show_button = $text_button = $css_animation = $description_typography = $title_typography = '';
$icon_image = $icon_image_hover = $link = $el_class = $css = $border_image = $img_size = $button_is_3d =
$button_size = $button_style = $button_shape = $button_color = '';
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

G5ELEMENT()->assets()->enqueue_assets_for_shortcode('image_box');

$wrapper_classes = array(
    'gel-image-box',
    $img_style,
    $img_size,
    $border_image,
    'gel-image-box-' . $layout_style,
    $this->getExtraClass($el_class),
    $this->getCSSAnimation($css_animation),
    vc_shortcode_custom_css_class($css),
);

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

$img_box_link = g5element_build_link($link);


// img html
$icon_html = '';
$icon_hover_html = '';
if (!empty($icon_image)) {
    $icon_image_id = preg_replace('/[^\d]/', '', $icon_image);
    $icon_image_src = wp_get_attachment_image_src($icon_image_id, 'full');
    if (!empty($icon_image_src[0])) {
        if ($img_size === 'img-size-sm') {
            $width_img = 60;
            $height_img = 60;
        }
        if ($img_size === 'img-size-md') {
            $width_img = 80;
            $height_img = 80;
        }
        if ($img_size === 'img-size-lg') {
            $width_img = 120;
            $height_img = 120;
        }
        if ($img_size !== 'img-size-origin') {
            $img = G5CORE()->image_resize()->resize(array(
                'image_id' => $icon_image,
                'width' => $width_img,
                'height' => $height_img,
            ));
        }
        if (isset($img['url']) && ($img['url'] !== '')) {
            $icon_html = '<img alt="' . the_title_attribute(array('post' => $icon_image_id, 'echo' => false)) . '" src="' . esc_url($img['url']) . '">';
        } else {
            $icon_image_src = $icon_image_src[0];
            $icon_html = '<img alt="' . the_title_attribute(array('post' => $icon_image_id, 'echo' => false)) . '" src="' . esc_url($icon_image_src) . '">';
        }
    }
}

if (!empty($icon_image_hover)) {
	$icon_image_hover_id = preg_replace('/[^\d]/', '', $icon_image_hover);
	$icon_image_hover_src = wp_get_attachment_image_src($icon_image_hover_id, 'full');
	if (!empty($icon_image_hover_src[0])) {
		if ($img_size === 'img-size-sm') {
			$width_img = 60;
			$height_img = 60;
		}
		if ($img_size === 'img-size-md') {
			$width_img = 80;
			$height_img = 80;
		}
		if ($img_size === 'img-size-lg') {
			$width_img = 120;
			$height_img = 120;
		}
		if ($img_size !== 'img-size-origin') {
			$img_hover = G5CORE()->image_resize()->resize(array(
				'image_id' => $icon_image_hover,
				'width' => $width_img,
				'height' => $height_img,
			));
		}
		if (isset($img_hover['url']) && ($img_hover['url'] !== '')) {
			$icon_hover_html = '<img alt="' . the_title_attribute(array('post' => $icon_image_hover_id, 'echo' => false)) . '" src="' . esc_url($img_hover['url']) . '">';
		} else {
			$icon_image_hover_src = $icon_image_hover_src[0];
			$icon_hover_html = '<img alt="' . the_title_attribute(array('post' => $icon_image_hover_id, 'echo' => false)) . '" src="' . esc_url($icon_image_hover_src) . '">';
		}
	}
}

$class_to_filter = implode(' ', array_filter($wrapper_classes));
$class_to_filter .= vc_shortcode_custom_css_class($css, ' ');
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->getShortcode(), $atts);

?>
<div class="<?php echo esc_attr($css_class) ?>">
    <?php G5ELEMENT()->get_template("image-box/{$layout_style}.php", array(
        'title_class' => $title_class,
        'img_box_link' => $img_box_link,
        'title' => $title,
        'content' => $content,
        'description_class' => $description_class,
        'switch_show_button' => $switch_show_button,
        'text_button' => $text_button,
        'icon_html' => $icon_html,
        'icon_hover_html' => $icon_hover_html,
        'link' => $link,
        'button_size' => $button_size,
        'button_style' => $button_style,
        'button_shape' => $button_shape,
        'button_color' => $button_color,
        'button_is_3d' => $button_is_3d,
    )); ?>
</div>