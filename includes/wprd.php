<?php
require_once(dirname(__FILE__) . '/wprd_Settings.php');
require_once(dirname(__FILE__) . '/wprd_Editor.php');
// require_once(dirname(__FILE__) . '/wprd_Navigation.php');
// require_once(dirname(__FILE__) . '/wprd_CustomPost.php');
// require_once(dirname(__FILE__) . '/wprd_CMB2.php');
// require_once(dirname(__FILE__) . '/wprd_Options.php');
// require_once(dirname(__FILE__) . '/wprd_Taxonomy.php');

class WPRD
{
	static private $domain;

	public function __construct( $domain = 'WPRD' )
	{
		self::$domain = $domain;

		$this->settings = new WPRD_Settings( $domain );
		$this->editor = new WPRD_Editor( $domain );
	}

}
