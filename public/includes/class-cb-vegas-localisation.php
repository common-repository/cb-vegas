<?php

/**
 * The class responsible for localizing the "Vegas Background Slideshow" configuration file.
 *
 * @link              https://wordpress.org/plugins/cb-vegas/
 * @since             0.1.0
 * @package           CB_Vegas
 * @subpackage        CB_Vegas/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class CB_Vegas_Localisation {

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
	 * Kicks off the localisation of the "Vegas Background Slideshow" configuration file.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  $plugin_name
	 *
	 * @return void
	 */
	public function __construct( $plugin_name, $plugin_domain ) {

		$this->plugin_name   = $plugin_name;
		$this->plugin_domain = $plugin_domain;
	}

	/**
	 * Delivers the configuration data and the overlay url to the "Vegas Background Slideshow" library.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function localize_vegas( $current_slideshow ) {

		wp_localize_script( 'cb-vegas-localisation-js',
			'Vegas',
			array_merge(
				$this->get_localized_configuration( $current_slideshow ),
				$this->get_overlays_url()
			)
		);
	}

	/**
	 * Retrieves the configuration data.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return mixed bool false / array $configuration
	 */
	private function get_configuration( $current_slideshow ) {

		if ( false === get_option( 'cb_vegas_options' ) || true !== is_array( get_option( 'cb_vegas_options' ) ) ) {

			return false;
		} else {

			$slides   = array();
			$settings = array();
			$options  = get_option( 'cb_vegas_options' );

			// Arranges the configuration to serve Vegas in an optimal way.
			foreach ( (array) $options[ $current_slideshow ]['slides'] as $index => $setting ) {

				foreach ( $setting as $key => $value ) {

					if ( $key === 'src' ) {

						// Array containing the list of slides.
						$slides[ $index ] = '{src: ' . site_url() . $value . '}';
						// Array containing the settings per slide stored in the list above.
						$settings[ $index ][ $key ] = $value;
						$slides[ $index ]           = $value;
					} else {

						$settings[ $index ][ $key ] = $value;
					}

					if ( $key === 'delay' ) {
						$alternateKeyForDelay                        = 'slideDelay';
						$settings[ $index ][ $alternateKeyForDelay ] = $value;
						unset( $settings[ $index ][ $key ] );
					}
				}
			}

			// Add the meta data.
			$meta                 = $options[ $current_slideshow ]['meta'];
			$meta['overlay_path'] = $this->get_overlays_url();

			// Assign the previously created arrays to the configuration variable.
			$configuration = array( 'meta' => $meta, 'slides' => $slides, 'settings' => $settings );

			// Removes slides w/o assigned media.
			foreach ( (array) $configuration['settings'] as $index => $slide ) {

				if ( false === $slide['src'] || '' === $slide['src'] ) {

					unset( $configuration['settings'][ $index ], $configuration['slides'][ $index ] );
					//unset( $configuration['slides'][ $index ] );
				}

				// Remove slides that contain our placeholder
				if ( site_url() . '/wp-content/plugins/cb-vegas/admin/images/the-placeholder.jpg' === $configuration['slides'][ $index ] ) {

					unset( $configuration['slides'][ $index ] );
				}
			}

			ksort( $configuration['settings'] );

			return $configuration;
		}
	}

	/**
	 * Retrieves the path to the overlay image.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array $path
	 */
	private function get_overlays_url() {

		$overlay_path = site_url() . '/wp-content/plugins/cb-vegas/vendor/vegas-slideshow/overlays/';

		return array( 'overlay_path' => $overlay_path );
	}

	/**
	 * Helper function, that translates "non-default-locale strings" into strings of the default locale,
	 * to propperly serve the script.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param  $post_meta
	 *
	 * @return array
	 */
	private function get_localized_configuration( $current_slideshow ) {

		$slideshow = $this->get_configuration( $current_slideshow );
		$stored_slideshow = $slideshow['settings'];
		$prepared_slideshow = array();

		foreach ( (array) $stored_slideshow as $i => $settings ) {

			foreach ( (array) $settings as $key => $value ) {

				switch ( $key ) {

					case( $key === 'none' );

						if ( ( null !== $value && $value === 'none' ) || ( null !== $value && $value === __( 'none', $this->plugin_domain ) ) ) {

							$prepared_slideshow[ $i ][ $key ] = 'none';
						} else {

							$prepared_slideshow[ $i ][ $key ] = $value;
						}
						break;

					case( $key === 'animation' || $key === 'transition' );

						if ( ( null !== $value && $value === 'random' ) || ( null !== $value && $value === __( 'random', $this->plugin_domain ) ) ) {

							$prepared_slideshow[ $i ][ $key ] = 'random';
						} else {

							$prepared_slideshow[ $i ][ $key ] = $value;
						}
						break;

					case( $key === 'valign' );

						if ( ( null !== $value && $value === 'top' ) || ( null !== $value && $value === __( 'top', $this->plugin_domain ) ) ) {

							$prepared_slideshow[ $i ][ $key ] = 'top';
						} else if ( ( null !== $value && $value === 'center' ) || ( null !== $value && $value === __( 'center', $this->plugin_domain ) ) ) {

							$prepared_slideshow[ $i ][ $key ] = 'center';
						} else if ( ( null !== $value && $value === 'bottom' ) || ( null !== $value && $value === __( 'bottom', $this->plugin_domain ) ) ) {

							$prepared_slideshow[ $i ][ $key ] = 'bottom';
						} else {

							$prepared_slideshow[ $i ][ $key ] = 'center';
						}
						break;

					case( $key === 'align' );

						if ( ( null !== $value && $value === 'left' ) || ( null !== $value && $value === __( 'left', $this->plugin_domain ) ) ) {

							$prepared_slideshow[ $i ][ $key ] = 'left';
						} else if ( ( null !== $value && $value === 'center' ) || ( null !== $value && $value === __( 'center', $this->plugin_domain ) ) ) {

							$prepared_slideshow[ $i ][ $key ] = 'center';
						} else if ( ( null !== $value && $value === 'right' ) || ( null !== $value && $value === __( 'right', $this->plugin_domain ) ) ) {

							$prepared_slideshow[ $i ][ $key ] = 'right';
						} else {

							$prepared_slideshow[ $i ][ $key ] = 'center';
						}
						break;

					case( $key === 'cover' );

						if ( ( null !== $value && $value === 'yes' ) || ( null !== $value && $value === __( 'yes', $this->plugin_domain ) ) ) {

							$prepared_slideshow[ $i ][ $key ] = true;
						} else if ( ( null !== $value && $value === 'no' ) || ( null !== $value && $value === __( 'no', $this->plugin_domain ) ) ) {

							$prepared_slideshow[ $i ][ $key ] = '';
						} else if ( ( null !== $value && $value === 'repeat' ) || ( null !== $value && $value === __( 'repeat', $this->plugin_domain ) ) ) {

							$prepared_slideshow[ $i ][ $key ] = 'repeat';
						} else {

							$prepared_slideshow[ $i ][ $key ] = $value;
						}
						break;

					case( $i === 'overlayImage' );

						if ( isset( $value ) && $value === __( 'none', $this->plugin_domain ) ) {

							$prepared_slideshow[ $i ][ $key ] = 'none';
						} else {

							$prepared_slideshow[ $i ][ $key ] = $value;
						}
						break;

					case( $i === 'overlayOpacity' );

						if ( isset( $value ) && $value === __( 'default', $this->plugin_domain ) ) {

							$prepared_slideshow[ $i ][ $key ] = 'default';
						} else {

							$prepared_slideshow[ $i ][ $key ] = $value;
						}
						break;

					default:

						$prepared_slideshow[ $i ][ $key ] = $value;
				}
			}
		}

		$newConfig = $slideshow;
		unset( $newConfig['settings'] );
		$newConfig['settings'] = $prepared_slideshow;

		return apply_filters( 'get_localized_configuration', $newConfig );
	}

}
