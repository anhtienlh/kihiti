<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Portfolio_Widget_Categories')) {
    class G5Portfolio_Widget_Categories extends GSF_Widget {

        /**
         * Category ancestors.
         *
         * @var array
         */
        public $cat_ancestors;

        /**
         * Current Category.
         *
         * @var bool
         */
        public $current_cat;

        public function __construct()
        {
            $this->widget_cssclass = 'g5portfolio__widget-categories widget_categories';
            $this->widget_id = 'g5portfolio__widget_categories';
            $this->widget_name = esc_html__('G5Plus: Portfolio Categories', 'g5-portfolio');
            $this->widget_description = esc_html__( 'A list or dropdown of portfolio categories.', 'g5-portfolio' );
            $this->settings = array(
                'fields' => array(
                    'title' => array(
                        'id'      => 'title',
                        'title'   => esc_html__('Title', 'g5-portfolio'),
                        'type'    => 'text',
                        'default' => esc_html__('Portfolio categories','g5-portfolio'),
                    ),
                    'dropdown' => array(
                        'id' => 'dropdown',
                        'type' => 'checkbox',
                        'desc' => esc_html__('Show as dropdown','g5-portfolio'),
                        'default' => 0
                    ),
                    'count' => array(
                        'id' => 'count',
                        'type' => 'checkbox',
                        'desc' => esc_html__('Show portfolio counts','g5-portfolio'),
                        'default' => 0
                    ),
                    'hierarchical' => array(
                        'id' => 'hierarchical',
                        'type' => 'checkbox',
                        'desc' => esc_html__('Show hierarchy','g5-portfolio'),
                        'default' => 1
                    ),
                    'show_children_only' => array(
                        'id' => 'show_children_only',
                        'type' => 'checkbox',
                        'desc' => esc_html__('Only show children of the current category','g5-portfolio'),
                        'default' => 0
                    ),
                    'hide_empty' => array(
                        'id' => 'hide_empty',
                        'type' => 'checkbox',
                        'desc' => esc_html__('Hide empty categories','g5-portfolio'),
                        'default' => 0
                    ),
                    'max_depth' => array(
                        'id' => 'max_depth',
                        'title' => esc_html__('Maximum depth','g5-portfolio'),
                        'type' => 'text',
                        'input_mode' => 'number',
                        'default' => ''
                    )


                )
            );
            parent::__construct();
        }

        function widget($args, $instance)
        {
            global $wp_query, $post;
            if ($this->get_cached_widget($instance)) {
                return;
            }
            extract($args, EXTR_SKIP);
            $count              = isset( $instance['count'] ) ? $instance['count'] : $this->settings['fields']['count']['default'];
            $hierarchical       = isset( $instance['hierarchical'] ) ? $instance['hierarchical'] : $this->settings['fields']['hierarchical']['default'];
            $show_children_only = isset( $instance['show_children_only'] ) ? $instance['show_children_only'] : $this->settings['fields']['show_children_only']['default'];
            $dropdown           = isset( $instance['dropdown'] ) ? $instance['dropdown'] : $this->settings['fields']['dropdown']['default'];
            $hide_empty         = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : $this->settings['fields']['hide_empty']['default'];

            $dropdown_args      = array(
                'hide_empty' => $hide_empty,
            );

            $list_args          = array(
                'show_count'   => $count,
                'hierarchical' => $hierarchical,
                'taxonomy'     => 'portfolio_category',
                'hide_empty'   => $hide_empty,
            );

            $max_depth          = absint( isset( $instance['max_depth'] ) ? $instance['max_depth'] : $this->settings['fields']['max_depth']['default'] );

            $dropdown_args['depth']  = $max_depth;
            $list_args['depth']      = $max_depth;

            $this->current_cat   = false;
            $this->cat_ancestors = array();

            if ( g5portfolio_is_cat() ) {
                $this->current_cat   = $wp_query->queried_object;
                $this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'portfolio_category' );
            } elseif (g5portfolio_is_single()) {
                $terms = wp_get_post_terms($post->ID,'portfolio_category',array(
                    'orderby' => 'parent',
                    'order'   => 'DESC',
                ));
                if ( $terms ) {
                    $main_term           = apply_filters( 'g5portfolio_categories_widget_main_term', $terms[0], $terms );
                    $this->current_cat   = $main_term;
                    $this->cat_ancestors = get_ancestors( $main_term->term_id, 'portfolio_category' );
                }
            }

            // Show Siblings and Children Only.
            if ( $show_children_only && $this->current_cat ) {
                if ( $hierarchical ) {
                    $include = array_merge(
                        $this->cat_ancestors,
                        array( $this->current_cat->term_id ),
                        get_terms(
                            'portfolio_category',
                            array(
                                'fields'       => 'ids',
                                'parent'       => 0,
                                'hierarchical' => true,
                                'hide_empty'   => false,
                            )
                        ),
                        get_terms(
                            'portfolio_category',
                            array(
                                'fields'       => 'ids',
                                'parent'       => $this->current_cat->term_id,
                                'hierarchical' => true,
                                'hide_empty'   => false,
                            )
                        )
                    );
                    // Gather siblings of ancestors.
                    if ( $this->cat_ancestors ) {
                        foreach ( $this->cat_ancestors as $ancestor ) {
                            $include = array_merge(
                                $include,
                                get_terms(
                                    'portfolio_category',
                                    array(
                                        'fields'       => 'ids',
                                        'parent'       => $ancestor,
                                        'hierarchical' => false,
                                        'hide_empty'   => false,
                                    )
                                )
                            );
                        }
                    }
                } else {
                    // Direct children.
                    $include = get_terms(
                        'portfolio_category',
                        array(
                            'fields'       => 'ids',
                            'parent'       => $this->current_cat->term_id,
                            'hierarchical' => true,
                            'hide_empty'   => false,
                        )
                    );
                }

                $list_args['include']     = implode( ',', $include );
                $dropdown_args['include'] = $list_args['include'];

                if ( empty( $include ) ) {
                    return;
                }
            } elseif ( $show_children_only ) {
                $dropdown_args['depth']        = 1;
                $dropdown_args['child_of']     = 0;
                $dropdown_args['hierarchical'] = 1;
                $list_args['depth']            = 1;
                $list_args['child_of']         = 0;
                $list_args['hierarchical']     = 1;
            }



            ob_start();
            $this->widget_start($args,$instance);
            if ($dropdown) {
                g5portfolio_dropdown_categories(
                    apply_filters(
                        'g5portfolio_categories_widget_dropdown_args',
                        wp_parse_args(
                            $dropdown_args,
                            array(
                                'show_count'         => $count,
                                'hierarchical'       => $hierarchical,
                                'show_uncategorized' => 0,
                                'selected'           => $this->current_cat ? $this->current_cat->slug : '',
                            )
                        )
                    )
                );

            } else {
                $list_args['title_li']                   = '';
                $list_args['pad_counts']                 = 1;
                $list_args['show_option_none']           = __( 'No portfolio categories exist.', 'g5-portfolio' );
                $list_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
                $list_args['current_category_ancestors'] = $this->cat_ancestors;
                $list_args['max_depth']                  = $max_depth;
                echo  '<ul class="g5portfolio__portfolio-categories">';
                wp_list_categories( apply_filters( 'g5portfolio__widget_categories_args', $list_args, $instance ) );
                echo '</ul>';
            }
            $this->widget_end($args);
            echo $this->cache_widget( $args, ob_get_clean() ); // WPCS: XSS ok.
        }
    }
}