<?php

class WPRD_Roles
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;
	}

	public function setup()
	{
		// 管理者に下位権限を付与
		add_action( 'init', function() {
			// $users = [
			// 	'administrator',
			// 	'editor',
			// 	'author',
			// 	'contributor',
			// 	'subscriber',
			// ];
			$roles = new WP_Roles();
			foreach( ['administrator'] as $role_id ) {
				$role = $roles->get_role($role_id);
				$role->add_cap('editor');
				$role->add_cap('author');
				$role->add_cap('contributor');
				$role->add_cap('subscriber');
			}
		}, 0);
	}

}
