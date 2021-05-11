<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$post_settings = &G5CAREERS()->listing()->get_layout_settings();
$post_paging = isset($post_settings['post_paging']) ? $post_settings['post_paging'] : 'pagination';
$post_animation = isset($post_settings['post_animation']) ? $post_settings['post_animation'] : '';
$table_columns = isset($post_settings['table_columns']) ? $post_settings['table_columns'] : G5CAREERS()->options()->get_option('archive_table_columns');
unset($table_columns['sort_order']);
$table_responsive = isset($post_settings['table_responsive']) ? $post_settings['table_responsive'] : G5CAREERS()->options()->get_option('archive_table_responsive');
$prefix = G5CAREERS()->meta_prefix;
$current_date = new DateTime();

$wrapper_classes = array(
    'g5careers__listing-wrap'
);

$wrapper_attributes = array();

$inner_attributes = array(
    'data-items-container'
);

$inner_classes = array(
    'g5careers__listing-inner'
);

$table_classes = array(
    'g5careers__table',
);

if ($table_responsive === 'on') {
	$table_classes[] = 'g5careers__table-responsive';
}

if (isset($post_settings['isMainQuery']))  {
    $wrapper_attributes[] = 'data-archive-wrapper';
}

$settingId = isset($post_settings['settingId']) ? $post_settings['settingId'] : uniqid();
$post_settings['settingId'] = $settingId;
$wrapper_attributes[] = sprintf('data-items-wrapper="%s"',$settingId) ;

$wrapper_class = join(' ', $wrapper_classes);
$inner_class = join(' ', $inner_classes);
$table_class = join(' ', $table_classes);
?>
<?php if (G5CORE()->query()->have_posts()): ?>
<div <?php echo join(' ', $wrapper_attributes); ?> class="<?php echo esc_attr($wrapper_class) ?>">
    <?php
    // You can use this for adding codes before the main loop
    do_action('g5core_before_listing_wrapper');
    ?>
	<table class="<?php echo esc_attr($table_class)?>">
		<thead>
			<tr>
				<?php foreach ($table_columns as $column): ?>
					<?php
					$title = '';
					switch ($column) {
						case 'title':
							$title = esc_html__('Job Title','g5-careers');
							break;
						case 'location':
							$title = esc_html__('Location','g5-careers');
							break;
						case 'department':
							$title = esc_html__('Department','g5-careers');
							break;
						case 'salary':
							$title = esc_html__('Salary','g5-careers');
							break;
						case 'expired_date':
							$title =  esc_html__('Expired Date','g5-careers');
							break;
					}
					?>
					<th class="<?php echo esc_attr($column)?>">
						<?php echo esc_html($title)?>
					</th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody <?php echo join(' ', $inner_attributes); ?> class="<?php echo esc_attr($inner_class); ?>">
			<?php while (G5CORE()->query()->have_posts()): G5CORE()->query()->the_post() ?>
			<tr>
				<?php foreach ($table_columns as $column): ?>
					<?php
					$html = '';
					$title = '';
					switch ($column) {
						case 'title':
							$title = esc_html__('Job Title','g5-careers');
							$html = sprintf('<a href="%s">%s</a>',get_the_permalink(),get_the_title());
							break;
						case 'location':
							$title = esc_html__('Location','g5-careers');
							$html = get_post_meta(get_the_ID(),"{$prefix}location",true);
							break;
						case 'department':
							$title = esc_html__('Department','g5-careers');
							$html = get_post_meta(get_the_ID(),"{$prefix}department",true);
							break;
						case 'salary':
							$title = esc_html__('Salary','g5-careers');
							$html = get_post_meta(get_the_ID(),"{$prefix}salary",true);
							break;
						case 'expired_date':
							$expired_date =  get_post_meta(get_the_ID(),"{$prefix}expired_date",true);
							$title =  esc_html__('Expired Date','g5-careers');
							if ($expired_date !== '') {
								$expired_date = DateTime::createFromFormat('d/m/Y', $expired_date);
								$html = sprintf('<span class="%s">%s</span>',
									$current_date->getTimestamp() > $expired_date->getTimestamp() ? 'expired' : '',
									date_i18n(get_option('date_format'), $expired_date->getTimestamp())
								);
							}
							break;
					} ?>
					<td class="<?php echo esc_attr($column)?>" data-title="<?php echo esc_attr($title)?>">
						<?php echo wp_kses_post($html)?>
					</td>
				<?php endforeach; ?>
			</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
    <?php
    // You can use this for adding codes before the main loop
    do_action('g5core_after_listing_wrapper');
    ?>
</div>
<?php elseif (isset($post_settings['isMainQuery'])): ?>
    <?php G5CAREERS()->get_template( 'loop/content-none.php' ); ?>
<?php endif; ?>
