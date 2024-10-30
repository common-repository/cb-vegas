<?php

/**
 * The class responsible for the ajax functionality.
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
class CB_Vegas_Ajax {

	/**
	 * The domain of the plugin.
	 *
	 * @since    0.1.0
	 * @access   public
	 * @var string $plugin_domain
	 */
	public $plugin_domain;

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
	 * Kicks off the ajax functionality.
	 *
	 * @param $plugin_domain
	 * @param $vegas_options
	 * @param $settings
	 * @param $settings_factory
	 */
	public function __construct( $plugin_domain, $vegas_options, $settings, $settings_factory ) {

		$this->plugin_domain    = $plugin_domain;
		$this->vegas_options    = $vegas_options;
		$this->settings         = $settings;
		$this->settings_factory = $settings_factory;
	}

	/**
	 * Adds a new slide.
	 *
	 * @param $slideshow_index
	 * @param $new_slide_index
	 *
	 * @return bool|string $html
	 */
	public function add_the_slide( $slideshow_index, $new_slide_index ) {

		//$current_menu_order = get_transient( 'cb_vegas_current_menu_order' );

		$current_slideshows = get_option( 'cb_vegas_options' );
		$html               = '';
		// Registers the new settings fields with WordPress.
		$this->settings->add_settings_fields( $slideshow_index, $new_slide_index );
		// Creates the settings fields for all the settings that make up a slide.
		foreach ( $this->vegas_options->the_options as $setting => $args ) {
			// Assigns the slideshow_index to the array of arguments so the function assigns the new slide to the current slideshow.
			$args['slideshow_index'] = $slideshow_index;
			// Creates the settings fields.
			$html .= $this->settings_factory->create_settings_fields( $args, $new_slide_index );
		}
		// Retrieves the default options to populate the new slide with initial values.
		$the_new_slide = $this->settings_factory->create_slide_template( $new_slide_index );
		// Assigns the new slide to it's array.
		$current_slideshows[ $slideshow_index ]['slides'][ $new_slide_index ] = $the_new_slide['slides'][ $new_slide_index ];

		//delete_transient( 'cb_vegas_current_menu_order' );
		//set_transient( 'cb_vegas_current_menu_order', $new_order, 3600 );

		if ( true === update_option( 'cb_vegas_options', $current_slideshows ) ) {

			return $html;
		} else {

			return false;
		}
	}

	/**
	 * Adds a new slideshow.
	 *
	 * @param $new_slideshow_index
	 *
	 * @return bool|string $html
	 */
	public function add_the_slideshow( $new_slideshow_index ) {

		if ( false !== $new_slideshow_index ) {

			$current_slideshows = get_option( 'cb_vegas_options' );
			// Retrieves the options for a complete slideshow with one slide.
			$new_slideshow = $this->settings_factory->create_slideshow( $new_slideshow_index );
			// Adds the new slideshow to the array of slideshows.
			array_push( $current_slideshows, $new_slideshow );
			// Extracts the name of the new slideshow.
			$slideshow_name = $new_slideshow['meta']['slideshow_name'];
			// Creates a new navigation tab for the slideshow and assigns its name to it.
			$html = $this->settings_factory->create_navigation_tab( $new_slideshow_index, $slideshow_name );

			if ( true === update_option( 'cb_vegas_options', $current_slideshows ) ) {

				return $html;
			} else {

				return false;
			}
		} else {

			return false;
		}
	}

	/**
	 * Duplicates the slide.
	 *
	 * @param $slideshow_index
	 * @param $slide_index
	 *
	 * @return bool|string $html
	 */
	public function duplicate_the_slide( $slideshow_index, $slide_index ) {

		$current_slideshows = get_option( 'cb_vegas_options' );
		$duplicated_slide   = $current_slideshows[ (int) $slideshow_index ]['slides'][ (int) $slide_index ];
		$new_slide_index    = count( $current_slideshows[ (int) $slideshow_index ]['slides'] );
		$html               = '';

		// Registers the new settings fields with WordPress.
		$this->settings->add_settings_fields( $slideshow_index, $new_slide_index );

		// Creates the settings fields for all the settings that make up a slide.
		foreach ( $this->vegas_options->the_options as $setting => $args ) {
			// Assigns the slideshow_index to the array of arguments so the function assigns the new slide to the current slideshow.
			$args['slideshow_index'] = $slideshow_index;
			$value                   = $current_slideshows[ $slideshow_index ]['slides'][ $slide_index ][ $setting ];
			$args['value']           = $value;

			$html .= $this->settings_factory->create_settings_fields( $args, $new_slide_index );
		}

		$current_slideshows[ $slideshow_index ]['slides'][ $new_slide_index ] = $duplicated_slide;

		if ( true === update_option( 'cb_vegas_options', $current_slideshows ) ) {

			return $html;
		} else {

			return false;
		}
	}

	/**
	 * Duplicates the slideshow.
	 *
	 * @param $slideshow_index
	 *
	 * @return bool|string $html
	 */
	public function duplicate_the_slideshow( $slideshow_index ) {

		if ( false !== $slideshow_index ) {

			$uniqid = uniqid( '', false );

			$current_slideshows = get_option( 'cb_vegas_options' );

			$copy                                  = $current_slideshows[ $slideshow_index ];
			$copy['meta']['slideshow_uniqid']      = $uniqid;
			$copy['meta']['slideshow_name']        .= ' ( Copy )';
			$copy['meta']['slideshow_is_global']   = false;
			$copy['meta']['slideshow_is_fallback'] = false;

			$slideshow_name                             = $copy['meta']['slideshow_name'];
			$new_slideshow_index                        = count( $current_slideshows );
			$current_slideshows[ $new_slideshow_index ] = $copy;

			$html = $this->settings_factory->create_navigation_tab( $new_slideshow_index, $slideshow_name );

			if ( true === update_option( 'cb_vegas_options', $current_slideshows ) ) {

				return $html;
			} else {

				return false;
			}
		} else {

			return false;
		}
	}

	/**
	 * Rearranges the sldeshwows.
	 *
	 * @param $indices
	 * @param $current_slideshow_index
	 *
	 * @return bool
	 */
	public function sort_the_slideshows( $indices, $current_slideshow_index ) {

		$current_slideshows    = get_option( 'cb_vegas_options' );
		$list                  = array();
		$rearranged_slideshows = array();

		// Removes the substring so we have a list (an array) of old old_slides_order as "old_slides_order" containing the list of new old_slides_order as "values".
		$pattern = '/^cb-vegas-navigation-tab_/';
		foreach ( $indices as $new => $old ) {

			if ( preg_match( $pattern, $old ) ) {

				$old_index_number = preg_replace( $pattern, '', $old );

				$list[ $new ] = $old_index_number;
			}
		}

		// Rearranges the slideshows.
		foreach ( $list as $new_index => $old_index ) {

			$rearranged_slideshows[ $new_index ] = $current_slideshows[ $old_index ];
		}

		if ( true === update_option( 'cb_vegas_options', $rearranged_slideshows ) ) {

			return true;
		} else {

			return false;
		}
	}

	/**
	 * Rearranges the slides.
	 *
	 * @param int $slidshow_index
	 * @param array $old_slides_order
	 * @param array $new_slides_order
	 *
	 * @return bool
	 */
	public function sort_the_slides( $slidshow_index, $old_slides_order, $new_slides_order ) {

		$current_menu_order = get_transient( 'cb_vegas_current_menu_order' );
		$count              = 0;
		$list               = array();
		$new_order          = array();

		// Rearranges the post types.
		$current_slideshows = get_option( 'cb_vegas_options' );
		$rearranged_slides  = array();

		// Filters the old_slides_order and removes the pattern.
		$pattern = '/^cb-vegas-single-slide_/';
		foreach ( $old_slides_order as $old_slideshow_index => $new_slideshow_index ) {

			if ( ! preg_match( $pattern, $new_slideshow_index ) ) {

				unset( $old_slideshow_index );
			} else {

				$new_index = preg_replace( $pattern, '', $new_slideshow_index );

				$list[ $count ] = $new_index;

				$count ++;
			}
		}

		foreach ( $new_slides_order as $old_index => $name ) {

			$new_index = 0;
			foreach ( $current_menu_order as $new_index => $same_name ) {

				if ( $name == $same_name ) {

					$new_order[]                     = $name;
					$list[ $name ]                   = $new_index;
					$rearranged_slides[ $old_index ] = $current_slideshows[ $slidshow_index ]['slides'][ $new_index ];
				}
			}
		}

		unset( $current_slideshows[ $slidshow_index ]['slides'] );
		$current_slideshows[ $slidshow_index ]['slides'] = $rearranged_slides;

		delete_transient( 'cb_vegas_current_menu_order' );
		set_transient( 'cb_vegas_current_menu_order', $new_order, 3600 );

		$result = update_option( 'cb_vegas_options', $current_slideshows );

		if ( false != $result ) {

			return true;
		} else {

			return false;
		}
	}

	/**
	 * Removes the slideshow.
	 *
	 * @param $deprecated_slideshow_index
	 *
	 * @return bool
	 */
	public function remove_the_slideshow( $deprecated_slideshow_index ) {

		$current_slideshows = get_option( 'cb_vegas_options' );

		unset( $current_slideshows[ $deprecated_slideshow_index ] );
		sort( $current_slideshows );
		if ( true === update_option( 'cb_vegas_options', $current_slideshows ) ) {

			return true;
		} else {

			return false;
		}
	}

	/**
	 * Removes the slide.
	 *
	 * @param $slideshow_index
	 * @param $slide_index
	 *
	 * @return bool
	 */
	public function remove_the_slide( $slideshow_index, $slide_index ) {

		$current_slideshows = get_option( 'cb_vegas_options' );
		// Unsets the deprecated slide.
		unset( $current_slideshows[ $slideshow_index ]['slides'][ $slide_index ] );
		// Sorts the slides.
		sort( $current_slideshows[ $slideshow_index ]['slides'] );

		if ( true === update_option( 'cb_vegas_options', $current_slideshows ) ) {

			return true;
		} else {

			return false;
		}
	}

	/**
	 * Returns the domain of the plugin.
	 *
	 * @return string $plugin_domain
	 */
	public function get_plugin_domain() {

		return $this->plugin_domain;
	}

	/**
	 * Returns the reference to the class responsible for handling the settings.
	 *
	 * @return object $settings
	 */
	public function get_settings() {

		return $this->settings;
	}

}
