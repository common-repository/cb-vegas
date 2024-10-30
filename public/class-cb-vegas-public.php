<?php

/**
 * The public-facing side of the plugin.
 * This class is responsible for loading the styles and the scripts
 * and localizing the "Vegas Background Slideshow" Javascript library.
 *
 * @link       https://wordpress.org/plugins/cb-vegas/
 * @since      0.1.0
 * @package    CB_Vegas
 * @subpackage CB_Vegas/public
 */
class CB_Vegas_Public {

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
	 * The reference to the class responsible
	 * for localizing this plugins instance of "Vegas Slideshow".
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $vegas
	 */
	private $vegas;

	/**
	 * Kicks off the public part of this plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  string $plugin_name
	 * @param  string $plugin_version
	 * @param  object $loader
	 */
	public function __construct( $plugin_name, $plugin_domain, $plugin_version ) {

		$this->plugin_name    = $plugin_name;
		$this->plugin_domain  = $plugin_domain;
		$this->plugin_version = $plugin_version;

		$this->get_localisation();
	}

	/**
	 * Loads it's dependency.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function get_localisation() {

		$this->vegas = new CB_Vegas_Localisation( $this->get_plugin_name(), $this->get_plugin_domain() );
	}

	public function add_hooks() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'localize_vegas' ), 11 );
	}

	/**
	 * Registers the styles.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function enqueue_styles() {

		// If no post-related slideshow exists, we do nothing here.
		if ( $this->slideshow_is_global() || $this->slideshow_is_fallback() || ( $this->slideshow_is_enabled() && $this->slideshow_is_valid() ) ) {

			wp_enqueue_style(
				'cb-vegas-inc-min-css',
				plugin_dir_url( __FILE__ ) . '../vendor/vegas-slideshow/vegas.min.css',
				array(),
				$this->plugin_version,
				'all'
			);

			wp_enqueue_style(
				'cb-vegas-inc-min-css',
				plugin_dir_url( __FILE__ ) . 'css/public.css',
				array(),
				$this->plugin_version,
				'all'
			);
		}
	}

	/**
	 * Registers the scripts.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {

		// If no post-related slideshow exists, we do nothing here.
		if ( $this->slideshow_is_global() || $this->slideshow_is_fallback() || ( $this->slideshow_is_enabled() && $this->slideshow_is_valid() ) ) {

			wp_enqueue_script(
				'cb-vegas-inc-min-js',
				plugin_dir_url( __FILE__ ) . '../vendor/vegas-slideshow/vegas.min.js',
				array(
					'jquery',
				),
				$this->plugin_version,
				'all'
			);

			wp_enqueue_script(
				'cb-vegas-localisation-js',
				plugin_dir_url( __FILE__ ) . 'js/vegas-localisation.js',
				array(
					'jquery',
					'cb-vegas-inc-min-js',
				),
				$this->plugin_version,
				'all'
			);
		}
	}

	/**
	 * Initiates localisation of the frontend.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @uses   run()
	 * @see    public/includes/class-cb-vegas-localisation.php
	 * @access public
	 * @return void
	 */
	public function localize_vegas() {

		$slideshow_index = false;

		if ( $this->slideshow_is_enabled() && $this->slideshow_is_valid() ) {

			$slideshow_index = $this->get_slideshow_index();
		} else if ( $this->slideshow_is_fallback() ) {

			$slideshow_index = $this->get_fallback_slideshow_index();
		}

		if ( false !== $slideshow_index ) {

			$this->vegas->localize_vegas( $slideshow_index );
		}
	}

	/**
	 * Checks if a slideshow is enabled for this specific page or post.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return bool
	 */
	private function slideshow_is_enabled() {

		$post_meta = get_post_meta( get_the_ID(), 'cb_vegas_singular', true );
		$is_slideshow_enabled  = isset( $post_meta['slideshow_enabled'] ) ? $post_meta['slideshow_enabled'] : false;

		if ( $is_slideshow_enabled ) {

			return true;
		} else {

			return false;
		}
	}

	/**
	 * Checks if a global slideshow is set.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return bool
	 */
	private function slideshow_is_global() {

		$slideshows = get_option( 'cb_vegas_options' );

		$index = false;

		if ( is_array( $slideshows ) ) {

			foreach ( (array) $slideshows as $i => $slideshow ) {

				$bool = isset( $slideshow['meta']['slideshow_is_global'] ) ? $slideshow['meta']['slideshow_is_global'] : false;

				if ( false !== $bool ) {

					$index = $i;
				}
			}
		}

		if ( false !== $index ) {

			return true;
		} else {

			return false;
		}
	}

	/**
	 * Checks if a fallback slideshow is enabled.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return bool
	 */
	private function slideshow_is_fallback() {

		$slideshows = get_option( 'cb_vegas_options' );

		$index = false;

		if ( is_array( $slideshows ) ) {

			foreach ( (array) $slideshows as $i => $slideshow ) {

				$bool = isset( $slideshow['meta']['slideshow_is_fallback'] ) ? $slideshow['meta']['slideshow_is_fallback'] : false;

				if ( false !== $bool ) {

					$index = $i;
				}
			}
		}

		if ( false !== $index ) {

			return true;
		} else {

			return false;
		}
	}

	/**
	 * Checks if the defined slideshow still exists.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return bool
	 */
	private function slideshow_is_valid() {

		$uniqids = $this->get_slideshow_uniqids();

		if ( false !== $uniqids ) {

			$post_meta = get_post_meta( get_the_ID(), 'cb_vegas_singular', true );
			$uniqid    = $post_meta['slideshow_uniqid'];

			if ( in_array( $uniqid, $uniqids, true ) ) {

				return true;
			} else {

				return false;
			}
		} else {

			return false;
		}
	}

	/**
	 * Returns a list of all available slideshow unique ids.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array | bool $uniqids | false
	 */
	private function get_slideshow_uniqids() {

		$uniqids = array();
		$options = get_option( 'cb_vegas_options' );

		// Creates an array with all existing slideshiw uniqids.
		if ( is_array( $options ) ) {

			foreach ( (array) $options as $i => $option ) {

				$id = $option['meta']['slideshow_uniqid'];

				if ( null !== $id ) {

					$uniqids[] = $id;
				}
			}

			return $uniqids;
		} else {

			return false;
		}
	}

	/**
	 * Returns the index number of the fallback slideshow.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return int | bool $slideshow_index | false
	 */
	private function get_fallback_slideshow_index() {

		$slideshows = get_option( 'cb_vegas_options' );

		$slideshow_index = false;

		foreach ( (array) $slideshows as $i => $slideshow ) {

			if ( '1' === $slideshow['meta']['slideshow_is_fallback'] ) {

				$slideshow_index = $i;
			}
		}

		if ( false !== $slideshow_index ) {

			return (int) $slideshow_index;
		} else {

			return false;
		}
	}

	/**
	 * Returns the index number of the slideshow.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return mixed | bool $slideshow_index | false
	 */
	private function get_slideshow_index() {

		$meta             = get_post_meta( get_the_ID(), 'cb_vegas_singular', true );
		$slideshow_uniqid = isset( $meta['slideshow_uniqid'] ) ? $meta['slideshow_uniqid'] : false;
		$uniqids          = $this->get_slideshow_uniqids();
		$slideshow_index  = null;

		// Determines the current slideshow index.
		if ( is_array( $uniqids ) && $slideshow_uniqid !== false ) {

			foreach ( $uniqids as $i => $id ) {

				if ( $id === $slideshow_uniqid ) {

					$slideshow_index = $i;
				}
			}

			return (int) $slideshow_index;
		} else {

			return false;
		}
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

}
