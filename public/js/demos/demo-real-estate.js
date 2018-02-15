/*
Name: 			RealEstate
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.0.0
*/


/*
Header
*/

// Search Properties
var $headerWrapper = $('#headerSearchProperties'),
	$window = $(window);

$headerWrapper.on('click', function() {
	if ($window.width() > 992) {
		$headerWrapper.addClass('open');
	}
});

$(document).mouseup(function(e) {
	if (!$headerWrapper.is(e.target) && $headerWrapper.has(e.target).length === 0) {
		$headerWrapper.removeClass('open');
	}
});

$('#propertiesFormHeader').validate({
	onkeyup: false,
	onclick: false,
	onfocusout: false,
	errorPlacement: function(error, element) {
		if (element.attr('type') == 'radio' || element.attr('type') == 'checkbox') {
			error.appendTo(element.parent().parent());
		} else {
			error.insertAfter(element);
		}
	}
});

/*
Custom Rev Slider Numbers
*/

// Check and show total
$('#revolutionSlider').bind("revolution.slide.onloaded",function (e) {
	var max_slides = $(this).revmaxslide();

	$('.slides-number .total').text( max_slides );
});

// Show current slide number
$('#revolutionSlider').bind("revolution.slide.onchange",function (e,data) {
	var atual_slide = $(this).revcurrentslide();

	$('.slides-number .atual').text( atual_slide );
});

/*
* Isotope
*/
var $wrapper = $('.properties-listing');

if( $wrapper.get(0) ) {
	$wrapper.waitForImages(function() {
		$wrapper.isotope({
			itemSelector: '.isotope-item'
		});
	});
}

/*
Custom Listing Load More
*/
var listingLoadMore = {

	pages: 0,
	currentPage: 0,
	$wrapper: $('#listingLoadMoreWrapper'),
	$btn: $('#listingLoadMore'),
	$btnWrapper: $('#listingLoadMoreBtnWrapper'),
	$loader: $('#listingLoadMoreLoader'),

	build: function() {

		var self = this

		self.pages = self.$wrapper.data('total-pages');

		if(self.pages <= 1) {

			self.$btnWrapper.remove();
			return;

		} else {

			// init isotope
			self.$wrapper.isotope();

			self.$btn.on('click', function() {
				self.loadMore();
			});

			// Lazy Load
			if(self.$btn.hasClass('btn-listing-lazy-load')) {
				self.$btn.appear(function() {
					self.$btn.trigger('click');
				}, {
					data: undefined,
					one: false,
					accX: 0,
					accY: 0
				});
			}

			// Relayout Isotope on resize
			var $grid = self.$wrapper;
			
			$(window).on('resize', function() {
				setTimeout(function() {
					$grid.isotope('layout');
				}, 300);
			});

		}

	},
	loadMore: function() {

		var self = this;

		self.$btn.hide();
		self.$loader.show();

		// Ajax
		$.ajax({
			url: 'demo-real-estate-ajax-load-more.html',
			complete: function(data) {

				var $items = $(data.responseText);

				setTimeout(function() {

					self.$wrapper.append($items)

					self.$wrapper.isotope('appended', $items);

					self.currentPage++;

					if(self.currentPage < self.pages) {
						self.$btn.show().blur();
					} else {
						self.$btnWrapper.remove();
					}

					// Carousel
					$(function() {
						$('[data-plugin-carousel]:not(.manual), .owl-carousel:not(.manual)').each(function() {
							var $this = $(this),
								opts;

							var pluginOptions = theme.fn.getOptions($this.data('plugin-options'));
							if (pluginOptions)
								opts = pluginOptions;

							$this.themePluginCarousel(opts);
						});
					});

					self.$loader.hide();

				}, 1000);

			}
		});

	}

}

$window.on('load', function() {
	if($('#listingLoadMoreWrapper').get(0)) {
		listingLoadMore.build();
	}
});

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
