/*
Name: 			Shortcodes - Image Gallery - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.0.0
*/
(function($) {

	'use strict';

	/*
	Thumb Gallery
	*/
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
			rtl: ( $('html').attr('dir') == 'rtl' ) ? true : false
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
			rtl: ( $('html').attr('dir') == 'rtl' ) ? true : false
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

	/*
	Thumb Gallery 2
	*/
	var $thumbGalleryDetail2 = $('#thumbGalleryDetail2'),
		$thumbGalleryThumbs2 = $('#thumbGalleryThumbs2'),
		flag = false,
		duration = 300;

	$thumbGalleryDetail2
		.owlCarousel({
			items: 1,
			margin: 10,
			nav: false,
			dots: false,
			rtl: ( $('html').attr('dir') == 'rtl' ) ? true : false
		})
		.on('changed.owl.carousel', function(e) {
			if (!flag) {
				flag = true;
				$thumbGalleryThumbs2.trigger('to.owl.carousel', [e.item.index, duration, true]);
				flag = false;
			}
		});

	$thumbGalleryThumbs2
		.owlCarousel({
			margin: 15,
			items: 4,
			nav: false,
			center: true,
			dots: false,
			rtl: ( $('html').attr('dir') == 'rtl' ) ? true : false
		})
		.on('click', '.owl-item', function() {
			$thumbGalleryDetail2.trigger('to.owl.carousel', [$(this).index(), duration, true]);

		})
		.on('changed.owl.carousel', function(e) {
			if (!flag) {
				flag = true;
				$thumbGalleryDetail2.trigger('to.owl.carousel', [e.item.index, duration, true]);
				flag = false;
			}
		});

}).apply(this, [jQuery]);