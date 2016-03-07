/*
Name: 			Shortcodes - Lightboxes - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	4.5.0
*/

(function( $ ) {

	'use strict';

	/*
	Popup with video or map
	*/
	$('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
		disableOn: 700,
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,

		fixedContentPos: false
	});

	/*
	Dialog with CSS animation
	*/
	$('.popup-with-zoom-anim').magnificPopup({
		type: 'inline',

		fixedContentPos: false,
		fixedBgPos: true,

		overflowY: 'auto',

		closeBtnInside: true,
		preloader: false,

		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-zoom-in'
	});

	$('.popup-with-move-anim').magnificPopup({
		type: 'inline',

		fixedContentPos: false,
		fixedBgPos: true,

		overflowY: 'auto',

		closeBtnInside: true,
		preloader: false,

		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-slide-bottom'
	});

	/*
	Form
	*/
	$('.popup-with-form').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#name',

		// When elemened is focused, some mobile browsers in some cases zoom in
		// It looks not nice, so we disable it:
		callbacks: {
			open: function() {
				$('body').addClass('lightbox-opened');
			},
			close: function() {
				$('body').removeClass('lightbox-opened');
			},
			beforeOpen: function() {
				if($(window).width() < 700) {
					this.st.focus = false;
				} else {
					this.st.focus = '#name';
				}
			}
		}
	});

	/*
	Ajax
	*/
	$('.simple-ajax-popup').magnificPopup({
		type: 'ajax',
		callbacks: {
			open: function() {
				$('body').addClass('lightbox-opened');
			},
			close: function() {
				$('body').removeClass('lightbox-opened');
			}
		}
	});

}).apply( this, [ jQuery ]);