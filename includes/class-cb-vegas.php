<?php

/**
 * The file that defines the core plugin class and bootstraps
 * the public and the admin facing parts of the plugin.
 *
 * @link       https://wordpress.org/plugins/cb-vegas/
 * @since      0.1.0
 * @package    CB_Vegas
 * @subpackage CB_Vegas/includes
 */

/**
 * The core plugin class.
 *
 * @since      0.1.0
 * @package    CB_Vegas
 * @subpackage CB_Vegas/includes
 * @author     demispatti
 */
class CB_Vegas {

	/**
	 * The name of the plugin.
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    string $plugin_name
	 */
	protected $plugin_name;

	/**
	 * The domain of the plugin.
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    string $plugin_domain
	 */
	protected $plugin_domain;

	/**
	 * The version of the plugin.
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    string $plugin_version
	 */
	protected $plugin_version;

	/**
	 * Kicks of the plugin's main file.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->plugin_name    = 'cb-vegas';
		$this->plugin_domain  = 'cb-vegas';
		$this->plugin_version = '0.3.6';
	}

	/**
	 * Sets the plugin domain.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function set_locale() {

		$plugin_i18n = new CB_Vegas_i18n();
		$plugin_i18n->set_plugin_domain( $this->get_plugin_domain() );

		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );
	}

	/**
	 * Registers all admin hooks with WordPress.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function define_admin() {

		$Admin = new CB_Vegas_Admin( $this->get_plugin_name(), $this->get_plugin_domain(), $this->get_plugin_version() );
		$Admin->add_hooks();
	}

	/**
	 * Registers all public hooks with WordPress.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function define_public() {

		$Public = new CB_Vegas_Public( $this->get_plugin_name(), $this->get_plugin_domain(), $this->get_plugin_version() );
		$Public->add_hooks();
	}

	/**
	 * Runs the loader.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function run() {

		$this->set_locale();
		$this->define_admin();
		$this->define_public();
	}

	/**
	 * Returns the name of the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return string $plugin_name
	 */
	public function get_plugin_name() {

		return $this->plugin_name;
	}

	/**
	 * Returns the domain of the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return string $plugin_domain
	 */
	public function get_plugin_domain() {

		return $this->plugin_domain;
	}

	/**
	 * Returns the version of the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return string $plugin_version
	 */
	public function get_plugin_version() {

		return $this->plugin_version;
	}

}
