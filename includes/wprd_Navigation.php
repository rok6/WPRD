<?php

class WPRD_Navigation
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;

	}


	public function init( array $menus )
	{
		/*	カスタムメニューの位置を登録	*/
		register_nav_menus ( $menus );

		/*	管理画面のカスタムメニューの位置変更	*/
		if( is_admin() ) {
			$this->reorder_custom_nav_menu();
		}

	}


	//外観サブメニュー内のメニュー項目を外部に出す
	private function reorder_custom_nav_menu()
	{
		add_action('admin_menu', function() {
			remove_submenu_page('themes.php', 'nav-menus.php');
			add_menu_page( __('メニュー', self::$domain), __('メニュー', self::$domain), 'edit_theme_options', 'nav-menus.php', '', null, 5 );
		});
	}

}


/**
 * WPRD_Walker
 **/
class WPRD_Walker
{
	function __construct( $menus, $indent = 0 )
	{
		$this->output = $this->_walker($menus, $indent);
	}


	private function _walker( $menus, $indent )
	{
		$history = [];
		$output = '';
		$prev_depth = '';
		$indent = (int)$indent;

		foreach( $menus as $item ) {
			$depth = $this->get_child_depth($history, $item);
			/**
			 *
			 */
			if( $depth < $prev_depth ) {
				$this->end_lvl($output, $item, ($prev_depth-1)*2, $indent);
			}
			/**
 			 *
 			 */
			else {
				if( $depth > $prev_depth ) {
					$this->start_lvl($output, $item, ($depth-1)*2, $indent);
				}
				$this->start_el($output, $item, $depth*2, $indent);
			}
			/**
 			 *
 			 */
			$this->end_el($output, $item, $depth, $indent);
			$prev_depth = $depth;
			array_unshift($history, $item['ID']);
		}

		return $output;
	}


	protected function start_el(&$output, $item, $depth = 0, $indent = 0)
	{
		$output .= PHP_EOL.$this->indent($depth, $indent).'<li>';
		$output .= $this->anchor($item);
	}

	protected function end_el(&$output, $item, $depth = 0, $indent = 0)
	{
		$output .= '</li>';
	}

	protected function start_lvl(&$output, $item, $depth = 0, $indent = 0)
	{
		$output .= PHP_EOL.$this->indent($depth, $indent).'<li>';
		$output .= PHP_EOL.$this->indent($depth+1, $indent).'<ul>';
	}

	protected function end_lvl(&$output, $item, $depth = 0, $indent = 0)
	{
		$output .= PHP_EOL.$this->indent($depth+1, $indent).'</ul>';
		$output .= PHP_EOL.$this->indent($depth, $indent).'</li>';
	}

	/**
	 * get_child_depth
	 * @since 0.1
	 */
	protected function get_child_depth(&$history, $item)
	{
		if( current($history) !== $item['parent_id'] ) {
			if( in_array($item['parent_id'], $history) ) {
				array_shift($history);
				return $this->get_child_depth($history, $item);
			}
			$history = [];
			return 0;
		}
		else {
			return count($history);
		}
	}


	/**
	 * anchor
	 * @since 0.1
	 */
	protected function anchor($item)
	{
		$title	= esc_html($item['title']);
		$attrs	= sprintf(' href="%1$s"', esc_url($item['url']));
		$attrs .= !empty($item['target'])		? sprintf( ' target="%1$s"',	esc_attr($item['target']) )					: '';
		$attrs .= !empty($item['classes'])		? sprintf( ' class="%1$s"',		esc_attr(implode(' ', $item['classes'])) )	: '';
		$attrs .= !empty($item['attr_title'])	? sprintf( ' title="%1$s"',		esc_attr(implode(' ', $item['classes'])) )	: '';
		$attrs .= !empty($item['xfn'])			? sprintf( ' rel="%1$s"',		esc_attr(implode(' ', $item['classes'])) )	: '';

		return sprintf('<a%2$s>%1$s</a>', $title, $attrs);
	}


	/**
	 * indent
	 * @since 0.1
	 */
	protected function indent($depth = 0, $indent = 0)
	{
		return str_repeat( "\t", $depth + $indent );
	}

}
