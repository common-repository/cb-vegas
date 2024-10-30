<?php

/**
 * Fired during plugin activation.
 *
 * @link       https://wordpress.org/plugins/cb-vegas/
 * @since      0.1.0
 * @package    CB_Vegas
 * @subpackage CB_Vegas/includes
 */

/**
 * Fired during plugin activation.
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.1.0
 * @package    CB_Vegas
 * @subpackage CB_Vegas/includes
 * @author     demispatti
 */
class CB_Vegas_Activator {

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
	 * Fired during activation of the plugin.
	 * Adds the capability to edit custom backgrounds to the administrator role.
	 *
	 * @since    0.1.0
	 * @access   static
	 * @return   bool
	 */
	public static function activate() {

		// Gets the administrator role.
		$role = get_role( 'administrator' );

		// If the acting user has admin rights, the capability gets added.
		if ( ! empty( $role ) ) {
			$role->add_cap( self::$capability );

			return true;
		} else {

			return false;
		}
	}
}
