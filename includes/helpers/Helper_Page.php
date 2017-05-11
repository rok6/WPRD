<?php

trait Helper_Page
{

	/**
	 * logo
	 *=====================================================*/
	static public function logo( $link = false )
	{
		$title = $link ?
			sprintf('<a href="%1$s">%2$s</a>',
				esc_url( get_home_url() ),
				esc_html( get_bloginfo('name') )
			)
		: get_bloginfo('name');

		return '<h1>'. (string)$title .'</h1>' . PHP_EOL;
	}
}
