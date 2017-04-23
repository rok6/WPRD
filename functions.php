<?php

/**
 * WPRD Paths
 *
 * @since 0.1
 * @WPRD_PARENT_PATH	: WPRD directory path
 * @WPRD_INC_PATH		: WPRD includes path
 * @WPRD_ASSETS_PATH	: WPRD assets path
 * @WPRD_MDLS_PATH		: WPRD modules path
 *
 */
if( !defined('WPRD_PARENT_PATH') ) {
	define('WPRD_PARENT_PATH' , dirname(__FILE__));
}

if( !defined('WPRD_INC_PATH') ) {
	define('WPRD_INC_PATH' , dirname(__FILE__) . '/includes');
}

if( !defined('WPRD_ASSETS_PATH') ) {
	define('WPRD_ASSETS_PATH' , dirname(__FILE__) . '/includes/assets');
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

	$wprd = new WPRD();


	// リダイレクト時の推測候補先への遷移を禁止
	$wprd->remove_auto_redirect();
	// 管理画面用CSSの追加
	$wprd->add_admin_style('/assets/css/admin-style.css');
	// Editor CSS へのパス
	add_editor_style( '/assets/css/editor-style.css' );

	// メール設定
	add_filter('wp_mail_from', function($email) {
		// 送信側メールアドレス
		return 'wordpress@example.com';
	});
	add_filter('wp_mail_from_name', function($email_name) {
		// 送信者名
		return $email_name;
	});
}, 0);
