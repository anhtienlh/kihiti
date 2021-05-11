<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
function g5staff_template_loop_title( $args = array() ) {

	$post_settings = &G5STAFF()->listing()->get_layout_settings();
	$single_link_disable = isset($post_settings['single_link_disable']) ? $post_settings['single_link_disable'] : G5STAFF()->options()->get_option('single_link_disable');

	$args = wp_parse_args( $args, array(
		'post' => null,
		'single_link_disable' => $single_link_disable === 'on'
	) );
	G5STAFF()->get_template( 'loop/title.php', $args );
}

add_action( 'g5staff_loop_post_content', 'g5staff_template_loop_title', 5 );

function g5staff_template_loop_job() {
	$prefix                  = G5STAFF()->meta_prefix;
	$job_title = get_post_meta(get_the_ID(),"{$prefix}job_title",true);
	if ($job_title === '') return;
	G5STAFF()->get_template( 'loop/job.php', array('job_title' => $job_title) );
}
add_action('g5staff_loop_post_content','g5staff_template_loop_job',10);
add_action('g5staff_single_info','g5staff_template_loop_job',10);



function g5staff_template_social_profile () {
	$prefix                  = G5STAFF()->meta_prefix;
	$social_profiles = get_post_meta(get_the_ID(),"{$prefix}social_profiles",true);
	if ($social_profiles === '') return;
	G5STAFF()->get_template( 'loop/social-profile.php', array('social_profiles' => $social_profiles) );
}
add_action('g5staff_single_info','g5staff_template_social_profile',20);

function g5staff_template_loop_social_profile() {
	$post_settings = &G5STAFF()->listing()->get_layout_settings();
	$social_profiles_enable = isset($post_settings['social_profiles_enable']) ? $post_settings['social_profiles_enable'] : G5STAFF()->options()->get_option('social_profiles_enable');
	if ($social_profiles_enable !== 'on') return;
	g5staff_template_social_profile();
}
add_action('g5staff_loop_post_content','g5staff_template_loop_social_profile',15);


function g5staff_template_loop_excerpt() {
	$post_settings  = &G5STAFF()->listing()->get_layout_settings();
	$excerpt_enable = isset( $post_settings['excerpt_enable'] ) ? $post_settings['excerpt_enable'] : G5STAFF()->options()->get_option( 'excerpt_enable' );
	if ( $excerpt_enable === 'on' ) {
		echo '<div class="g5staff__post-excerpt">';
		the_excerpt();
		echo '</div>';
	}
}

add_action( 'g5staff_loop_post_content', 'g5staff_template_loop_excerpt', 20 );

