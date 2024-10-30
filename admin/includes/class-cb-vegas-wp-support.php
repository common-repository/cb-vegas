<?php

/**
 * Enables the post type support for the given post types.
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
class CB_Vegas_WP_Support {

	/**
	 * The supported post types.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      array $post_types
	 */
	public static $post_types = array( 'post', 'page', 'product', 'portfolio', 'gallery', 'art', 'movies', 'videos' );

	/**
	 * The feature we want the post types to work with.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string $feature
	 */
	private $feature = 'custom-background';

	/**
	 * The array containing the default values we support the custom background feature with.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      array $defaults
	 */
	public static $defaults;

	/**
	 * The feature we want the post types to work with.
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return   void
	 */
	public function __construct() {

		$this->set_defaults();
	}

	public function add_hooks() {

		add_action( 'after_setup_theme', array( $this, 'enable_post_type_support' ), 1 );
		add_action( 'after_setup_theme', array( $this, 'add_theme_support' ), 2 );
	}

	/**
	 * Assigns the feature we want the post types to work with.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @return   void
	 */
	private function set_defaults() {

		self::$defaults = array(
			'default-color'          => '',
			'default-image'          => '',
			'wp-head-callback'       => '_custom_background_cb',
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
		);
	}

	/**
	 * Adds the theme support for the feature we want the post types to work with.
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return   void
	 */
	public function add_theme_support() {

		// Get the callback for printing styles on 'wp_head'.
		$wp_head_callback = get_theme_support( $this->feature, 'wp-head-callback' );

		// If the theme hasn't set up a custom callback, let's roll our own.
		if ( empty( $wp_head_callback ) || '_cb_vegas_cb' === $wp_head_callback ) {

			add_theme_support( $this->feature );
		}
	}

	/**
	 * Adds post type support for the given post types.
	 *
	 * @since    0.1.0
	 * @access   public
	 * @return   void
	 */
	public function enable_post_type_support() {

		foreach ( self::$post_types as $post_type ) {

			add_post_type_support( $post_type, $this->feature );
		}
	}
}
