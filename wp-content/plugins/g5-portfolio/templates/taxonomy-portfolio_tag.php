<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
get_header();
G5PORTFOLIO()->listing()->render_content();
get_footer();