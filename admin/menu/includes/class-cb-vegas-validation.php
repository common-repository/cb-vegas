<?php

/**
 * The class responsible for sanitizing and validating the user inputs.
 *
 * @link              https://wordpress.org/plugins/cb-vegas/
 * @since             0.1.0
 * @package           CB_Vegas
 * @subpackage        CB_Vegas/admin/menu/includes
 * Author:            demispatti
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class CB_Vegas_Validation {

	/**
	 * The domain of the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    string $plugin_domain
	 */
	protected $plugin_domain;

	/**
	 * The reference to the options class.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    object $vegas_options
	 */
	public $vegas_options;

	/**
	 * The array holding the properties.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array $properties
	 */
	//protected $properties;

	/**
	 * The array holding the indices.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array $indices
	 */
	protected $indices;

	/**
	 * The array holding the meta data of the validated slideshow.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    object $slideshow_meta_data
	 */
	protected $slideshow_meta_data;

	/**
	 * Kicks off the validation class.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  string $plugin_domain
	 * @param  object $vegas_options
	 *
	 * @return void
	 */
	public function __construct( $plugin_domain, $vegas_options ) {

		$this->plugin_domain = $plugin_domain;
		$this->vegas_options = $vegas_options;
	}

	/**
	 * Kicks off sanitisation and validation.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array $input
	 * @param  int $slideshow_index
	 *
	 * @return mixed bool false / array $output
	 */
	public function run( $input, $slideshow_index, $ajax_action = false ) {

		if ( $ajax_action ) {

			switch ( $ajax_action ) {

				case( 'add_slide' == $ajax_action );

					return $input;
					break;

				case( 'sort_slides' == $ajax_action );

					return $input;
					break;

				case( 'remove_slide' == $ajax_action );

					return $input;
					break;

				case( 'remove_slideshow' == $ajax_action );

					return $input;
					break;

				case( 'add_slideshow' == $ajax_action );

					return $input;
					break;
				case( 'duplicate_slide' == $ajax_action );

					return $input;
					break;
				case( 'duplicate_slideshow' == $ajax_action );

					return $input;
					break;

				default:

					return false;
			}
		}

		$is_initial_slideshow = false;
		$initial_meta_data    = null;

		if ( isset( $input['slideshow_uniqid'] ) ) {

			// Store the meta data.
			$this->slideshow_meta_data['slideshow_name']        = isset( $input['slideshow_name'] ) ? $input['slideshow_name'] : false;
			$this->slideshow_meta_data['slideshow_uniqid']      = isset( $input['slideshow_uniqid'] ) ? $input['slideshow_uniqid'] : false;
			$this->slideshow_meta_data['slideshow_is_global']   = isset( $input['slideshow_is_global'] ) ? $input['slideshow_is_global'] : false;
			$this->slideshow_meta_data['slideshow_is_fallback'] = isset( $input['slideshow_is_fallback'] ) ? $input['slideshow_is_fallback'] : false;
			$this->slideshow_meta_data['slideshow_is_autoplay'] = isset( $input['slideshow_is_autoplay'] ) ? $input['slideshow_is_autoplay'] : false;
			$this->slideshow_meta_data['slideshow_is_overlay']  = isset( $input['slideshow_is_overlay'] ) ? $input['slideshow_is_overlay'] : false;
			$this->slideshow_meta_data['slideshow_is_shuffle']  = isset( $input['slideshow_is_shuffle'] ) ? $input['slideshow_is_shuffle'] : false;
			$this->slideshow_meta_data['slideshow_is_timer']    = isset( $input['slideshow_is_timer'] ) ? $input['slideshow_is_timer'] : false;

			// Routines.
			$sanitized = $this->sanitize( $input );
			$this->set_slide_indices( $sanitized, $is_initial_slideshow );
			$updated    = $this->clean_input( $sanitized, $is_initial_slideshow );
			$normalized = $this->normalize_options( $updated, $is_initial_slideshow );
			$valid      = $this->validate( $normalized );
			$output     = $this->compose_options( $valid, $slideshow_index, $is_initial_slideshow, $initial_meta_data );

			return $output;
		} else {
			// Set flag
			$is_initial_slideshow = true;

			// Store the meta data.
			$this->slideshow_meta_data['slideshow_name']        = isset( $input[ $slideshow_index ]['meta']['slideshow_name'] ) ? $input[ $slideshow_index ]['meta']['slideshow_name'] : false;
			$this->slideshow_meta_data['slideshow_uniqid']      = isset( $input[ $slideshow_index ]['meta']['slideshow_uniqid'] ) ? $input[ $slideshow_index ]['meta']['slideshow_uniqid'] : false;
			$this->slideshow_meta_data['slideshow_is_global']   = isset( $input[ $slideshow_index ]['meta']['slideshow_is_global'] ) ? $input[ $slideshow_index ]['meta']['slideshow_is_global'] : false;
			$this->slideshow_meta_data['slideshow_is_fallback'] = isset( $input[ $slideshow_index ]['meta']['slideshow_is_fallback'] ) ? $input[ $slideshow_index ]['meta']['slideshow_is_fallback'] : false;
			$this->slideshow_meta_data['slideshow_is_autoplay'] = isset( $input[ $slideshow_index ]['meta']['slideshow_is_autoplay'] ) ? $input[ $slideshow_index ]['meta']['slideshow_is_autoplay'] : false;
			$this->slideshow_meta_data['slideshow_is_overlay']  = isset( $input[ $slideshow_index ]['meta']['slideshow_is_overlay'] ) ? $input[ $slideshow_index ]['meta']['slideshow_is_overlay'] : false;
			$this->slideshow_meta_data['slideshow_is_shuffle']  = isset( $input[ $slideshow_index ]['meta']['slideshow_is_shuffle'] ) ? $input[ $slideshow_index ]['meta']['slideshow_is_shuffle'] : false;
			$this->slideshow_meta_data['slideshow_is_timer']    = isset( $input[ $slideshow_index ]['meta']['slideshow_is_timer'] ) ? $input[ $slideshow_index ]['meta']['slideshow_is_timer'] : false;
			// Routines
			$valid  = $this->validate( $input );
			$output = $this->compose_options( $valid, $slideshow_index, $is_initial_slideshow, $initial_meta_data );

			return $output;
		}
	}

	/* ------------------------------------------------------------------------ *
	 *Routines
	 * ------------------------------------------------------------------------ */

	/**
	 * Sanitizes the input.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param  array $input
	 *
	 * @return array $sanitized
	 */
	private function sanitize( $input ) {

		$sanitized = array();

		foreach ( $input as $key => $value ) {

			if ( isset ( $input[ $key ] ) ) {
				$sanitized[ $key ] = strip_tags( stripslashes( $value ) );
			}
		}

		return apply_filters( 'sanitize', $sanitized, $input );
	}

	/**
	 * Counts the sets of options aka slides per edited view / tab and sets it.
	 **
	 *
	 * @since  0.1.0
	 * @access protected
	 *
	 * @param  array $sanitized
	 * @param  bool $is_initial_slideshow
	 *
	 * @return void
	 */
	protected function set_slide_indices( $sanitized, $is_initial_slideshow = false ) {

		if ( $is_initial_slideshow ) {

			$this->indices = $this->vegas_options['option_keys'];
		} else {

			$indices = array();

			foreach ( $sanitized as $key => $value ) {

				$pattern = preg_replace( '/_\d{1,2}$/', '', $key );

				if ( array_key_exists( $pattern, $this->vegas_options->option_keys ) ) {

					$key = str_replace( $pattern . '_', '', $key );

					if ( ! in_array( $key, $indices ) ) {

						array_push( $indices, $key );
					}
				}
			}

			$this->indices = $indices;
		}
	}

	/**
	 * Removes meta data entries from the options array.
	 *
	 * @since  0.1.0
	 * @access protected
	 *
	 * @param  array $sanitized
	 * @param  bool $is_initial_slideshow
	 *
	 * @return array $options
	 */
	protected function clean_input( $sanitized, $is_initial_slideshow ) {

		$options = null;

		if ( $is_initial_slideshow ) {

			return apply_filters( 'update_meta', $sanitized );
		} else {

			foreach ( $sanitized as $key => $value ) {

				if ( ! preg_match( '/slideshow_/', $key ) ) {
					$options[ $key ] = $value;
				}
			}

			return apply_filters( 'clean_input', $options );
		}
	}

	/**
	 * Fills "missing" option_keys values with "false" and chunks the array into storeable options.
	 * That way we have complete option sets.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param  array $sanitized
	 * @param        $initial_slideshow
	 *
	 * @return array $normalized
	 */
	private function normalize_options( $sanitized, $initial_slideshow ) {

		if ( ! $initial_slideshow ) {

			$index      = 0;
			$meta       = null;
			$normalized = array();
			$count      = count( $this->indices );

			while ( $index < $count ) {

				foreach ( $this->indices as $index => $slide ) {

					foreach ( $this->vegas_options->option_keys as $option_key => $value ) {

						if ( isset( $sanitized[ $option_key . '_' . $slide ] ) ) {

							$normalized[ $index ][ $option_key ] = $sanitized[ $option_key . '_' . $slide ];
						} else {
							$normalized[ $index ][ $option_key ] = false;
						}
					}
				}
				$index ++;
			}

			return apply_filters( 'normalize_options', $normalized, $sanitized );
		} else {
			$output[0] = $sanitized;

			return apply_filters( 'normalize_options', $output );
		}
	}

	/**
	 * Validates the input.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param  array $normalized
	 *
	 * @return array $valid
	 */
	private function validate( $normalized ) {

		$errors = null;
		$valid  = array();
		$value  = null;

		foreach ( $normalized as $index => $vegas_options ) {

			foreach ( $vegas_options as $key => $value ) {

				if ( isset( $key ) ) {

					switch ( $key ) {

						case( $key === 'color' );

							if ( isset( $value ) && $value !== false && $value !== '' ) {

								if ( ! preg_match( '/^#[a-f0-9]{3,6}$/i', $value ) ) {

									$value = '';

									$errors[ $index ][ $key ] = array(
										'name'         => $this->vegas_options->the_options[ $key ]['title'],
										'notice_level' => $this->vegas_options->the_options[ $key ]['notice_level'],
										'target'       => $key . '_' . $index,
										'message'      => $this->color_error_message(),
									);
								}
							}
							break;

						case( $key === 'delay' );

							if ( isset( $value ) ) {

								if ( ! ctype_digit( $value ) ) {

									$value = $this->vegas_options->the_options[ $key ]['value'];

									$errors[ $index ][ $key ] = array(
										'name'         => $this->vegas_options->the_options[ $key ]['title'],
										'notice_level' => $this->vegas_options->the_options[ $key ]['notice_level'],
										'target'       => $key . '_' . $index,
										'message'      => $this->delay_error_message(),
									);
								}
							}
							break;

						case( $key === 'transitionDuration' );

							if ( isset( $value ) && $value !== 'auto' ) {

								if ( ! ctype_digit( $value ) ) {

									$value = $this->vegas_options->the_options[ $key ]['value'];

									$errors[ $index ][ $key ] = array(
										'name'         => $this->vegas_options->the_options[ $key ]['title'],
										'notice_level' => $this->vegas_options->the_options[ $key ]['notice_level'],
										'target'       => $key . '_' . $index,
										'message'      => $this->transition_duration_error_message(),
									);
								}
							}
							break;

						case( $key === 'animationDuration' );

							if ( isset( $value ) && $value !== 'auto' ) {

								if ( ! ctype_digit( $value ) ) {

									$value                    = $this->vegas_options->the_options[ $key ]['value'];
									$errors[ $index ][ $key ] = array(
										'name'         => $this->vegas_options->the_options[ $key ]['title'],
										'notice_level' => $this->vegas_options->the_options[ $key ]['notice_level'],
										'target'       => $key . '_' . $index,
										'message'      => $this->animation_duration_error_message(),
									);
								}
							}
							break;
					}
					$valid[ $index ][ $key ] = $value;
				} else {
					// Sets unset keys (null) to false;
					$valid[ $index ][ $key ] = false;
				}
			}
		}

		if ( is_array( $errors ) ) {

			set_transient( 'cb_vegas_validation_transient', $errors, 1800 );
		}
		// Translate values if necessary.
		if ( get_locale() !== 'en_US' ) {

			$valid = $this->translate( $valid );
		}

		return apply_filters( 'validate', $valid, $normalized );
	}

	/**
	 * Composes the options by adding the meta data to the previously validated slideshow.
	 *
	 * @since  0.1.0
	 * @access protected
	 *
	 * @param  array $valid
	 * @param  int $slideshow_index
	 * @param  bool $is_initial_slideshow
	 * @param  mixed $initial_meta_data
	 *
	 * @return array $current_options
	 */
	private function compose_options( $valid, $slideshow_index, $is_initial_slideshow = false, $initial_meta_data = null ) {

		$current_options = null;

		if ( $is_initial_slideshow ) {

			$current_options = $valid;

			return apply_filters( 'compose_options', $current_options );
		} else {
			$current_options = get_option( 'cb_vegas_options' )/*get_transient('cb_vegas_current_options_transient')*/
			;

			$current_options[ $slideshow_index ]['meta']   = $this->slideshow_meta_data;
			$current_options[ $slideshow_index ]['slides'] = $valid;

			return apply_filters( 'compose_options', $current_options );
		}
	}

	/**
	 * Merges the input with the existing array of post types (aka options)
	 *
	 * since  0.1.0
	 * @uses   get_default_post_type()
	 * @see    admin/menu/includes/class-admin-menu-include-options.php
	 *
	 * @param  array $input
	 *
	 * @return array $output
	 */
	private function merge_options( $valid, $requested_options_index ) {

		// If there are stored post types...
		if ( false != get_option( 'cb_vegas_options' ) ) {

			$post_types = get_option( 'cb_vegas_options' )/*$this->options->get_stored_post_type_options()*/
			;

			// If a stored post type is being updated...
			if ( isset( $post_types[ $requested_options_index ] ) ) {

				unset( $post_types[ $requested_options_index ] );

				$post_types[ $requested_options_index ] = (object) $valid;
				// ...else append a new one.
			} else {

				$post_types[ $requested_options_index ] = (object) $valid;
			}

			ksort( $post_types );

			return $post_types;
			// ...else we create the first one.
		} else {

			$post_types[0] = (object) $valid;

			return $post_types;
		}
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since   0.1.0
	 * @access  public
	 * @return \WP_Error
	 */
	public function color_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a hexadecimal color value. It was reset. To customize it, please input a color value like '#fff' or '#0073AA'.", $this->plugin_domain ) );
	}

	/* ------------------------------------------------------------------------ *
	 * Error Messages
	 * ------------------------------------------------------------------------ */

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return \WP_Error
	 */
	public function animation_duration_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero).", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return \WP_Error
	 */
	public function delay_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer but must not be 0 (zero). To aviod unwanted behaviour, the delay was reset to its default.", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return \WP_Error
	 */
	public function transition_duration_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero).", $this->plugin_domain ) );
	}

	/* ------------------------------------------------------------------------ *
	 * Helper functions
	 * ------------------------------------------------------------------------ */
	/**
	 * Helper function, that translates "non-default-locale strings" into strings of the default locale.
	 * This task is necessary, since Vegas needs some strings as parameters and they have to be served in English.
	 * With this step, we ensure that the translation functionality remains fully functional.
	 *
	 * @since  0.1.0
	 * @access protected
	 *
	 * @param  $valid
	 *
	 * @return array $output
	 */
	protected function translate( $valid ) {

		$output = array();

		foreach ( $valid as $index => $vegas_options ) {

			foreach ( $vegas_options as $key => $value ) {

				switch ( $key ) {

					case( $key === 'transition' );

						if ( isset( $value ) && $value === __( 'random', $this->plugin_domain ) ) {

							$output[ $index ][ $key ] = $this->vegas_options->transitions['random'];
						} else {
							$output[ $index ][ $key ] = $value;
						}
						break;

					case( $key === 'align' );

						if ( isset( $value ) && $value === __( 'center', $this->plugin_domain ) ) {

							$output[ $index ][ $key ] = $this->vegas_options->align['center'];
						} else if ( isset( $value ) && $value === __( 'right', $this->plugin_domain ) ) {

							$output[ $index ][ $key ] = $this->vegas_options->align['right'];
						} else if ( isset( $value ) && $value === __( 'left', $this->plugin_domain ) ) {

							$output[ $index ][ $key ] = $this->vegas_options->align['left'];
						} else {
							$output[ $index ][ $key ] = $value;
						}
						break;

					case( $key === 'valign' );

						if ( isset( $value ) && $value === __( 'center', $this->plugin_domain ) ) {

							$output[ $index ][ $key ] = $this->vegas_options->valign['center'];
						} else if ( isset( $value ) && $value === __( 'top', $this->plugin_domain ) ) {

							$output[ $index ][ $key ] = $this->vegas_options->valign['top'];
						} else if ( isset( $value ) && $value === __( 'bottom', $this->plugin_domain ) ) {

							$output[ $index ][ $key ] = $this->vegas_options->valign['bottom'];
						} else {
							$output[ $index ][ $key ] = $value;
						}
						break;

					case( $key === 'animation' );

						if ( isset( $value ) && $value === __( 'random', $this->plugin_domain ) ) {

							$output[ $index ][ $key ] = $this->vegas_options->animations['random'];
						} else {
							$output[ $index ][ $key ] = $value;
						}
						break;

					default:

						$output[ $index ][ $key ] = $value;
				}
			}
		}

		return apply_filters( 'translate', $output, $valid );
	}

	/**
	 * Returns an array containing the meta data of the validated slideshow.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return array $slideshow_meta_data
	 */
	protected function get_meta_data() {

		return $this->slideshow_meta_data;
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
