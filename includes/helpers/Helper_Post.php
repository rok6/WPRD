<?php

trait Helper_Post
{
	
	/**
	 * title
	 *=====================================================*/
	static public function title( $id, $level = 2, $link = false )
	{
		$title = $link ?
			sprintf('<a href="%1$s">%2$s</a>',
				esc_url( get_permalink($id) ),
				esc_html( get_the_title($id) )
			)
		: esc_html( get_the_title($id) );

		return sprintf('<h%1$d>%2$s</h%1$d>' . PHP_EOL,
			(int)$level,
			(string)$title
		);
	}

	/**
	 * thumbnail
	 *=====================================================*/
	static public function thumbnail( $id, array $args = [] )
	{
		$args = $args += [
			'alt' => '',
			'title' => '',
		];
		$args['alt'] = trim( strip_tags( $args['alt'] ) );
		$args['title'] = trim( strip_tags( $args['title'] ) );
		return get_the_post_thumbnail($id, 'medium', $args);
	}

	/**
	 * author
	 *=====================================================*/
	static public function author( $userid, $field = 'nickname' )
	{
		return sprintf('<span>%1$s</span>' . PHP_EOL,
			esc_html( get_the_author_meta($field, $userid) )
		);
	}

	/**
	 * datetime
	 *=====================================================*/
	static public function datetime( $id )
	{
		$entry_date			= '';
		$format				= get_option('date_format');
		$published			= get_the_date($format, $id);
		$published_datetime = get_the_date(DATE_W3C, $id);
		$updated			= get_post_modified_time($format, false, $id);
		$updated_datetime	= get_post_modified_time(DATE_W3C, false, $id);

		if( is_single() ) {
			$entry_date .= sprintf(
				'<span class="elapsed-time">%1$s前</span>',
				human_time_diff( get_post_modified_time('U', false, $id), date_i18n('U') )
			);
		}

		if( $published !== $updated ) {
			$entry_date .= sprintf(
				'<time datetime="%1$s" class="updated">%2$s</time>',
				esc_attr($updated_datetime),
				esc_html($updated)
			);
		}

		$entry_date .= sprintf(
			'<time datetime="%1$s" class="published">%2$s</time>',
			esc_attr($published_datetime),
			esc_html($published)
		);

		return $entry_date . PHP_EOL;
	}

	/**
	 * content
	 *=====================================================*/
	static public function content()
	{
		the_post();
		return sprintf( PHP_EOL . '<!-- Content -->' . PHP_EOL . '%1$s' . '<!-- //Content -->' . PHP_EOL . PHP_EOL,
			str_replace( ']]>', ']]&gt;', apply_filters('the_content', get_the_content( '続きを表示する' )) )
		);
	}

	/**
	 * tas
	 *=====================================================*/
	static public function taxonomies( $id )
	{
		$taxonomy	= get_object_taxonomies(get_post_type($id));
		$taxonomies	= wp_get_object_terms($id, $taxonomy);

		$terms = [];

		if( !empty($taxonomies) && !is_wp_error($taxonomies) ) {

			$history = [];
			$terms_hierarchical = [];

			foreach( $taxonomies as $term ) {

				$tax = get_taxonomy($term->taxonomy);
				$name = $tax->name;
				$label = $item = null;

				$_ith = is_taxonomy_hierarchical($term->taxonomy);

				if( !in_array($name, $history) ) {
					$history[] = $name;
					$label = sprintf('<span class="name%2$s">%1$s</span>',
						esc_html( $tax->label ),
						esc_attr(' ' . $name)
					);
				}
				$item = sprintf('<a href="%2$s" class="term">%1$s</a>',
					esc_html( $term->name ),
					esc_url( get_tag_link($term->term_id) )
				);

				if( $_ith ) {
					if( !empty($label) ) {
						$terms_hierarchical[] = $label;
					}
					$terms_hierarchical[] = $item;
				}
				else {
					if( !empty($label) ) {
						$terms[] = $label;
					}
					$terms[] = $item;
				}

			}
			$terms = array_merge($terms_hierarchical, $terms);
		}
		else {
			$terms[] = '<span class="no-taxonomy"></span>';
		}

		return self::_render($terms, 6);
	}
}
