<?php

/**
 * Fired during plugin deactivation.
 *
 * @link       https://wordpress.org/plugins/cb-vegas/
 * @since      0.1.0
 * @package    CB_Vegas
 * @subpackage CB_Vegas/includes
 */

/**
 * Fired during plugin deactivation.
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.1.0
 * @package    CB_Vegas
 * @subpackage CB_Vegas/includes
 * @author     demispatti
 */
class CB_Vegas_Deactivator {

	/**
	 * The variable that holds the name of the capability which is necessary
	 * to interact with this plugin.
	 *
	 * @since    0.1.0
	 * @access   static
	 * @var      string $capability The name of the capability.
	 */
	public static $capability = 'cb_vegas_edit';

	/**
	 * @access static
	 * @since  0.1.0
	 * @return bool
	 */
	public static function deactivate() {

		// Gets the administrator role.
		$role = get_role( 'administrator' );

		// If the acting user has admin rights, the capability gets removed.
		if ( ! empty( $role ) ) {
			$role->remove_cap( self::$capability );

			return true;
		} else {

			return false;
		}
	}
}
