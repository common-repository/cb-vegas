<?php

/**
 * The class responsible for creating and displaying the help tab on this plugin's menu page.
 *
 * @link              https://wordpress.org/plugins/cb-vegas/
 * @since             0.1.0
 * @package           CB_Vegas
 * @subpackage        CB_Vegas/admin/includes
 * Author:            demispatti <demis@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class CB_Vegas_Help_Tab {

	/**
	 * The name of the domain.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $plugin_domain
	 */
	private $plugin_domain;

	/**
	 * The array containing the title and the content of the help tab.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    array $tabs
	 */
	private $tabs;

	/**
	 * Kicks off the help tab.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param string $plugin_domain
	 *
	 * @return void
	 */
	public function __construct( $plugin_domain ) {

		$this->plugin_domain = $plugin_domain;
	}


	public function add_hooks() {

		// We do only add the help tab on the plugin options page.
		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'cb_vegas_settings_page' ) {

			$this->set_tab();

			add_action( 'in_admin_header', array( $this, 'add_tab' ) );
			add_action( "load-{$GLOBALS['pagenow']}", array( $this, 'add_tab' ) );
		}
	}

	/**
	 * Adds the tab to the current page.
	 *
	 * @hooked_action
	 *
	 * @uses   WordPress Administration Screen API screen.php / add_help_tab()
	 * @uses   display_content_callback()
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function add_tab() {

		foreach ( $this->tabs as $id => $data ) {

			$title = __( $data['title'], $this->plugin_domain );

			get_current_screen()->add_help_tab( array(
				'id'      => $id,
				'title'   => __( $title, $this->plugin_domain ),
				'content' => $this->display_content_callback(),
			) );
		}
	}

	/**
	 * Sets the title of the help tab.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function set_tab() {

		$this->tabs = array( __( 'HELP', $this->plugin_domain ) => array( 'title' => __( 'cbVegas help', $this->plugin_domain ) ) );
	}

	/**
	 * Returns the content of the help tab.
	 *
	 * @used-by add_help_tab()
	 * @since   0.1.0
	 * @access  private
	 * @return string $html
	 */
	private function display_content_callback() {

		$html = '<p>' . __( "Thanks for using this plugin.", $this->plugin_domain ) . '</p>';

		$html .= '<p>' . __( "Please note: Whenever you add a new image to a slide or add one to a newly added slide, save the slideshow. Then you can rearrange, copy, delete etc. safely. Video is not (yet) supported.", $this->plugin_domain ) . '</p>';

		$html .= '<p>' . __( "If you set a slideshow as fallback, it is displayed on all supported post types, but the slideshows you defined on a per page / post basis override the fallback slideshow. On all other regular pages and posts the slideshow you defined as fallback will be displayed.", $this->plugin_domain ) . '</p>';

		$html .= '<p>' . __( "All other settings are part of the 'Vegas Background Slideshow'. For help regarding 'Vegas Background Slideshow' itself, please refer to it's <a href='http://vegas.jaysalvat.com/documentation/setup/' target='_blank'>official documentation</a>.", $this->plugin_domain ) . '</p>';

		$html .= '<p>' . __( "If you have any questions, comments or issues regarding this plugin, please visit the <a href='https://wordpress.org/plugins/cb-vegas/' target='_blank'>plugin homepage</a>.", $this->plugin_domain ) . '</p>';

		$html .= '<p>' . __( "Enjoy playing around with the animations, transitions and timings :-)", $this->plugin_domain ) . '</p>';

		return $html;
	}

}
