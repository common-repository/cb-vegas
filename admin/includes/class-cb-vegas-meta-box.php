<?php

/**
 * The class that defines the meta box on post edit screens.
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
class CB_Vegas_Meta_Box {

	/**
	 * The domain of the plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $plugin_domain
	 */
	private $plugin_domain;

	/**
	 * The value for the callback of the plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $theme_has_callback
	 */
	private $theme_has_callback = 'false';

	/**
	 * Kicks off the meta box.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    string $plugin_domain
	 */
	public function __construct( $plugin_domain ) {

		$this->plugin_domain = $plugin_domain;
	}

	public function add_hooks() {

		add_action( 'load-post.php', array( $this, 'load_post' ) );
		add_action( 'load-post-new.php', array( $this, 'load_post' ) );
	}

	/**
	 * Loads all relevant parts for the post to display.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    string $plugin_domain
	 */
	public function load_post() {

		$screen = get_current_screen();

		/* If the current theme doesn't support custom backgrounds, bail. */
		if ( ! current_theme_supports( 'custom-background' ) || ! post_type_supports( $screen->post_type, 'custom-background' ) ) {
			return;
		}

		/* Get the 'wp_head' callback. */
		$wp_head_callback = get_theme_support( 'custom-background', 'wp-head-callback' );

		/* Checks if the theme has set up a custom callback. */
		$this->theme_has_callback = empty( $wp_head_callback ) || '_custom_background_cb' === $wp_head_callback ? false : true;

		// Add the meta box.
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), 10 );
		// Save meta data.
		add_action( 'save_post', array( $this, 'save_post' ), 20, 2 );
	}

	/**
	 * Registers the meta box with WordPress.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  string $post_type
	 */
	public function add_meta_box( $post_type ) {

		// if on post edit screen conditional enibauen

		add_meta_box(
			'cb_vegas-meta-box',
			__( 'Vegas Background Slideshow', $this->plugin_domain ),
			array( $this, 'display_meta_box' ),
			$post_type,
			'side',
			'core'
		);
	}

	/**
	 * DIsplays the meta box.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  object $post
	 *
	 * @return echo
	 */
	public function display_meta_box( $post ) {

		// Adds a nonce field.
		wp_nonce_field( 'cb_vegas_meta_box_nonce', 'cb_vegas_nonce_field' );

		$post_meta        = get_post_meta( $post->ID, 'cb_vegas_singular', 'slideshow' );
		$slideshow_switch = isset( $post_meta['slideshow_enabled'] ) ? $post_meta['slideshow_enabled'] : false;

		$html = '<div id="cb-vegas-meta-box-container">';
		// Creates the on/off-switch.
		$html .= '<div id="cb-vegas-meta-box-checkbox" class="cb-vegas-input-container">';

		$html .= '<label class="cbv-switch label-for-cbv-switch" title="' . __( 'Enable or disable Vegas Slideshow for this page / post.', $this->plugin_domain ) . '">';
		$html .= '<input type="checkbox" id="slideshow_enabled" class="cbv-switch-input cbv-input-checkbox" name="slideshow_enabled" value="1" ' . checked( 1, isset( $slideshow_switch ) ? $slideshow_switch : 0, false ) . '/>';
		$html .= '<span class="cbv-switch-label" data-on="On" data-off="Off"></span>';
		$html .= '<span class="cbv-switch-handle"></span>';
		$html .= '</label>';
		$html .= '</div>';

		// Settings Container
		$html .= '<div id="cb-vegas-meta-box-select" class="cb-vegas-setting-container">';

		// Creates the dropdown containing the names of the available slideshows.
		$html .= '<div class="cb-vegas-input-container">';
		$html .= '<select name="slideshow_uniqid" class="floating-element fancy-select cb-vegas-fancy-select" id="slideshow_uniqid">';

		$none = __( 'none selected', $this->plugin_domain );

		$slideshows = get_option( 'cb_vegas_options' );

		if ( false !== $slideshows ) {

			if ( $this->is_valid_slideshow() ) {

				$html .= '<option value="' . $none . '" selected="">' . $none . '</option>';
				foreach ( (array) $slideshows as $slideshow_index => $slideshow ) {

					$html .= '<option value="' . $slideshow['meta']['slideshow_uniqid'] . '" ' . selected( $post_meta['slideshow_uniqid'], $slideshow['meta']['slideshow_uniqid'], false ) . '>' . $slideshow['meta']['slideshow_name'] . '</option>';
				}
			} else {

				foreach ( (array) $slideshows as $slideshow_index => $slideshow ) {

					$html .= '<option value="' . $slideshow['meta']['slideshow_uniqid'] . '" selected="">' . $slideshow['meta']['slideshow_name'] . '</option>';
				}
				// Output when there is no valid slideshow set.
				$html .= '<option value="' . $none . '" selected="selected">' . $none . '</option>';
			}
		} else {

			$available = __( 'none available', $this->plugin_domain );

			$html .= '<option value="' . $none . '" selected="">' . $available . '</option>';
		}

		$html .= '</select>';
		$html .= '</div>';

		// # Settings Container
		$html .= '</div>';

		// end .cb-vegas-meta-box
		$html .= '</div>';

		echo $html;
	}

	/**
	 * Registers the meta box with WordPress.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return bool
	 */
	private function is_valid_slideshow() {

		// Determines if the slideshow stored within the post meta data still exists and thus can be used or has to be deleted from the post's meta data.
		$list = $this->get_slideshow_uniqids();

		if ( false !== $list ) {

			$post_meta = get_post_meta( get_the_ID(), 'cb_vegas_singular', true );
			$uniqid    = isset( $post_meta['slideshow_uniqid'] ) ? $post_meta['slideshow_uniqid'] : false;

			if ( false !== $uniqid && in_array( $uniqid, $list, false ) ) {

				return true;
			} else if ( __( 'none selected', $this->plugin_domain ) === $uniqid ) { // @todo: test this with localized string

				return true;
			} else {

				return false;
			}
		}
	}

	/**
	 * Registers the meta boxx with WordPress.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return mixed | bool $uniqids | false
	 */
	private function get_slideshow_uniqids() {

		$uniqids = array();
		$options = get_option( 'cb_vegas_options' );
		// Creates an array with all existing slideshiw uniqids.
		if ( is_array( $options ) ) {

			foreach ( (array) $options as $i => $option ) {

				$id = $option['meta']['slideshow_uniqid'];

				if ( isset( $id ) ) {

					$uniqids[] = $id;
				}
			}

			return $uniqids;
		} else {

			return false;
		}
	}

	/**
	 * Registers the meta box with WordPress.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  string $post_id
	 * @param  object $post
	 *
	 * @return mixed | void
	 */
	public function save_post( $post_id, $post ) {

		// Verify the nonce.
		if ( ! isset( $_POST['cb_vegas_nonce_field'] ) || ! wp_verify_nonce( $_POST['cb_vegas_nonce_field'], "cb_vegas_meta_box_nonce" ) ) {
			return;
		}
		// Get the post type object.
		$post_type = get_post_type_object( $post->post_type );
		// Check if the current user has permission to edit the post.
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}
		// Don't save if the post is only a revision.
		if ( 'revision' == $post->post_type ) {
			return;
		}
		// Makes sure the value is not undefined.
		$post_meta['slideshow_enabled'] = isset( $_POST['slideshow_enabled'] ) ? $_POST['slideshow_enabled'] : false;
		$post_meta['slideshow_uniqid']  = isset( $_POST['slideshow_uniqid'] ) ? $_POST['slideshow_uniqid'] : false;
		// Updates the meta field.
		update_post_meta( $post_id, 'cb_vegas_singular', $post_meta );
	}

}
