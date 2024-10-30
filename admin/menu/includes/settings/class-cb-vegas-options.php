<?php

/**
 * The class responsible for maintaining the options.
 *
 * @link              https://wordpress.org/plugins/cb-vegas/
 * @since             0.1.0
 * @package           CB_Vegas
 * @subpackage        CB_Vegas/admin/menu/includes/settings
 * Author:            demispatti <demis@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class CB_Vegas_Options {

	/**
	 * The domain of the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    string $plugin_domain
	 */
	public $plugin_domain;

	public $align;

	public $valign;

	public $cover;

	/**
	 * The array containing the transitions.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array $transitions
	 */
	public $transitions;

	/**
	 * The array containing the animations.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array $animations
	 */
	public $animations;

	/**
	 * The array containing the overlays.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array $overlays
	 */
	public $overlays;

	/**
	 * The array containing the option keys.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array $option_keys
	 */
	public $option_keys;

	/**
	 * The array containing the options.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array $the_options
	 */
	public $the_options;

	/**
	 * The reference to the settings class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    object $settings
	 */
	public $settings;

	/**
	 * The reference to the settings factory class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    object $settings_factory
	 */
	public $settings_factory;

	/**
	 * Initializes the options class.
	 *
	 * @since 0.1.0
	 *
	 * @param string $plugin_domain
	 * @param string $settings
	 */
	public function __construct( $plugin_domain, $settings ) {

		$this->plugin_domain = $plugin_domain;
		$this->settings      = $settings;

		$this->load_dependencies();
		$this->set_cover();
		$this->set_alignments();
		$this->set_transitions();
		$this->set_animations();
		$this->set_overlays();
		$this->set_option_keys();
		$this->set_vegas_options();
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'settings/class-cb-vegas-settings-factory.php';

		$this->settings_factory = new CB_Vegas_Settings_Factory( $this->get_plugin_domain(), $this, $this->get_settings() );
	}

	/**
	 * Sets the settings template.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function set_vegas_options() {

		$this->the_options = array(
			'src'                => array(
				'option_key'    => 'src',
				'title'         => '',
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'The Media.', $this->plugin_domain ),
				'value'         => site_url() . '/wp-content/plugins/cb-vegas/admin/images/the-placeholder.jpg',
				'field_type'    => 'src',
				'notice_level'  => 'notice-warning',
				'select_values' => false,
				'hidden'        => true,
				'has_label'     => false,
				'html_tag'      => 'img',
				'class'         => 'cb-vegas-settings-src',
			),
			'color'              => array(
				'option_key'    => 'color',
				'title'         => __( 'Background Color', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Slide background color.', $this->plugin_domain ),
				'value'         => '',
				'field_type'    => 'color',
				'notice_level'  => 'notice-warning',
				'select_values' => false,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'input',
				'class'         => 'cb-vegas-settings-color',
			),
			'delay'              => array(
				'option_key'    => 'delay',
				'title'         => __( 'Duration (ms)', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Delay beetween slides in milliseconds.', $this->plugin_domain ),
				'value'         => '6000',
				'field_type'    => 'text',
				'notice_level'  => 'notice-correction',
				'select_values' => false,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'input',
				'class'         => 'cb-vegas-settings-delay',
			),
			'preload'            => array(
				'option_key'    => 'preload',
				'title'         => __( 'Preload', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Preload both images and videos at start.', $this->plugin_domain ),
				'value'         => true,
				'field_type'    => 'checkbox',
				'notice_level'  => false,
				'select_values' => false,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'input',
				'class'         => 'cb-vegas-settings-preload',
			),
			'preloadImage'       => array(
				'option_key'    => 'preloadImage',
				'title'         => __( 'Preload Image', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Preload images at start. "preload" must be "false".', $this->plugin_domain ),
				'value'         => true,
				'field_type'    => 'checkbox',
				'notice_level'  => false,
				'select_values' => false,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'input',
				'class'         => 'cb-vegas-settings-preloadImage',
			),//preloadvideo
			/*'preloadVideo'       => array(
				'option_key'    => 'preloadVideo',
				'title'         => __('Preload Video', $this->plugin_domain),
				'callback'      => 'render_options_field_callback',
				'description'   => __('Preload videos at start. "preload" must be "false".', $this->plugin_domain),
				'value'         => false,
				'field_type'    => 'checkbox',
				'notice_level'  => false,
				'select_values' => false,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'input',
				'class'         => 'cb-vegas-settings-preloadVideo',
			),*///shuffle
			/*'shuffle'            => array(
				'option_key'    => 'shuffle',
				'title'         => __( 'Shuffle', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'The array of slides is shuffled before.', $this->plugin_domain ),
				'value'         => false,
				'field_type'    => 'checkbox',
				'notice_level'  => false,
				'select_values' => false,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'input',
				'class'         => 'cb-vegas-settings-shuffle'
			),*/
			'transition'         => array(
				'option_key'    => 'transition',
				'title'         => __( 'Transition', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Set the transition between slides.', $this->plugin_domain ),
				'value'         => $this->create_random_transition(),
				'field_type'    => 'select',
				'notice_level'  => false,
				'select_values' => $this->transitions,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'select',
				'class'         => 'cb-vegas-settings-transition',
			),
			'transitionDuration' => array(
				'option_key'    => 'transitionDuration',
				'title'         => __( 'Transition Duration (ms)', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Set the transition duration in milliseconds.', $this->plugin_domain ),
				'value'         => '400', // @todo fn für "auto" einbauen
				'field_type'    => 'text',
				'notice_level'  => 'notice-correction',
				'select_values' => false,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'input',
				'class'         => 'cb-vegas-settings-transitionDuration',
			),
			'align'              => array(
				'option_key'    => 'align',
				'title'         => __( 'Align', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Horizontal alignment of the image in the slide.', $this->plugin_domain ),
				'value'         => 'center',
				'field_type'    => 'select',
				'notice_level'  => false,
				'select_values' => $this->align,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'select',
				'class'         => 'cb-vegas-settings-align',
			),
			/*'timer'              => array(
				'option_key'    => 'timer',
				'title'         => __( 'Timer Bar', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Display/hide timer bar.', $this->plugin_domain ),
				'value'         => true,
				'field_type'    => 'checkbox',
				'notice_level'  => false,
				'select_values' => false,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'input',
				'class'         => 'cb-vegas-settings-timer'
			),*/
			'cover'              => array(
				'option_key'    => 'cover',
				'title'         => __( 'Cover', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Set the background size.', $this->plugin_domain ),
				'value'         => 'cover',
				'field_type'    => 'select',
				'notice_level'  => false,
				'select_values' => $this->cover,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'select',
				'class'         => 'cb-vegas-settings-cover',
			),
			'animation'          => array(
				'option_key'    => 'animation',
				'title'         => __( 'Animation', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Set the animation of the slides.', $this->plugin_domain ),
				'value'         => 'kenburns',
				'field_type'    => 'select',
				'notice_level'  => false,
				'select_values' => $this->animations,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'select',
				'class'         => 'cb-vegas-settings-animation',
			),
			'animationDuration'  => array(
				'option_key'    => 'animationDuration',
				'title'         => __( 'Animation Duration (ms)', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Set the animation duration in milliseconds.', $this->plugin_domain ),
				'value'         => '400',
				'field_type'    => 'text',
				'notice_level'  => 'notice-correction',
				'select_values' => false,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'input',
				'class'         => 'cb-vegas-settings-animationDuration',
			),
			'valign'             => array(
				'option_key'    => 'valign',
				'title'         => __( 'Vertical Align', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Vertical alignment of the image in the slide.', $this->plugin_domain ),
				'value'         => 'center',
				'field_type'    => 'select',
				'notice_level'  => false,
				'select_values' => $this->valign,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'select',
				'class'         => 'cb-vegas-settings-valign',
			),
			'overlay'            => array(
				'option_key'    => 'overlay',
				'title'         => __( 'Show Overlay', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Display/hide the overlay. Could be true false or the path of an overlay image pattern.', $this->plugin_domain ),
				'value'         => __( 'none', $this->plugin_domain ),
				'field_type'    => 'select',
				'notice_level'  => false,
				'select_values' => $this->overlays,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'select',
				'class'         => 'cb-vegas-settings-overlay',
			),//autoplay
			'autoplay'           => array(
				'option_key'    => 'autoplay',
				'title'         => __( 'Autoplay', $this->plugin_domain ),
				'callback'      => 'render_options_field_callback',
				'description'   => __( 'Start the Slideshow automatically.', $this->plugin_domain ),
				'value'         => true,
				'field_type'    => 'checkbox',
				'notice_level'  => false,
				'select_values' => false,
				'hidden'        => false,
				'has_label'     => true,
				'html_tag'      => 'input',
				'class'         => 'cb-vegas-settings-autoplay',
			),
		);
	}

	/**
	 * Sets the translateable strings for the "alignment" select values.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function set_alignments() {

		$this->align = array(
			'center' => __( 'center', $this->plugin_domain ),
			'right'  => __( 'right', $this->plugin_domain ),
			'left'   => __( 'left', $this->plugin_domain ),
		);

		$this->valign = array(
			'center' => __( 'center', $this->plugin_domain ),
			'top'    => __( 'top', $this->plugin_domain ),
			'bottom' => __( 'bottom', $this->plugin_domain ),
		);
	}

	/**
	 * Sets the available transition options.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function set_transitions() {

		$this->transitions = array(
			'random'      => __( 'random', $this->plugin_domain ),
			'fade'        => 'fade',
			'fade2'       => 'fade2',
			'blur'        => 'blur',
			'blur2'       => 'blur2',
			'flash'       => 'flash',
			'flash2'      => 'flash2',
			'negative'    => 'negative',
			'negative2'   => 'negative2',
			'burn'        => 'burn',
			'burn2'       => 'burn2',
			'slideLeft'   => 'slideLeft',
			'slideLeft2'  => 'slideLeft2',
			'slideRight'  => 'slideRight',
			'slideRight2' => 'slideRight2',
			'slideUp'     => 'slideUp',
			'slideUp2'    => 'slideUp2',
			'slideDown'   => 'slideDown',
			'slideDown2'  => 'slideDown2',
			'zoomIn'      => 'zoomIn',
			'zoomIn2'     => 'zoomIn2',
			'zoomOut'     => 'zoomOut',
			'zoomOut2'    => 'zoomOut2',
			'swirlLeft'   => 'swirlLeft',
			'swirlLeft2'  => 'swirlLeft2',
			'swirlRight'  => 'swirlRight',
			'swirlRight2' => 'swirlRight2',
		);
	}

	public function set_cover() {

		$this->cover = array(
			'yes'    => 'yes',
			'no'     => 'no',
			'repeat' => 'repeat'/*__( 'repeat', $this->plugin_domain )*/
		);
	}

	/**
	 * Sets the available animation options.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function set_animations() {

		$this->animations = array(
			'random'            => __( 'random', $this->plugin_domain ),
			'kenburns'          => 'kenburns',
			'kenburnsUp'        => 'kenburnsUp',
			'kenburnsDown'      => 'kenburnsDown',
			'kenburnsRight'     => 'kenburnsRight',
			'kenburnsLeft'      => 'kenburnsLeft',
			'kenburnsUpLeft'    => 'kenburnsUpLeft',
			'kenburnsUpRight'   => 'kenburnsUpRight',
			'kenburnsDownLeft'  => 'kenburnsDownLeft',
			'kenburnsDownRight' => 'kenburnsDownRight',
		);
	}

	/**
	 * Sets the available overlays.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function set_overlays() {

		$this->overlays = array(
			'none' => __( 'none', $this->plugin_domain ),
			'01'   => '01.png',
			'02'   => '02.png',
			'03'   => '03.png',
			'04'   => '04.png',
			'05'   => '05.png',
			'06'   => '06.png',
			'07'   => '07.png',
			'08'   => '08.png',
			'09'   => '09.png',
		);
	}

	/**
	 * Sets the option keys.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function set_option_keys() {

		$this->option_keys = array(
			'src'                => 'src',
			'color'              => 'color',
			'delay'              => 'delay',
			'preload'            => 'preload',
			'preloadImage'       => 'preloadImage',
			'transition'         => 'transition',
			'transitionDuration' => 'transitionDuration',
			'align'              => 'align',
			'timer'              => 'timer',
			'cover'              => 'cover',
			'animation'          => 'animation',
			'animationDuration'  => 'animationDuration',
			'valign'             => 'valign',
			'overlay'            => 'overlay',
		);
	}

	/**
	 * Seeeds an initial slideshow.
	 *
	 * @uses cb_vegas_settings_factory / create_slideshow()
	 *
	 * @param $slideshow_index
	 *
	 * @return void
	 */
	public function seed_options( $slideshow_index = 0 ) {

		$options[0] = $this->settings_factory->create_slideshow( $slideshow_index );

		delete_option( 'cb_vegas_options' );
		add_option( 'cb_vegas_options', $options );
	}

	/**
	 * Returns a random transition.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return string $transitions
	 */
	public function create_random_transition() {

		$transitions = array(
			'fade'     => 'fade',
			'fade2'    => 'fade2',
			'blur'     => 'blur',
			'blur2'    => 'blur2',
			'zoomIn'   => 'zoomIn',
			'zoomIn2'  => 'zoomIn2',
			'zoomOut'  => 'zoomOut',
			'zoomOut2' => 'zoomOut2',
		);

		return array_rand( $transitions );
	}

	/**
	 * Returns a random animation.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return string $animations
	 */
	public function create_random_animation() {

		$animations = array(
			'fade'     => 'fade',
			'fade2'    => 'fade2',
			'blur'     => 'blur',
			'blur2'    => 'blur2',
			'zoomIn'   => 'zoomIn',
			'zoomIn2'  => 'zoomIn2',
			'zoomOut'  => 'zoomOut',
			'zoomOut2' => 'zoomOut2',
		);

		return array_rand( $animations );
	}

	/**
	 * Returns the array containing the transitions.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return array $transitions
	 */
	public function get_transitions() {

		return $this->transitions;
	}

	/**
	 * Returns the array containing the animations.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return array $animations
	 */
	public function get_animations() {

		return $this->animations;
	}

	/**
	 * Returns the array containing the overlays.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return array $overlays
	 */
	public function get_overlays() {

		return $this->overlays;
	}

	/**
	 * Returns the array containing the option keys.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return array $option_keys
	 */
	public function get_option_keys() {

		return $this->option_keys;
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
	 * Determine if we are on a post type settings page.
	 *
	 * @access public
	 *
	 * @return bool
	 */
	public function is_slideshow_tab() {

		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'cb_vegas_settings_page' && isset( $_REQUEST['tab'] ) /*&& in_array( $_REQUEST['tab'], $this->get_slideshow_names() ) */ ) {

			return true;
		} else {

			return false;
		}
	}

}
