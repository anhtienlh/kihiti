<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
function g5portfolio_template_loop_title($args = array())
{
    $args = wp_parse_args($args, array(
        'post' => null
    ));
    G5PORTFOLIO()->get_template('loop/title.php', $args);
}
add_action('g5portfolio_loop_post_content','g5portfolio_template_loop_title',10);

function g5portfolio_template_loop_cat()
{
	$post_settings = &G5PORTFOLIO()->listing()->get_layout_settings();
	$category_enable = isset($post_settings['category_enable']) ? $post_settings['category_enable'] : G5PORTFOLIO()->options()->get_option('category_enable');
	if ($category_enable === 'on') {
		echo get_the_term_list(get_the_ID(), 'portfolio_category', '<div class="g5portfolio__post-cat">', ' / ', '</div>');
	}
}
add_action('g5portfolio_loop_post_content','g5portfolio_template_loop_cat',5);

function g5portfolio_template_loop_excerpt() {
	$post_settings = &G5PORTFOLIO()->listing()->get_layout_settings();
	$excerpt_enable = isset($post_settings['excerpt_enable']) ? $post_settings['excerpt_enable'] : G5PORTFOLIO()->options()->get_option('excerpt_enable');
	if ($excerpt_enable === 'on') {
		echo '<div class="g5portfolio__post-excerpt">';
		the_excerpt();
		echo '</div>';
	}
}
add_action('g5portfolio_loop_post_content','g5portfolio_template_loop_excerpt',15);

function g5portfolio_render_thumbnail_markup($args = array())
{
    $args = wp_parse_args($args, array(
        'image_size' => 'thumbnail',
        'image_ratio' => '',
        'image_id' => '',
        'animated_thumbnail' => true,
        'display_permalink' => true,
        'image_mode' => '',
        'post' => null,
    ));

    if (empty($args['image_id'])) {
        $args['image_id'] = get_post_thumbnail_id($args['post']);
    }


    $image_data = g5core_get_image_data(array(
        'image_id' => $args['image_id'],
        'image_size' => $args['image_size'],
        'animated_thumbnail' => $args['animated_thumbnail']
    ));

    if (!$image_data) {
        $args['image_mode'] = '';
    }

    ob_start();
    if ($args['image_mode'] !== 'image') {
        $attributes = array();

        if (!empty($image_data['title']) && $args['display_permalink']) {
            $attributes[] = sprintf('title="%s"', esc_attr($image_data['title']));
        }

        $classes = array(
            'g5core__entry-thumbnail',
            'g5core__embed-responsive',
        );
        if (empty($args['image_ratio'])) {
            if (preg_match('/x/', $args['image_size'])) {
                if (!$image_data) {
                    $image_sizes = preg_split('/x/', $args['image_size']);
                    $image_width = isset($image_sizes[0]) ? intval($image_sizes[0]) : 0;
                    $image_height = isset($image_sizes[1]) ? intval($image_sizes[1]) : 0;
                } else {
                    $image_width = $image_data['width'];
                    $image_height = $image_data['height'];
                }


                if (($image_width > 0) && ($image_height > 0)) {
                    $ratio = ($image_height / $image_width) * 100;
                    $custom_css = <<<CSS
                .g5core__image-size-{$image_width}x{$image_height}:before{
                    padding-top: {$ratio}%;
                }
CSS;
                    G5Core()->custom_css()->addCss($custom_css, "g5core__image-size-{$image_width}x{$image_height}");
                }

                $classes[] = "g5core__image-size-{$image_width}x{$image_height}";
            } else {
                $classes[] = "g5core__image-size-{$args['image_size']}";
            }

        } else {
            $classes[] = "g5core__image-size-{$args['image_ratio']}";

            if (!in_array($args['image_ratio'], array('1x1', '3x4', '4x3', '16x9', '9x16'))) {

                $image_ratio_sizes = preg_split('/x/', $args['image_ratio']);
                $image_ratio_width = isset($image_ratio_sizes[0]) ? intval($image_ratio_sizes[0]) : 0;
                $image_ratio_height = isset($image_ratio_sizes[1]) ? intval($image_ratio_sizes[1]) : 0;

                if (($image_ratio_width > 0) && ($image_ratio_height > 0)) {
                    $ratio = ($image_ratio_height / $image_ratio_width) * 100;
                    $custom_css = <<<CSS
                .g5core__image-size-{$args['image_ratio']}:before{
                    padding-top: {$ratio}%;
                }
CSS;
                    G5Core()->custom_css()->addCss($custom_css, "g5core__image-size-{$args['image_ratio']}");
                }
            }
        }


        if (!empty($image_data['url'])) {
            $attributes[] = sprintf('style="background-image: url(%s);"', esc_url($image_data['url']));
        }

        $attributes[] = sprintf('class="%s"', join(' ', $classes));

        if ($args['display_permalink']) {
            ?>
            <a <?php echo join(' ', $attributes) ?> href="<?php g5portfolio_the_permalink() ?>">
            </a>
            <?php
        } else {
            ?>
            <div <?php echo join(' ', $attributes) ?>></div>
            <?php

        }
    } else {
        $attributes = array();

        if (!empty($image_data['alt'])) {
            $attributes[] = sprintf('alt="%s"', esc_attr($image_data['alt']));
        }

        if (!empty($image_data['width'])) {
            $attributes[] = sprintf('width="%s"', esc_attr($image_data['width']));
        }

        if (!empty($image_data['height'])) {
            $attributes[] = sprintf('height="%s"', esc_attr($image_data['height']));
        }

        if (!empty($image_data['url'])) {
            $attributes[] = sprintf('src="%s"', esc_url($image_data['url']));
        }

        if ($args['display_permalink']) {
            ?>
            <a class="g5core__entry-thumbnail g5core__entry-thumbnail-image"
               href="<?php g5portfolio_the_permalink() ?>">
                <img <?php echo join(' ', $attributes); ?>>
            </a>
            <?php
        } else {
            ?>
            <div class="g5core__entry-thumbnail g5core__entry-thumbnail-image">
                <img <?php echo join(' ', $attributes); ?>>
            </div>
            <?php

        }
    }
    echo ob_get_clean();
}

