<?php

class WPRD
{

	public static function on_support()
	{
		//HTML5でのマークアップの許可
		add_theme_support('html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		]);

		// wp_head() でのタイトルタグ出力の有効化
		add_theme_support('title-tag');

		//投稿・コメントのRSSフィードリンクの有効化
		add_theme_support('automatic-feed-links');
	}

	public function set_load_theme_textdomain(　$domain, $folder = 'languages' )
	{
		load_theme_textdomain($domain, get_template_directory() . '/' . $folder);
	}

	public function add_admin_style( $css_path = 'admin-style.css' )
	{
		add_action('admin_enqueue_scripts', function() use($css_path) {
			wp_enqueue_style( 'my_admin_style', get_template_directory_uri().$css_path );
		});
	}

	public function remove_auto_redirect() {
		add_filter( 'redirect_canonical', function($redirect_url) {
			if( is_404() ) {
				return false;
			}
			return $redirect_url;
		});
	}
}
