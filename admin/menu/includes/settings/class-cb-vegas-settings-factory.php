<?php

/**
 * The class responsible for creating the settings for the settings page.
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
class CB_Vegas_Settings_Factory {

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
	 * @access   public
	 * @var object $settings
	 */
	public $settings;

	/**
	 * The reference to the options class.
	 *
	 * @since    0.1.0
	 * @access   public
	 * @var object $vegas_options
	 */
	public $vegas_options;

	/**
	 * Kicks off the settings factory.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $plugin_domain
	 * @param $vegas_options
	 * @param $settings
	 *
	 * @return void
	 */
	public function __construct( $plugin_domain, $vegas_options, $settings ) {

		$this->plugin_domain = $plugin_domain;
		$this->vegas_options = $vegas_options;
		$this->settings      = $settings;
	}

	/**
	 * Creates and returns the settings fields.
	 * @todo   : refactor this into something more elegant...
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array $args
	 * @param  int | bool $new_slideshow_index
	 *
	 * @return string
	 */
	public function create_settings_fields( $args, $new_slideshow_index = false ) {

		// Determines which slide_index to add to the array of arguments.
		if ( false === $new_slideshow_index ) {

			$slide_index = $args['slide_index'];
		} else {

			$slide_index = $new_slideshow_index;
		}
		$args['slide_index'] = $slide_index;

		$option_key = $args['option_key'];

		switch ( $option_key ) {

			case( $option_key === 'src' );

				$html = $this->create_slide_wrap_start( $slide_index ); // Slide wrap start.

				$html .= $this->create_settings_block_wrap_start( 'one' ); // Block one start.

				$html .= $this->create_media_field( $args, $new_slideshow_index );

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key === 'color' );

				$html = $this->create_color_picker_field( $args, $new_slideshow_index );

				$html .= $this->create_wrap_end();

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key === 'delay' );

				$html = $this->create_settings_block_wrap_start( 'two' );

				$html .= $this->create_text_field( $args, $new_slideshow_index );

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key === 'preload' );

				$html = $this->create_checkbox_field( $args, $new_slideshow_index );

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key === 'preloadImage' );

				$html = $this->create_checkbox_field( $args, $new_slideshow_index );

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key === 'preloadVideo' );

				$html = $this->create_checkbox_field( $args, $new_slideshow_index );

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key == 'transition' );

				$html = $this->create_select_field( $args, $new_slideshow_index );

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key == 'transitionDuration' );

				$html = $this->create_text_field( $args, $new_slideshow_index );

				$html .= $this->create_wrap_end();

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key == 'align' );

				$html = $this->create_settings_block_wrap_start( 'three' );

				$html .= $this->create_select_field( $args, $new_slideshow_index );

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key === 'timer' );

				$html = $this->create_checkbox_field( $args, $new_slideshow_index );

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key == 'cover' );

				$html = $this->create_select_field( $args, $new_slideshow_index );

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key == 'animation' );

				$html = $this->create_select_field( $args, $new_slideshow_index );

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key == 'animationDuration' );

				$html = $this->create_text_field( $args, $new_slideshow_index );

				return $this->return_html( $html, $new_slideshow_index );
				break;

			case( $option_key == 'valign' );

				$html = $this->create_select_field( $args, $new_slideshow_index );

				$html .= $this->create_wrap_end();

				$html .= $this->create_settings_block_wrap_start( 'four' );

				$html .= $this->create_slide_sortable_handle( $slide_index );
				$html .= $this->create_duplicate_slide_button( $slide_index );
				$html .= $this->create_remove_slide_button();

				$html .= $this->create_wrap_end(); // # Block five wrap.

				$html .= $this->create_wrap_end(); // # Slide wrap.

				return $this->return_html( $html, $new_slideshow_index );
				break;

			/*return $this->return_html($html, $new_slideshow_index);
			break;*/

			case( $option_key === 'overlay' );

				//$html = $this->create_select_field($args, $new_slideshow_index);

				/*$html = $this->create_wrap_end();

				$html .= $this->create_settings_block_wrap_start('four');

				$html .= $this->create_slide_sortable_handle($slide_index);
				$html .= $this->create_duplicate_slide_button($slide_index);
				$html .= $this->create_remove_slide_button();

				$html .= $this->create_wrap_end(); // # Block five wrap.

				$html .= $this->create_wrap_end(); // # Slide wrap.

				return $this->return_html($html, $new_slideshow_index);
				break;*/
		}
	}

	/**
	 * Creates and echoes the navigation.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  int $slideshow_index
	 *
	 * @return echo
	 */
	public function create_navigation( $slideshow_index ) {

		$slideshows = get_option( 'cb_vegas_options' );

		if ( false != $slideshows ) {

			foreach ( $slideshows as $i => $slideshow ) {

				$slideshow_name = isset( $slideshow['meta']['slideshow_name'] ) ? $slideshow['meta']['slideshow_name'] : '';

				if ( $slideshow_index == $i ) {

					$active_tab = true;
				} else {

					$active_tab = false;
				}

				echo $this->create_navigation_tab( $i, $slideshow_name, $active_tab );
			}
		}
	}

	/**
	 * Creates a navigation tab.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param int $slideshow_index
	 * @param string $slideshow_name
	 * @param string $active_tab
	 *
	 * @return string
	 */
	public function create_navigation_tab( $slideshow_index, $slideshow_name, $active_tab = false ) {

		$id = "cb_vegas-navigation-tab_" . $slideshow_index;

		if ( $active_tab ) {

			$active = 'active-tab';
		} else {

			$active = '';
		}

		$html = '<a id="' . $id . '" href="?page=cb_vegas_settings_page&tab=' . $slideshow_index . '"class="nav-tab ui-sortable-handle ' . $active . '" data-slideshow-index="' . $slideshow_index . '" data-slideshow-name="' . $slideshow_name . '">';
		$html .= $slideshow_name;
		$html .= '</a>';

		return $html;
	}

	/**
	 * Returns the opening tag for the fieldset wrapper.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  string $slide_index
	 *
	 * @return string $html
	 */
	public function create_slide_wrap_start( $slide_index ) {

		$uniqid = uniqid();

		$html = '<div id="cb-vegas-single-slide_' . $slide_index . '" class="ui-state-default cb-vegas-single-slide ui-sortable-handle" data-slide-index="' . $slide_index . '" data-uniqid="' . $uniqid . '">';

		if ( false != get_transient( 'cb_vegas_current_menu_order' ) ) {

			$menuorder = get_transient( 'cb_vegas_current_menu_order' );
		}
		$menuorder[ $slide_index ] = $uniqid;

		delete_transient( 'cb_vegas_current_menu_order' );
		set_transient( 'cb_vegas_current_menu_order', $menuorder, 3600 );

		return $html;
	}

	/**
	 *  Creates the opening tag for the "settings block wrap".
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $settings_section_index
	 *
	 * @return bool|string
	 */
	public function create_settings_block_wrap_start( $settings_section_index ) {

		if ( $settings_section_index === 'one' ) {

			return '<div class="cb-vegas-settings-block-one-container">';
		} else if ( $settings_section_index === 'two' ) {

			return '<div class="cb-vegas-settings-block-two-container">';
		} else if ( $settings_section_index === 'three' ) {

			return '<div class="cb-vegas-settings-block-three-container">';
		} else if ( $settings_section_index === 'four' ) {

			return '<div class="cb-vegas-settings-block-four-container">';
		} else if ( $settings_section_index === 'five' ) {

			return '<div class="cb-vegas-settings-block-five-container">';
		} else {

			return false;
		}
	}

	/**
	 * Renders a settings field with a media uploader.
	 *
	 * @since  0.1.0
	 * @access public
	 * @uses   translate_to_custom_locale()
	 *
	 * @param  array $args
	 * @param  string $new_slideshow
	 *
	 * @return string $html
	 */
	public function create_media_field( $args, $new_slideshow = false ) {

		$tab        = $args['slideshow_index'];
		$option_key = $args['option_key'];
		$slide      = $args['slide_index'];
		$value      = isset( $args['value'] ) && $args['value'] != '' ? $args['value'] : $this->fetch_value( $tab, $slide, $option_key, $new_slideshow );
		$slide      = $this->fetch_slide_index( $slide, $new_slideshow );

		$html = $this->create_before_content();

		// Teh Media.
		$html .= '<div class="cb-vegas-media-container">';
		$html .= ( '' !== $value ) ? '<img class="cb-vegas-media" src="' . esc_url( $value ) . '" data-slide-index="' . $slide . '"/>' : '<img class="cb-vegas-media"  data-slide-index="' . $slide . '"/>';

		if ( $args['hidden'] ) {

			$html .= '<input type="hidden" id="' . $option_key . '_' . $slide . '" name="' . 'cb_vegas_options' . '[' . $option_key . '_' . $slide . ']' . '" class="cb-vegas-media-hidden" value="' . esc_url( $value ) . '" style="display: none;"  data-slide-index="' . $slide . '" />';
		}

		$html .= '</div>';

		$html .= $this->create_after_content();

		return $html;
	}

	/**
	 * Creates the wrapper after the option.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return string $html
	 */
	public function create_after_content() {

		// # Settings Container
		$html = '</div>';

		return $html;
	}

	/**
	 * Renders a settings field with a color picker.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array $args
	 * @param  string $new_slideshow
	 *
	 * @return string $html
	 */
	public function create_color_picker_field( $args, $new_slideshow = false ) {

		$tab         = $args['slideshow_index'];
		$option_key  = $args['option_key'];
		$title       = $args['title'];
		$slide_index = $args['slide_index'];
		$value       = isset( $args['value'] ) && $args['value'] != '' ? $args['value'] : $this->fetch_value( $tab, $slide_index, $option_key, $new_slideshow );
		$slide_index = $this->fetch_slide_index( $slide_index, $new_slideshow );

		$html = $this->create_before_content();

		$html .= '<div class="cb-vegas-color-picker-container">';
		// Label
		$html .= $this->create_label( $option_key, $slide_index, $title );
		// Input
		$html .= '<div class="cb-vegas-input-container cb-vegas-color-picker">';
		$html .= '<input type="text"  id="' . $option_key . '_' . $slide_index . '" name="' . 'cb_vegas_options' . '[' . $option_key . '_' . $slide_index . ']' . '" value="' . $value . '" class="' . $option_key . '[' . $slide_index . ']' . ' cb-vegas-color-picker" />';
		$html .= '</div>';// end .cb-vegas-input-container
		$html .= '</div>';// end .cb-vegas-color-picker-container

		$html .= $this->create_after_content();

		$html .= $this->create_add_media_button( $slide_index );

		return $html;
	}

	/**
	 * Creates the wrapper before the option.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  string $option_key
	 * @param  int $slide_index
	 * @param  string $title
	 *
	 * @return string $html
	 */
	public function create_before_content() {

		// Settings Container
		$html = '<div class="cb-vegas-single-settings-container">';

		return $html;
	}

	/**
	 * Creates the labels for the settings fields.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $option_key
	 * @param $slide_index
	 * @param $title
	 *
	 * @return string
	 */
	public function create_label( $option_key, $slide_index, $title ) {

		$html = '<div class="cb-vegas-label-container">';
		$html .= '<label class="label-for-cb-vegas-switch" for="' . $option_key . '_' . $slide_index . '">&nbsp;' . $title . '</label>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Returns the closing tab for the fieldset wrapper.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return string
	 */
	public function create_wrap_end() {

		return '</div>';
	}

	/**
	 * Renders a settings field with a text field.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array $args
	 * @param  string $new_slideshow
	 *
	 * @return string $html
	 */
	public function create_text_field( $args, $new_slideshow = false ) {

		//$this->fetch_options($args, $new_slideshow);
		$tab         = $args['slideshow_index'];
		$option_key  = $args['option_key'];
		$title       = $args['title'];
		$slide_index = $args['slide_index'];
		$value       = isset( $args['value'] ) && $args['value'] != '' ? $args['value'] : $this->fetch_value( $tab, $slide_index, $option_key, $new_slideshow );
		$slide_index = $this->fetch_slide_index( $slide_index, $new_slideshow );
		$placeholder = $value;

		$html = $this->create_before_content();

		// Label
		$html .= $this->create_label( $option_key, $slide_index, $title );

		// Input
		$html .= '<div class="cb-vegas-input-container">';
		$html .= '<input type="text" id="' . $option_key . '_' . $slide_index . '" name="cb_vegas_options[' . $option_key . '_' . $slide_index . ']" Placeholder="' . $placeholder . '" value="' . $value . '" />';
		$html .= '</div>';

		$html .= $this->create_after_content();

		return $html;
	}

	/**
	 * Renders a settings field with a checkbox.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array $args
	 * @param  string $new_slideshow
	 *
	 * @return string $html
	 */
	public function create_checkbox_field( $args, $new_slideshow = false ) {

		$tab         = $args['slideshow_index'];
		$slide_index = $args['slide_index'];
		$option_key  = $args['option_key'];
		$title       = $args['title'];
		$value       = isset( $args['value'] ) && $args['value'] != '' ? $args['value'] : $this->fetch_value( $tab, $slide_index, $option_key, $new_slideshow );
		$slide_index = $this->fetch_slide_index( $slide_index, $new_slideshow );

		$html = $this->create_before_content();

		// Label
		$html .= $this->create_label( $option_key, $slide_index, $title );

		// Input
		$html .= '<div class="cb-vegas-input-container">';
		$html .= '<label class="cbv-switch label-for-cbv-switch" title="' . $title . '">';
		$html .= '<input type="checkbox" id="' . $option_key . '_' . $slide_index . '" class="cbv-switch-input cbv-input-checkbox" name="cb_vegas_options[' . $option_key . '_' . $slide_index . ']" value="1" ' . checked( 1, isset( $value ) ? $value : 0, false ) . '/>';
		$html .= '<span class="cbv-switch-label" data-on="On" data-off="Off"></span>';
		$html .= '<span class="cbv-switch-handle"></span>';
		$html .= '</label>';
		$html .= '</div>';

		$html .= $this->create_after_content();

		return $html;
	}

	/**
	 * Renders a settings field with a select dropdown.
	 *
	 * @uses   translate_to_custom_locale()
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array $args
	 * @param  string $new_slideshow
	 *
	 * @return string $html
	 */
	public function create_select_field( $args, $new_slideshow = false ) {

		$slideshow_index = $args['slideshow_index'];
		$option_key      = $args['option_key'];
		$title           = $args['title'];
		$slide_index     = $args['slide_index'];
		$value           = isset( $args['value'] ) && $args['value'] != '' ? $args['value'] : $this->fetch_value( $slideshow_index, $slide_index, $option_key, $new_slideshow );
		$slide_index     = $this->fetch_slide_index( $slide_index, $new_slideshow );
		$select_values   = $this->get_select_values( $option_key );

		// Continue with translated values if necessary.
		if ( get_locale() !== 'en_US' ) {

			$select_values = $this->translate_to_custom_locale( $select_values );
		}

		$html = $this->create_before_content();
		// Label
		$html .= $this->create_label( $option_key, $slide_index, $title );
		// Input
		$html .= '<div class="cb-vegas-input-container">';
		$html .= '<select name="cb_vegas_options[' . $option_key . '_' . $slide_index . ']" class="floating-element fancy-select cb-vegas-fancy-select" id="' . $option_key . '_' . $slide_index . '">';
		foreach ( $select_values as $key => $select_value ) {

			$html .= '<option value="' . $select_value . '"' . selected( $value, $select_value, false ) . '>' . $select_value . '</option>';
		}

		$html .= '</select>';
		$html .= '</div>';

		$html .= $this->create_after_content();

		return $html;
	}

	/**
	 * Creates a handle to sort the slides.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $slide_index
	 *
	 * @return string
	 */
	public function create_slide_sortable_handle( $slide_index ) {

		// Handle
		$html = '<div class="cb-vegas-button cb-vegas-slide-handle dashicons dashicons-sort" title="' . __( 'Sort Slides', $this->plugin_domain ) . '" data-slide-index="' . $slide_index . '"></div>';

		return $html;
	}

	/**
	 * Renders the "remove slide" button.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @access public
	 * @return string $html
	 */
	public function create_remove_slide_button() {

		$nonce = wp_create_nonce( 'cb_vegas_remove_slide_nonce' );

		// Input
		$html = '<div class="cb-vegas-button cb-vegas-remove-slide-button dashicons dashicons-trash" data-nonce="' . $nonce . '" title="' . __( 'Remove Slide', $this->plugin_domain ) . '" ></div>';

		return $html;
	}

	/**
	 * Echoes a hidden field for the slideshow index.
	 * @todo   : Implement a sortable for the slideshows or remove this and all of the functions related to the "slideshow sorting functionality".
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $slideshow_index
	 *
	 * @return echo
	 */
	public function create_slideshow_index_hidden_field( $slideshow_index ) {

		$nonce = wp_create_nonce( 'cb_vegas_slideshow_index_nonce' );

		$html = '<input type="hidden" name="cb_vegas_options[slideshow_index]" id="current_slideshow_index" data-nonce="' . $nonce . '" value="' . $slideshow_index . '" />';

		echo $html;
	}

	/**
	 * Echoes a hidden field for the current slideshow's uniqid.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $slideshow_index
	 *
	 * @return echo
	 */
	public function create_slideshow_uniqid_hidden_field( $slideshow_index ) {

		$slideshows = get_option( 'cb_vegas_options' );
		$uniqid     = isset( $slideshows[ $slideshow_index ]['meta']['slideshow_uniqid'] ) ? $slideshows[ $slideshow_index ]['meta']['slideshow_uniqid'] : '';

		$nonce = wp_create_nonce( 'cb_vegas_slideshow_uniqid_nonce' );

		$html = '<input type="hidden" name="cb_vegas_options[slideshow_uniqid]" id="slideshow_uniqid" data-nonce="' . $nonce . '" value="' . $uniqid . '" />';

		echo $html;
	}

	/**
	 * Creates a settings field for the slideshow name.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $slideshow_index
	 *
	 * @return echo
	 */
	public function create_slideshow_name_setting( $slideshow_index ) {

		$nonce = wp_create_nonce( 'cb_vegas_slideshow_name_nonce' );

		$slideshow_name = $this->get_slideshow_name( $slideshow_index );

		$html = '<div class="cb-vegas-slideshow-name-container">';

		$html .= '<div class="cb-vegas-label-container">';
		$html .= '<label class="label-for-cb-vegas-switch" for="cb_vegas_options[slideshow_name]">';
		$html .= __( 'Slideshow Name:', $this->plugin_domain );
		$html .= '</label>';
		$html .= '</div>';

		$html .= '<input style="width: 160px;" type="text" name="cb_vegas_options[slideshow_name]" id="cb_vegas_options[slideshow_name]" data-nonce="' . $nonce . '" value="' . $slideshow_name . '" />';

		$html .= $this->create_settings_toggle();

		$html .= '</div>';

		echo $html;
	}

	/**
	 * Creates a settings field for the "fallback slideshow" option.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $slideshow_index
	 *
	 * @return echo
	 */
	public function create_slideshow_is_fallback_setting( $slideshow_index ) {

		$nonce                 = wp_create_nonce( 'cb_vegas_slideshow_is_fallback_nonce' );
		$options               = get_option( 'cb_vegas_options' );
		$slideshow_is_fallback = isset( $options[ $slideshow_index ]['meta']['slideshow_is_fallback'] ) ? $options[ $slideshow_index ]['meta']['slideshow_is_fallback'] : false;

		$html = '<div class="cb-vegas-setting-container">';

		$html .= '<div class="cb-vegas-label-container">';
		$html .= '<label class="label-for-cb-vegas-switch" for="cb_vegas_options[slideshow_is_fallback]">';
		$html .= __( 'Use this slideshow as a fallback slideshow:', $this->plugin_domain );
		$html .= '</label>';
		$html .= '</div>';

		$html .= '<div class="cb-vegas-input-container">';
		$html .= '<label class="cbv-switch label-for-cbv-switch" title="' . __( 'Use this slideshow as a fallback slideshow.', $this->plugin_domain ) . '">';
		$html .= '<input type="checkbox" id="cb_vegas_options[slideshow_is_fallback]" class="cbv-switch-input cbv-input-checkbox" name="cb_vegas_options[slideshow_is_fallback]" value="1" ' . checked( 1, isset( $slideshow_is_fallback ) ? $slideshow_is_fallback : 0, false ) . ' data-slideshow-index="' . $slideshow_index . '" data-nonce="' . $nonce . '"/>';
		$html .= '<span class="cbv-switch-label" data-on="On" data-off="Off"></span>';
		$html .= '<span class="cbv-switch-handle"></span>';
		$html .= '</label>';
		$html .= '</div>';

		$html .= '</div>';

		echo $html;
	}

	/**
	 * Creates a settings field for the "shuffle" option.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $slideshow_index
	 *
	 * @return echo
	 */
	public function create_slideshow_is_shuffle_setting( $slideshow_index ) {

		$nonce = wp_create_nonce( 'cb_vegas_slideshow_is_shuffle_nonce' );

		$options             = get_option( 'cb_vegas_options' );
		$slideshow_is_global = isset( $options[ $slideshow_index ]['meta']['slideshow_is_shuffle'] ) ? $options[ $slideshow_index ]['meta']['slideshow_is_shuffle'] : false;

		$html = '<div class="cb-vegas-setting-container">';

		$html .= '<div class="cb-vegas-label-container">';
		$html .= '<label class="label-for-cb-vegas-switch" for="cb_vegas_options[slideshow_is_shuffle]">';
		$html .= __( 'Shuffle:', $this->plugin_domain );
		$html .= '</label>';
		$html .= '</div>';

		$html .= '<div class="cb-vegas-input-container">';
		$html .= '<label class="cbv-switch label-for-cbv-switch" title="' . __( 'Shuffle.', $this->plugin_domain ) . '">';
		$html .= '<input type="checkbox" id="cb_vegas_options[slideshow_is_shuffle]" class="cbv-switch-input cbv-input-checkbox" name="cb_vegas_options[slideshow_is_shuffle]" value="1" ' . checked( 1, isset( $slideshow_is_global ) ? $slideshow_is_global : 0, false ) . ' data-slideshow-index="' . $slideshow_index . '" data-nonce="' . $nonce . '"/>';
		$html .= '<span class="cbv-switch-label" data-on="On" data-off="Off"></span>';
		$html .= '<span class="cbv-switch-handle"></span>';
		$html .= '</label>';
		$html .= '</div>';

		$html .= '</div>';

		echo $html;
	}

	/**
	 * Creates a settings field for the "timer" option.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $slideshow_index
	 *
	 * @return echo
	 */
	public function create_slideshow_is_timer_setting( $slideshow_index ) {

		$nonce = wp_create_nonce( 'cb_vegas_slideshow_is_timer_nonce' );

		$options             = get_option( 'cb_vegas_options' );
		$slideshow_is_global = isset( $options[ $slideshow_index ]['meta']['slideshow_is_timer'] ) ? $options[ $slideshow_index ]['meta']['slideshow_is_timer'] : false;

		$html = '<div class="cb-vegas-setting-container">';

		$html .= '<div class="cb-vegas-label-container">';
		$html .= '<label class="label-for-cb-vegas-switch" for="cb_vegas_options[slideshow_is_timer]">';
		$html .= __( 'Show Timer:', $this->plugin_domain );
		$html .= '</label>';
		$html .= '</div>';

		$html .= '<div class="cb-vegas-input-container">';
		$html .= '<label class="cbv-switch label-for-cbv-switch" title="' . __( 'Show Timer.', $this->plugin_domain ) . '">';
		$html .= '<input type="checkbox" id="cb_vegas_options[slideshow_is_timer]" class="cbv-switch-input cbv-input-checkbox" name="cb_vegas_options[slideshow_is_timer]" value="1" ' . checked( 1, isset( $slideshow_is_global ) ? $slideshow_is_global : 0, false ) . ' data-slideshow-index="' . $slideshow_index . '" data-nonce="' . $nonce . '"/>';
		$html .= '<span class="cbv-switch-label" data-on="On" data-off="Off"></span>';
		$html .= '<span class="cbv-switch-handle"></span>';
		$html .= '</label>';
		$html .= '</div>';

		$html .= '</div>';

		echo $html;
	}

	/**
	 * Creates a settings field for the "autoplay" option.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $slideshow_index
	 *
	 * @return echo
	 */
	public function create_slideshow_is_autoplay_setting( $slideshow_index ) {

		$nonce = wp_create_nonce( 'cb_vegas_slideshow_is_autoplay_nonce' );

		$options             = get_option( 'cb_vegas_options' );
		$slideshow_is_global = isset( $options[ $slideshow_index ]['meta']['slideshow_is_autoplay'] ) ? $options[ $slideshow_index ]['meta']['slideshow_is_autoplay'] : false;

		$html = '<div class="cb-vegas-setting-container">';

		$html .= '<div class="cb-vegas-label-container">';
		$html .= '<label class="label-for-cb-vegas-switch" for="cb_vegas_options[slideshow_is_autoplay]">';
		$html .= __( 'Autoplay:', $this->plugin_domain );
		$html .= '</label>';
		$html .= '</div>';

		$html .= '<div class="cb-vegas-input-container">';
		$html .= '<label class="cbv-switch label-for-cbv-switch" title="' . __( 'Autoplay.', $this->plugin_domain ) . '">';
		$html .= '<input type="checkbox" id="cb_vegas_options[slideshow_is_autoplay]" class="cbv-switch-input cbv-input-checkbox" name="cb_vegas_options[slideshow_is_autoplay]" value="1" ' . checked( 1, isset( $slideshow_is_global ) ? $slideshow_is_global : 0, false ) . ' data-slideshow-index="' . $slideshow_index . '" data-nonce="' . $nonce . '"/>';
		$html .= '<span class="cbv-switch-label" data-on="On" data-off="Off"></span>';
		$html .= '<span class="cbv-switch-handle"></span>';
		$html .= '</label>';
		$html .= '</div>';

		$html .= '</div>';

		echo $html;
	}

	/**
	 * Creates a settings field for the "overlay" option.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $slideshow_index
	 *
	 * @return echo
	 */
	public function create_slideshow_is_overlay_setting( $slideshow_index ) {

		$nonce = wp_create_nonce( 'cb_vegas_slideshow_is_autoplay_nonce' );

		$select_values        = $this->get_select_values( 'overlay' );
		$options              = get_option( 'cb_vegas_options' );
		$slideshow_is_overlay = isset( $options[ $slideshow_index ]['meta']['slideshow_is_overlay'] ) ? $options[ $slideshow_index ]['meta']['slideshow_is_overlay'] : false;

		// Continue with translated values if necessary.
		if ( get_locale() !== 'en_US' ) {

			$select_values = $this->translate_to_custom_locale( $select_values );
		}

		$html = '<div class="cb-vegas-setting-container">';

		$html .= '<div class="cb-vegas-label-container">';
		$html .= '<label class="label-for-cb-vegas-switch" for="cb_vegas_options[slideshow_is_overlay]">Overlay:</label>';
		$html .= '</div>';

		$html .= '<div class="cb-vegas-input-container">';
		$html .= '<select name="cb_vegas_options[slideshow_is_overlay]" class="floating-element fancy-select cb-vegas-fancy-select" id="cb_vegas_options[slideshow_is_overlay]" data-nonce="' . $nonce . '">';

		foreach ( $select_values as $key => $select_value ) {

			$html .= '<option value="' . $select_value . '"' . selected( $slideshow_is_overlay, $select_value, false ) . '>' . $select_value . '</option>';
		}

		$html .= '</select>';

		$html .= '</div>';

		$html .= $this->create_after_content();

		echo $html;
	}

	/**
	 * Creates a "submit" button.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return echo
	 */
	public function create_submit_button() {

		$nonce = wp_create_nonce( 'cb_vegas_submit_nonce' );

		$html = '<div class="cb-vegas-submit-button-container">';
		$html .= '<input type="submit" name="submit" id="submit" class="button button-primary button-large" value="' . __( 'Save Changes', $this->plugin_domain ) . '" data-nonce="' . $nonce . '"/>';
		$html .= '</div>';

		echo $html;
	}

	/**
	 * Creates an "add slideshow" button.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return echo
	 */
	public function create_add_slideshow_button() {

		$add_slideshow_nonce = wp_create_nonce( 'cb_vegas_add_slideshow_nonce' );

		/*if ( $this->options->is_add_new_tab() ) {

			$active = 'active-tab';
		} else {

			$active = '';
		}*/

		$icon  = '<i class="fa fa-plus-square" aria-hidden="true"></i>';
		$title = __( "Add New Slideshow", $this->plugin_domain );

		//$html = '<a href="#" class="nav-tab add-new ' . $active . '"  id="cb-vegas-add-tab-button" title="' . $title . '" data-nonce="' . $add_slideshow_nonce . '">';
		$html = '<a href="#" class="nav-tab add-new"  id="cb-vegas-add-tab-button" title="' . $title . '" data-nonce="' . $add_slideshow_nonce . '">';
		$html .= $icon;
		$html .= $title;
		$html .= '</a>';

		echo $html;
	}

	/**
	 * Creates an "add slide" button.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return echo
	 */
	public function create_add_slide_button() {

		$nonce = wp_create_nonce( 'cb_vegas_add_slide_nonce' );

		$html = '<div class="cb-vegas-add-slide-button-container">';
		$html .= '<div id="cb-vegas-add-slide-button" href="#" class="button button-secondary button-large" data-nonce="' . $nonce . '">';
		$html .= __( 'Add Slide', $this->plugin_domain );
		$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	/**
	 * Creates the section heading.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return string $section_heading
	 */
	public function create_section_heading() {

		$section_heading = array(
			'title' => __( 'Settings', $this->plugin_domain ),
		);

		return $section_heading;
	}

	/**
	 * Creates the array containing the meta data of the slideshow,
	 * such as slideshow name, uniqid, and all options regarding the slides of that slideshow
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $slideshow_index
	 *
	 * @return array $slideshow_meta_template
	 */
	public function create_slideshow_template( $slideshow_index = 0 ) {

		$uniqid = uniqid();

		$slideshow_meta_template['meta'] = array(
			'slideshow_uniqid'      => $uniqid,
			'slideshow_index'       => $slideshow_index,
			'slideshow_name'        => __( 'New Slideshow', $this->plugin_domain ),
			'slideshow_is_global'   => false,
			'slideshow_is_fallback' => false,
			'slideshow_is_autoplay' => true,
			'slideshow_is_overlay'  => false,
			'slideshow_is_shuffle'  => false,
			'slideshow_is_timer'    => true,

		);

		return $slideshow_meta_template;
	}

	/**
	 * Creates a slide with some random settings and a placeholder image.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $slideshow_index
	 *
	 * @return array $slide_template
	 */
	public function create_slide_template( $slide_index = 0 ) {

		$slide_template = array();

		foreach ( $this->vegas_options->the_options as $key => $args ) {

			$slide_template['slides'][ $slide_index ][ $key ] = $args['value'];
		}

		return $slide_template;
	}

	/**
	 * Merges the "slideshow template" and the "slide template" into a slideshow.
	 *
	 * @used-by cb_vegas_options / seed_options()
	 *
	 * @since   0.1.0
	 * @access  public
	 *
	 * @param int $slideshow_index
	 * @param int $slide_index
	 *
	 * @return array $new_slideshow
	 */
	public function create_slideshow( $slideshow_index = 0, $slide_index = 0 ) {

		$slideshow     = $this->create_slideshow_template( $slideshow_index );
		$slide         = $this->create_slide_template( $slide_index );
		$new_slideshow = array_merge( $slideshow, $slide );

		return $new_slideshow;
	}

	/**
	 * Returns the value for the given option.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param int $slideshow_index
	 * @param int $slide_index
	 * @param string $option_key
	 * @param bool $new_slideshow
	 *
	 * @return mixed
	 */
	private function fetch_value( $slideshow_index, $slide_index, $option_key, $new_slideshow = false ) {

		if ( false === $new_slideshow ) {

			$options = get_option( 'cb_vegas_options' );
			$value   = $options[ $slideshow_index ]['slides'][ $slide_index ][ $option_key ];

			return $value;
		} else {

			$options = $this->vegas_options->the_options;
			$value   = $options[ $option_key ]['value'];

			return $value;
		}
	}

	/**
	 * Returns the slide index.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param $slide_index
	 * @param $new_slideshow_index
	 *
	 * @return int $slide_index
	 */
	private function fetch_slide_index( $slide_index, $new_slideshow_index ) {

		if ( false === $new_slideshow_index ) {

			$the_slide_index = $slide_index;
		} else {

			$the_slide_index = $new_slideshow_index;
		}

		$slide_index = $the_slide_index;

		return $slide_index;
	}

	/**
	 * Translates "select values" into the user's custom language (if a translation is available).
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param $select_values
	 *
	 * @return string $output
	 */
	private function translate_to_custom_locale( $select_values ) {

		$output = array();

		foreach ( $select_values as $key => $value ) {

			if ( isset( $value ) && $value === $this->vegas_options->transitions['random'] ) {

				$output[ $key ] = __( 'random', $this->plugin_domain );
			} else {

				$output[ $key ] = $value;
			}
			if ( isset( $value ) && $value === $this->vegas_options->align['center'] ) {

				$output[ $key ] = __( 'center', $this->plugin_domain );
			} else if ( isset( $value ) && $value === $this->vegas_options->valign['center'] ) {

				$output[ $key ] = __( 'center', $this->plugin_domain );
			} else if ( isset( $value ) && $value === $this->vegas_options->valign['top'] ) {

				$output[ $key ] = __( 'top', $this->plugin_domain );
			} else if ( isset( $value ) && $value === $this->vegas_options->align['right'] ) {

				$output[ $key ] = __( 'right', $this->plugin_domain );
			} else if ( isset( $value ) && $value === $this->vegas_options->valign['bottom'] ) {

				$output[ $key ] = __( 'bottom', $this->plugin_domain );
			} else if ( isset( $value ) && $value === $this->vegas_options->align['left'] ) {

				$output[ $key ] = __( 'left', $this->plugin_domain );
			} else if ( isset( $value ) && $value === $this->vegas_options->cover['repeat'] ) {

				$output[ $key ] = __( 'repeat', $this->plugin_domain );
			} else {

				$output[ $key ] = $value;
			}
			if ( isset( $value ) && $value === $this->vegas_options->animations['random'] ) {

				$output[ $key ] = __( 'random', $this->plugin_domain );
			} else {

				$output[ $key ] = $value;
			}
		}

		return $output;
	}

	/**
	 * Returns or echoes the output (the settings fields) depending on the situation this function is being called.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param $html
	 * @param $new_slideshow_index
	 *
	 * @return echo | string $html
	 */
	private function return_html( $html, $new_slideshow_index ) {

		if ( false === $new_slideshow_index ) {

			echo $html;
		} else {

			return $html;
		}
	}

	/**
	 * Creates an "add media" button.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param $slide_index
	 *
	 * @return string $html
	 */
	private function create_add_media_button( $slide_index ) {

		$html = '<div class="cb-vegas-media-button-container">';
		// Add Button.
		$html .= '<div title="' . __( 'Add Media', $this->plugin_domain ) . '" class="cb-vegas-button cb-vegas-add-media-button dashicons dashicons-plus" data-slide-index="' . $slide_index . '"></div>';
		// Remove Button.
		$html .= '<div title="' . __( 'Remove Media', $this->plugin_domain ) . '" class="cb-vegas-button cb-vegas-remove-media-button dashicons dashicons-trash" data-slide-index="' . $slide_index . '"></div>';

		$html .= '</div>';

		return $html;
	}

	/**
	 * Creates a "duplicate slide" button.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param $slide_index
	 *
	 * @return string $html
	 */
	private function create_duplicate_slide_button( $slide_index ) {

		$nonce = wp_create_nonce( 'cb_vegas_duplicate_slide_nonce' );

		// Input
		$html = '<div class="cb-vegas-button cb-vegas-duplicate-slide-button dashicons dashicons-admin-page" data-nonce="' . $nonce . '" title="' . __( 'Duplicate Slide', $this->plugin_domain ) . '" data-slide-index="' . $slide_index . '" ></div>';

		return $html;
	}

	/**
	 * Creates a toggle for the general slideshow settings area.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $html
	 */
	private function create_settings_toggle() {

		$nonce = wp_create_nonce( 'cb_vegas_duplicate_slide_nonce' );

		// Input
		$html = '<div class="cb-vegas-button cb-vegas-settings-toggle dashicons dashicons-admin-settings" data-nonce="' . $nonce . '" title="' . __( 'Toggle Settings', $this->plugin_domain ) . '"></div>';

		return $html;
	}

	/**
	 * Creates a "remove slideshow" button.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param $slide_index
	 *
	 * @return echo
	 */
	public function create_remove_slideshow_button() {

		$nonce = wp_create_nonce( 'cb_vegas_remove_slideshow_nonce' );

		// Input
		$html = '<div class="cb-vegas-remove-slideshow-button-container">';
		$html .= '<div id="cb-vegas-remove-slideshow-button" href="#" class="button button-secondary button-large" data-nonce="' . $nonce . '">';
		$html .= __( 'Remove Slideshow', $this->plugin_domain );
		$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	/**
	 * Creates a "duplicate slideshow" button.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return echo
	 */
	public function create_duplicate_slideshow_button() {

		$nonce = wp_create_nonce( 'cb_vegas_duplicate_slideshow_nonce' );

		// Input
		$html = '<div class="cb-vegas-duplicate-slideshow-button-container">';
		$html .= '<div id="cb-vegas-duplicate-slideshow-button" href="#" class="button button-secondary button-large" data-nonce="' . $nonce . '">';
		$html .= __( 'Duplicate Slideshow', $this->plugin_domain );
		$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	/**
	 * Returns - or creates - the name of the slideshow.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return string $name
	 */
	public function get_slideshow_name( $slideshow_index ) {

		$options = get_option( 'cb_vegas_options' );

		if ( false != $options ) {

			$slideshow_name = $options[ $slideshow_index ]['meta']['slideshow_name'];
			// If no name is given, we give it a default name ourselves.
			$name = isset( $slideshow_name ) && $slideshow_name != '' && $slideshow_name !== false ? $slideshow_name : __( 'New Slideshow', $this->plugin_domain );

			return $name;
		} else {

			return __( 'New Slideshow', $this->plugin_domain );
		}

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
	 * Returns an array containing the values for settings based on select boxes.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return mixed array $select_values
	 */
	public function get_select_values( $option_key ) {

		$settings_template = $this->vegas_options->the_options;
		$select_values     = $settings_template[ $option_key ]['select_values'];

		return $select_values;
	}

}
