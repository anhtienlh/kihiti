<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


function g5careers_template_single_title() {
	$single_title_enable = G5CAREERS()->options()->get_option('single_title_enable');
	if ($single_title_enable === 'on') {
		G5CAREERS()->get_template( 'single/title.php' );
	}
}
add_action('g5careers_before_single_content','g5careers_template_single_title',5);


function g5careers_template_single_meta() {
	$single_meta_enable = G5CAREERS()->options()->get_option('single_meta_enable');
	if ($single_meta_enable === 'on') {
		$items = g5careers_get_careers_info(true);
		G5CAREERS()->get_template( 'single/meta.php', array('items' => $items) );
	}
}
add_action('g5careers_before_single_content','g5careers_template_single_meta',10);


function g5careers_template_single_button_contact_us() {
	$prefix = G5CAREERS()->meta_prefix;
	$contact_us_type = get_post_meta(get_the_ID(),"{$prefix}contact_us_type",true);
	if ($contact_us_type === '') return;
	$contact_us_form = get_post_meta(get_the_ID(),"{$prefix}contact_us_form",true);
	$contact_us_link = get_post_meta(get_the_ID(),"{$prefix}contact_us_link",true);

	if((($contact_us_type === 'link') && ($contact_us_link === ''))
	|| (($contact_us_type === 'form') && ($contact_us_form === ''))) {
		return;
	}
	G5CAREERS()->get_template('single/contact-us.php',array(
		'type' => $contact_us_type,
		'form' => $contact_us_form,
		'link' => $contact_us_link
	));
}
add_action( 'g5careers_after_single_content', 'g5careers_template_single_button_contact_us', 5 );

function g5careers_template_contact_form_popup() {
	$prefix = G5CAREERS()->meta_prefix;
	$contact_us_form = get_post_meta(get_the_ID(),"{$prefix}contact_us_form",true);
	if ($contact_us_form !== '') {
		G5CAREERS()->get_template('popup/contact.php',array('id' => $contact_us_form));
	}
}


function g5careers_template_single_share() {
	$social_share_enable =  g5careers_single_share_enable();
	if ($social_share_enable) {
		g5core_template_social_share();
	}
}

add_action( 'g5careers_after_single_content', 'g5careers_template_single_share', 10 );


add_action('init','g5careers_template_single_meta_bottom_wrap');
function g5careers_template_single_meta_bottom_wrap() {
	$prefix = G5CAREERS()->meta_prefix;
	$contact_us_type = get_post_meta(get_the_ID(),"{$prefix}contact_us_type",true);
	$social_share_enable =  g5careers_single_share_enable();

	if ($contact_us_type !== '' || $social_share_enable) {
		add_action('g5careers_after_single_content','g5careers_template_single_meta_bottom_wrap_start',1);
		add_action('g5careers_after_single_content','g5careers_template_single_meta_bottom_wrap_end',100);
	}

}

function g5careers_template_single_meta_bottom_wrap_start() {
	echo '<div class="g5careers__single-meta-bottom-wrap">';
}

function g5careers_template_single_meta_bottom_wrap_end() {
	echo  '</div>';
}

