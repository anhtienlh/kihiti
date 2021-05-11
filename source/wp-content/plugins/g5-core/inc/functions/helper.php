<?php
/**
 * Truncate text
 *
 * @param $text
 * @param $length
 *
 * @return null|string|string[]
 */
function g5core_truncate_text($text, $length) {
	$text = strip_tags($text, '<img />');
	$length = abs((int)$length);
	if (strlen($text) > $length) {
		$text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
	}
	return $text;
}

/**
 * Get site name
 *
 * @return string
 */
function g5core_site_name() {
	if ( is_multisite() ) {
		return get_network()->site_name;
	}

	/*
	 * The blogname option is escaped with esc_html on the way into the database
	 * in sanitize_option we want to reverse this for the plain text arena of emails.
	 */
	return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
}

/**
 * Get social login count
 *
 * @return int|mixed
 */
function g5core_social_login_count() {
	if (isset($GLOBALS['g5core_social_login_count'])) {
		return $GLOBALS['g5core_social_login_count'];
	}
	global $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
	$count = 0;
	if (isset($WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG) && is_array($WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG) && count($WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG)) {
		foreach ($WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG as $provider) {
			$provider_id = isset( $provider["provider_id"] ) ? $provider["provider_id"] : '';
			$is_enable   = get_option( 'wsl_settings_' . $provider_id . '_enabled' );

			if ( ! $is_enable ) {
				continue;
			}
			$count++;
		}
	}
	$GLOBALS['g5core_social_login_count'] = $count;
	return $count;
}


/**
 * Standardized font variable
 * @param $fonts
 *
 * @return mixed
 */
function g5core_process_font($fonts) {
	if (!isset($fonts['font_weight']) || (($fonts['font_weight'] === '') || ($fonts['font_weight'] === 'regular')) ) {
		$fonts['font_weight'] = '400';
	}

	if (!isset($fonts['font_style']) || ($fonts['font_style'] === '') ) {
		$fonts['font_style'] = 'normal';
	}

	if (!isset($fonts['letter_spacing']) || ($fonts['letter_spacing'] === '') ) {
		$fonts['letter_spacing'] = '0';
	}

	if (!isset($fonts['transform']) || ($fonts['transform'] === '') ) {
		$fonts['transform'] = 'none';
	}
	return $fonts;
}

/**
 * Standardized background variable
 *
 * @var $background array
 * @var $selector string
 * @return string
 */
function g5core_get_background_css($background, $selector) {
	$background = wp_parse_args($background, array(
		'background_color' => '',
		'background_image_url' => '',
		'background_position' => '',
		'background_repeat' => '',
		'background_size' => '',
		'background_attachment' => '',
	));

	$css = "background-color: {$background['background_color']};";
	if ( ! empty( $background['background_image_url'] ) ) {
		$css .= "background-image: url({$background['background_image_url']});";
		$css .= "background-position: {$background['background_position']};";
		$css .= "background-repeat: {$background['background_repeat']};";
		$css .= "background-size: {$background['background_size']};";
		$css .= "background-attachment: {$background['background_attachment']};";
	}
	return "{$selector}{{$css}}";
}

/**
 * Get all post type in front-end
 *
 * @return array
 */
function g5core_post_types_active() {
	if (G5CORE()->cache()->exists('g5core_post_types_active')) {
		return G5CORE()->cache()->get('g5core_post_types_active');
	}

	$out = array();
	$all_post_types = get_post_types(array(
		'public' => true,
		'exclude_from_search' => false,
	), 'objects');
	foreach ($all_post_types as $pt_name => $pt) {
		if ($pt_name === 'attachment') {
			continue;
		}
		switch ($pt_name) {
			case 'post':
				$pt->label = esc_html__('Blog','g5-core');
				break;
			case 'product':
				$pt->label = esc_html__('Shop','g5-core');
				break;
		}

		$out[$pt_name] = array(
			'label' => $pt->label,
			'icon'  => $pt->menu_icon === NULL ? 'dashicons-admin-post' : $pt->menu_icon,
		);
	}
	$res = array();
	if (isset($out['page'])) {
		$res['page'] = $out['page'];
		unset($out['page']);
		$res = array_merge($res, $out);
	}

	$res = apply_filters('g5core_post_types_active', $res);
	$GLOBALS['g5core_post_types_active'] = $res;
	G5CORE()->cache()->set('g5core_post_types_active', $res);
	return $res;
}

function g5core_is_post_archive() {
	global $post;
	$post_type = get_post_type($post);

	return is_home() || is_category() || is_tag() || is_search() || (is_archive() && ($post_type === 'post'));
}

/**
 * Get current post type
 *
 * @return mixed|string
 */
