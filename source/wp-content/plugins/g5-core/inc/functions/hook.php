<?php
add_filter('xmenu_submenu_transition', 'g5core_xmenu_submenu_transition_filter', 20,2);
function g5core_xmenu_submenu_transition_filter($transition, $args) {
	if (isset($args->main_menu)) {
		$transition = G5CORE()->options()->header()->get_option('submenu_transition');
	}
	return $transition;
}
