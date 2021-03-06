<?php
namespace WPRD\module\model;
/**
 * WPRD Model
 * @since 0.1
 */
class Model
{
	protected $default = [];

	public function __construct()
	{
		$this->default = [
			// 'post_status'	=> 'publish',
			// 'orderby'		=> 'post_date',
			// 'order'			=> 'desc',
			// 'category'		=> null,
			// 'exclude'		=> null,
			'posts_per_page' => get_option('posts_per_page'),
			'paged'			 => get_query_var('paged'),
		];
	}

	public function get( array $args = [] ) {
		global $post, $wp_query;

		if( is_single() ) {
			return $post;
		}

		if( empty($args['post_type']) ) {
			return $wp_query->posts;
		}

		return get_posts($args += $this->default);
	}
}
