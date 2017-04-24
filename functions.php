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
	require_once dirname(__FILE__) . '/includes/Helper.php';
}, 0);



/**
 * 子テーマで下記と同等の優先順位（9999）の after_setup_theme を記述した場合、
 * 以下の記述は無視され、子テーマの after_setup_theme が優先されます。
 */
add_action('after_setup_theme', function () {
	require_once dirname(__FILE__) . '/config-base.php';
	require_once dirname(__FILE__) . '/config.php';
}, 9999);
