<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/cb-vegas/
 * @since      0.1.0
 * @package    CB_Vegas
 * @subpackage CB_Vegas/admin
 */

/**
 * The admin-specific functionality of the plugin.
 * Initiates all admin-related modules such as
 * help tab,
 * wp support,
 * meta box,
 * settings page
 * and the settings.
 *
 * @package    CB_Vegas
 * @subpackage CB_Vegas/admin
 * @author     demispatti
 */
class CB_Vegas_Admin {

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
	 * @var    string $plugin_name
	 */
	protected $plugin_version;

	/**
	 * The reference to the settings page object.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    object $settings_page
	 */
	public $settings_page;

	/**
	 * The name of the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    object $settings
	 */
	public $settings;

	/**
	 * Initializes the admin part.
	 *
	 * @since 0.1.0
	 *
	 * @param string $plugin_name
	 * @param string $plugin_domain
	 * @param string $plugin_version
	 * @param object $loader
	 */
	public function __construct( $plugin_name, $plugin_domain, $plugin_version ) {

		$this->plugin_name    = $plugin_name;
		$this->plugin_domain  = $plugin_domain;
		$this->plugin_version = $plugin_version;

		$this->define_help_tab();
		$this->define_wp_support();
		$this->define_meta_box();
		$this->define_settings_page();
		$this->define_settings();
	}


	public function add_hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
	}

	/**
	 * Registers the style with WordPress.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function enqueue_styles() {

		wp_enqueue_style(
			'cb-vegas-admin-css',
			plugin_dir_url( __FILE__ ) . 'css/admin.css',
			array(),
			$this->plugin_version,
			'all'
		);
	}

	/**
	 * Registers the scripts with WordPress.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {

		if ( ! wp_script_is( 'fancySelect.js' ) ) {
			// Fancy Select
			wp_enqueue_script(
				'cb-vegas-inc-fancy-select-js',
				plugin_dir_url( __FILE__ ) . '../vendor/fancy-select/fancySelect.js',
				array( 'jquery' ),
				$this->plugin_version,
				false
			);
		}

		wp_enqueue_script(
			'cb-vegas-meta-box-js',
			plugin_dir_url( __FILE__ ) . 'js/meta-box.js',
			array( 'jquery', 'cb-vegas-inc-fancy-select-js' ),
			$this->plugin_version,
			false
		);
	}

	/**
	 * Creates a reference to the "help tab class" and hooks the initial function with WordPress.
	 *
	 * @see    admin/includes/class-cb-vegas-help-tab.php
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function define_help_tab() {

		$Help_Tab = new CB_Vegas_Help_Tab( $this->get_plugin_domain() );
		$Help_Tab->add_hooks();
	}

	/**
	 * Registers the action to execute on the object regarding the post type support.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @return   void
	 */
	private function define_wp_support() {

		$post_type_support = new CB_Vegas_WP_Support();
		$post_type_support->add_hooks();
	}

	/**
	 * Registers the meta box related features to execute them on the just created object.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @return   void
	 */
	private function define_meta_box() {

		$Meta_Box = new CB_Vegas_Meta_Box( $this->get_plugin_domain() );
		$Meta_Box->add_hooks();
	}

	/**
	 * Registers all necessary hooks and instanciates all necessary objects the settings page is made of.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function define_settings_page() {

		$this->settings_page = new CB_Vegas_Settings_Page( $this->get_plugin_name(), $this->get_plugin_domain(), $this->get_plugin_domain() );
		$this->settings_page->add_hooks();
	}

	/**
	 * Defines the hooks that initiate the settings api.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function define_settings() {

		$this->settings = new CB_Vegas_Settings( $this->get_plugin_domain(), $this->get_settings_page() );
		$this->settings->add_hooks();
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

	/**
	 * Returns the reference to the class responsible for the settings page.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return object $settings_page
	 */
	public function get_settings_page() {

		return $this->settings_page;
	}

	/**
	 * Adds support, rating, and donation links to the plugin row meta on the plugins admin screen.
	 *
	 * @hooked_action
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return   array
	 *
	 * @param    array $meta
	 * @param    string $file
	 */
	public function plugin_row_meta( $meta, $file ) {

		$plugin = plugin_basename( 'cb-vegas/cb-vegas.php' );

		if ( $file == $plugin ) {
			$meta[] = '<a href="https://wordpress.org/support/plugin/cb-vegas" target="_blank">' . __( 'Plugin Support', $this->plugin_domain ) . '</a>';
			$meta[] = '<a href="https://wordpress.org/plugins/cb-vegas/" target="_blank">' . __( 'Rate Plugin', $this->plugin_domain ) . '</a>';
			$meta[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XLMMS7C62S76Q" target="_blank">' . __( 'Donate', $this->plugin_domain ) . '</a>';
		}

		return $meta;
	}

}
