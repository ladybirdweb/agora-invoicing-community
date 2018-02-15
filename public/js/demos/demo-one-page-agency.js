/*
Name: 			One Page Agency
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.0.0
*/

(function( $ ) {

	'use strict';

	var $window = $(window);

	/*
	* Slider Options
	*/
	var sliderOptions = {
		sliderType: 'standard',
		sliderLayout: 'fullscreen',
		delay: 5000,
		responsiveLevels: [1920, 1200, 992, 500],
		gridwidth: [1170, 970, 750],
		gridheight: 700,
		autoHeight: "on",
		spinner: "off",
		fullScreenAlignForce: "off",
		fullScreenOffset: "",
		disableProgressBar: "on",
		navigation: {
			keyboardNavigation: "on",
			keyboard_direction: "horizontal",
			mouseScrollNavigation: "off",
			onHoverStop: "off",
			touch: {
				touchenabled: "on",
				swipe_threshold: 75,
				swipe_min_touches: 1,
				swipe_direction: "horizontal",
				drag_block_vertical: false
			},
			arrows: {
				enable: true,
				style: "custom-rev-arrows-style-1",
				left : {
			        container:"slider",
			        h_align:"left",
		            v_align:"center",
		            h_offset:0,
		            v_offset:0,
			    },
			    right : {
		            container:"slider",
		            v_align:"center",
		            h_align:"right",
		            h_offset:0,
		            v_offset:0
			    }
			}
		},
		parallax:{
			type:"on",
			levels:[20,40,60,80,100],
			origo:"enterpoint",
			speed:400,
			bgparallax:"on",
			disable_onmobile:"off"
		}
	}
		
	/*
	* Slider Init
	*/
	$('#revolutionSlider').revolution(sliderOptions);

	/*
	* Collapse Menu Button
	*/
	$('.header-btn-collapse-nav').on('click', function(){
		 $('html, body').animate({
	        scrollTop: $(".header-btn-collapse-nav").offset().top - 18
	    }, 300);
	});
	
	/*
	* Isotope
	*/
    var $wrapper = $('#itemDetailGallery');

	if( $wrapper.get(0) ) {
		$wrapper.waitForImages(function() {
			$wrapper.isotope({
				itemSelector: '.isotope-item'
			});
		});
	}

	/*
	Load More
	*/
	var loadMore = {

		pages: 0,
		currentPage: 0,
		$wrapper: $('#loadMoreWrapper'),
		$btn: $('#loadMore'),
		$btnWrapper: $('#loadMoreBtnWrapper'),
		$loader: $('#loadMoreLoader'),

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
				if(self.$btn.hasClass('btn-lazy-load')) {
					self.$btn.appear(function() {
						self.$btn.trigger('click');
					}, {
						data: undefined,
						one: false,
						accX: 0,
						accY: 0
					});
				}

			}

		},
		loadMore: function() {

			var self = this;

			self.$btn.hide();
			self.$loader.show();

			// Ajax
			$.ajax({
				url: 'ajax/demo-one-page-agency-ajax-load-more.html',
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
		if($('#loadMoreWrapper').get(0)) {
			loadMore.build();
		}
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

	/*
	* Map and Contact Position
	*/
	var customContactPos = {
		$elements: $('.custom-contact-pos'),
		build: function() {
			var self = this;

			self.init();
		},
		init: function() {
			var self = this,
				elementHeight = [];

			// Get Map and Contact Box Height
			self.$elements.each(function(){
				elementHeight.push($(this).outerHeight());
			});

			// Set Map and Contact box with same height
			self.$elements.each(function(){
				$(this).css({
					height: Math.max.apply(null, elementHeight)
				})
			});

			// Set contact-box position over google maps
			$('.custom-contact-box').css({
				'margin-top': -Math.max.apply(null, elementHeight)
			});
		}
	}

	if( $('.custom-contact-pos').get(0) ) {
		customContactPos.build();
	}

	/*
	* Contact Form
	*/
	$('#contactForm').validate({
		submitHandler: function(form) {

			var $form = $(form),
				$messageSuccess = $('#contactSuccess'),
				$messageError = $('#contactError'),
				$submitButton = $(this.submitButton),
				$errorMessage = $('#mailErrorMessage'),
				submitButtonText = $submitButton.val();

			$submitButton.val( $submitButton.data('loading-text') ? $submitButton.data('loading-text') : 'Loading...' ).attr('disabled', true);

			// Ajax Submit
			$.ajax({
				type: 'POST',
				url: $form.attr('action'),
				data: {
					name: $form.find('#name').val(),
					email: $form.find('#email').val(),
					subject: 'Contact Form Received',
					message: $form.find('#message').val(),
				}
			}).always(function(data, textStatus, jqXHR) {

				$errorMessage.empty().hide();

				if (data.response == 'success') {

					$messageSuccess.removeClass('d-none');
					$messageError.addClass('d-none');

					// Reset Form
					$form.find('.form-control')
						.val('')
						.blur()
						.parent()
						.removeClass('has-success')
						.removeClass('has-danger')
						.find('label.error')
						.remove();

					if (($messageSuccess.offset().top - 80) < $(window).scrollTop()) {
						$('html, body').animate({
							scrollTop: $messageSuccess.offset().top - 80
						}, 300);
					}

					$form.find('.form-control').removeClass('error');

					$submitButton.val( submitButtonText ).attr('disabled', false);
					
					return;

				} else if (data.response == 'error' && typeof data.errorMessage !== 'undefined') {
					$errorMessage.html(data.errorMessage).show();
				} else {
					$errorMessage.html(data.responseText).show();
				}

				$messageError.removeClass('d-none');
				$messageSuccess.addClass('d-none');

				if (($messageError.offset().top - 80) < $(window).scrollTop()) {
					$('html, body').animate({
						scrollTop: $messageError.offset().top - 80
					}, 300);
				}

				$form.find('.has-success')
					.removeClass('has-success');
					
				$submitButton.val( submitButtonText ).attr('disabled', false);

			});
		}
	});

}).apply( this, [ jQuery ]);