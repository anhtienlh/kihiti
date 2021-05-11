<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * @var $type
 * @var $form
 * @var $link
 */
?>


<?php if ($type === 'link'): ?>
<a href="<?php echo esc_url($link)?>" class="g5careers__btn-contact-us btn"><?php esc_html_e('Apply Now','g5-careers') ?></a>
<?php else: ?>
	<?php
	$args = array(
		'type'           => 'inline',
		'closeOnBgClick' => false,
		'closeBtnInside' => true,
		'mainClass'      => 'mfp-move-from-top',
	);
	add_action('wp_footer','g5careers_template_contact_form_popup');
	?>
	<a href="#g5careers_contact_popup" data-g5core-mfp="true" data-mfp-options='<?php echo json_encode( $args ) ?>' class="g5careers__btn-contact-us btn"><?php esc_html_e('Apply Now','g5-careers') ?></a>
<?php endif; ?>