function g5staff_render_thumbnail_markup( $args = array() ) {
	$post_settings = &G5STAFF()->listing()->get_layout_settings();
	$single_link_disable = isset($post_settings['single_link_disable']) ? $post_settings['single_link_disable'] : G5STAFF()->options()->get_option('single_link_disable');

	$args = wp_parse_args( $args, array(
		'image_size'         => 'thumbnail',
		'image_ratio'        => '',
		'image_id'           => '',
		'animated_thumbnail' => true,
		'display_permalink'  => $single_link_disable !== 'on',
		'image_mode'         => '',
		'post'               => null,
	) );

	if ( empty( $args['image_id'] ) ) {
		$args['image_id'] = get_post_thumbnail_id( $args['post'] );
	}


	$image_data = g5core_get_image_data( array(
		'image_id'           => $args['image_id'],
		'image_size'         => $args['image_size'],
		'animated_thumbnail' => $args['animated_thumbnail']
	) );

	if ( ! $image_data ) {
		$args['image_mode'] = '';
	}

	ob_start();
	if ( $args['image_mode'] !== 'image' ) {
		$attributes = array();

		if ( ! empty( $image_data['title'] ) && $args['display_permalink'] ) {
			$attributes[] = sprintf( 'title="%s"', esc_attr( $image_data['title'] ) );
		}

		$classes = array(
			'g5core__entry-thumbnail',
			'g5core__embed-responsive',
		);
		if ( empty( $args['image_ratio'] ) ) {
			if ( preg_match( '/x/', $args['image_size'] ) ) {
				if ( ! $image_data ) {
					$image_sizes  = preg_split( '/x/', $args['image_size'] );
					$image_width  = isset( $image_sizes[0] ) ? intval( $image_sizes[0] ) : 0;
					$image_height = isset( $image_sizes[1] ) ? intval( $image_sizes[1] ) : 0;
				} else {
					$image_width  = $image_data['width'];
					$image_height = $image_data['height'];
				}


				if ( ( $image_width > 0 ) && ( $image_height > 0 ) ) {
					$ratio      = ( $image_height / $image_width ) * 100;
					$custom_css = <<<CSS
                .g5core__image-size-{$image_width}x{$image_height}:before{
                    padding-top: {$ratio}%;
                }
CSS;
					G5Core()->custom_css()->addCss( $custom_css, "g5core__image-size-{$image_width}x{$image_height}" );
				}

				$classes[] = "g5core__image-size-{$image_width}x{$image_height}";
			} else {
				$classes[] = "g5core__image-size-{$args['image_size']}";
			}

		} else {
			$classes[] = "g5core__image-size-{$args['image_ratio']}";

			if ( ! in_array( $args['image_ratio'], array( '1x1', '3x4', '4x3', '16x9', '9x16' ) ) ) {

				$image_ratio_sizes  = preg_split( '/x/', $args['image_ratio'] );
				$image_ratio_width  = isset( $image_ratio_sizes[0] ) ? intval( $image_ratio_sizes[0] ) : 0;
				$image_ratio_height = isset( $image_ratio_sizes[1] ) ? intval( $image_ratio_sizes[1] ) : 0;

				if ( ( $image_ratio_width > 0 ) && ( $image_ratio_height > 0 ) ) {
					$ratio      = ( $image_ratio_height / $image_ratio_width ) * 100;
					$custom_css = <<<CSS
                .g5core__image-size-{$args['image_ratio']}:before{
                    padding-top: {$ratio}%;
                }
CSS;
					G5Core()->custom_css()->addCss( $custom_css, "g5core__image-size-{$args['image_ratio']}" );
				}
			}
		}


		if ( ! empty( $image_data['url'] ) ) {
			$attributes[] = sprintf( 'style="background-image: url(%s);"', esc_url( $image_data['url'] ) );
		}

		$attributes[] = sprintf( 'class="%s"', join( ' ', $classes ) );

		if ( $args['display_permalink'] ) {
			?>
			<a <?php echo join( ' ', $attributes ) ?> href="<?php g5core_the_permalink() ?>">
			</a>
			<?php
		} else {
			?>
			<div <?php echo join( ' ', $attributes ) ?>></div>
			<?php

		}
	} else {
		$attributes = array();

		if ( ! empty( $image_data['alt'] ) ) {
			$attributes[] = sprintf( 'alt="%s"', esc_attr( $image_data['alt'] ) );
		}

		if ( ! empty( $image_data['width'] ) ) {
			$attributes[] = sprintf( 'width="%s"', esc_attr( $image_data['width'] ) );
		}

		if ( ! empty( $image_data['height'] ) ) {
			$attributes[] = sprintf( 'height="%s"', esc_attr( $image_data['height'] ) );
		}

		if ( ! empty( $image_data['url'] ) ) {
			$attributes[] = sprintf( 'src="%s"', esc_url( $image_data['url'] ) );
		}

		if ( $args['display_permalink'] ) {
			?>
			<a class="g5core__entry-thumbnail g5core__entry-thumbnail-image"
			   href="<?php g5core_the_permalink() ?>">
				<img <?php echo join( ' ', $attributes ); ?>>
			</a>
			<?php
		} else {
			?>
			<div class="g5core__entry-thumbnail g5core__entry-thumbnail-image">
				<img <?php echo join( ' ', $attributes ); ?>>
			</div>
			<?php

		}
	}
	echo ob_get_clean();
}


function g5staff_template_single_title() {
	G5STAFF()->get_template( 'single/title.php' );
}
add_action('g5staff_single_info','g5staff_template_single_title',5);




function g5staff_template_search_form() {
	G5STAFF()->get_template( 'searchform.php' );
}

function g5staff_template_single_image() {
	G5STAFF()->get_template('single/image.php');
}

function g5staff_template_single_meta() {
	$prefix = G5STAFF()->meta_prefix;
	$staff_info = get_post_meta(get_the_ID(),"{$prefix}staff_info",true);
	$staff_info = !is_array($staff_info) ? array($staff_info) : $staff_info;
	G5STAFF()->get_template('single/meta.php',array('staff_info' => $staff_info));
}
add_action('g5staff_single_info','g5staff_template_single_meta',15);