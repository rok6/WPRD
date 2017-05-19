<?php

/**
 * pascalize
 *
 * @since 0.1
 * @param string || array $t	: convert text
 */
function pascalize( $t )
{
	return ucwords(strtolower($t));
}


/**
 * HTML special characters
 *
 * @since 0.1
 * @param $str
 */
function h( $str )
{
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


/**
 * dump ex
 *
 * @since 0.1
 * @param $str
 */
function _dump( $str ){
	echo PHP_EOL,'<pre>';
	var_dump($str);
	echo '</pre>',PHP_EOL;
}


/**
 * set_option
 *
 * @since 0.1
 * @param $key		: wp option kye
 * @param $value	: set value
 * @param bool $add = false	: use add_option()
 */
function set_option( $key, $value, $add = false )
{
	if( get_option($key) !== false ) {
		update_option($key, $value);
	}
	else if( $add ) {
		add_option($key, $value, null, 'no');
	}
}


/**
 * component
 *
 * @since 0.1
 * @param strign $name
 * @param string $render_type
 */
function component( $render_type = false, $post_type = false )
{
	return request_module('controller', $post_type, $render_type );
}


/**
 * request_module
 * @since 0.1
 * @param $key		: wp option kye
 * @param $value	: set value
 * @param bool $add = false	: use add_option()
 */
function request_module( $module_type, $post_type = false, $render_type = false )
{
	$namespace	= 'WPRD\\module\\';
	$post_type	= (string) $post_type;
	$module_path = WPRD_MDLS_PATH . '/'. $module_type .'/';

	// ファイル名のパスカライズ
	$filename = pascalize($post_type) . pascalize($module_type);

	if( !is_file( $module = $module_path . $filename . '.php' ) ) {

		if( $module_type === 'model' && is_file( $module = $module_path . 'Model.php' ) ) {
			$filename = 'Model';
		}
		else if( $module_type === 'controller' && is_file( $module = $module_path . 'Controller.php' ) ) {
			$filename = 'Controller';
		}
		else {
			return;
		}

	}

	require_once($module);

	$request_class = $namespace . $module_type . '\\' . $filename;
	$load_class = new $request_class();

	if( $module_type === 'controller' ) {
		$load_class->set($post_type, $render_type);
	}

	return $load_class;
}
