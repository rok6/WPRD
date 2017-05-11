<?php

trait Helper_Common
{

	/**
	 * GET Page link
	 *=====================================================*/
	private static function get_page_link( $page = 1 )
	{
		$page = (int)$page;

		if( !is_single() ) {
			$page_link = get_pagenum_link($page);
		}
		else {
			$page_link = get_the_permalink();
			if( $page > 1 ) {
				$page_link .= $page . '/';
			}
		}

		return $page_link;
	}


	/**
	 * Helper render
	 *=====================================================*/
	public static function _render( array $array_elements = [], $indent = 0 )
	{
		echo PHP_EOL;
		foreach( $array_elements as $element ) {
			if( is_array($element) ) {
				self::render($element, $indent + 1);
				continue;
			}
			else {
				echo str_repeat( "\t", $indent ) . $element . PHP_EOL;
			}
		}
		echo PHP_EOL;
	}

}