function g5core_get_current_post_type() {
	if (isset($GLOBALS['g5core_current_post_type'])) {
		return $GLOBALS['g5core_current_post_type'];
	}

	global $wp_query;
	$query_obj            = $wp_query->get_queried_object();
	$current_post_type = '';
	if ( is_a( $query_obj, 'WP_Post' ) ) {
		$current_post_type = $query_obj->post_type;
	} else if ( is_a( $query_obj, 'WP_Term' ) ) {
		$tax = get_taxonomy( $query_obj->taxonomy );
		if ( ! empty( $tax->object_type ) ) {
			$current_post_type = $tax->object_type[0];
		}
	} else if ( is_a( $query_obj, 'WP_Post_Type' ) ) {
		$current_post_type = $query_obj->name;
	}

	if (is_page()) {
		$current_post_type = 'page_single';
	}
	elseif ( $current_post_type && is_singular( $current_post_type )) {
		$current_post_type = $current_post_type . '_single';
	} else {
	    if (is_search() && isset($_GET['post_type'])) {
            $current_post_type = $_GET['post_type'] . '_archive';
        } else if ( g5core_is_post_archive() ) {
			$current_post_type = 'post_archive';
		} else if ( is_archive() ) {
			$current_post_type = $current_post_type . '_archive';
		} else {
			$current_post_type = '';
		}
	}

	$current_post_type = apply_filters( "g5core_post_type_{$current_post_type}_for_setting", $current_post_type );

	$GLOBALS['g5core_current_post_type'] = $current_post_type;
	return $current_post_type;
}

/**
 * Get content block
 * @param $id
 *
 * @return string
 */
