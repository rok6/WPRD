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

	public function add_admin_script( $js_path = 'admin.js', array $allowed_hook = [] )
	{
		add_action('admin_enqueue_scripts', function($hook) use($js_path, $allowed_hook) {
			if( self::valid_hook($hook, $allowed_hook) ) {
				wp_enqueue_script( 'my_admin_scripts', get_template_directory_uri().$js_path, false, null, true );
			}
		});
	}

	public static function valid_hook( $hook, array $allowed = [] )
	{
		$hook = (string)$hook;

		if( empty($allowed) ) {
			return true;
		}
		return in_array($hook, $allowed);
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
		add_filter('menu_order', function($menu_order) {
			$menu = [];

			foreach( $menu_order as $key => $val ) {
				if( 0 === strpos($val, 'edit.php') ) {
					break;
				}
				$menu[] = $val;
				unset($menu_order[$key]);
			}

			foreach( $menu_order as $key => $val ) {
				if( 0 === strpos($val, 'edit.php') ) {
					$menu[] = $val;
					unset($menu_order[$key]);
				}
			}

			return array_merge($menu, $menu_order);
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


	public function switch_to_radio_type_category()
	{
		$this->add_non_categorize();

		add_action( 'wp_terms_checklist_args', function($args, $post_id = null) {
			$args['checked_ontop'] = false;
			$args['walker'] = new Walker_Taxonomy_radio();
			return $args;
		});
	}

	private function add_non_categorize()
	{
		add_filter('wp_dropdown_cats', function($output, $r) {
			global $pagenow;

			if( $pagenow === 'edit.php' ) {
				return $output;
			}

			$regex = '#(<option(.)*</option>)#u';
			if ( preg_match($regex, $output) ) {
				$output = preg_split($regex, $output, 2);
				$output[0] .= '<option class="level-0" value="-1">'.__('設定しない').'</option>';
				$output = $output[0].$output[1];
			}

			return $output;
		}, 10, 2);
	}

}



/**
 * Walker_Taxonomy_radio extends Walker_Category_Checklist
 * @since 0.1
 */
require_once(ABSPATH . 'wp-admin/includes/template.php');

class Walker_Taxonomy_radio extends Walker_Category_Checklist
{
	// 参考：http://chaika.hatenablog.com/entry/2015/06/08/210000

	function start_el( &$output, $category, $depth = 0, $args = [], $id = 0 )
	{
		extract($args);

		if( empty($taxonomy) ) {
			$taxonomy = 'category';
		}

		if( $taxonomy === 'category' ) {
			$name = 'post_category';
		} else {
			$name = 'tax_input['.$taxonomy.']';
		}

		$class = in_array( $category->term_id, $popular_cats ) ? ' class="popular-category"' : '';

		// 先頭に「設定しない」の項目を追加
		if( $output === '' ) {
			$output .= PHP_EOL."<li id=\"{$taxonomy}-{$category->term_id}\"$class>" .
				'<label class="selectit"><input value="-1" type="radio" name="'.$name.'[]" id="no-categorize"' .
				checked( !in_array( $category->term_id, $selected_cats ), true, false ) .
				disabled( empty( $args['disabled'] ), false, false ) . ' /> 設定しない</label>';
		}

		// 子カテゴリーがある場合は選択ボタンを表示しない
		$chaild_terms = get_term_children($category->term_id, $taxonomy);
		if( !empty( $chaild_terms ) ) {
			$output .= PHP_EOL."<li id=\"{$taxonomy}-{$category->term_id}\"$class>" .
				'<label class="selectit">' . esc_html( apply_filters('the_category', $category->name )) . '</label>';
		}
		else {
			$output .= PHP_EOL."<li id=\"{$taxonomy}-{$category->term_id}\"$class>" .
				'<label class="selectit"><input value="' . $category->term_id . '" type="radio" name="'.$name.'[]" id="in-'.$taxonomy.'-' . $category->term_id . '"' .
				checked( in_array( $category->term_id, $selected_cats ), true, false ) .
				disabled( empty( $args['disabled'] ), false, false ) . ' /> ' .
				esc_html( apply_filters('the_category', $category->name )) . '</label>';
		}
	}

}
