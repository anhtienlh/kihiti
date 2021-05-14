<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
?>
<div class="g5portfolio__single-wrap">
    <article id="post-<?php the_ID() ?>" <?php post_class('g5portfolio__single'); ?>>
        <?php g5portfolio_template_single_gallery(); ?>
        <div class="g5portfolio__single-content-wrap">
            <div class="g5portfolio__single-content row">
                <div class="entry-content clearfix col-lg-8">
                    <?php g5portfolio_template_single_title() ?>
                    <?php the_content();?>
                </div>
                <div class="g5portfolio__single-meta-wrap col-lg-4">
                    <?php g5portfolio_template_single_meta(); ?>
                </div>
            </div>
        </div>
    </article>
    <?php
    /**
     * @hooked - g5portfolio_template_single_navigation - 10
     * @hooked - g5portfolio_template_single_related - 20
     * @hooked - g5portfolio_template_single_comment - 30
     */
    do_action('g5portfolio_after_single');
    ?>
</div>
