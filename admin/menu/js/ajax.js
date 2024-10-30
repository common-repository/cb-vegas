/**
 * cbVegas Ajax script.
 *
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
jQuery( function ( $ ) {
	"use strict";

	function Plugin () {
		this.cbVegasAjax = CbVegasAjax;

		this.form              = $( '#cb-vegas-settings-form' );
		this.navigation        = $( '.cb-vegas-navigation-tabs-container' );
		this.slides            = $( '#cb-vegas-slides-container' );
		this.singleSlide       = $( '.cb-vegas-single-slide' );
		this.addMediaButton    = $( '.cb-vegas-add-media-button' );
		this.removeMediaButton = $( '.cb-vegas-remove-media-button' );
		this.media             = $( '.cb-vegas-media' );
		this.notice            = $( '.wp-core-ui .notice.is-dismissible' );

		this.removeSlideButton        = $( '.cb-vegas-remove-slide-button' );
		this.addSlideButton           = $( '#cb-vegas-add-slide-button' );
		this.removeSlideshowButton    = $( '#cb-vegas-remove-slideshow-button' );
		this.addTabButton             = $( '#cb-vegas-add-tab-button' );
		this.duplicateSlideButton     = $( '.cb-vegas-duplicate-slide-button' );
		this.duplicateSLideshowButton = $( '#cb-vegas-duplicate-slideshow-button' );
		this.handles                  = $( '.cb-vegas-slide-handle' );

		//this.slideshowIndexHolder = $( '#slideshow_index' );
		this.activeTab            = $( '.cb-vegas-navigation-tab-active-tab' );

		this.colorpicker = $( '.cb-vegas-color-picker' );
		this.fancySelect = $( '.cb-vegas-fancy-select' );
	}

	Plugin.prototype = {

		constructor     : Plugin,
		init            : function () {
			this.initColorPicker();
			this.initFancySelect();
			this.setAlertify();
			//this.updateActiveTabIndex();
			this.addSpinnerContainer();
			this.setupLoaderContainer();
			this.bind();
			//this.doTooltips();
		},
		initColorPicker : function () {

			this.colorpicker.wpColorPicker();
		},
		initFancySelect : function () {

			this.fancySelect.fancySelect();
		},
		setAlertify     : function () {

			alertify.defaults = {
				// dialogs defaults
				autoReset        : true,
				basic            : false,
				closable         : true,
				closableByDimmer : true,
				frameless        : false,
				maintainFocus    : true, // <== global default not per instance, applies to all dialogs
				maximizable      : true,
				modal            : true,
				movable          : true,
				moveBounded      : false,
				overflow         : true,
				padding          : true,
				pinnable         : true,
				pinned           : true,
				preventBodyShift : false, // <== global default not per instance, applies to all dialogs
				resizable        : true,
				startMaximized   : false,
				transition       : 'zoom',

				// notifier defaults
				notifier : {
					// auto-dismiss wait time (in seconds)
					delay    : 6,
					// default position
					position : 'top-right'
				},

				// language resources
				glossary : {
					// dialogs default title
					title  : 'Vegas Background Slideshow',
					// ok button text
					ok     : this.cbVegasAjax.okiDoki,
					// cancel button text
					cancel : this.cbVegasAjax.noWayJose
				},

				// theme settings
				theme : {
					// class name attached to prompt dialog input textbox.
					input  : 'ajs-input',
					// class name attached to ok button
					ok     : 'ajs-ok',
					// class name attached to cancel button
					cancel : 'ajs-cancel'
				}
			};
		},
		bind            : function () {

			$( this.addSlideButton.selector ).live( 'click', { context : this }, this.addSlide );
			$( this.removeSlideButton.selector ).live( 'click', { context : this }, this.removeSlide );
			$( this.removeSlideshowButton.selector ).live( 'click', { context : this }, this.removeSlideshow );
			$( this.addTabButton.selector ).live( 'click', { context : this }, this.addSlideshow );
			$( this.duplicateSlideButton.selector ).live( 'click', { context : this }, this.duplicateSlide );
			// @notice: using the values from the current options transient is okay for now.
			$( this.duplicateSLideshowButton.selector ).live( 'click', { context : this }, this.duplicateSlideshow );

			if ( this.handles.length ) {
				$( this.handles.selector ).live( 'mousedown', { context : this }, this.doSortable );
			}
		},

		/*updateNavTabLinks       : function () {
		 // Reassign the (tab-)indices to the links except for the first item.
		 $( this.navTab.selector ).each( function ( i ) {

		 if ( i == 0 ) {

		 $( this ).attr( 'href', '?page=cb_vegas_settings_page&tab=0' );
		 }
		 else {

		 $( this ).attr( 'href', '?page=cb_vegas_settings_page&tab=' + i );
		 }
		 } );
		 },
		 updateNavTabIndices     : function () {

		 $( this.navTab.selector ).each( function ( i ) {
		 $( this ).data( 'slideshow-index', i );
		 $( this ).attr( 'data-slideshow-index', i );
		 $( this ).attr( 'id', 'cb-vegas-navigation-tab_' + i );
		 } );
		 },
		 updateActiveTabIndex    : function () {

		 var form           = $( this.form.selector );
		 var activeTabIndex = $( this.activeTab.selector ).data( 'slideshow-index' );

		 form.data( 'slideshow-index', activeTabIndex );
		 form.attr( 'data-slideshow-index', activeTabIndex );
		 $( this.slideshowIndexHolder.selector ).prop( 'value', activeTabIndex );
		 },
		 updateUrl               : function () {

		 var activeTabIndex = $( this.activeTab.selector ).data( 'slideshow-index' );
		 var pathname       = window.location.pathname;
		 var settingsTab    = '?page=cb_vegas_settings_page&tab=' + activeTabIndex;
		 var newUrl         = pathname + settingsTab;

		 window.history.pushState( null, '', newUrl );
		 },*/
		addSlideshow       : function ( event ) {

			event = event || window.event;
			event.preventDefault();
			var self = event.data.context;

			var data = {
				action : 'add_slideshow',
				nonce  : $( this ).attr( 'data-nonce' )
			};

			alertify.confirm().set( {
				'title' : self.cbVegasAjax.addSlideshowConfirmationHeading
			} );
			alertify.confirm( self.cbVegasAjax.addSlideshowConfirmation, function ( event ) {
				if ( event ) {
					$.post( ajaxurl, data, function ( response ) {

						if ( response.data.success == true ) {

							var tab = response.data.html;

							// Appends the slide.
							$( '.cb-vegas-navigation-tabs-container' ).append( tab );
							// Switchifies the newly created checkboxes.
							$( "<div class='cb-vegas-switch-container'></div>" ).insertAfter( '.cb-vegas-switch' );
							// Updates "Fancy Select".
							$( '.cb-vegas-fancy-select' ).trigger( 'update.fs' );

							//self.updateNavTabLinks();
							//self.updateNavTabIndices();
							alertify.success( response.data.message, 'success', 3 );
							return true;
						}
						else {
							/*alertify.notify().set( {
								'title' : 'Ooops!'
							} );*/
							alertify.error( response.data.message + '<br>Error-Code: ajax-add-slideshow', 'error', 5 );
							return false;
						}
					} );
				}
				else {
					/*alertify.notify().set( {
						'title' : 'Ooops!'
					} );*/
					alertify.error( 'No action: ' + response.data.message + '<br>Error-Code: ajax-add-slideshow', 'error', 5 );
					return false;
				}
			} );
		},
		duplicateSlideshow : function ( event ) {

			event = event || window.event;
			event.preventDefault();
			var self = event.data.context;

			var slideIndex     = $( this ).data( 'slide-index' );
			var slideshowIndex = $( '#current_slideshow_index' ).val();

			var data = {
				action          : 'duplicate_slideshow',
				slideshow_index : slideshowIndex,
				slide_index     : slideIndex,
				nonce           : $( this ).attr( 'data-nonce' )
			};

			alertify.confirm().set( {
				'title' : self.cbVegasAjax.dupliacteSlideshowConfirmationHeading
			} );
			alertify.confirm( self.cbVegasAjax.dupliacteSlideshowConfirmation, function ( event ) {

				if ( event ) {

					$.post( ajaxurl, data, function ( response ) {

						if ( response.data.success == true ) {

							var navigationTab = response.data.html;
							// Appends the slide.
							$( '.cb-vegas-navigation-tabs-container' ).append( navigationTab );
							// Updates "Fancy Select".
							$( '.cb-vegas-fancy-select' ).trigger( 'update.fs' );

							alertify.alert( response.data.message, function () {
								window.location.href = "/wp-admin/options-general.php?page=cb_vegas_settings_page&tab=" + response.data.tab;
							} );

							return true;
						}
						else {
							/*alertify.notify().set( {
								'title' : 'Ooops!'
							} );*/
							alertify.error( response.data.message + '<br>Error-Code: ajax-duplicate-slideshow', 'error', 5);
							return false;
						}
					} );
				}
				else {
					/*alertify.notify().set( {
						'title' : 'Ooops!'
					} );*/
					alertify.error( 'No action: ' + response.data.message + '<br>Error-Code: ajax-duplicate-slideshow', 'error', 5 );
					return false;
				}
			} );
		},
		removeSlideshow    : function ( event ) {

			event = event || window.event;
			event.preventDefault();
			var self = event.data.context;

			var data = {
				action                     : 'remove_slideshow',
				deprecated_slideshow_index : $( '#current_slideshow_index' ).val(),
				nonce_key                  : $( this ).data( 'nonce-key' ),
				nonce                      : $( this ).attr( 'data-nonce' )
			};

			alertify.confirm().set( {
				'title' : self.cbVegasAjax.removeSlideshowConfirmationHeading
			} );
			alertify.confirm( self.cbVegasAjax.removeSlideshowConfirmation, function ( event ) {

				if ( event ) {

					$.post( ajaxurl, data, function ( response ) {

						if ( response.data.success === true ) {

							self.updateSlideIndices(self);
							alertify.alert( response.data.message, function () {
								window.location.href = "/wp-admin/options-general.php?page=cb_vegas_settings_page&tab=0";
								return true;
							} );
						}
						else {
							self.updateSlideIndices(self);
							/*alertify.notify().set( {
								'title' : 'Ooops!'
							} );*/
							alertify.error( response.data.message + '<br>Error-Code: ajax-remove-slideshow', 'error', 5 );
							return false;
						}
					} );
				}
				else {
					self.updateSlideIndices(self);
					/*alertify.notify().set( {
						'title' : 'Ooops!'
					} );*/
					alertify.error( 'No action: ' + response.data.message + '<br>Error-Code: ajax-remove-slideshow', 'error', 5 );
					return false;
				}
			} );
		},
		addSlide           : function ( event ) {
			event = event || window.event;
			event.preventDefault();
			var self = event.data.context;

			// Determine the highest index number.
			var indices = $( self.singleSlide.selector ).map( function ( i, element ) {

				return $( element ).data( 'slide-index' );
			} );

			var numberOfSlides = indices.length;
			var slide_index  = null;
			if ( $.isNumeric( numberOfSlides ) ) {

				slide_index = numberOfSlides;
			}
			else {

				slide_index = 0;
			}

			var data = {
				action          : 'add_slide',
				slideshow_index : $( '#current_slideshow_index' ).val(),
				slide_index     : slide_index,
				nonce           : $( this ).attr( 'data-nonce' )
			};

			alertify.confirm().set( {
				'title' : self.cbVegasAjax.addSlideConfirmationHeading
			} );
			alertify.confirm( self.cbVegasAjax.addSlideConfirmation, function ( event ) {

				if ( event ) {

					$.post( ajaxurl, data, function ( response ) {

						if ( response.data.success == true ) {

							// Get and append the slide.
							var slide = response.data.html;
							self.slides.append( slide );

							// Switchifies the newly created checkboxes.
							$( "<div class='cb-vegas-switch-container'></div>" ).insertAfter( '.cb-vegas-switch' );

							// Reinitiate these including the newly created ones
							$( self.fancySelect.selector ).fancySelect();
							$( self.colorpicker.selector ).wpColorPicker();

							self.updateSlideIndices(self);
							alertify.success( response.data.message, 'success', 3 );
							return true;
						}
						else {
							self.updateSlideIndices(self);
							/*alertify.notify().set( {
								'title' : 'Ooops!'
							} );*/
							alertify.error( response.data.message + '<br>Error-Code: ajax-add-slide', 'error', 5 );
							return false;
						}
					} );
				}
				else {
					self.updateSlideIndices(self);
					/*alertify.notify().set( {
						'title' : 'Ooops!'
					} );*/
					alertify.error( 'No action: ' + response.data.message + '<br>Error-Code: ajax-add-slide', 'error', 5 );
					return false;
				}
			} );
		},
		duplicateSlide     : function ( event ) {

			event = event || window.event;
			event.preventDefault();
			var self = event.data.context;

			var slide_index     = $( this ).parents( self.singleSlide.selector ).data( 'slide-index' )/*$( this ).data( 'slide-index' )*/;
			var slideshow_index = $( '#current_slideshow_index'/*self.slideshowIndexHolder.selector*/ ).val();

			var data = {
				action          : 'duplicate_slide',
				slideshow_index : slideshow_index,
				slide_index     : slide_index,
				nonce           : $( this ).attr( 'data-nonce' )
			};

			alertify.confirm().set( { 'title' : self.cbVegasAjax.duplicateSlideConfirmationHeading } );
			alertify.confirm( self.cbVegasAjax.duplicateSlideConfirmation, function ( event ) {

				if ( event ) {

					$.post( ajaxurl, data, function ( response ) {

						if ( response.data.success == true ) {

							var slide         = response.data.html;
							var newSlideIndex = $( self.singleSlide.selector ).length;

							// Appends the slide.
							self.slides.append( slide );

							// Sets the initial view.
							$( self.addMediaButton.selector ).filter( '[data-slide-index="' + newSlideIndex + '"]' ).css( 'visibility', 'visible' ).hide();
							$( self.removeMediaButton.selector ).filter( '[data-slide-index="' + newSlideIndex + '"]' ).css( 'visibility', 'visible' ).show();
							$( self.addMediaButton.selector ).filter( '[data-slide-index="' + newSlideIndex + '"]' ).css( {
								display : 'none'
							} );
							$( self.removeMediaButton.selector ).filter( '[data-slide-index="' + newSlideIndex + '"]' ).css( {
								display : 'inline-block'

							} );

							// Switchifies the newly created checkboxes.
							$( "<div class='cb-vegas-switch-container'></div>" ).insertAfter( '.cb-vegas-switch' );

							// Reinitiate these including the newly created ones
							$( self.fancySelect.selector ).fancySelect();
							$( self.colorpicker.selector ).wpColorPicker();

							/*alertify.set( {
								'title' : 'OkiDoki!'
							} );*/
							alertify.success( response.data.message, 'success', 3 );
							//alertify.notify( response.data.message, 'success', 5 );
							self.updateSlideIndices(self);
							return true;
						}
						else {
							self.updateSlideIndices(self);
							/*alertify.set( {
								'title' : 'Ooops!'
							} );*/
							alertify.error( response.data.message + '<br>Error-Code: ajax-duplicate-slide', 'error', 5 );
							return false;
						}
					} );
				}
				else {
					self.updateSlideIndices(self);
					/*alertify.set( {
						'title' : 'Ooops!'
					} );*/
					alertify.error( 'No action: ' + response.data.message + '<br>Error-Code: ajax-duplicate-slide', 'error', 5 );
					return false;
				}
			} );
		},
		removeSlide        : function ( event ) {
			event = event || window.event;
			event.preventDefault();
			var self = event.data.context;

			var data = {
				action          : 'remove_slide',
				slideshow_index : $( '#current_slideshow_index' ).val(),
				slide_index     : $( this ).parents( self.singleSlide.selector ).data( 'slide-index' )/*$( this ).parents().filter( '.cb-vegas-single-slide' ).data( 'slide-index' )*/,
				nonce           : $( this ).attr( "data-nonce" )
			};

			alertify.confirm().set( {
				'title' : self.cbVegasAjax.removeSlideConfirmationHeading
			} );
			alertify.confirm( self.cbVegasAjax.removeSlideConfirmation, function ( event ) {

				if ( event ) {

					$.post( ajaxurl, data, function ( response ) {

						if ( response.data.success == true ) {

							// Removes the element containing the slide.
							self.singleSlide.filter( '[data-slide-index=' + data.slide_index + ']' ).remove();
							// Reassigns the index in ascending order.
							self.updateSlideIndices(self);
							alertify.success( response.data.message, 'success', 3 );
							return true;
						}
						else {
							self.updateSlideIndices(self);
							/*alertify.notify().set( {
								'title' : 'Ooops!'
							} );*/
							alertify.error( response.data.message + '<br>Error-Code: ajax-remove-slide', 'error', 5 );
							return false;
						}
					} );
				}
				else {
					self.updateSlideIndices(self);
					/*alertify.notify().set( {
						'title' : 'Ooops!'
					} );*/
					alertify.error( 'No action: ' + response.data.message + '<br>Error-Code: ajax-remove-slide', 'error', 5 );
					return false;
				}
			} );
		},

		doSortable : function ( event ) {
			event.preventDefault();

			var self = event.data.context;

			self.sortSlides( event );
		},
		sortSlides : function ( event ) {
			event.preventDefault();
			var self = event.data.context;

			$( self.slides.selector ).sortable( {
				containment          : 'parent',
				appendTo             : 'parent',
				cursor               : 'move',
				delay                : 150,
				forceHelperSize      : true,
				forcePlaceholderSize : true,

				start : function ( event, ui ) {
					var start_pos = ui.item.index();
					ui.item.data( 'start_pos', start_pos );
				},

				update : function ( /*event, ui*/ ) {

					var menuorder = [];
					$.each( $( /*'.cb-vegas-single-slide'*/self.singleSlide.selector ), function () {

						menuorder.push( $( this ).data( 'uniqid' ) );
					} );
					var data = {
						action          : 'sort_slides',
						slideshow_index : $( '#current_slideshow_index'/*self.slideshowIndexHolder.selector*/ ).val(),
						indices         : $( this ).sortable( 'toArray' ),
						nonce           : $( this ).attr( "data-nonce" ),
						menuorder       : menuorder
					};

					$.post( ajaxurl, data, function ( response ) {

						if ( response.data.success == true ) {

							self.updateSlideIndices(self);
							alertify.success( response.data.message, 'success', 3 );
							return true;
						}
						else {
							self.updateSlideIndices(self);
							alertify.error( response.data.message, 'success', 5 );
							return false;
						}
					} );
				}
			} );
		},

		updateSlideIndices : function (self) {

			var slides = $( self.singleSlide.selector );

			slides.each( function ( i ) {
				slides.eq( i ).attr( 'data-slide-index', i );
				slides.eq( i ).attr( 'id', 'cb-vegas-single-slide_' + i );
			} );
		},

		dismissNotice : function () {
			var notice = $( '.settings_page_cb_vegas_settings_page .updated.notice' );

			if ( notice.length ) {
				notice.slideUp( 400 );
			}
		},

		addSpinnerContainer  : function () {

			$( 'body' ).prepend( '<div id="ajax-loader-container"></div>' );
		},
		setupLoaderContainer : function () {
			$( '#ajax-loader-container' ).hide();
		},

		doTooltips : function () {
			$( this.addSlideButton.selector ).tooltip( {
				content      : { title : this.cbVegasAjax.addSlideButtonText },
				show         : { effect : "blind", duration : 800 },
				hide         : { effect : "pulsate", duration : 1000 },
				tooltipClass : "cb-vegas-tooltip",
				track        : true
			} );
			$( this.removeSlideButton.selector ).tooltip( {
				content      : { title : this.cbVegasAjax.removeSlideButtonText },
				show         : { effect : "blind", duration : 800 },
				hide         : { effect : "pulsate", duration : 1000 },
				tooltipClass : "cb-vegas-tooltip",
				track        : true
			} );
			this.addTabButton.tooltip( {
				content : { title : this.cbVegasAjax.addSlideshowButtonText },
				show    : { effect : "blind", duration : 800 },
				hide    : { effect : "pulsate", duration : 1000 },
				track   : true
			} );
			this.removeSlideshowButton.tooltip( {
				content : { title : this.cbVegasAjax.removeSlideshowButtonText },
				show    : { effect : "blind", duration : 800 },
				hide    : { effect : "pulsate", duration : 1000 },
				track   : true
			} );
			$( this.duplicateSlideButton.selector ).tooltip( {
				content      : { title : this.cbVegasAjax.duplicateSlideButtonText },
				show         : { effect : "blind", duration : 800 },
				hide         : { effect : "pulsate", duration : 1000 },
				tooltipClass : "cb-vegas-tooltip",
				track        : true
			} );
			$( this.removeSlideButton.selector ).tooltip( {
				content      : { title : this.cbVegasAjax.removeSlideButtonText },
				show         : { effect : "blind", duration : 800 },
				hide         : { effect : "pulsate", duration : 1000 },
				tooltipClass : "cb-vegas-tooltip",
				track        : true
			} );
		}

	};

	$( document ).one( 'ready', function () {

		var plugin = new Plugin();
		plugin.init();

		//setTimeout( plugin.dismissNotice, 3600 );
	} );

} );