function g5portfolio_render_single_thumbnail_markup($args = array()) {
    $args = wp_parse_args($args, array(
        'image_size' => 'thumbnail',
        'image_ratio' => '',
        'image_id' => '',
        'image_mode' => '',
        'display_permalink' => false,
        'gallery_id' => ''
    ));

    if (empty($args['image_id'])) {
        $args['image_id'] = get_post_thumbnail_id();
    }
    echo  '<div class="g5core__post-featured">';
    g5portfolio_render_thumbnail_markup($args);

    $image_full_url = '';
    if (!empty($args['image_id'])) {
        $image_full = wp_get_attachment_image_src($args['image_id'],'full');
        if (is_array($image_full) && isset($image_full[0])) {
            $image_full_url = $image_full[0];

        }
    }

    $zoom_attributes = array();
    if (!empty($args['gallery_id'])) {
        $zoom_attributes[] = sprintf('data-gallery-id="%s"',esc_attr($args['gallery_id']));
    }
    $zoom_attributes[] = sprintf('href="%s"', esc_url($image_full_url));

    if (!empty($image_full_url)) {
        ?>
        <a data-g5core-mfp <?php echo join(' ', $zoom_attributes)?>  class="g5core__zoom-image"><i class="fas fa-expand"></i></a>
        <?php
    }
    echo '</div>';
}

function g5portfolio_the_permalink()
{
    $prefix = G5PORTFOLIO()->meta_prefix;
    $external_link = get_post_meta(get_the_ID(), "{$prefix}single_external_link", true);
    if (!empty($external_link)) {
        echo esc_url($external_link);
    } else {
        the_permalink();
    }
}

