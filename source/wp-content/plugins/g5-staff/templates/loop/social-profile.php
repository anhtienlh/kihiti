<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * @var $social_profiles
 */
?>
<ul class="g5staff__loop-social-profiles">
	<?php foreach ( $social_profiles as $k => $link ): ?>
		<?php
		$icon = '';
		switch ( $k ) {
			case 'facebook':
				$icon = 'fab fa-facebook-f';
				break;
			case 'twitter':
				$icon = 'fab fa-twitter';
				break;
			case 'linkedin':
				$icon = 'fab fa-linkedin';
				break;
			case 'instagram':
				$icon = 'fab fa-instagram';
				break;
			case 'dribbble':
				$icon = 'fab fa-dribbble';
				break;
			case 'youtube':
				$icon = 'fab fa-youtube';
				break;
			case 'vimeo':
				$icon = 'fab fa-vimeo';
				break;
		}
		$icon = apply_filters('g5staff_loop_social_profile_icons',$icon,$k);
		?>
		<?php if ( $link !== '' ): ?>
			<li>
				<a href="<?php echo esc_url( $link ) ?>"><i class="<?php echo esc_attr( $icon ) ?>"></i></a>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
