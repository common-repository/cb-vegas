<?php

/**
 * The class responsible for maintaining the settings.
 *
 * @link              https://wordpress.org/plugins/cb-vegas/
 * @since             0.1.0
 * @package           CB_Vegas
 * @subpackage        CB_Vegas/admin/menu/includes
 * Author:            demispatti <demis@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class CB_Vegas_Settings extends CB_Vegas_Validation {

	/**
	 * The domain of the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    string $plugin_domain
	 */
	public $plugin_domain;

	/**
	 * The reference to the settings page class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    object $settings_page
	 */
	public $settings_page;

	/**
	 * The reference to the options class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    object $vegas_options
	 */
	public $vegas_options;

	/**
	 * The reference to the settings factory class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    object $settings_factory
	 */
	public $settings_factory;

	// Array of actions that require the options that are currently stored in a transient.
	public static $ajax_related_actions = array(
		'add_slideshow',
		'add_slide',
		/*'sort_slideshows',*/
		'sort_slides',
		'remove_slideshow',
		'remove_slide',
		'duplicate_slide',
		'duplicate_slideshow'
	);

	/**
	 * Kicks off the settings class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return object $this
	 */
	public function __construct( $plugin_domain, $settings_page ) {

		$this->plugin_domain = $plugin_domain;
		$this->settings_page = $settings_page;

		$this->load_dependencies();
		parent::__construct( $this->get_plugin_domain(), $this->get_vegas_options() );
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		$this->vegas_options = new CB_Vegas_Options($this->get_plugin_domain(), $this->get_vegas_settings());
		$this->settings_factory = new CB_Vegas_Settings_Factory( $this->get_plugin_domain(), $this->get_vegas_options(), $this->get_vegas_settings() );
	}

	public function add_hooks() {

		add_action( 'admin_init', array( $this, 'register_settings' ), 10 );
		add_action( 'admin_init', array( $this, 'initialize_settings' ), 20 );
	}

	/**
	 * Returns a reference to the options class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return object $vegas_options
	 */
	public function get_vegas_options() {

		return $this->vegas_options;
	}

	/**
	 * Returns a reference to this class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return object $this
	 */
	public function get_vegas_settings() {

		return $this;
	}

	/**
	 * Registers the settings with WordPress.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function register_settings() {

		register_setting( 'cb_vegas_options', 'cb_vegas_options', array( $this, 'run_validation' ) );
	}

	/**
	 * Registers the section and their settings fields with WordPress,
	 * if there are any stored options.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function initialize_settings() {

		$options = null;

		// Bail on heartbeat.
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'heartbeat' ) {
			return;
		}
		// Bail if a settings page is requested and it is not ours.
		if ( isset( $_REQUEST['settings_page'] ) && $_REQUEST['settings_page'] != 'cb_vegas_settings_page' ) {
			return;
		}
		// Bail if an options page is requested and it is not ours.
		if ( isset( $_REQUEST['options_page'] ) && $_REQUEST['options_page'] != 'cb_vegas_options' ) {
			return;
		}

		$request_action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : false;
		// Checks for actions and assigns the .
		if ( false !== $request_action && in_array( $request_action, self::$ajax_related_actions, true ) ) {

			$options = get_option( 'cb_vegas_options' );
		} else {

			$options = get_option( 'cb_vegas_options' );
		}

		// If there are no options in the database, we seed an initial slideshow containing one slide.
		if ( '' === $options || false === $options || ( is_array( $options ) && ! isset( $options[0] ) ) ) {
			$this->vegas_options->seed_options();
			$options = get_option( 'cb_vegas_options' );
		}

		// Adds a settings field for each retrieved option related to the current slideshow.
		$slideshow_index = $this->settings_page->get_slideshow_index();
		if ( isset( $options[ $slideshow_index ]['slides'] ) && ! empty( $options[ $slideshow_index ]['slides'] ) ) {

			foreach ( (array) $options[ $slideshow_index ]['slides'] as $slide_index => $args ) {

				$this->add_settings_fields( $slideshow_index, $slide_index );
			}
		}
	}

	/**
	 * Adds the settings fields.
	 *
	 * @callback
	 *
	 * @param      $slideshow_index
	 * @param      $slide_index
	 * @param null $ajax
	 *
	 * @return bool
	 */
	public function add_settings_fields( $slideshow_index, $slide_index ) {

		foreach ( (array) $this->vegas_options->the_options as $i => $args ) {

			add_settings_field(
				$args['option_key'] . '_' . $slide_index,
				null,
				array( $this->settings_factory, 'create_settings_fields' ),
				'cb_vegas_settings_page',
				'cb_vegas_settings_section',
				array(
					'slideshow_index' => $slideshow_index,
					'slide_index'     => $slide_index,
					'option_key'      => $args['option_key'],
					'title'           => $args['title'],
					'description'     => $args['description'],
					'field_type'      => $args['field_type'],
					'select_values'   => $args['select_values'],
					'hidden'          => $args['hidden'],
					'has_label'       => $args['has_label'],
					'html_tag'        => $args['html_tag'],
					'class'           => $args['class'],
				)
			);
		}
	}

	/**
	 * Updates the global slideshow / fallback slideshow meta data
	 * if the user defined a new one and sets this option for all other slideshows to "false".
	 *
	 * @access private
	 *
	 * @param $slideshow_index
	 * @param $option ('slideshow_is_global', 'slideshow_is_fallback')
	 *
	 * @return void
	 */
	private function set_slideshow_meta_data( $slideshow_index, $option ) {

		if ( false !== $slideshow_index ) {

			$slideshows = get_option( 'cb_vegas_options' );

			foreach ( (array) $slideshows as $i => $slideshow ) {

				if ( $i != $slideshow_index ) {

					$slideshows[ $i ]['meta'][ $option ] = false;
				} else {

					$slideshows[ $i ]['meta'][ $option ] = true;
				}
			}

			update_option( 'cb_vegas_options', $slideshows );
			set_transient( 'cb_vegas_current_options_transient', $slideshows );
		}
	}

	/**
	 * Runs the validation and sets the indices
	 * for the global slideshow and the fallback slideshow, if the user defined a new one.
	 *
	 * @callback
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array $input
	 *
	 * @return array $output
	 */
	public function run_validation( $input ) {

		$output          = null;
		$slideshow_index = $this->settings_page->get_slideshow_index();

		$fallback = isset( $input['slideshow_is_fallback'] ) ? $input['slideshow_is_fallback'] : false;
		if ( false != $fallback ) {

			$this->set_slideshow_meta_data( $slideshow_index, 'slideshow_is_fallback' );
		}

		$ajax_action = $this->ajax_action();
		$output      = parent::run( $input, $slideshow_index, $ajax_action );

		return $output;
	}

	/**
	 * Retrieve the name of the ajax action if there is one involved.
	 *
	 * @since  0.4.2
	 *
	 * @access private
	 *
	 * @return mixed string $ajax_action / bool false
	 */
	private function ajax_action() {

		if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], self::$ajax_related_actions, true ) ) {

			return $_REQUEST['action'];
		} else {

			return false;
		}
	}

}
