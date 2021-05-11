<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'G5Portfolio_Query' ) ) {
	class G5Portfolio_Query {
		private static $_instance;
		public static function getInstance() {
			if (self::$_instance == NULL) { self::$_instance = new self(); }
			return self::$_instance;
		}

		public function init() {
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		}

		public function get_archive_ordering_args( $orderby = '', $order = '' ) {
			// Get ordering from query string unless defined.
			if ( ! $orderby ) {
				$orderby = isset($_GET['orderby']) ? wp_unslash($_GET['orderby']) : get_query_var('orderby');
				$order = isset($_GET['order']) ? wp_unslash($_GET['order']) : get_query_var('order');

				if (!$orderby) {
					$orderby       = G5PORTFOLIO()->options()->get_option('archive_orderby');
				}

				if (!$order) {
					$order         = G5PORTFOLIO()->options()->get_option('archive_order');
				}
			}

			$orderby = strtolower( $orderby );
			$order   = strtoupper( $order );
			$args    = array(
				'orderby'  => $orderby,
				'order'    => ( 'ASC' === $order ) ? 'ASC' : 'DESC'
			);
			switch ( $orderby ) {
				case 'id':
					$args['orderby'] = 'ID';
					break;
				case 'menu_order':
					$args['orderby'] = 'menu_order title';
					break;
				case 'title':
					$args['orderby'] = 'title';
					$args['order']   = ( 'DESC' === $order ) ? 'DESC' : 'ASC';
					break;
				case 'relevance':
					$args['orderby'] = 'relevance';
					$args['order']   = 'DESC';
					break;
				case 'rand':
					$args['orderby'] = 'rand'; // @codingStandardsIgnoreLine
					break;
				case 'date':
					$args['orderby'] = 'date ID';
					$args['order']   = ( 'ASC' === $order ) ? 'ASC' : 'DESC';
					break;
			}

			return apply_filters( 'g5portfolio_get_archive_ordering_args', $args );
		}

		public function pre_get_posts( $query ) {
			if ( ! is_admin() && $query->is_main_query() && ( $query->is_post_type_archive( 'portfolio' ) || $query->is_tax( get_object_taxonomies( 'portfolio' ) ) ) ) {
				$ordering = $this->get_archive_ordering_args();
				$query->set( 'orderby', $ordering['orderby'] );
				$query->set( 'order', $ordering['order'] );


				$posts_per_page = absint( G5PORTFOLIO()->options()->get_option( 'posts_per_page' ) );
				if ( ! empty( $posts_per_page ) ) {
					$query->set( 'posts_per_page', $posts_per_page );
				}

			}
		}
	}
}