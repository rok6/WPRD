<?php

class WPRD_Settings
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;
	}


	public function set_load_theme_textdomain( $folder = 'languages' )
	{
		load_theme_textdomain(self::$domain, get_template_directory() . '/' . $folder);
	}


	public function add_admin_style( $css_path = 'admin-style.css' )
	{
		add_action('admin_enqueue_scripts', function() use($css_path) {
			wp_enqueue_style( 'my_admin_style', get_template_directory_uri().$css_path );
		});
	}

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


	public function on_post_auto_slug()
	{
		add_filter('wp_unique_post_slug', function( $slug, $post_ID, $post_status, $post_type ) {
			if( preg_match( '/(%[0-9a-f]{2})+/', $slug ) ) {
				//$slug = utf8_uri_encode( $post_type ) . '-' . $post_ID;
				$slug = 'entry-' . $post_ID;
			}
			return $slug;
		}, 10, 4);
	}


	public function remove_auto_redirect() {
		add_filter( 'redirect_canonical', function($redirect_url) {
			if( is_404() ) {
				return false;
			}
			return $redirect_url;
		});
	}


	public function remove_wp_admin_logo() {
		add_action('wp_before_admin_bar_render', function() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu( 'wp-logo' );
		});
	}


	public function reorder_admin_menu()
	{
		add_filter('custom_menu_order', '__return_true');
		add_filter('menu_order', function( $menu_order ) {
			$menu = [];

			foreach( $menu_order as $key => $val ) {
				if( 0 === strpos( $val, 'edit.php' ) ) {
					break;
				}
				$menu[] = $val;
				unset( $menu_order[$key] );
			}

			foreach( $menu_order as $key => $val ) {
				if( 0 === strpos( $val, 'edit.php' ) ) {
					$menu[] = $val;
					unset( $menu_order[$key] );
				}
			}

			return array_merge( $menu, $menu_order );
		});
	}


	public function clean_wp_head( array $keys = [] )
	{
		foreach( $keys as $key ) {
			switch( $key ) {

				case 'index_rel_link' :
				case 'rsd_link' :
				case 'wlwmanifest_link' :
				case 'wp_generator' :
				case 'wp_shortlink_wp_head' :
				case 'rest_output_link_wp_head' :
				case 'wp_oembed_add_discovery_links' :
				case 'wp_oembed_add_host_js' :
					remove_action( 'wp_head', $key );
					break;

				case 'parent_post_rel_link' :
				case 'start_post_rel_link' :
				case 'adjacent_posts_rel_link_wp_head' :
					remove_action( 'wp_head', $key, 10, 0 );
					break;

				case 'feed_links' :
					remove_action( 'wp_head', $key, 2 );
					break;

				case 'feed_links_extra' :
					remove_action( 'wp_head', $key, 3 );
					break;

				case 'print_emoji_detection_script' :
					remove_action( 'wp_head', $key, 7 );
					remove_action( 'admin_print_scripts', $key );
				case 'print_emoji_styles' :
					remove_action( 'wp_print_styles', $key );
					remove_action( 'admin_print_styles', $key );
					break;

				case 'wp_staticize_emoji_for_email' :
					remove_action( 'wp_mail', $key );
					break;

				case 'wp_staticize_emoji' :
					remove_action( 'the_content_feed', $key );
					remove_action( 'comment_text_rss', $key );
					break;

				case 'recent_comments_style' :
					add_action('widgets_init', function() {
						global $wp_widget_factory;
						remove_action( 'wp_head', [
							$wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'
						]);
					});
					break;
			}
		}
	}
}
