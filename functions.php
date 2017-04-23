<?php

/**
 * WPRD Paths
 *
 * @since 0.1
 * @WPRD_PARENT_PATH	: WPRD directory path
 * @WPRD_INC_PATH		: WPRD includes path
 * @WPRD_MDLS_PATH		: WPRD modules path
 *
 */
if( !defined('WPRD_PARENT_PATH') ) {
	define('WPRD_PARENT_PATH' , dirname(__FILE__));
}

if( !defined('WPRD_INC_PATH') ) {
	define('WPRD_INC_PATH' , dirname(__FILE__) . '/includes');
}

if( !defined('WPRD_MDLS_PATH') ) {
	define('WPRD_MDLS_PATH' , dirname(__FILE__) . '/modules');
}


/**
 * WPRD load
 * @since 0.1
 */
//require_once(WPRD_INC_PATH . '/Helper.php');
require_once(WPRD_MDLS_PATH . '/model/Model.php');
require_once(WPRD_MDLS_PATH . '/controller/Controller.php');

add_action('after_setup_theme', function () {
	require_once dirname(__FILE__) . '/includes/wprd.php';
	require_once dirname(__FILE__) . '/includes/wprd_Functions.php';
	require_once dirname(__FILE__) . '/includes/wprd_Helper.php';
	require_once dirname(__FILE__) . '/config.php';

	$wprd = new WPRD( 'WPRD' );

	// Editor CSS へのパス
	add_editor_style( '/assets/css/editor-style.css' );
	// 管理画面用CSSの追加
	$wprd->settings->add_admin_style('/includes/admin/css/admin-style.css');
	$wprd->settings->set_load_theme_textdomain();
	$wprd->settings->on_support();
	$wprd->settings->remove_wp_admin_logo();
	$wprd->settings->reorder_admin_menu();
	$wprd->settings->clean_wp_head([
		'wp_generator',
		'wp_shortlink_wp_head',
		//'feed_links',
		//'feed_links_extra',
		/* EditURI */
		'rsd_link',
		/* wlwmanifest */
		'wlwmanifest_link',
		/* page-links */
		'index_rel_link',
		'parent_post_rel_link',
		'start_post_rel_link',
		'adjacent_posts_rel_link_wp_head',
		/* oEmbed */
		//'rest_output_link_wp_head',
		'wp_oembed_add_discovery_links',
		'wp_oembed_add_host_js',
		/* emoji */
		'print_emoji_detection_script',
		'print_emoji_styles',
		/* inline-style */
		'recent_comments_style',
	]);

	// 日本語スラッグの禁止
	$wprd->settings->on_post_auto_slug();
	// リダイレクト時の推測候補先への遷移を禁止
	$wprd->settings->remove_auto_redirect();

	// メール設定
	add_filter('wp_mail_from', function($email) {
		// 送信側メールアドレス
		return 'wordpress@example.com';
	});
	add_filter('wp_mail_from_name', function($email_name) {
		// 送信者名
		return $email_name;
	});


	$wprd->editor->on_buttons_visual_editor();
	$wprd->editor->on_buttons_text_editor();
}, 0);
