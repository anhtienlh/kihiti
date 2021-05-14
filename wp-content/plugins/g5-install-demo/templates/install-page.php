<?php
$pages_settings = G5CORE()->dashboard()->get_config_pages();
$current_theme = wp_get_theme();
?>
<div class="g5core-dashboard wrap">
	<h2 class="screen-reader-text"><?php printf(esc_html__('%s Dashboard', 'gid'), $current_theme['Name']) ?></h2>
	<?php G5CORE()->get_plugin_template("inc/dashboard/templates/tab.php", array(
		'current_page' => 'install_demo'
	)) ?>
	<div class="g5core-dashboard-content">
		<div class="g5core-message-box">
			<h4 class="g5core-heading"><?php esc_html_e('Install Demo Data','gid') ?></h4>
			<p><?php esc_html_e('Install demo data (post, page, image, menu, widget, slider, theme setting, ...) is the easiest way to setup your theme. It will allow you to quickly edit everything instead of creating content from scratch.','gid') ?></p>
			<hr>
			<p><?php esc_html_e( 'When you import the data, the following things might happen:', 'gid' ); ?></p>
			<small>
				<ul>
					<li><strong><?php esc_html_e( 'All data in database and Upload Dir will be deleted when install demo.', 'gid' ); ?></strong></li>
					<li><?php esc_html_e( 'The included plugins need to be installed and activated before you install a demo.','gid') ?></li>
					<li><?php esc_html_e( 'Please check the "System Status" tab to ensure your server meets all requirements for a successful import. Settings that need attention will be listed in red.','gid') ?></li>
					<li><?php esc_html_e( 'Posts, pages, images, widgets, menus and other theme settings will get imported.', 'gid' ); ?></li>
					<li><?php esc_html_e( 'Please click on the Import button only once and wait, it can take a couple of minutes.', 'gid' ); ?></li>
				</ul>
			</small>
		</div>
		<div class="gid-demo-wrapper" data-nonce="<?php echo wp_create_nonce('gid_install_demo_data_action') ?>">
			<div class="g5core-row">
				<?php $_demo_list = gid_demo_list(); ?>
				<?php if (empty($_demo_list)): ?>
					<p><?php esc_html_e('Demo data not found!','gid') ?></p>
				<?php else: ?>
					<?php foreach (gid_demo_list() as $k => $v): ?>
						<div class="g5core-col g5core-col-4">
							<div class="gid-demo-item" data-demo="<?php echo esc_attr($k)?>">
								<figure>
									<img src="<?php echo esc_url($v['thumbnail']) ?>" alt="<?php echo esc_attr($v['name']) ?>">
									<a href="<?php echo esc_url($v['preview']) ?>" target="_blank"><?php esc_html_e('Preview','gid') ?></a>
								</figure>
								<div class="gid-demo-item-body">
									<div class="gid-demo-item-name" data-name="<?php echo esc_attr($v['name']) ?>"><?php echo esc_html($v['name']) ?></div>
									<button type="button" class="button button-secondary gid-demo-item-import"
									        data-confirm="<?php echo esc_attr__("Type \"install\" to accept install setting.\nNOTE: Your theme option, post, page, attachment... may change when the installation completed!",'gid') ?>"
									        data-ajax="<?php echo admin_url('admin-ajax.php?action=gid_install_demo_setting') ?>"
									        data-import-done="<?php esc_attr_e('Import Done','gid') ?>"
									        data-importing="<?php esc_attr_e('Importing','gid') ?>">
										<?php esc_html_e('Import Setting','gid') ?>
									</button>
									<button type="button" class="button button-primary gid-demo-item-import"
									        data-confirm="<?php echo esc_attr__("Type \"install\" to accept install demo data.\nNOTE: Will delete all post, page, term ... before install!",'gid') ?>"
									        data-ajax="<?php echo admin_url('admin-ajax.php?action=gid_install_demo_data') ?>"
									        data-import-done="<?php esc_attr_e('Import Done','gid') ?>"
									        data-importing="<?php esc_attr_e('Importing','gid') ?>">
										<?php esc_html_e('Import','gid') ?>
									</button>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>