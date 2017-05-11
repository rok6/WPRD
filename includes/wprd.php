<?php
require_once(dirname(__FILE__) . '/wprd_Roles.php');
require_once(dirname(__FILE__) . '/wprd_Settings.php');
require_once(dirname(__FILE__) . '/wprd_Editor.php');
require_once(dirname(__FILE__) . '/wprd_CustomPost.php');
require_once(dirname(__FILE__) . '/wprd_CustomField.php');
require_once(dirname(__FILE__) . '/wprd_Navigation.php');
require_once(dirname(__FILE__) . '/wprd_Options.php');

class WPRD
{
	static private $domain;

	public function __construct( $domain = 'WPRD' )
	{
		self::$domain = $domain;

		$role = new WPRD_Roles( $domain );
		$role->setup();

		$this->settings = new WPRD_Settings( $domain );
		$this->editor = new WPRD_Editor( $domain );
		$this->cpost = new WPRD_CustomPost( $domain );
		$this->cfield = new WPRD_CustomField( $domain );
		$this->nav = new WPRD_Navigation( $domain );
		$this->options = new WPRD_Options( $domain );

	}

}
