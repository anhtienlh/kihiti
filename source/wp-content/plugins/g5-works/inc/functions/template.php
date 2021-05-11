<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
function g5works_template_loop_title( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'post' => null
	) );
	G5WORKS()->get_template( 'loop/title.php', $args );
}

add_action( 'g5works_loop_post_content', 'g5works_template_loop_title', 10 );

function g5works_template_loop_cat() {
	$post_settings   = &G5WORKS()->listing()->get_layout_settings();
	$category_enable = isset( $post_settings['category_enable'] ) ?  $post_settings['category_enable']  : G5WORKS()->options()->get_option( 'category_enable' );
	if ( $category_enable === 'on' ) {
		echo get_the_term_list( get_the_ID(), 'works_category', '<div class="g5works__post-cat">', ' / ', '</div>' );
	}

}
add_action( 'g5works_loop_post_content', 'g5works_template_loop_cat', 5 );

function g5works_template_loop_excerpt() {
	$post_settings  = &G5WORKS()->listing()->get_layout_settings();
	$excerpt_enable = isset( $post_settings['excerpt_enable'] ) ? $post_settings['excerpt_enable'] : G5WORKS()->options()->get_option( 'excerpt_enable' );
	if ( $excerpt_enable === 'on' ) {
		echo '<div class="g5works__post-excerpt">';
		the_excerpt();
		echo '</div>';
	}
}

add_action( 'g5works_loop_post_content', 'g5works_template_loop_excerpt', 15 );

function g5works_render_thumbnail_markup( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'image_size'         => 'thumbnail',
		'image_ratio'        => '',
		'image_id'           => '',
		'animated_thumbnail' => true,
		'display_permalink'  => true,
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


function g5works_template_single_title() {
	$single_title_enable = G5WORKS()->options()->get_option('single_title_enable');
	if ($single_title_enable === 'on') {
		G5WORKS()->get_template( 'single/title.php' );
	}
}
add_action('g5works_before_single_content','g5works_template_single_title',5);




function g5works_template_single_navigation() {
	$single_navigation = G5WORKS()->options()->get_option( 'single_navigation_enable' );
	if ( $single_navigation !== 'on' ) {
		return;
	}
	G5WORKS()->get_template( 'single/navigation.php' );
}

add_action( 'g5works_after_single', 'g5works_template_single_navigation', 10 );

function g5works_template_single_related() {
	$related_enable = G5WORKS()->options()->get_option( 'single_related_enable' );
	if ( $related_enable !== 'on' ) {
		return;
	}
	G5WORKS()->get_template( 'single/related.php' );
}

add_action( 'g5works_after_single', 'g5works_template_single_related', 20 );


function g5works_template_single_comment() {
	$comment_enable = G5WORKS()->options()->get_option( 'comment_enable' );
	if ( $comment_enable !== 'on' ) {
		return;
	}
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

add_action( 'g5works_after_single', 'g5works_template_single_comment', 30 );


function g5works_template_single_share() {
	$social_share = g5works_single_share_enable();
	if (!$social_share) {
		return;
	}
	g5core_template_social_share();
}

add_action( 'g5works_after_single_content', 'g5works_template_single_share', 10 );


function g5works_template_search_form() {
	G5WORKS()->get_template( 'searchform.php' );
}

