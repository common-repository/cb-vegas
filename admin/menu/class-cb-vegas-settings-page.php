<?php

/**
 * The class that defines the settings page.
 *
 * @link              https://wordpress.org/plugins/cb-vegas/
 * @since             0.1.0
 * @package           CB_Vegas
 * @subpackage        CB_Vegas/admin/menu
 * Author:            demispatti
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class CB_Vegas_Settings_Page {

	/**
	 * The name of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var string $plugin_name
	 */
	protected $plugin_name;

	/**
	 * The domain of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var string $plugin_domain
	 */
	protected $plugin_domain;

	/**
	 * The version of the plugin.
	 * @since    0.1.0
	 * @access   protected
	 * @var string $plugin_version
	 */
	protected $plugin_version;

	/**
	 * The reference to the settings class.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var object $settings
	 */
	public $settings;

	/**
	 * The reference to the options class.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var object $vegas_options
	 */
	public $vegas_options;

	/**
	 * The reference to the class that generates all the settings.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var object $settings_factory
	 */
	public $settings_factory;

	/**
	 * The reference to the class that's responsible for all ajax actions.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var object $ajax
	 */
	public $ajax;

	/**
	 * Kicks off the settings page.
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

		$this->load_dependencies();
		$this->add_ajax_hooks();
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		$this->settings         = new CB_Vegas_Settings( $this->get_plugin_domain(), $this->get_settings_page() );
		$this->vegas_options    = new CB_Vegas_Options( $this->get_plugin_domain(), $this->get_settings() );
		$this->settings_factory = new CB_Vegas_Settings_Factory( $this->get_plugin_domain(), $this->get_vegas_options(), $this->get_settings() );
		$this->ajax             = new CB_Vegas_Ajax( $this->get_plugin_domain(), $this->get_vegas_options(), $this->get_settings(), $this->get_settings_factory() );
	}

	/**
	 * Adds the settings page and initiates its localisation.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function add_hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'define_localisations' ) );
		add_action( 'admin_notices', array( $this, 'admin_notice_display' ) );
	}

	/**
	 * Hooks all ajax functions with WordPress.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function add_ajax_hooks() {

		add_action( 'wp_ajax_add_slide', array( $this, 'add_slide' ) );
		add_action( 'wp_ajax_duplicate_slide', array( $this, 'duplicate_slide' ) );
		add_action( 'wp_ajax_sort_slides', array( $this, 'sort_slides' ) );
		add_action( 'wp_ajax_remove_slide', array( $this, 'remove_slide' ) );

		add_action( 'wp_ajax_add_slideshow', array( $this, 'add_slideshow' ) );
		add_action( 'wp_ajax_duplicate_slideshow', array( $this, 'duplicate_slideshow' ) );
		//add_action( 'wp_ajax_sort_slideshows', array( $this, 'sort_slideshows' ) );
		add_action( 'wp_ajax_remove_slideshow', array( $this, 'remove_slideshow' ) );
	}

	/**
	 * Registers the stylesheet for the settings page.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function enqueue_styles( $hook_suffix ) {

		// Menu
		if ( isset( $hook_suffix ) && $hook_suffix === 'settings_page_cb_vegas_settings_page' ) {

			wp_enqueue_style( 'thickbox' );

			wp_enqueue_style( 'wp-color-picker' );

			if ( ! wp_style_is( 'font-awesome.css', 'enqueued' ) ) {

				wp_enqueue_style(
					'cb-vegas-inc-font-awesome',
					'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
					array(),
					$this->plugin_version,
					false
				);
			}

			wp_enqueue_style( 'cb-vegas-inc-jquery-ui-css',
				'http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
				false,
				'all',
				false
			);

			wp_enqueue_style(
				'cb-vegas-inc-alertify-min-css',
				plugin_dir_url( __FILE__ ) . '../../vendor/alertify/css/alertify.min.css',
				array(),
				'all',
				'all'
			);

			wp_enqueue_style(
				'cb-vegas-inc-alertify-theme-semantic-min-css',
				plugin_dir_url( __FILE__ ) . '../../vendor/alertify/css/themes/semantic.min.css',
				array(),
				'all',
				'all'
			);

			wp_enqueue_style(
				'cb-vegas-menu-css',
				plugin_dir_url( __FILE__ ) . 'css/menu.css',
				array(),
				'all',
				'all'
			);
		}
	}

	/**
	 * Register the scripts for the settings menu.
	 *
	 * @since    0.1.0
	 *
	 * @param string $hook_suffix
	 *
	 * @return void
	 */
	public function enqueue_scripts( $hook_suffix ) {

		// We load this stuff only on the settings page.
		if ( null !== $hook_suffix && $hook_suffix === 'settings_page_cb_vegas_settings_page' ) {

			$jquery_ui_libraries = array(
				'jquery-ui-core',
				'jquery-ui-widget',
				'jquery-ui-mouse',
				'jquery-ui-position',
				'jquery-ui-draggable',
				'jquery-ui-droppable',
				'jquery-ui-sortable',
				'jquery-ui-accordion',
				'jquery-ui-tooltip',
				'jquery-effects-core',
				'jquery-effects-bounce',
			);
			foreach ( $jquery_ui_libraries as $jquery_ui_library ) {
				wp_enqueue_script( $jquery_ui_library );
			}

			wp_enqueue_media();

			wp_enqueue_script( 'wp-color-picker' );

			wp_enqueue_script(
				'cb-vegas-inc-alertify-min-js',
				plugin_dir_url( __FILE__ ) . '../../vendor/alertify/alertify.min.js',
				array( 'jquery' ),
				'all',
				false
			);

			$dependencies = array_merge(
				array(
					'jquery',
					'wp-color-picker',
					'cb-vegas-inc-fancy-select-js',
					'cb-vegas-inc-alertify-min-js'
				),
				$jquery_ui_libraries
			);

			wp_enqueue_script(
				'cb-vegas-ajax-js',
				plugin_dir_url( __FILE__ ) . 'js/ajax.js',
				$dependencies,
				$this->plugin_version,
				false
			);

			wp_enqueue_script(
				'cb-vegas-main-js',
				plugin_dir_url( __FILE__ ) . 'js/main.js',
				$dependencies,
				$this->plugin_version,
				false
			);
		}
	}

	/**
	 * Registers the settings page with WordPress.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function add_settings_page() {

		$this->maybe_wp_redirect();

		add_options_page( 'cbVegas', 'cbVegas', 'manage_options', 'cb_vegas_settings_page', array( $this, 'display' ) );
	}

	public function maybe_wp_redirect() {

		$options      = get_option( 'cb_vegas_options' );
		$tab          = isset( $_REQUEST['tab'] ) ? (int) $_REQUEST['tab'] : false;
		$site_url     = site_url();
		$request_page = "cb_vegas_settings_page";
		$string       = '/wp-admin/options-general.php?page=' . $request_page . '&tab=';

		$redirects = array();
		if ( false != $options ) {

			foreach ( (array) $options as $i => $option ) {
				$redirects[] = $i;
			}
		}

		if ( ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === $request_page && ! in_array( $tab, $redirects ) ) ||
		     ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === $request_page && ! isset( $_REQUEST['tab'] ) )
		) {

			$slug = 0;
			$url  = $site_url . $string . $slug;
			wp_redirect( $url );
		}
	}

	/**
	 * Runs the instance of the class that's responsible for localizing the settings page texts served via javascript.
	 *
	 * @hooked_action
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  string $hook_suffix
	 *
	 * @return void
	 */
	public function define_localisations( $hook_suffix ) {

		if ( null !== $hook_suffix && $hook_suffix === 'settings_page_cb_vegas_settings_page' ) {

			$Localisations = new CB_Vegas_Localisations( $this->get_plugin_name(), $this->get_plugin_domain() );
			$Localisations->add_hooks();
		}
	}

	/**
	 * Displays the validation errors in the admin notice area.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @access public
	 * @uses   WP_Error/get_error_message()
	 * @return echo
	 */
	public function admin_notice_display() {

		// If there are any error-related transients
		if ( false !== get_transient( 'cb_vegas_validation_transient' ) ) {

			// Retrieves the error-array and the corresponding meta data
			$errors = get_transient( 'cb_vegas_validation_transient' );

			foreach ( $errors as $slide => $error ) {

				foreach ( $error as $key => $args ) {
					// Extracts the error message and echoes it inside the admin notice area.
					if ( is_wp_error( $error[ $key ]['message'] ) ) {

						$error_message = $error[ $key ]['message']->get_error_message();
						$slide_number  = $slide + 1;// Converts the index number to slide number.

						// The actual error message.
						$html = '<div class="error notice is-dismissible"' . $error[ $key ]['notice_level'] . '">';
						$html .= '<p class="cb-vegas-validation-error" >' . $error[ $key ]['name'] . ', Slide Nr. ' . $slide_number . '</p>';
						$html .= '<p>' . $error_message . '</p>';
						$html .= '</div>';

						echo $html;
					}
				}
			}
			// Clean up
			delete_transient( 'cb_vegas_validation_transient' );
		}
	}

	/**
	 * Renders the page for the menu.
	 *
	 * @callback
	 *
	 * @since  0.1.0
	 * @access public
	 * @return mixed
	 */
	public function display() {

		$slideshow_index = $this->get_slideshow_index();
		?>

		<div class="wrap">

			<!-- Error notice fix - we do not want error notices to break our settings page display ;-)-->
			<h2></h2>

			<div class="cb-vegas-settings-heading title-section    cb-vegas-menu-container">
				<h2 class="" style="float: left;"><?php echo __( 'Vegas Background Slideshow', $this->plugin_domain ); ?></h2>

				<!-- Plugin Navigation -->
				<div class="cb-vegas-plugin-navigation">
					<?php
					////$this->settings->create_plugin_settings_button();
					$this->settings_factory->create_add_slideshow_button();
					// Show on the "add new" page
					if ( false == $this->vegas_options->is_slideshow_tab() ) {

						//$this->settings_factory->create_go_to_slideshow_settings_button();
					}
					?>
				</div>
			</div>
			<?php
			// Hide on the "add new" page
			if ( true == $this->vegas_options->is_slideshow_tab() ) {

				?>
				<!-- Slideshow Heading -->
				<div class="cb-vegas-settings-heading cb-vegas-post-types-heading" style="margin-bottom: 4px;"><?php echo __( 'Slideshows', $this->plugin_domain ) ?></div>
				<!-- SLideshow Navigation -->
				<?php $cb_vegas_sort_tabs_nonce = wp_create_nonce( 'cb_vegas_sort_tabs_nonce' ); ?>
				<div class="cb-vegas-slideshow-navigation cb-vegas-navigation-tabs-container    cb-vegas-slideshow-navigation" data-nonce="<?php echo $cb_vegas_sort_tabs_nonce ?>" data-active-tab-name="<?php /*$active_tab_name*/ ?>" data-active-tab-index="<?php /*$post_type_index*/ ?>">
					<?php
					$this->settings_factory->create_navigation( $slideshow_index /*$active_tab_name, $post_type_index*/ );
					?>
				</div>
				<?php
			}
			?>

			<!-- Form -->
			<form id="cb-vegas-settings-form" method="POST" action="options.php" data-slideshow-index="<?php echo $slideshow_index ?>">

				<div id="settings">
					<?php
					$this->settings_factory->create_slideshow_index_hidden_field( $slideshow_index );
					$this->settings_factory->create_slideshow_uniqid_hidden_field( $slideshow_index );
					$this->settings_factory->create_slideshow_name_setting( $slideshow_index );
					?>
					<div class="cb-vegas-settings-slider">
						<?php
						//$this->settings_factory->create_slideshow_is_global_setting($slideshow_index);
						$this->settings_factory->create_slideshow_is_fallback_setting( $slideshow_index );
						$this->settings_factory->create_slideshow_is_autoplay_setting( $slideshow_index );
						$this->settings_factory->create_slideshow_is_overlay_setting( $slideshow_index );
						$this->settings_factory->create_slideshow_is_shuffle_setting( $slideshow_index );
						$this->settings_factory->create_slideshow_is_timer_setting( $slideshow_index );
						?>
					</div>
				</div>
				<?php $cb_vegas_sort_slides_nonce = wp_create_nonce( 'cb_vegas_sort_slides_nonce' ); ?>
				<div id="cb-vegas-slides-container" data-nonce="<?php echo $cb_vegas_sort_slides_nonce ?>">
					<?php
					settings_fields( 'cb_vegas_options' );
					do_settings_sections( 'cb_vegas_settings_section' );
					do_settings_fields( 'cb_vegas_settings_page', 'cb_vegas_settings_section' );
					?>
				</div>

				<div id="controls">
					<?php
					$this->settings_factory->create_add_slide_button();
					$this->settings_factory->create_duplicate_slideshow_button();
					$this->settings_factory->create_remove_slideshow_button();
					$this->settings_factory->create_submit_button();
					?>
				</div>
			</form>

		</div>

		<?php
	}

	/**
	 * Returns the slideshow index.
	 *
	 * @callback
	 *
	 * @since  0.1.0
	 * @access public
	 * @return mixed
	 */
	public function get_slideshow_index() {

		$current_slideshow_index = get_transient( 'cb_vegas_current_slideshow_index_transient' );
		$slideshow_index         = null;

		$allowed_actions = array(
			'add_slideshow',
			'add_slide',
			'sort_slideshows',
			'sort_slides',
			'remove_slideshow',
			'remove_slide'
		);
		$request_action  = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : false;

		// If the user reordered the slideshows using ajax, then this is the current slideshow index.
		if ( isset( $_REQUEST['settings-updated'] ) && $_REQUEST['settings-updated'] === 'true' ) {

			$slideshow_index = get_transient( 'cb_vegas_current_slideshow_index_transient' );
		} elseif ( isset( $_REQUEST['current_slideshow_index'] ) && $_REQUEST['current_slideshow_index'] !== '' && $_REQUEST['current_slideshow_index'] !== false ) {

			$slideshow_index = $_REQUEST['current_slideshow_index'];
			// If this is true, we use this slideshow index.
		} else if ( isset( $_REQUEST['cb_vegas_options']['slideshow_index'] ) && $_REQUEST['cb_vegas_options']['slideshow_index'] !== '' && $_REQUEST['cb_vegas_options']['slideshow_index'] !== false ) {

			$slideshow_index = $_REQUEST['cb_vegas_options']['slideshow_index'];
			// If this is true, we use this slideshow index.
		} else if ( isset( $_REQUEST['action'] ) && in_array( $request_action, $allowed_actions ) ) {

			$slideshow_index = get_transient( 'cb_vegas_current_slideshow_index_transient' );
			//$slideshow_index = $_REQUEST['cb_vegas_options']['slideshow_index'];
			set_transient( 'cb_vegas_current_slideshow_index_transient', $slideshow_index, 1800 );
			// If the user calls a regular settings page tab, use that.
		} else if ( isset( $_REQUEST['tab'] ) && $_REQUEST['tab'] !== 'undefined' ) {

			$slideshow_index = $_REQUEST['tab'];
			// Else if there is a current value for the slideshow index, use this one and touch wood.
		} else if ( false !== $current_slideshow_index && '' !== $current_slideshow_index && null !== $current_slideshow_index ) {

			$slideshow_index = $current_slideshow_index;
			// Else we can assume a regular call to the plugin's settings page and we assign an index number of zero. Don't forget to touch wood.
		} else {

			$slideshow_index = 0;
		}
		// Necessary after settings got updated.
		set_transient( 'cb_vegas_current_slideshow_index_transient', $slideshow_index, 1800 );

		return (int) $slideshow_index;
	}

	/**
	 * Returns the reference to the settings class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return object $settings
	 */
	public function get_settings() {

		return $this->settings;
	}

	/**
	 * Returns the reference to the settings page class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return object $settings_page
	 */
	public function get_settings_page() {

		return $this;
	}

	/**
	 * Returns the reference to the settings factory class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return object $settings_factory
	 */
	public function get_settings_factory() {

		return $this->settings_factory;
	}

	/**
	 * Returns the reference to the options class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return object $vegas_options
	 */
	public function get_vegas_options() {

		return $this->vegas_options;
	}

	/**
	 * Returns the plugin name.
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

	/** ------------------------------------------------------------------------ *
	 ** The functions that handle ajax requests.
	 ** ------------------------------------------------------------------------ */
	/**
	 * Adds a slideshow.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function add_slideshow() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], "cb_vegas_add_slideshow_nonce" ) ) {
			exit( __( "One more try and your browser will burst into flames ;-)", $this->plugin_domain ) );
		}

		// Get the new slideshow index.
		$new_slideshow_index = count( get_option( 'cb_vegas_options' ) );

		// Add slide.
		$html = $this->ajax->add_the_slideshow( $new_slideshow_index );

		if ( false !== $html ) {

			$response = array(
				'success' => true,
				'message' => __( 'Slideshow added.', $this->plugin_domain ),
				'html'    => $html,
			);

			wp_send_json_success( $response );
		} else {

			$response = array(
				'success' => false,
				'message' => __( 'Nope. Couldn\'t add a new slideshow :-(', $this->plugin_domain ) . '</br>' . __( 'Please save your work and try again.', $this->plugin_domain ),
			);

			wp_send_json_error( $response );
		}
	}

	/**
	 * Adds a new slide to the slideshow.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function add_slide() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], "cb_vegas_add_slide_nonce" ) ) {
			exit( __( "One more try and your browser will burst into flames ;-)", $this->plugin_domain ) );
		}

		$slideshow_index = isset( $_REQUEST['slideshow_index'] ) ? $_REQUEST['slideshow_index'] : false;
		$slide_index     = isset( $_REQUEST['slide_index'] ) ? $_REQUEST['slide_index'] : false;
		$html            = $this->ajax->add_the_slide( $slideshow_index, $slide_index );

		if ( false !== $html ) {

			$response = array(
				'success' => true,
				'message' => __( 'Slide added.', $this->plugin_domain ),
				'html'    => $html,
			);

			//$this->admin_notice_display();

			wp_send_json_success( $response );
		} else {

			$response = array(
				'success' => false,
				'message' => __( 'Nope. Couldn\'t add a slide :-(', $this->plugin_domain ) . '</br>' . __( 'Please save your work and try again.', $this->plugin_domain ),
			);

			wp_send_json_error( $response );
		}
	}

	/**
	 * Duplicates a slideshow.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function duplicate_slideshow() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], "cb_vegas_duplicate_slideshow_nonce" ) ) {
			exit( __( "One more try and your browser will burst into flames ;-)", $this->plugin_domain ) );
		}

		$slideshow_index = isset( $_REQUEST['slideshow_index'] ) ? $_REQUEST['slideshow_index'] : false;
		$html            = $this->ajax->duplicate_the_slideshow( $slideshow_index );

		if ( false !== $html ) {

			$tab = count( get_option( 'cb_vegas_options' ) );

			$response = array(
				'success' => true,
				'message' => __( 'Slideshow duplicated.', $this->plugin_domain ) . '</br>' . __( 'You will be redirected now.', $this->plugin_domain ),
				'html'    => $html,
				'tab'     => $tab - 1
			);

			wp_send_json_success( $response );
		} else {

			$response = array(
				'success' => false,
				'message' => __( 'Nope. Couldn\'t duplicate the slideshow :-(', $this->plugin_domain ) . '</br>' . __( 'Please save your work and try again.', $this->plugin_domain ),
			);

			wp_send_json_error( $response );
		}
	}

	/**
	 * Duplicates a slide.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function duplicate_slide() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'cb_vegas_duplicate_slide_nonce' ) ) {
			exit( __( 'One more try and your browser will burst into flames ;-)', $this->plugin_domain ) );
		}

		$slideshow_index = isset( $_REQUEST['slideshow_index'] ) ? $_REQUEST['slideshow_index'] : false;
		$slide_index     = isset( $_REQUEST['slide_index'] ) ? $_REQUEST['slide_index'] : false;
		$html            = $this->ajax->duplicate_the_slide( $slideshow_index, $slide_index );

		if ( false != $html ) {

			$response = array(
				'success' => true,
				'message' => __( 'Slide duplicated.', $this->plugin_domain ),
				'html'    => $html,
			);

			wp_send_json_success( $response );
		} else {

			$response = array(
				'success' => false,
				'message' => __( 'Nope. Couldn\'t duplicate the slide :-(', $this->plugin_domain ) . '</br>' . __( 'Please save your work and try again.', $this->plugin_domain ),
			);

			wp_send_json_error( $response );
		}
	}

	/**
	 * Sorts the slideshows.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function sort_slideshows() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], "cb_vegas_sort_tabs_nonce" ) ) {
			exit( __( 'One more try and your browser will burst into flames ;-)', $this->plugin_domain ) );
		}

		$indices                 = $_REQUEST['indices'];
		$current_slideshow_index = $_REQUEST['slideshow_index'];

		if ( true === $this->ajax->sort_the_slideshows( $indices, $current_slideshow_index ) ) {

			$response = array(
				'success' => true,
				'message' => __( 'Slideshows rearranged.', $this->plugin_domain ),
			);

			wp_send_json_success( $response );
		} else {

			$response = array(
				'success' => false,
				'message' => __( 'Nope. Couldn\'t sort the slideshows :-(', $this->plugin_domain ) . '</br>' . __( 'Please save your work and try again.', $this->plugin_domain ),
			);

			wp_send_json_error( $response );
		}
	}

	/**
	 * Sorts the slides of the current slideshow.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function sort_slides() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], "cb_vegas_sort_slides_nonce" ) ) {
			exit( __( "One more try and your browser will burst into flames ;-)", $this->plugin_domain ) );
		}

		$slideshow_index = $_REQUEST['slideshow_index'];
		$indices         = $_REQUEST['indices'];
		$menuorder       = $_REQUEST['menuorder'];

		if ( true === $this->ajax->sort_the_slides( $slideshow_index, $indices, $menuorder ) ) {

			$response = array(
				'success' => true,
				'message' => __( 'Slides rearranged.', $this->plugin_domain ),
			);

			wp_send_json_success( $response );
		} else {

			$response = array(
				'success' => false,
				'message' => __( 'Nope. Couldn\'t sort the slides :-(', $this->plugin_domain ) . '</br>' . __( 'Please save your work and try again.', $this->plugin_domain ),
			);

			wp_send_json_error( $response );
		}
	}

	/**
	 * Removes the current slideshow.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function remove_slideshow() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'cb_vegas_remove_slideshow_nonce' ) ) {
			exit( __( "One more try and your browser will burst into flames ;-)", $this->plugin_domain ) );
		}

		$deprecated_slideshow_index = isset( $_REQUEST['deprecated_slideshow_index'] ) ? $_REQUEST['deprecated_slideshow_index'] : false;

		if ( true === $this->ajax->remove_the_slideshow( $deprecated_slideshow_index ) ) {

			$response = array(
				'success' => true,
				'message' => __( 'Slideshow removed.', $this->plugin_domain ) . '</br>' . __( 'You will be redirected now.', $this->plugin_domain ),
			);

			wp_send_json_success( $response );
		} else {

			$response = array(
				'success' => false,
				'message' => __( 'Nope. Couldn\'t remove the slideshow :-(', $this->plugin_domain ) . '</br>' . __( 'Please save your work and try again.', $this->plugin_domain ),
			);

			wp_send_json_error( $response );
		}
	}

	/**
	 * Removes a slide.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function remove_slide() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], "cb_vegas_remove_slide_nonce" ) ) {
			exit( __( "One more try and your browser will burst into flames ;-)", $this->plugin_domain ) );
		}

		// Get the slide index.
		$slideshow_index = isset( $_REQUEST['slideshow_index'] ) ? $_REQUEST['slideshow_index'] : false;
		$slide_index     = isset( $_REQUEST['slide_index'] ) ? $_REQUEST['slide_index'] : false;

		if ( true === $this->ajax->remove_the_slide( $slideshow_index, $slide_index ) ) {

			$response = array(
				'success' => true,
				'message' => __( 'Slide removed.', $this->plugin_domain ),
			);

			wp_send_json_success( $response );
		} else {

			$response = array(
				'success' => false,
				'message' => __( 'Nope. Couldn\'t remove the slide :-(', $this->plugin_domain ) . '</br>' . __( 'Please save your work and try again.', $this->plugin_domain ),
			);

			wp_send_json_error( $response );
		}
	}

}
