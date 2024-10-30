jQuery( function ( $ ) {
	"use strict";

	function Plugin () {

		this.switchContainer = $( '.cb-vegas-switch-container' );
		this.fancyselect     = $( '.cb-vegas-fancy-select' );
	}

	Plugin.prototype = {

		init : function () {
			this.initFancySelect();
			this.wrapCheckbox();
			this.bind();
		},

		initFancySelect : function () {
			this.fancyselect.fancySelect();
		},
		wrapCheckbox    : function () {
			$( "<div class='cb-vegas-switch-container'></div>" ).insertAfter( ".cb-vegas-switch" );
		},
		bind            : function () {
			this.switchContainer.on( 'click', { context : this }, this.toggleCheckbox );
		},

		toggleCheckbox : function ( event ) {
			event.preventDefault();

			$( this ).prev().attr( "checked", ! $( this ).prev().attr( "checked" ) );
		}
	};

	$( document ).one( 'ready', function () {

		var plugin = new Plugin();
		plugin.init();
	} );

} );
