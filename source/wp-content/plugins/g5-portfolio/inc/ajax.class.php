<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Portfolio_Ajax')) {
    class G5Portfolio_Ajax
    {
        private static $_instance;

        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function init()
        {
            add_action('wp_ajax_nopriv_g5portfolio_light_box_gallery', array($this, 'light_box_gallery'));
            add_action('wp_ajax_g5portfolio_light_box_gallery', array($this, 'light_box_gallery'));
        }

        function light_box_gallery()
        {
            check_ajax_referer('g5portfolio_light_box_gallery');
            $id = absint(isset($_REQUEST['id']) ? $_REQUEST['id'] : '');
            $items = array();
            if ($id > 0) {
                $prefix = G5PORTFOLIO()->meta_prefix;
                $media_type = get_post_meta($id, "{$prefix}single_media_type", true);
                if ($media_type === 'image') {
                    $gallery = get_post_meta($id, "{$prefix}single_gallery", true);
                    $gallery = explode('|', $gallery);
                    if (has_post_thumbnail($id)) {
                        array_unshift($gallery, get_post_thumbnail_id($id));
                    }

                    foreach ($gallery as $image_id) {
                        $image = wp_get_attachment_image_src($image_id, 'full');
                        if ($image === false) continue;
                        @list($src, $width, $height) = $image;
                        $items[] = array(
                            'src' => $src
                        );
                    }
                } else {
                    $gallery_video = get_post_meta($id, "{$prefix}single_video", true);
                    $gallery_video = !is_array($gallery_video) ? array($gallery_video) : $gallery_video;
                    $gallery_video = array_filter($gallery_video, 'strlen');

                    foreach ($gallery_video as $video) {
                        if (wp_oembed_get($video) !== false) {
                            $items[] = array(
                                'src' => $video
                            );
                        }
                    }

                    if (count($items) === 0) {
                        if (has_post_thumbnail($id)) {
                            $image = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'full');
                            if ($image !== false) {
                                $media_type = 'image';
                                @list($src, $width, $height) = $image;
                                $items[] = array(
                                    'src' => $src
                                );
                            }
                        }
                    }
                }
                wp_send_json_success(array(
                    'type' => $media_type,
                    'items' => $items
                ));
            }
            wp_send_json_error();
            wp_die();
        }
    }
}