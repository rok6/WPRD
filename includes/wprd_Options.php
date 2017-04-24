<?php

class WPRD_Options
{
	static private $domain;

	private $roles = [
		'administrator',
		'editor',
		'author',
		'contributor',
		'subscriber',
	];

	public function __construct( $domain )
	{
		self::$domain = $domain;

		//特権管理者　マルチサイト全般
		//管理者　シングルサイト全般
		// administrator
		//編集者　投稿に関する権限のみ
		// editor
		//投稿者　自身の投稿管理のみ
		// author
		//寄稿者　投稿のみ、許可町
		// contributor
		//購読者　閲覧のみ
		// subscriber

		$user = wp_get_current_user();

		switch($user->roles[0]) {
			case 'administrator':
				break;
			case 'editor':
				break;
			case 'author':
				break;
			case 'contributor':
				break;
			case 'subscriber':
				break;
		}
	}

	public function init()
	{
		if( is_admin() ) {
			add_action('admin_menu', [$this, 'set_menu_options']);
		}
	}

	public function register_settings()
	{
		register_setting('site_settings', 'blogname');
		register_setting('site_settings', 'blogdescription');
		register_setting('site_settings', 'admin_email');
		register_setting('site_settings', 'twitter');
		register_setting('site_settings', 'facebook');
		register_setting('site_settings', 'posts_per_page');
		register_setting('site_settings', 'posts_per_rss');
		register_setting('site_settings', 'rss_use_excerpt');
		register_setting('site_settings', 'blog_public');
	}

	public function set_menu_options()
	{
		//$this->reset_wp_options_general();

		$options_group = 'site_settings';
		$role = 'administrator';

		add_menu_page(
			__( 'サイト設定', self::$domain ),
			__( 'サイト設定', self::$domain ),
			$role,
			$options_group,
			[$this, 'require_options'],
			'dashicons-admin-settings',
			80
		);
		add_submenu_page(
			$options_group,
			__( '一般設定', self::$domain ),
			__( '一般設定', self::$domain ),
			$role,
			$options_group,
			[$this, 'require_options']
		);
		add_submenu_page(
			$options_group,
			__( 'メディア', self::$domain ),
			__( 'メディア', self::$domain ),
			$role,
			$options_group . '-media',
			[$this, 'require_options']
		);

		add_action('admin_init', array( $this, 'register_settings' ));
	}

	public function require_options()
	{
		require_once(TEMPLATEPATH . '/modules/setting/options.php');
	}

	private function reset_wp_options_general()
	{
		//options-general.php, options-writing.php, options-reading.php, options-discussion.php, options-media.php, options-permalink.php
		remove_submenu_page('options-general.php', 'options-general.php');
		remove_submenu_page('options-general.php', 'options-writing.php');
		remove_submenu_page('options-general.php', 'options-reading.php');
		remove_submenu_page('options-general.php', 'options-discussion.php');
		remove_submenu_page('options-general.php', 'options-media.php');
		remove_submenu_page('options-general.php', 'options-permalink.php');

		remove_menu_page('options-general.php');

		add_menu_page(
			__( 'その他の設定', self::$domain ),
			__( 'その他の設定', self::$domain ),
			'administrator',
			'options-general.php',
			'',
			null,
			81
		);
	}

}
