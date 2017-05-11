<?php
require_once(__DIR__.'/helpers/Helper_Common.php');
require_once(__DIR__.'/helpers/Helper_Post.php');
require_once(__DIR__.'/helpers/Helper_Page.php');
require_once(__DIR__.'/helpers/Helper_Navigation.php');

class Helper
{
	use Helper_Common,
		Helper_Post,
		Helper_Page,
		Helper_Navigation;


	/**
	 * headline
	 * 現在表示しているページのタイトル表示
	 *=====================================================*/
	static public function headline()
	{
		$label = '';

		if( is_front_page() ) {
			$label = __('What\'s new');
		}

		else if( is_404() ) {
			$label = '404';
		}

		else if( is_search() ) {
			$label = get_search_query();
		}

		else if( is_home() || is_single() ){
			$label = get_queried_object()->post_title;
		}

		else if( is_category() || is_tag() || is_tax() ) {
			$label = get_queried_object()->slug;
		}

		else {
			$label = get_queried_object()->label;
		}

		return esc_html($label);
	}


	/**
	 * robots
	 *=====================================================*/
	static public function robots()
	{
		//全体の公開設定が非公開の時はスルー
		if( !$public = get_option('blog_public') ) {
			return;
		}

		//カスタムフィールドの値を取得
		if( isset(get_post_custom()['meta_robots'][0]) ) {
			$public = get_post_custom()['meta_robots'][0];
		}

		//アーカイブページは共通して noindex 設定にする
		if( is_archive() ) {
			$public = false;
		}

		//取得した値が true の場合はスルー
		if( !!$public ) {
			return;
		}

		return '<meta name="robots" content="noindex, follow" />' . PHP_EOL;
	}

	/**
	 * description
	 *=====================================================*/
	static public function description()
	{
		$desc = get_post_custom();
		$desc = ( isset($desc['meta_description'][0]) && $desc['meta_description'][0] !== '' ) ?
			$desc['meta_description'][0]
		: ( is_front_page() ) ? get_bloginfo('description') : '';

		if( $desc === '' ) {
			return;
		}

		return sprintf(
			'<meta name="description" content="%1$s" />' . PHP_EOL,
			esc_html( $desc )
		);
	}

}
