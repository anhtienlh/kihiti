<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Portfolio_Widget_Portfolio')) {
    class G5Portfolio_Widget_Portfolio extends GSF_Widget {
        public function __construct()
        {
            $this->widget_cssclass = 'g5portfolio__widget-portfolio';
            $this->widget_id = 'g5portfolio__widget_portfolio';
            $this->widget_name = esc_html__('G5Plus: Portfolio', 'g5-portfolio');
            $this->widget_description = esc_html__( 'Display list portfolio', 'g5-portfolio' );
            $this->settings = array(
                'fields' => array(
                    array(
                        'id'      => 'title',
                        'title'   => esc_html__('Title', 'g5-portfolio'),
                        'type'    => 'text',
                        'default' => esc_html__( 'Recent Portfolio', 'g5-portfolio' ),
                    ),
                    array(
                        'id'      => 'source',
                        'type'    => 'select',
                        'title'   => esc_html__('Source', 'g5-portfolio'),
                        'default' => 'recent',
                        'options' => array(
                            'random'   => esc_html__('Random', 'g5-portfolio'),
                            'featured'  => esc_html__('Featured', 'g5-portfolio'),
                            'recent'   => esc_html__('Recent', 'g5-portfolio'),
                            'oldest'   => esc_html__('Oldest', 'g5-portfolio'),
                        )
                    ),
                    array(
                        'id'         => 'posts_per_page',
                        'type'       => 'text',
                        'input_type' => 'number',
                        'title'      => esc_html__('Number of portfolio to show:', 'g5-portfolio'),
                        'default'    => '4',
                    ),
                    array(
                        'id' => 'post_layout',
                        'title' => esc_html__('Layout','g5-portfolio'),
                        'type' => 'image_set',
                        'options' => G5PORTFOLIO()->settings()->get_widget_portfolio_layout(),
                        'default' => 'grid'
                    ),
                    array(
                        'id' => 'post_image_size',
                        'title' => esc_html__('Image size', 'g5-portfolio'),
                        'subtitle' => esc_html__('Enter your image size', 'g5-portfolio'),
                        'desc' => esc_html__('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'g5-portfolio'),
                        'type' => 'text',
                        'default' => 'thumbnail',
                    ),
                    array(
                        'id'       => 'post_image_ratio',
                        'title'    => esc_html__('Image Ratio', 'g5-portfolio'),
                        'subtitle' => esc_html__('Enter image ratio', 'g5-portfolio'),
                        'type'     => 'dimension',
                        'required' => array('post_image_size', '=', 'full')
                    ),
                    array(
                        'id' => 'post_columns_gutter',
                        'title' => esc_html__('Columns Gutter', 'g5-portfolio'),
                        'subtitle' => esc_html__('Specify your horizontal space between item.', 'g5-portfolio'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_columns_gutter(),
                        'default' => 10,
                    ),
                    array(
                        'id' => 'post_columns_group',
                        'title' => esc_html__('Columns', 'g5-portfolio'),
                        'type' => 'group',
                        'toggle_default' => false,
                        'required' => array('post_layout', 'in', array('grid')),
                        'fields' => array(
                            array(
                                'id' => 'post_columns_xl',
                                'title' => esc_html__('Extra Large Devices', 'g5-portfolio'),
                                'desc' => esc_html__('Specify your columns on extra large devices (>= 1200px)', 'g5-portfolio'),
                                'type' => 'select',
                                'options' => G5CORE()->settings()->get_post_columns(),
                                'default' => 2,
                            ),
                            array(
                                'id' => 'post_columns_lg',
                                'title' => esc_html__('Large Devices', 'g5-portfolio'),
                                'desc' => esc_html__('Specify your columns on large devices (>= 992px)', 'g5-portfolio'),
                                'type' => 'select',
                                'options' => G5CORE()->settings()->get_post_columns(),
                                'default' => 2,
                            ),
                            array(
                                'id' => 'post_columns_md',
                                'title' => esc_html__('Medium Devices', 'g5-portfolio'),
                                'desc' => esc_html__('Specify your columns on medium devices (>= 768px)', 'g5-portfolio'),
                                'type' => 'select',
                                'options' => G5CORE()->settings()->get_post_columns(),
                                'default' => 2,
                            ),
                            array(
                                'id' => 'post_columns_sm',
                                'title' => esc_html__('Small Devices', 'g5-portfolio'),
                                'desc' => esc_html__('Specify your columns on small devices (< 768px)', 'g5-portfolio'),
                                'type' => 'select',
                                'options' => G5CORE()->settings()->get_post_columns(),
                                'default' => 2,
                            ),
                            array(
                                'id' => 'post_columns',
                                'title' => esc_html__('Extra Small Devices', 'g5-portfolio'),
                                'desc' => esc_html__('Specify your columns on extra small devices (< 576px)', 'g5-portfolio'),
                                'type' => 'select',
                                'options' => G5CORE()->settings()->get_post_columns(),
                                'default' => 2,
                            )
                        )
                    )
                )
            );
            parent::__construct();
        }

        function widget($args, $instance)
        {
            if ($this->get_cached_widget($instance)) {
                return;
            }
            extract($args, EXTR_SKIP);
            $source = (!empty($instance['source'])) ? $instance['source'] : 'recent';
            $posts_per_page = (!empty($instance['posts_per_page'])) ? absint($instance['posts_per_page']) : 4;
            $post_layout = (!empty($instance['post_layout'])) ? $instance['post_layout'] : 'grid';
            $post_image_size = (!empty($instance['post_image_size'])) ? $instance['post_image_size'] : 'thumbnail';
            $post_image_ratio = (!empty($instance['post_image_ratio'])) ? $instance['post_image_ratio'] : '';
            $post_columns_gutter = (!empty($instance['post_columns_gutter'])) ? $instance['post_columns_gutter'] : 10;
            $post_columns_xl = (!empty($instance['post_columns_xl'])) ? $instance['post_columns_xl'] : 2;
            $post_columns_lg = (!empty($instance['post_columns_lg'])) ? $instance['post_columns_lg'] : 2;
            $post_columns_md = (!empty($instance['post_columns_md'])) ? $instance['post_columns_md'] : 2;
            $post_columns_sm = (!empty($instance['post_columns_sm'])) ? $instance['post_columns_sm'] : 2;
            $post_columns = (!empty($instance['post_columns'])) ? $instance['post_columns'] : 2;

            $query_args = array(
                'posts_per_page'      => $posts_per_page,
                'no_found_rows'       => true,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
                'post_type'           => 'portfolio',
                'tax_query' => array(
                    'relation' => 'AND',
                ),
                'meta_query' => array()
            );

            switch ($source) {
                case 'random' :
                    $query_args['orderby'] = 'rand';
                    $query_args['order'] = 'DESC';
                    break;
                case 'featured':
                    $query_args['tax_query'][] = array(
                        'taxonomy' => 'portfolio_visibility',
                        'field'    => 'slug',
                        'terms'    => 'featured',
                    );

                    break;
                case 'recent':

                    $query_args['orderby'] = 'date';
                    $query_args['order'] = 'DESC';

                    break;
                case 'oldest':
                    $query_args['orderby'] = 'date';
                    $query_args['order'] = 'ASC';
                    break;
            }


            G5CORE()->query()->query_posts($query_args);
            if (!G5CORE()->query()->have_posts()) {
                G5CORE()->query()->reset_query();
                return;
            }

            $skin = 'skin-07';
            if ($post_layout === 'list') {
                $skin = 'skin-08';
                $post_columns_xl = $post_columns_lg = $post_columns_md = $post_columns_sm = $post_columns = 1;
            }

            $settings = apply_filters('g5portfolio_widget_portfolio_settings',array(
                'post_layout' => 'grid',
                'item_skin' => $skin,
                'image_size' => $post_image_size,
                'image_ratio' => $post_image_ratio,
                'columns_gutter' => $post_columns_gutter,
                'post_columns' => array(
                    'xl' => $post_columns_xl,
                    'lg' => $post_columns_lg,
                    'md' => $post_columns_md,
                    'sm' => $post_columns_sm,
                    '' => $post_columns,
                ),
                'category_enable' => true,
                'excerpt_enable' => false,
                'post_paging' => '',
                'post_animation' => 'none'
            ),$post_layout);


            ob_start();
            $this->widget_start($args,$instance);
            G5PORTFOLIO()->listing()->render_content($query_args, $settings);
            $this->widget_end($args);
            echo $this->cache_widget( $args, ob_get_clean() ); // WPCS: XSS ok.
        }
    }
}