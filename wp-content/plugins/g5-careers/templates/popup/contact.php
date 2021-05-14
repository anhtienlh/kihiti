<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * @var $id
 */
?>
<div id="g5careers_contact_popup" class="g5careers-contact-popup mfp-hide mfp-with-anim">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title"><?php esc_html_e('Apply for this position','g5-careers') ?></h5>
		</div>
		<div class="modal-body">
			<?php echo do_shortcode('[contact-form-7 id="'. $id .'"]')?>
		</div>
	</div>
</div>
