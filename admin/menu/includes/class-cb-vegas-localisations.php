<?php

/**
 * The class responsible for localizing the admin part of this plugin.
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
class CB_Vegas_Localisations {

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
	 * Localizes texts concerning slide handling.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param $plugin_name
	 * @param $plugin_domain
	 *
	 * @return void
	 */
	public function __construct( $plugin_name, $plugin_domain ) {

		$this->plugin_name   = $plugin_name;
		$this->plugin_domain = $plugin_domain;
	}

	/**
	 * Register the hook with WordPress.
	 *
	 * @hooked_action
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function add_hooks() {

		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'cb_vegas_settings_page' ) {

			add_action( 'admin_enqueue_scripts', array( $this, 'localize_main_script' ), 11 );
			add_action( 'admin_enqueue_scripts', array( $this, 'localize_ajax_script' ), 11 );
		}
	}

	/**
	 * Localizes the settings page.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array $texts
	 */
	public function localize_main_script() {

		wp_localize_script(
			'cb-vegas-main-js',
			'cbVegas',
			array_merge(
				$this->get_checkbox_texts(),
				$this->get_main_script_texts()
			)
		);
	}

	/**
	 * Localizes an ajax related script.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array $texts
	 */
	public function localize_ajax_script() {

		wp_localize_script(
			'cb-vegas-ajax-js',
			'CbVegasAjax',
			array_merge(
				$this->get_ok_cancel_texts(),
				$this->get_ajax_script_texts()

			)
		);
	}

	/**
	 * Returns translated texts.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array $texts
	 */
	private function get_main_script_texts() {

		$texts = array(
			'frameTitleText'                                => __( 'Select Media', $this->plugin_domain ),
			'frameButtonText'                               => __( 'Set Media', $this->plugin_domain ),
			'addMediaButtonText'                            => __( "Add Media", $this->plugin_domain ),
			'removeMediaButtonText'                         => __( "Remove Media", $this->plugin_domain ),
			'duplicateSlideshowSaveSettingsFirstButtonText' => __( "Save your settings first, then duplicate the slideshow.", $this->plugin_domain )
		);

		return $texts;
	}

	/**
	 * Returns translated texts.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array $texts
	 */
	private function get_ajax_script_texts() {

		$texts = array(
			'actionCancelledMessageHeadingHeading'  => __( "Nothing changed", $this->plugin_domain ),
			'addSlideshowConfirmationHeading'       => __( "Add Slideshow", $this->plugin_domain ),
			'dupliacteSlideshowConfirmationHeading' => __( "Duplicate Slideshow", $this->plugin_domain ),
			'removeSlideshowConfirmationHeading'    => __( "Remove Slideshow", $this->plugin_domain ),
			'addSlideshowButtonTextHeading'         => __( "Add Slide", $this->plugin_domain ),
			'removeSlideshowButtonTextHeading'      => __( "Remove Slide", $this->plugin_domain ),
			'addSlideConfirmationHeading'           => __( "Add Slide", $this->plugin_domain ),
			'duplicateSlideConfirmationHeading'     => __( "Duplicate Slide", $this->plugin_domain ),
			'removeSlideConfirmationHeading'        => __( "Remove Slide", $this->plugin_domain ),
			'addSlideButtonTextHeading'             => __( "Add Slide", $this->plugin_domain ),
			'removeSlideButtonTextHeading'          => __( "Remove Slide", $this->plugin_domain ),

			'actionCancelledMessage'         => __( "Nothing changed!", $this->plugin_domain ),
			'addSlideshowConfirmation'       => __( "Add a new Slideshow?", $this->plugin_domain ),
			'dupliacteSlideshowConfirmation' => __( "Duplicate this Slideshow?", $this->plugin_domain ),
			'removeSlideshowConfirmation'    => __( "Remove this Slideshow?", $this->plugin_domain ),
			'addSlideshowButtonText'         => __( "Add a Slide", $this->plugin_domain ),
			'removeSlideshowButtonText'      => __( "Remove this Slide", $this->plugin_domain ),
			'addSlideConfirmation'           => __( "Add a Slide?", $this->plugin_domain ),
			'duplicateSlideConfirmation'     => __( "Duplicate this Slide?", $this->plugin_domain ),
			'removeSlideConfirmation'        => __( "Remove this Slide?", $this->plugin_domain ),
			'addSlideButtonText'             => __( "Add a Slide", $this->plugin_domain ),
			'removeSlideButtonText'          => __( "Remove this Slide", $this->plugin_domain )
		);

		return $texts;
	}

	/**
	 * Localizes texts for "Alertify".
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array $texts
	 */
	private function get_ok_cancel_texts() {

		$texts = array(
			'okiDoki'   => __( 'ok', $this->plugin_domain ),
			'noWayJose' => __( 'cancel', $this->plugin_domain ),
		);

		return $texts;
	}

	/**
	 * Returns the texts for the switches (on/off switches).
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array
	 */
	private function get_checkbox_texts() {

		$locale = get_locale();

		switch ( $locale ) {

			case( $locale == 'de_DE' );

				$texts = array(
					'locale'  => $locale,
					'onText'  => 'Ein',
					'offText' => 'Aus',
				);
				break;
			default:

				$texts = array(
					'locale'  => 'default',
					'onText'  => 'On',
					'offText' => 'Off',
				);
				break;
		}

		return $texts;
	}

}
