<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $post
 */
?>
<h3 class="g5portfolio__post-title">
    <a title="<?php g5core_the_title_attribute($post)?>" href="<?php g5portfolio_the_permalink()?>"><?php g5core_the_title($post) ?></a>
</h3>
