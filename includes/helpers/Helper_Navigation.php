<?php

trait Helper_Navigation
{

	/**
	 * breadcrumb
	 *=====================================================*/
	static public function breadcrumb()
	{
		if( is_front_page() ) {
			return;
		}

		$content = 0;

		$list = [
			'<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">',
			[
				'<a href="' . home_url() . '" itemprop="item"><span itemprop="name">ホーム</span></a>',
				'<meta itemprop="position" content="'. ++$content .'" />',
			],
			'</li>'
		];

		if( is_search() ) {
			$list = array_merge($list, [
				'<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">',
				[
					'<span itemprop="name">"'. self::headline() .'" の検索結果</span>',
					'<meta itemprop="position" content="'. ++$content .'" />',
				],
				'</li>'
			]);
		}
		else {

			$queried = get_queried_object();

			if( is_category() || is_tag() || is_tax() || is_single() ) {

				$post_type = ( is_single() ) ?
					$post_type = $queried->post_type
				: get_taxonomy($queried->taxonomy)->object_type[0];

				$anchor = sprintf(
					'<a href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>',
					esc_url(get_post_type_archive_link($post_type)),
					esc_html(get_post_type_object($post_type)->label)
				);
				$list = array_merge($list, [
					'<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">',
					[
						$anchor,
						'<meta itemprop="position" content="'. ++$content .'" />',
					],
					'</li>'
				]);
			}

			$list = array_merge($list, [
				'<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">',
				[
					'<span itemprop="name">'. self::headline() .'</span>',
					'<meta itemprop="position" content="'. ++$content .'" />',
				],
				'</li>'
			]);

		}

		return self::_render([
			'<div class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">',
			[
				'<ol class="container">',
				$list,
				'</ol>',
			],
			'</div>',
		], 1);
	}

	/**
	 * navigation
	 *=====================================================*/
	static public function navgation_menu( array $args = [] )
	{
		$default = [
			'menu'		=> '',
			'location'	=> '',
			'container_class'	=> 'container',
		];
		$args += $default;

		if( ($locations = get_nav_menu_locations()) && isset($locations[$args['location']]) ) {
			$menu = wp_get_nav_menu_object( $locations[$args['location']] );
			$menu_id = $menu->term_id;
		}
		else {
			$menu_id = $args['menu'];
		}

		if( !$navs = wp_get_nav_menu_items( $menu_id ) ) {
			return false;
		}

		$menus = [];

		foreach( $navs as $nav ) {
			$m = [];
			$m['ID']			= $nav->ID;
			$m['title']			= $nav->title;
			$m['attr_title'] 	= $nav->attr_title;
			$m['description']	= $nav->description;
			$m['url']			= $nav->url;
			$m['target']		= $nav->target;
			$m['xfn']			= $nav->xfn;
			$m['classes']		= array_values(array_diff($nav->classes, ['']));
			$m['parent_id']		= (int)$nav->menu_item_parent;
			$m['has_parent']	= !$m['parent_id'] ? false : true;

			$menus[] = $m;
		}

		$walker = new WPRD_Walker( $menus, 2 );

		return self::_render([
			'<ul'. ( ('' !== $class = (string) $args['container_class']) ? ' class="'. esc_attr($class) .'"' : '' ) .'>',
			$walker->output,
			'</ul>',
		], 1);

	}


	/**
	 * pages
	 *=====================================================*/
	static public function post_paginations()
	{
		global $wp_query;

		$range	= 2;
		$max	= $wp_query->max_num_pages;
		$current = get_query_var('paged') ? : 1;

		if( is_front_page() || $max <= 1) {
			return;
		}

		return self::_pager( $max, $current, $range );
	}

	public static function page_links()
	{
		global $page, $numpages, $multipage, $more;

		if( !$multipage ) {
			return;
		}

		$max = $numpages;
		$current = $page;
		$range = 2;

		return self::_pager( $max, $current, $range, 'page-links' );
	}


	private static function _pager( $max, $current, $range = 2, $class = '' )
	{
		$page_list = [];
		$show_items = ($range * 2) + 1;

		/* 一番最初のページへのリンク */
		if( $current > $range + 1 ) {
			$page_list[] = '<li><a href="' . esc_url(self::get_page_link(1)) . '">&laquo;</a></li>' . PHP_EOL;
		}
		/* 一つ前のページへのリンク */
		if( $current > 1 ) {
			$page_list[] = '<li><a href="' . esc_url(self::get_page_link($current - 1)) . '">&lsaquo;</a></li>';
		}

		for( $i = 1; $i <= $max; $i++ ) {
			if( !($i < $current - $range || $i > $current + $range) ) {
				$page_list[] = ( $current === $i ) ? '<li><span class="current">' . $i . '</span></li>'
												   : '<li><a href="' . esc_url(self::get_page_link($i)) . '">' . $i . '</a></li>';
 			}
		}

		/* 一つ後のページへのリンク */
		if( $current < $max ) {
			$page_list[] = '<li><a href="' . esc_url(self::get_page_link($current + 1)) . '">&rsaquo;</a></li>';
		}
		/* 一番最後のページへのリンク */
		if( $current < $max - $range ) {
			$page_list[] = '<li><a href="' . esc_url(self::get_page_link($max)) . '">&raquo;</a></li>' . PHP_EOL;
		}


		if( $class !== '' ) {

			if( is_array($class) ) {
				$class = implode(' ', $class);
			}

			$class = ' ' . $class;
		}

		return self::_render([
			'<!-- .pagination -->',
			'<div class="pagination'. esc_attr($class) .'">',
			[
				'<div class="page-guide">' . $current . 'of' . $max . '</div>',
				'<ul class="pager">',
				$page_list,
				'</ul>',
			],
			'</div>',
			'<!-- //.pagination -->',
		], 1);
	}

}
