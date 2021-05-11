<?php
add_filter('g5core_dashboard_menu', 'gid_dashboard_menu_install_demo');
function gid_dashboard_menu_install_demo($page_configs) {
	$page_configs['install_demo'] = array(
		'page_title'      => esc_html__( 'Install Demo', 'gid' ),
		'menu_title'      => esc_html__( 'Install Demo', 'gid' ),
		'priority'        => 25,
		'function_binder' => 'gid_install_demo_template'
	);

	return $page_configs;
}
function gid_install_demo_template() {
	GID()->get_template('install-page.php');
}