function g5core_get_content_block($id) {
	if (empty($id)) return '';
	$content = get_post_field('post_content', $id);

	if (function_exists('vc_is_page_editable') && vc_is_page_editable()) {
		$content = do_shortcode($content);
	} else {
		$content = apply_filters('the_content', $content);
	}
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

/**
 * Get Page Title
 *
 * @return string
 */
function g5core_get_page_title()
{
	$page_title = '';
	if (is_home()) {
		$page_title = esc_html__('Blog', 'g5-core');
	}elseif (is_singular('product')) {
		$page_title = esc_html__('Shop', 'g5-core');
	} elseif (is_singular('post')) {
		$page_title = esc_html__('Blog', 'g5-core');
	} elseif (is_404()) {
		$page_title = esc_html__('Page Not Found', 'g5-core');
	} elseif (is_category() || is_tax()) {
		$page_title = single_term_title('', false);
	} elseif (is_tag()) {
		$page_title = single_tag_title(esc_html__("Tags: ", 'g5-core'), false);
	} elseif (is_search()) {
		$page_title = sprintf(esc_html__('Search Results For: %s', 'g5-core'), get_search_query());
	} elseif (is_day()) {
		$page_title = sprintf(esc_html__('Daily Archives: %s', 'g5-core'), get_the_date());
	} elseif (is_month()) {
		$page_title = sprintf(esc_html__('Monthly Archives: %s', 'g5-core'), get_the_date(_x('F Y', 'monthly archives date format', 'g5-core')));
	} elseif (is_year()) {
		$page_title = sprintf(esc_html__('Yearly Archives: %s', 'g5-core'), get_the_date(_x('Y', 'yearly archives date format', 'g5-core')));
	} elseif (is_author()) {
		$page_title = sprintf(esc_html__('Author: %s', 'g5-core'), get_the_author());
	} elseif (is_tax('post_format', 'post-format-aside')) {
		$page_title = esc_html__('Asides', 'g5-core');
	} elseif (is_tax('post_format', 'post-format-gallery')) {
		$page_title = esc_html__('Galleries', 'g5-core');
	} elseif (is_tax('post_format', 'post-format-image')) {
		$page_title = esc_html__('Images', 'g5-core');
	} elseif (is_tax('post_format', 'post-format-video')) {
		$page_title = esc_html__('Videos', 'g5-core');
	} elseif (is_tax('post_format', 'post-format-quote')) {
		$page_title = esc_html__('Quotes', 'g5-core');
	} elseif (is_tax('post_format', 'post-format-link')) {
		$page_title = esc_html__('Links', 'g5-core');
	} elseif (is_tax('post_format', 'post-format-status')) {
		$page_title = esc_html__('Statuses', 'g5-core');
	} elseif (is_tax('post_format', 'post-format-audio')) {
		$page_title = esc_html__('Audios', 'g5-core');
	} elseif (is_tax('post_format', 'post-format-chat')) {
		$page_title = esc_html__('Chats', 'g5-core');
	} elseif (is_singular()) {
		$page_title = get_the_title();
	}


    $page_title = apply_filters('g5core_page_title', $page_title);

	if (is_category() || is_tax()) {
		$term = get_queried_object();
		if ($term && property_exists($term, 'term_id')) {
			$page_title_content = get_term_meta($term->term_id, G5CORE()->meta_prefix . 'page_title_custom', true);

			if ($page_title_content !== '') {
				$page_title = $page_title_content;
			}
		}
	}

	if (is_singular()) {
		$page_title_content = get_post_meta( get_the_ID(), G5CORE()->meta_prefix . 'page_title_custom', true);
		if ($page_title_content !== '') {
			$page_title = $page_title_content;
		}
	}

	return $page_title;
}

function g5core_get_page_subtitle() {
	$page_subtitle = '';

	if (is_category() || is_tax()) {
		$term = get_queried_object();
		if ($term && property_exists($term, 'term_id')) {
			$term_description = strip_tags(term_description());
			if (!empty($term_description)) {
				$page_subtitle = $term_description;
			}
		}
	}

	if (is_singular()) {
		$page_subtitle_content = get_post_meta( get_the_ID(), G5CORE()->meta_prefix . 'page_subtitle_custom', true);
		if ($page_subtitle_content !== '') {
			$page_subtitle = $page_subtitle_content;
		}
	}
	$page_subtitle = apply_filters('g5core_page_subtitle', $page_subtitle);
	return $page_subtitle;
}

/**
 * Get taxonomy for term meta
 *
 * @return array
 */
function g5core_get_taxonomy_for_term_meta() {
	return apply_filters('g5core_taxonomy_for_term_meta', array('category', 'post_tag'));
}

function g5core_get_bootstrap_columns($columns = array())
{
    $default = array(
        'xl' => 2,
        'lg' => 2,
        'md' => 1,
        'sm' => 1,
        '' => 1,
    );
    $columns = wp_parse_args($columns, $default);
    $classes = array();
    foreach ($columns as $key => $value) {
        if ($key !== '') {
            $key = "-{$key}";
        }
        if ($value > 0) {
            if($value == 5){
                $classes[$key] = "col{$key}-12-5";
            } else {
                $classes[$key] = "col{$key}-" . (12 / $value);
            }
        }
    }
    return join(' ', $classes);
}

/**
 * Get Image Url From Attachment ID
 *
 * @param $id
 * @param string $size
 *
 * @return string
 */
function g5core_get_url_by_attachment_id($id, $size = 'full') {
	$image_arr = wp_get_attachment_image_src($id, $size);
	return isset($image_arr[0]) ? $image_arr[0] : '';
}

/**
 * Get color from option
 *
 * @param $key
 * @param string $custom_color
 *
 * @return string
 */
function g5core_get_color_from_option($key) {
	if ($key === '') {
		return '';
	}
	return G5CORE()->options()->color()->get_option("{$key}_color");
}

function g5core_get_animation_class($css_animation) {
    $output = '';
    if ('' !== $css_animation && 'none' !== $css_animation) {
        $output = ' g5core__animate-when-almost-visible ' . 'g5core__'.$css_animation;
    }

    return $output;
}

function g5core_sort_by_priority($v1, $v2) {
	return (isset($v1['priority']) ? $v1['priority'] : 10) - (isset($v2['priority']) ? $v2['priority'] : 10);
}

/**
 * Check current theme has sidebar
 * @return mixed
 */
function g5core_has_sidebar() {
	if (function_exists(G5CORE_CURRENT_THEME . '_has_sidebar')) {
		return call_user_func(G5CORE_CURRENT_THEME . '_has_sidebar');
	}
	return false;
}

/**
 * Get Attachment ID by URL
 *
 * @param $url string
 *
 * @return int
 */
function g5core_get_attachment_id_by_url($url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url));
	if (!empty($attachment)) {
		return $attachment[0];
	}
	return 0;
}

function g5core_get_social_share() {
	$social_share = G5CORE()->options()->get_option('single_social_share');
	if (!is_array($social_share)) return false;
	unset($social_share['sort_order']);
	if (count($social_share) === 0) false;
	return $social_share;
}

function g5core_sort_by_order_callback($a, $b) {
	if (!isset($a['priority'])) {
		$a['priority'] = 100;
	}
	if (!isset($b['priority'])) {
		$b['priority'] = 100;
	}
	return $a['priority'] === $b['priority'] ? 0 : ($a['priority'] > $b['priority'] ? 1 : -1);
}

function g5core_page_used_vc($post_id = null) {
	if (is_null($post_id)) {
		global $post;
	} elseif (intval($post_id)) {
		$post = get_post( $post_id );
	} else {
		return false;
	}

	if ( ! $post || is_null( $post ) || is_wp_error( $post ) ) {
		return false;
	}
	$result = false;
	if (class_exists('Vc_Manager')) {
		preg_match_all( '/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER );
		if ( ! empty( $matches ) ) {
			foreach ( $matches as $shortcode ) {
				if ($shortcode[2] === 'vc_row') {
					$result = true;
					break;
				}
			}
		}
	}
	return $result;
}