jQuery( function ( $ ) {
	"use strict";

	function Plugin () {

		this.cbVegas = cbVegas;

		this.addMediaButton    = $( '.cb-vegas-add-media-button' );
		this.removeMediaButton = $( '.cb-vegas-remove-media-button' );
		this.settingsToggle    = $( '.cb-vegas-settings-toggle' );
		//this.inputFields       = $( '#cb-vegas-settings-form input, div.fancy-select ul.options li.selected' );

		this.media            = $( '.cb-vegas-media' );
		this.mediaHiddenField = $( '.cb-vegas-media-hidden' );

		this.settingsContainer = $( '.cb-vegas-settings-slider' );

		//this.duplicateSlideshowButton = $( '#cb-vegas-duplicate-slideshow-button' );

		this.colorpicker = $( '.cb-vegas-color-picker' );
		this.fancySelect = $( '.cb-vegas-fancy-select' );

		this.settingsUpdatedNotice = $( '#setting-error-settings_updated' );

		this.singleSlide = $( '.cb-vegas-single-slide' );
	}

	Plugin.prototype = {

		constructor : Plugin,
		init        : function () {
			this.initColorPicker();
			this.initFancySelect();
			this.localize();
			this.bind();

			this.setInitialView();
			this.settingsContainer.css( 'display', 'none' );
			this.handleSettingsUpdatedNotice();
		},

		initColorPicker : function () {

			this.colorpicker.wpColorPicker();
		},
		initFancySelect : function () {

			this.fancySelect.fancySelect();
		},
		localize        : function () {

			if ( this.cbVegas.locale != 'default' ) {

				$( '<style>:root input[type="checkbox"].cb-vegas-switch + div:before{content:"' + this.cbVegas.onText + '";}</style>' ).appendTo( 'head' );
				$( '<style>:root input[type="checkbox"].cb-vegas-switch + div:after{content:"' + this.cbVegas.offText + '";}</style>' ).appendTo( 'head' );
			}
		},
		bind            : function () {

			$( this.addMediaButton.selector ).live( 'click', { context : this }, this.addMedia );
			$( this.removeMediaButton.selector ).live( 'click', { context : this }, this.removeMedia );
			$( this.settingsToggle.selector ).live( 'click', { context : this }, this.toggleSettingsSection );
		},

		addMedia              : function ( event ) {
			event.preventDefault();

			var self = event.data.context;

			var cb_vegas_frame;
			var slide_index = $( this ).parents( self.singleSlide.selector ).data( 'slide-index' );

			if ( cb_vegas_frame ) {
				cb_vegas_frame.open( slide_index );
				return;
			}
			cb_vegas_frame = wp.media.frames.cb_vegas_frame = wp.media( {

				className : "media-frame cb-vegas-frame",
				frame     : "select",
				multiple  : false,
				title     : self.cbVegas.frameTitleText,
				library   : { type : ['image', 'video'] },
				button    : { text : self.cbVegas.frameButtonText },
				index     : slide_index
			} );

			// Processes the selected image
			cb_vegas_frame.on( "select", function () {
				// Gets the selected item.
				var mediaAttachment = cb_vegas_frame.state().get( 'selection' ).first().toJSON();

				$( self.addMediaButton.selector ).filter( '[data-slide-index="' + slide_index + '"]' ).hide();
				$( self.media.selector ).filter( '[data-slide-index="' + slide_index + '"]' ).attr( 'src', mediaAttachment.url ).show();
				$( self.mediaHiddenField.selector ).filter( '[data-slide-index="' + slide_index + '"]' ).val( mediaAttachment.url );
				$( self.removeMediaButton.selector ).filter( '[data-slide-index="' + slide_index + '"]' ).css( 'visibility', 'visible' ).show();
				// reset
				cb_vegas_frame = null;
				self.removeOptionsUpdatedNotice();
				alertify.warning( 'Image added. You need to save the changes.', 'success', 5 );
			} );
			cb_vegas_frame.open();
		},
		removeMedia           : function ( event ) {
			event.preventDefault();

			//event = event || window.event;
			var self = event.data.context;

			var slide_index = $( this ).parents( self.singleSlide.selector ).data( 'slide-index' )/*$( this ).data( 'slide-index' )*/;
			$( self.media.selector ).filter( '[data-slide-index="' + slide_index + '"]' ).attr( 'src', '' ).hide();
			$( self.mediaHiddenField.selector ).filter( '[data-slide-index="' + slide_index + '"]' ).val( '' );
			$( self.removeMediaButton.selector ).filter( '[data-slide-index="' + slide_index + '"]' ).hide();
			$( self.addMediaButton.selector ).filter( '[data-slide-index="' + slide_index + '"]' ).show();
			// reset.
			slide_index = null;
			self.removeOptionsUpdatedNotice();
		},
		toggleSettingsSection : function ( event ) {
			event.preventDefault();
			var self = event.data.context;

			self.settingsContainer.slideToggle( 600 );
		},

		setInitialView              : function () {

			var image = $( this.media.selector );

			// Sets the initial view
			if ( image.attr( 'src' ) ) {

				$( this.addMediaButton.selector ).css( 'visibility', 'visible' ).hide();
				$( this.media.selector ).css( 'visibility', 'visible' ).show();
				$( this.removeMediaButton.selector ).css( 'visibility', 'visible' ).show();
			}
			else {

				$( this.media.selector ).hide();
				$( this.removeMediaButton.selector ).css( 'visibility', 'visible' ).hide();
				$( this.addMediaButton.selector ).css( 'visibility', 'visible' ).show();
			}
		},
		doTooltips                  : function () {

			$( function () {
				$( this.addMediaButton.selector ).tooltip( {
					content      : { title : this.cbVegas.addMediaButtonText },
					show         : { effect : "blind", duration : 800 },
					hide         : { effect : "pulsate", duration : 1000 },
					tooltipClass : "cb-vegas-tooltip",
					track        : true
				} );
				$( this.removeMediaButton.selector ).tooltip( {
					content      : { title : this.cbVegas.removeMediaButtonText },
					show         : { effect : "blind", duration : 800 },
					hide         : { effect : "pulsate", duration : 1000 },
					tooltipClass : "cb-vegas-tooltip",
					track        : true
				} );
			} );
		},
		handleSettingsUpdatedNotice : function () {

			if ( $( this.settingsUpdatedNotice.selector ).length ) {
				$( this ).css( 'display', 'none' );
				$( this.settingsUpdatedNotice.selector ).wrapAll( '<div class="setting-error-settings_updated_placeholder"></div>' );
				$( '.setting-error-settings_updated_placeholder' ).height( $( '#setting-error-settings_updated' ).outerHeight() );
				$( this ).css( 'display', 'initial' );
			}
		},
		removeOptionsUpdatedNotice  : function () {
			var notice = $( '.wp-core-ui .notice.is-dismissible' );

			notice.animate( {
				height   : 0,
				duration : '600'
			}, 600 ).remove();
		},
	};

	$( document ).one( 'ready', function () {

		var plugin = new Plugin();
		plugin.init();
	} );

} );
