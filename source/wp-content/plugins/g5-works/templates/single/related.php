<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
global $post;
$post_id = $post->ID;
$post_algorithm = G5WORKS()->options()->get_option('single_related_algorithm');
$posts_per_page = intval(G5WORKS()->options()->get_option('single_related_per_page'));
$post_columns_gutter = intval(G5WORKS()->options()->get_option('single_related_columns_gutter'));
$post_columns_xl = intval(G5WORKS()->options()->get_option('single_related_columns_xl'));
$post_columns_lg = intval(G5WORKS()->options()->get_option('single_related_columns_lg'));
$post_columns_md = intval(G5WORKS()->options()->get_option('single_related_columns_md'));
$post_columns_sm = intval(G5WORKS()->options()->get_option('single_related_columns_sm'));
$post_columns = intval(G5WORKS()->options()->get_option('single_related_columns'));
$post_paging = G5WORKS()->options()->get_option('single_related_paging');

$query_args = array(
    'ignore_sticky_posts' => true,
    'posts_per_page' => $posts_per_page,
    'post__not_in' => array($post_id),
    'post_type' => 'works',
    'post_status'      => 'publish',
    'tax_query'      => array(
        'relation' => 'AND',
    ),
);
switch ($post_algorithm) {
    case 'cat':
        $query_args['tax_query'][] = array(
            'taxonomy' => 'works_category',
            'field' => 'term_id',
            'terms' => wp_get_post_terms( $post_id, 'works_category', array('fields' => 'ids') ),
            'operator' 		=> 'IN'
        );

        break;
    case 'author':
        $query_args['author'] = $post->post_author;
        break;
    case 'cat-author':
        $query_args['author']       = $post->post_author;
        $query_args['tax_query'][] = array(
            'taxonomy' => 'works_category',
            'field' => 'term_id',
            'terms' => wp_get_post_terms( $post_id, 'works_category', array('fields' => 'ids') ),
            'operator' 		=> 'IN'
        );

        break;
    case 'random':
        $query_args['orderby'] = 'rand';
        break;
}

$settings = array(
    'post_layout' => 'grid',
    'item_skin' => 'skin-01',
    'image_size' => '300x300',
    'excerpt_enable' => '',
    'columns_gutter' => $post_columns_gutter,
    'post_paging' => $post_paging !== 'slider' ? $post_paging : '',
    'post_animation' => 'none'
);

if ($post_paging !== 'slider') {
    $settings['post_columns'] = array(
        'xl' => $post_columns_xl,
        'lg' => $post_columns_lg,
        'md' => $post_columns_md,
        'sm' => $post_columns_sm,
        '' => $post_columns
    );
} else {
    $settings['slick'] = array(
        'arrows' => false,
        'dots' => true,
        'slidesToShow' => $post_columns_xl,
        'slidesToScroll' => $post_columns_xl,
        'responsive' => array(
            array(
                'breakpoint' => 1200,
                'settings'   => array(
                    'slidesToShow'   => $post_columns_lg,
                    'slidesToScroll' => $post_columns_lg,
                )
            ),
            array(
                'breakpoint' => 992,
                'settings'   => array(
                    'slidesToShow'   => $post_columns_md,
                    'slidesToScroll' => $post_columns_md,
                )
            ),
            array(
                'breakpoint' => 768,
                'settings'   => array(
                    'slidesToShow'   => $post_columns_sm,
                    'slidesToScroll' => $post_columns_sm,
                )
            ),
            array(
                'breakpoint' => 576,
                'settings'   => array(
                    'slidesToShow'   => $post_columns,
                    'slidesToScroll' => $post_columns,
                )
            )
        )
    );
}

$settings = apply_filters('g5works_single_related_layout_setting',$settings);
G5CORE()->query()->query_posts($query_args);
if (!G5CORE()->query()->have_posts()) {
    G5CORE()->query()->reset_query();
    return;
}
?>
<div class="g5works__single-related-wrap">
    <h4><span><?php esc_html_e('Related Works', 'g5-works'); ?></span></h4>
    <?php G5WORKS()->listing()->render_content($query_args, $settings); ?>
</div>