function g5portfolio_template_single_gallery()
{
    $single_gallery_layout = G5PORTFOLIO()->options()->get_option('single_gallery_layout');
    $prefix = G5PORTFOLIO()->meta_prefix;

    $media_type = get_post_meta(get_the_ID(), "{$prefix}single_media_type", true);
    $gallery = get_post_meta(get_the_ID(), "{$prefix}single_gallery", true);
    $video = get_post_meta(get_the_ID(), "{$prefix}single_video", true);

    $image_size = G5PORTFOLIO()->options()->get_option('single_gallery_image_size');
    $image_ratio = '';
    if ($image_size === 'full') {
        $_image_ratio = G5PORTFOLIO()->options()->get_option('single_gallery_image_ratio');
        if (is_array($_image_ratio)) {
            $_image_ratio_width = isset($_image_ratio['width']) ? absint($_image_ratio['width']) : 0;
            $_image_ratio_height = isset($_image_ratio['height']) ? absint($_image_ratio['height']) : 0;
            if (($_image_ratio_width > 0) && ($_image_ratio_height > 0)) {
                $image_ratio = "{$_image_ratio_width}x{$_image_ratio_height}";
            }
        }
        if ($image_ratio === '') {
            $image_ratio = '1x1';
        }
    }

    $columns_gutter = absint(G5PORTFOLIO()->options()->get_option('single_gallery_columns_gutter'));
    $columns_xl = absint(G5PORTFOLIO()->options()->get_option('single_gallery_columns_xl'));
    $columns_lg = absint(G5PORTFOLIO()->options()->get_option('single_gallery_columns_lg'));
    $columns_md = absint(G5PORTFOLIO()->options()->get_option('single_gallery_columns_md'));
    $columns_sm = absint(G5PORTFOLIO()->options()->get_option('single_gallery_columns_sm'));
    $columns = absint(G5PORTFOLIO()->options()->get_option('single_gallery_columns'));

    $custom_class = G5PORTFOLIO()->options()->get_option('single_gallery_custom_class');

    $args = array(
        'media_type' => $media_type,
        'gallery' => $gallery,
        'video' => $video,
        'image_size' => $image_size,
        'image_ratio' => $image_ratio,
        'columns_gutter' => $columns_gutter,
        'columns_xl' => $columns_xl,
        'columns_lg' => $columns_lg,
        'columns_md' => $columns_md,
        'columns_sm' => $columns_sm,
        'columns' => $columns,
        'custom_class' => $custom_class
    );

    G5PORTFOLIO()->get_template("single/gallery/{$single_gallery_layout}.php", $args);
}

function g5portfolio_template_single_title()
{
    G5PORTFOLIO()->get_template('single/title.php');
}

function g5portfolio_template_single_meta()
{
    $prefix = G5PORTFOLIO()->meta_prefix;
    $additional_details = get_post_meta(get_the_ID(),"{$prefix}single_additional_details",true);
    $additional_details = !is_array($additional_details) ? array($additional_details) : $additional_details;
    G5PORTFOLIO()->get_template('single/meta.php',array('additional_details' => $additional_details));
}

function g5portfolio_template_single_navigation()
{
    $single_navigation = g5portfolio_single_navigation_enable();
    if (!$single_navigation) return;
    G5PORTFOLIO()->get_template('single/navigation.php');
}
add_action('g5portfolio_after_single', 'g5portfolio_template_single_navigation', 10);

function g5portfolio_template_single_related()
{
    $related_enable = G5PORTFOLIO()->options()->get_option('single_related_enable');
    if ($related_enable !== 'on') return;
    G5PORTFOLIO()->get_template('single/related.php');
}

add_action('g5portfolio_after_single', 'g5portfolio_template_single_related', 20);



function g5portfolio_template_single_comment()
{
    $comment_enable = G5PORTFOLIO()->options()->get_option('comment_enable');
    if ($comment_enable !== 'on') return;
    if ( comments_open() || get_comments_number() ) {
        comments_template();
    }
}
add_action('g5portfolio_after_single', 'g5portfolio_template_single_comment', 30);






function g5portfolio_template_single_cat() {
    G5PORTFOLIO()->get_template('single/meta/cat.php');
}
add_action('g5portfolio_before_single_meta','g5portfolio_template_single_cat',10);

function g5portfolio_template_single_tag() {
    G5PORTFOLIO()->get_template('single/meta/tag.php');
}
add_action('g5portfolio_before_single_meta','g5portfolio_template_single_tag',20);

function g5portfolio_template_single_share() {
    $single_share_enable =  g5portfolio_single_share_enable();
    if (!$single_share_enable) return;
	g5core_template_social_share();
}
add_action('g5portfolio_before_single_meta','g5portfolio_template_single_share',30);

function g5portfolio_template_loop_zoom() {
    $light_box_mode = G5PORTFOLIO()->options()->get_option('light_box_mode');
    if ($light_box_mode === '') return;
    G5PORTFOLIO()->get_template("loop/zoom/{$light_box_mode}.php");
}

function g5portfolio_template_search_form() {
    G5PORTFOLIO()->get_template('searchform.php');
}