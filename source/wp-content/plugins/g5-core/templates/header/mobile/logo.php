<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}
/**
 * @var $classes
 */

$logo = '';
$mobile_logo = G5CORE()->options()->header()->get_option('mobile_logo');
if (!isset($mobile_logo['url']) || empty($mobile_logo['url'])) {
	$logo = G5CORE()->options()->header()->get_option('logo');
	$logo = isset($logo['url']) ? $logo['url'] : '';
}
else {
	$logo = $mobile_logo['url'];
}

$logo_sticky = '';
$mobile_sticky_logo = G5CORE()->options()->header()->get_option('mobile_sticky_logo');
if (!isset($mobile_sticky_logo['url']) || empty($mobile_sticky_logo['url'])) {
	$logo_sticky = G5CORE()->options()->header()->get_option('logo_sticky');
	$logo_sticky = isset($logo_sticky['url']) ? $logo_sticky['url'] : '';
}
else {
	$logo_sticky = $mobile_sticky_logo['url'];
}

$logo_classes = array(
	'g5core-site-branding'
);
if (isset($classes) && !empty($classes)) {
	$logo_classes[] = $classes;
}

$logo_title = esc_attr(get_bloginfo('name', 'display')) . '-' . get_bloginfo('description', 'display');
$logo_text = get_bloginfo('name', 'display');
?>
<div class="<?php echo join(' ', $logo_classes)?>">
	<?php if (!empty($logo)): ?>
		<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr($logo_title) ?>">
			<img class="site-logo<?php echo $logo_sticky !== '' ? ' has-sticky' : '' ?>" src="<?php echo esc_url($logo) ?>" alt="<?php echo esc_attr($logo_title) ?>">
			<?php if ($logo_sticky !== ''): ?>
				<img class="site-logo site-logo-sticky" src="<?php echo esc_url($logo_sticky) ?>" alt="<?php echo esc_attr($logo_title) ?>">
			<?php endif; ?>
		</a>
	<?php else: ?>
		<div class="site-branding-text">
			<?php if ( is_front_page() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>

			<?php $description = get_bloginfo( 'description', 'display' ); ?>
			<?php if ($description || is_customize_preview()): ?>
				<p class="site-description"><?php echo $description; ?></p>
			<?php endif; ?>
		</div><!-- .site-branding-text -->
	<?php endif; ?>
</div>