/*
Name: 			Construction
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.0.0
*/

(function( $ ) {

	'use strict';

	// Nivo Slider
	if ($.isFunction($.fn.nivoSlider)) {
		$('#nivoSlider').nivoSlider({
			effect: 'random',
			slices: 15,
			boxCols: 8,
			boxRows: 4,
			animSpeed: 500,
			pauseTime: 3000,
			startSlide: 0,
			directionNav: true,
			controlNav: true,
			controlNavThumbs: false,
			pauseOnHover: true,
			manualAdvance: false,
			prevText: 'Prev',
			nextText: 'Next',
			randomStart: false,
			beforeChange: function(){},
			afterChange: function(){},
			slideshowEnd: function(){},
			lastSlide: function(){},
			afterLoad: function(){}
		});
	}

	// Thumb Gallery
	var $thumbGalleryDetail1 = $('#thumbGalleryDetail'),
		$thumbGalleryThumbs1 = $('#thumbGalleryThumbs'),
		flag = false,
		duration = 300;

	$thumbGalleryDetail1
		.owlCarousel({
			items: 1,
			margin: 10,
			nav: true,
			dots: false,
			loop: false,
			navText: [],
			rtl: (($('html[dir="rtl"]').get(0)) ? true : false)
		})
		.on('changed.owl.carousel', function(e) {
			if (!flag) {
				flag = true;
				$thumbGalleryThumbs1.trigger('to.owl.carousel', [e.item.index-1, duration, true]);
				flag = false;
			}
		});

	$thumbGalleryThumbs1
		.owlCarousel({
			margin: 15,
			items: 4,
			nav: false,
			center: false,
			dots: false,
			rtl: (($('html[dir="rtl"]').get(0)) ? true : false)
		})
		.on('click', '.owl-item', function() {
			$thumbGalleryDetail1.trigger('to.owl.carousel', [$(this).index(), duration, true]);
		})
		.on('changed.owl.carousel', function(e) {
			if (!flag) {
				flag = true;
				$thumbGalleryDetail1.trigger('to.owl.carousel', [e.item.index, duration, true]);
				flag = false;
			}
		});

}).apply( this, [ jQuery ]);