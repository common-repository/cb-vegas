jQuery( function ( $ ) {
	"use strict";

	function Plugin () {
		this.slideshow         = Vegas;
		this.body         = $( 'body' );
		this.overlay_path = this.slideshow.overlay_path;
		this.slidesList   = [];
		this.shuffle      = this.slideshow.meta.slideshow_is_shuffle;
		this.overlay      = this.slideshow.meta.slideshow_is_overlay != this.slideshow.noneString ? this.slideshow.meta.slideshow_is_overlay : false;
		this.autoplay     = this.slideshow.meta.slideshow_is_autoplay;
		this.timer        = this.slideshow.meta.slideshow_is_timer;
	}

	Plugin.prototype = {

		constructor            : Plugin,
		getCustomSlideSettings : function () {

			var customSlideSettings = [];
			var Data                = this.slideshow;

			$.each( this.slideshow.slides, function ( i ) {
				customSlideSettings.push( {
					src                : Data.settings[i].src,
					slideDelay         : Data.settings[i].slideDelay,
					preload            : Data.settings[i].preload,
					preloadImage       : Data.settings[i].preloadImage,
					preloadVideo       : false,
					cover              : Data.settings[i].cover,
					color              : Data.settings[i].color,
					align              : Data.settings[i].align,
					valign             : Data.settings[i].valign,
					transition         : Data.settings[i].transition,
					transitionDuration : Data.settings[i].transitionDuration,
					animation          : Data.settings[i].animation,
					animationDuration  : Data.settings[i].animationDuration
				} );
			} );

			return customSlideSettings;
		},
		setSlidesList          : function () {

			this.slidesList = this.getCustomSlideSettings();
		},
		doVegas                : function () {
			var self = this;

			self.body.vegas( {
				overlay                 : self.overlay_path + self.overlay,
				autoplay                : self.autoplay,
				timer                   : self.timer,
				slides                  : self.shuffle ? self.slidesList.sort( function () {
						return 0.5 - Math.random()
					} ) : self.slidesList,
				slidesToKeep            : 1,
				firstTransition         : 'fade',
				firstTransitionDuration : 4000,

				walk : function ( i, slideSettings ) {

					slideSettings.slide              = i;
					slideSettings.preload            = self.slidesList[i].preload;
					slideSettings.preloadImage       = self.slidesList[i].preloadImage;
					slideSettings.preloadVideo       = self.slidesList[i].preloadVideo;
					slideSettings.src                = self.slidesList[i].src;
					slideSettings.delay              = parseInt( self.slidesList[i].slideDelay );
					slideSettings.shuffle            = self.shuffle;
					slideSettings.cover              = self.slidesList[i].cover;
					slideSettings.color              = self.slidesList[i].color;
					slideSettings.align              = self.slidesList[i].align;
					slideSettings.valign             = self.slidesList[i].valign;
					slideSettings.transition         = self.slidesList[i].transition;
					slideSettings.transitionDuration = parseInt( self.slidesList[i].transitionDuration );
					slideSettings.animation          = self.slidesList[i].animation;
					slideSettings.animationDuration  = parseInt( self.slidesList[i].animationDuration );
				}
			} );
		},
		run                    : function () {
			this.setSlidesList();
			this.doVegas();
		}
	};

	$( document ).one( 'ready', function () {

		var plugin = new Plugin();
		plugin.run();
	} );

} );
