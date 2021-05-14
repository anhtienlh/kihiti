<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $additional_details
 */
?>
<div class="g5portfolio__single-meta">
    <?php foreach ($additional_details as $additional_detail): ?>
        <?php if (isset($additional_detail['title']) && !empty($additional_detail['title']) && isset($additional_detail['value']) && !empty($additional_detail['value'])): ?>
        <div>
            <label><?php echo wp_kses_post($additional_detail['title']) ?>:</label>
            <span><?php echo wp_kses_post($additional_detail['value']) ?></span>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php
    /**
     * @hooked - g5portfolio_template_single_cat - 10
     * @hooked - g5portfolio_template_single_tag - 20
     * @hooked - g5portfolio_template_single_share - 30
     */
    do_action('g5portfolio_before_single_meta')
    ?>
</div>
