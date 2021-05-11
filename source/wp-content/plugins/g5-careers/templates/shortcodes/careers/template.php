<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $table_columns
 * @var $table_responsive
 * @var $posts_per_page
 * @var $offset
 * @var $post_paging
 * @var $el_id
 * @var $el_class
 * @var $show
 * @var $ids
 * @var $orderby
 * @var $order
 * @var $animation_style
 * @var $animation_duration
 * @var $animation_delay
 * @var $css_editor
 * @var $responsive
 * Shortcode class
 * @var $this WPBakeryShortCode_G5Element_Careers
 */

$table_columns = $table_responsive =
$posts_per_page = $offset = $post_paging =
$el_id = $el_class =
$show = $ids = $orderby = $order =
$animation_style = $animation_duration = $animation_delay = $css_editor = $responsive = '';
$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$table_columns = (array)vc_param_group_parse_atts($table_columns);
$table_columns_settings = array();
foreach ($table_columns as $column) {
	$table_columns_settings[] = $column['column'];
}



$wrapper_classes = array(
	'g5element__careers',
	$this->getExtraClass( $el_class ),
	$this->getCSSAnimation( $css_animation ),
	vc_shortcode_custom_css_class( $css )
);

$query_args = array(
	'post_type' => 'careers'
);
$settings   = array(
	'table_responsive' => $table_responsive,
	'table_columns' => $table_columns_settings
);

$this->prepare_display( $atts, $query_args, $settings );
$class_to_filter    = implode( ' ', array_filter( $wrapper_classes ) );
$css_class          = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->getShortcode(), $atts );
$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
?>
<div class="<?php echo esc_attr( $css_class ) ?>" <?php echo implode( ' ', $wrapper_attributes ) ?>>
	<?php G5CAREERS()->listing()->render_content( $this->_query_args, $this->_settings ); ?>
</div>
