<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
echo get_the_term_list(get_the_ID(), 'portfolio_tag', '<div><label>'. esc_html__( 'Tags:', 'g5-portfolio' ) .'</label><span>', ' / ', '</span></div>');