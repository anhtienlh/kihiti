<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Core_Listing_Abstract', false)) {
    G5CORE()->load_file(G5CORE()->plugin_dir('inc/abstract/listing.class.php'));
}
if (!class_exists('G5Portfolio_Listing')) {
    class G5Portfolio_Listing extends G5Core_Listing_Abstract
    {
        private static $_instance;

        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        protected $key_layout_settings = 'g5portfolio_layout_settings';

        public function init()
        {
            add_action('g5core_portfolio_pagination_ajax_response', array($this, 'pagination_ajax_response'), 10, 2);
        }

        public function pagination_ajax_response($settings, $query_args)
        {
            $this->render_content($query_args, $settings);
        }

        public function get_layout_settings_default()
        {
            return array(
                'post_layout' => G5PORTFOLIO()->options()->get_option('post_layout'),
                'item_skin' => G5PORTFOLIO()->options()->get_option('item_skin'),
                'item_custom_class' => G5PORTFOLIO()->options()->get_option('item_custom_class'),
                'post_columns' => array(
                    'xl' => intval(G5PORTFOLIO()->options()->get_option('post_columns_xl')),
                    'lg' => intval(G5PORTFOLIO()->options()->get_option('post_columns_lg')),
                    'md' => intval(G5PORTFOLIO()->options()->get_option('post_columns_md')),
                    'sm' => intval(G5PORTFOLIO()->options()->get_option('post_columns_sm')),
                    '' => intval(G5PORTFOLIO()->options()->get_option('post_columns')),
                ),
                'post_animation' => G5PORTFOLIO()->options()->get_option('post_animation'),
                'columns_gutter' => intval(G5PORTFOLIO()->options()->get_option('post_columns_gutter')),
                'post_paging' => G5PORTFOLIO()->options()->get_option('post_paging'),
                'itemSelector' => 'article',
                'cate_filter_enable' => false,
                'append_tabs' => G5PORTFOLIO()->options()->get_option('append_tabs'),
                'post_type' => 'portfolio',
                'taxonomy' => 'portfolio_category',

            );
        }

        public function render_listing()
        {
            G5PORTFOLIO()->get_template('listing.php');
        }

        public function get_config_layout_matrix()
        {
            $post_settings = $this->get_layout_settings();
            $item_skin = isset($post_settings['item_skin']) ? $post_settings['item_skin'] : '';

            $justified_options = array();
            if ($post_settings['post_layout'] === 'justified' && !isset($post_settings['justified'])) {


                $justified_row_height_arr = G5PORTFOLIO()->options()->get_option('justified_row_height');
                if (is_array($justified_row_height_arr) && isset($justified_row_height_arr['height'])) {
                    $justified_row_height = absint($justified_row_height_arr['height']);
                } else {
                    $justified_row_height = 375;
                }

                $justified_row_max_height_arr = G5PORTFOLIO()->options()->get_option('justified_row_max_height');
                if (is_array($justified_row_max_height_arr) && isset($justified_row_max_height_arr['height'])) {
                    $justified_row_max_height = absint($justified_row_max_height_arr['height']);
                } else {
                    $justified_row_max_height = 0;
                }


                $justified_options = array(
                    'rowHeight' => $justified_row_height,
                    'maxRowHeight' => $justified_row_max_height > 0 ? $justified_row_max_height : false,
                    'margins' => $post_settings['columns_gutter'],
                    'selector' => '.g5portfolio__post-default',
                    'imgSelector' => '.g5core__entry-thumbnail-image > img'
                );
            }


            $data = apply_filters('g5portfolio_config_layout_matrix', array(
                'grid' => array(
                    'layout' => array(
                        array('template' => $item_skin)
                    ),
                ),
                'masonry' => array(
                    'layout' => array(
                        array('template' => $item_skin),
                    ),
                    'image_mode' => 'image',
                    'isotope' => array(
                        'itemSelector' => 'article',
                        'layoutMode' => 'masonry',
                    ),
                ),
                'masonry-2' => array(
                    'isotope' => array(
                        'itemSelector' => 'article',
                        'layoutMode' => 'masonry',
                    ),
                    'layout' => array(
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 3, 'lg' => 3, 'md' => 3, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 3, 'lg' => 3, 'md' => 3, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1.55'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 3, 'lg' => 3, 'md' => 3, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 3, 'lg' => 3, 'md' => 3, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1.55'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 3, 'lg' => 3, 'md' => 3, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1.55'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 3, 'lg' => 3, 'md' => 3, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),

                    )
                ),
                'justified' => array(
                    'layout' => array(
                        array('template' => $item_skin),
                    ),
                    'image_mode' => 'image',
                    'image_size' => 'full',
                    'justified' => $justified_options
                ),
                'metro-1' => array(
                    'isotope' => array(
                        'itemSelector' => 'article',
                        'layoutMode' => 'masonry',
                        'percentPosition' => true,
                        'masonry' => array(
                            'columnWidth' => '.g5core__col-base',
                        ),
                        'metro' => true
                    ),
                    'layout' => array(
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 2, 'lg' => 2, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '2x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x2'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x2'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 2, 'lg' => 2, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '2x1'),
                    )
                ),
                'metro-2' => array(
                    'isotope' => array(
                        'itemSelector' => 'article',
                        'layoutMode' => 'masonry',
                        'percentPosition' => true,
                        'masonry' => array(
                            'columnWidth' => '.g5core__col-base',
                        ),
                        'metro' => true
                    ),
                    'layout' => array(
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 2, 'lg' => 2, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '2x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x2'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x2'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 2, 'lg' => 2, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '2x1'),

                    )
                ),
                'metro-3' => array(
                    'isotope' => array(
                        'itemSelector' => 'article',
                        'layoutMode' => 'masonry',
                        'percentPosition' => true,
                        'masonry' => array(
                            'columnWidth' => '.g5core__col-base',
                        ),
                        'metro' => true
                    ),
                    'layout' => array(
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 2, 'lg' => 2, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '2x2'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 2, 'lg' => 2, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '2x2'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                        array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 2, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
                    )
                ),
                'metro-4' => array(
	                'isotope' => array(
		                'itemSelector' => 'article',
		                'layoutMode' => 'masonry',
		                'percentPosition' => true,
		                'masonry' => array(
			                'columnWidth' => '.g5core__col-base',
		                ),
		                'metro' => true
	                ),
	                'layout' => array(
		                array('columns' => g5core_get_bootstrap_columns(array('xl' => 2, 'lg' => 2, 'md' => 2, 'sm' => 1, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '2x2'),
		                array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 4, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
		                array('columns' => g5core_get_bootstrap_columns(array('xl' => 4, 'lg' => 4, 'md' => 4, 'sm' => 2, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '1x1'),
		                array('columns' => g5core_get_bootstrap_columns(array('xl' => 2, 'lg' => 2, 'md' => 2, 'sm' => 1, '' => 1)), 'template' => $item_skin, 'layout_ratio' => '2x1'),
	                )
                ),

            ),$item_skin);
            return $data;
        }
    }
}