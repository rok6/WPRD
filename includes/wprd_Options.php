<?php

class WPRD_Options
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;
	}

	public function init()
	{
		if( is_admin() ) {
			add_action('admin_init', [$this, 'register_settings']);
			add_action('admin_menu', [$this, 'set_menu_options']);
		}
	}

	public function set_menu_options()
	{
		$options_group = 'site_settings';
		$allowed_role_id = 'editor';

		add_menu_page(
			__( 'サイト設定', self::$domain ),
			__( 'サイト設定', self::$domain ),
			$allowed_role_id,
			$options_group,
			[$this, 'require_options'],
			'dashicons-admin-settings',
			80
		);
		// add_submenu_page(
		// 	$options_group,
		// 	__( 'サブ設定', self::$domain ),
		// 	__( 'サブ設定', self::$domain ),
		// 	$allowed_role_id,
		// 	$options_group,
		// 	[$this, 'require_options']
		// );

	}

	public function require_options()
	{
		include(TEMPLATEPATH . '/includes/settings/options.php');
	}

	public function add_settings_field( $id, $title, array $args = [] )
	{
		return add_settings_field($id, __($title),
			[$this, 'setting_callbacks'],
			'site_settings',
			'default',
			$args += ['id' => $id, 'title' => $title, 'label_for' => $id]
		);
	}

	public function setting_callbacks( $args )
	{
		$f = '_callback_'.$args['id'];
		if( method_exists($this, $f) ) {
			$this->$f($args['id'], $args);
		}
		register_setting('site_settings', $args['id']);
	}

	public function register_settings()
	{
		$this->add_settings_field( 'blogname',			'Site Title' );
		$this->add_settings_field( 'blogdescription',	'Tagline' );
		$this->add_settings_field( 'admin_email',		'Email Address' );
		$this->add_settings_field( 'posts_per_page',	'Blog pages show at most' );
		$this->add_settings_field( 'posts_per_rss',		'Syndication feeds show the most recent' );
		$this->add_settings_field( 'rss_use_excerpt',	'For each article in a feed, show' );
		$this->add_settings_field( 'blog_public',		'Search Engine Visibility' );
	}


	/**=====================================================
	 *	CALLBACKS
	 *=====================================================*/
	private function _callback_blogname( $id, $args )
	{
		echo '<input name="'.$id.'" type="text" id="'.$id.'" value="'. esc_attr( get_option($id) ) .'" class="regular-text" />';
	}
	private function _callback_blogdescription( $id, $args )
	{
		echo '<input name="'.$id.'" type="text" id="'.$id.'" aria-describedby="tagline-description" value="'. esc_attr( get_option($id) ) .'" class="regular-text" />',
			 '<p class="description" id="tagline-description">' . __('In a few words, explain what this site is about.') . '</p>';
	}
	private function _callback_admin_email( $id, $args )
	{
		echo '<input name="'.$id.'" type="email" id="'.$id.'" aria-describedby="admin-email-description" value="'. esc_attr( get_option($id) ) .'" class="regular-text ltr" />',
 			 '<p class="description" id="admin-email-description">' . __('This address is used for admin purposes, like new user notification.') . '</p>';
	}
	private function _callback_posts_per_page( $id, $args )
	{
		echo '<input name="'.$id.'" type="number" step="1" min="1" id="'.$id.'" value="'. esc_attr( get_option($id) ) .'" class="small-text" /> ', __('posts');
	}
	private function _callback_posts_per_rss( $id, $args )
	{
		echo '<input name="'.$id.'" type="number" step="1" min="1" id="'.$id.'" value="'. esc_attr( get_option($id) ) .'" class="small-text" /> ', __('items');
	}

	private function _callback_rss_use_excerpt( $id, $args )
	{
		echo '<fieldset>',
			 '<legend class="screen-reader-text"><span>'. __('For each article in a feed, show') .'</span></legend>',
			 '<p>',
				'<label><input name="'.$id.'" type="radio" value="0" '. checked(0, get_option($id), false) .' /> '. __('Full text') .'</label><br />',
				'<label><input name="'.$id.'" type="radio" value="1" '. checked(1, get_option($id), false) .' /> '. __('Summary') .'</label>',
			 '</p>',
			 '</fieldset>';
	}
	private function _callback_blog_public( $id, $args )
	{
		echo '<fieldset>',
			 	'<legend class="screen-reader-text"><span>'. ( has_action( 'blog_privacy_selector' ) ? __( 'Site Visibility' ) : __( 'Search Engine Visibility' ) ) .'</span></legend>';

			if( has_action( 'blog_privacy_selector' ) ) {

				echo '<input id="blog-public" type="radio" name="'.$id.'" value="1" '. checked('1', get_option($id), false) .' />',
					 '<label for="blog-public">'. __('Allow search engines to index this site') .'</label><br />',
					 '<input id="blog-norobots" type="radio" name="'.$id.'" value="0" '. checked('0', get_option($id), false) .' />',
	 				 '<label for="blog-norobots">'. __('Discourage search engines from indexing this site') .'</label>',
					 '<p class="description">',
	 				 	__('Note: Neither of these options blocks access to your site &mdash; it is up to search engines to honor your request.'),
	 				 '</p>';

				do_action( 'blog_privacy_selector' );
			}
			else {

				echo '<label for="blog_public">',
						'<input name="blog_public" type="checkbox" id="blog_public" value="0" '. checked( '0', get_option($id), false ) .' />',
						__( 'Discourage search engines from indexing this site' ),
					 '</label>',
					 '<p class="description">',
					 	__('It is up to search engines to honor this request.'),
					 '</p>';
			}
		echo '</fieldset>';

	}


}
