<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$nonce =  wp_create_nonce('g5portfolio_light_box_gallery');
?>
<a data-g5portfolio-light-box data-nonce="<?php echo esc_attr($nonce)?>" data-id="<?php echo esc_attr(get_the_ID()) ?>" class="g5core__zoom-image" href="#"><i class="fas fa-expand"></i></a>
