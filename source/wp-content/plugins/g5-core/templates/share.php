<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}
/**
 * @var $social_share
 */
$page_permalink = get_permalink();
$page_title = get_the_title();
$post_type = get_post_type();

$wrapper_classes = array(
	'g5core__social-share',
	$post_type
);

$wrapper_class = implode(' ', $wrapper_classes);

?>
<div class="<?php echo esc_attr($wrapper_class)?>">
	<label class="g5core__share-label"><?php esc_html_e( 'Share:', 'g5-core' ) ?></label>
	<ul class="g5core__share-list">
		<?php
		foreach((array)$social_share as $key => $value) {
			$link = '';
			$icon = '';
			$title = '';
			switch ($key) {
				case 'facebook':
					$link = "https://www.facebook.com/sharer.php?u=" . urlencode($page_permalink);
					$icon = 'fab fa-facebook-f';
					$title = esc_html__('Facebook', 'g5-core');
					break;
				case 'twitter':
					$link  = "javascript: window.open('http://twitter.com/share?text=" . $page_title . "&url=" . $page_permalink . "','_blank', 'width=900, height=450');";
					$icon = 'fab fa-twitter';
					$title = esc_html__('Twitter', 'g5-core');
					break;
				case 'linkedin':
					$link  = "javascript: window.open('http://www.linkedin.com/shareArticle?mini=true&url=" . $page_permalink . "&title=" . $page_title . "','_blank', 'width=500, height=450');";
					$icon = 'fab fa-linkedin-in';
					$title = esc_html__('LinkedIn', 'g5-core');
					break;
				case 'tumblr':
					$link  = "javascript: window.open('http://www.tumblr.com/share/link?url=" . $page_permalink . "&name=" . $page_title . "','_blank', 'width=500, height=450');";
					$icon = 'fab fa-tumblr';
					$title = esc_html__('Tumblr', 'g5-core');
					break;
				case 'pinterest':
					$_img_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
					$link     = "javascript: window.open('http://pinterest.com/pin/create/button/?url=" . $page_permalink . '&media=' . (($_img_src === false) ? '' :  $_img_src[0]) . "&description=" . $page_title . "','_blank', 'width=900, height=450');";
					$icon = 'fab fa-pinterest-p';
					$title = esc_html__('Pinterest', 'g5-core');
					break;
				case 'email':
					$link  = "mailto:?subject=" . $page_title . "&body=" . esc_url( $page_permalink );
					$icon = 'fal fa-envelope';
					$title = esc_html__('Email', 'g5-core');
					break;
				case 'telegram':
					$link  = "javascript: window.open('https://telegram.me/share/url?url=" . esc_url( $page_permalink ) . "&text=" . $page_title . "','_blank', 'width=900, height=450');";
					$icon = 'fab fa-telegram-plane';
					break;
				case 'whatsapp':
					$link  = "whatsapp://send?text=" . esc_attr( $page_title . "  \n\n" . esc_url( $page_permalink ) );
					$icon = 'fab fa-whatsapp';
					$title = esc_html__('Whats App', 'g5-core');
					break;
			}

			$args = apply_filters('g5core_social_share_'. $key .'_args',array(
				'link' => $link,
				'icon' => $icon,
				'title' => $title
			));

			?>
			<li class="<?php echo esc_attr($key);?>">
				<a href="<?php echo ($args['link']); ?>" data-delay="1" data-toggle="tooltip" title="<?php echo esc_attr($args['title'])?>" target="_blank" rel="nofollow">
					<i class="<?php echo esc_attr($args['icon']); ?>"></i>
				</a>
			</li>
			<?php
		}
		?>
	</ul>
</div>
