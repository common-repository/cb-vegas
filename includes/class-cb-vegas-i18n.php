<?php

/**
 * Define the internationalization functionality
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wordpress.org/plugins/cb-vegas/
 * @since      0.1.0
 * @package    CB_Vegas
 * @subpackage CB_Vegas/includes
 */

/**
 * Define the internationalization functionality.
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.1.0
 * @package    CB_Vegas
 * @subpackage CB_Vegas/includes
 * @author     demispatti
 */
class CB_Vegas_i18n {

	/**
	 * The domain of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string $plugin_domain
	 */
	private $plugin_domain;

	/**
	 * Loads the texxt domain.
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return   void
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->plugin_domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

	/**
	 * Assigns the plugin domain.
	 *
	 * @since    0.1.0
	 * @access   public
	 *
	 * @param    string $plugin_domain
	 *
	 * @return   void
	 */
	public function set_plugin_domain( $plugin_domain ) {

		$this->plugin_domain = $plugin_domain;
	}
}
