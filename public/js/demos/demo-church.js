/*
Name: 			Church
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.2.0
*/

(function( $ ) {

	/*
	Slider
	*/
	if( $('#revolutionSlider').get(0) ) {
		$('#revolutionSlider').revolution({
			sliderType: 'standard',
			sliderLayout: 'auto',
			delay: 9000,
			responsiveLevels: [4096,1200,992,420],
			gridwidth:[1170,970,750],
			gridheight: 600,
			disableProgressBar: 'on',
			spinner: 'spinner3',
			parallax:{
				type:"on",
				levels:[20,40,60,80,100],
				origo:"enterpoint",
				speed:400,
				bgparallax:"on",
				disable_onmobile:"off"
			},
			navigation: {
				arrows: {
					style: "custom-arrows-style-1",
					enable: true,
					hide_onmobile: false,
					hide_onleave: true,
					left: {
						h_align: "left",
						v_align: "center",
						h_offset: 0,
						v_offset: 0
					},
					right: {
						h_align: "right",
						v_align: "center",
						h_offset: 0,
						v_offset: 0
					}
				}
			}
		});
	}

	/*
	Countdown
	*/
	if( $('#countdown').get(0) ) {
		var countdown_date  = $('#countdown').data('countdown-date'),
			countdown_title = $('#countdown').data('countdown-title');

		$('#countdown').countdown(countdown_date).on('update.countdown', function(event) {
			var $this = $(this).html(event.strftime(countdown_title
				+ '<span class="days custom-secondary-font"><span class="text-color-primary">%D</span> Day%!d</span> '
				+ '<span class="hours custom-secondary-font"><span class="text-color-primary">%H</span> Hrs</span> '
				+ '<span class="minutes custom-secondary-font"><span class="text-color-primary">%M</span> Min</span> '
				+ '<span class="seconds custom-secondary-font"><span class="text-color-primary">%S</span> Sec</span> '
			));
		});
	}

	/*
	* Validate
	*/
	if($('#contactFormMessage').get(0) ) {
		$('#contactFormMessage').validate({
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
	}

	/*
	* Ajax on Page
	*/
	var ajaxOnPagePortfolioDetails = {

		pages: [],
		$ajaxBox: $('#galleryAjaxBox'),
		$ajaxBoxContent: $('#galleryAjaxBoxContent'),

		build: function() {

			var self = this;

			$('a[data-ajax-on-page]').each(function() {
				self.add($(this));
			});

			$(document).on('mousedown', 'a[data-ajax-on-page]', function (ev) {
				if (ev.which == 2) {
					ev.preventDefault();
					return false;
				}
			});

		},

		add: function($el) {

			var self = this,
				href = $el.attr('data-href');

			self.pages.push(href);

			$el.on('click', function(e) {
				e.preventDefault();
				self.show(self.pages.indexOf(href));

				// Remove active from all items
				$('a[data-ajax-on-page]').find('.thumb-info-wrapper').removeClass('active');

				// Set active current selected item
				$(this).find('.thumb-info-wrapper').addClass('active');
			});

		},

		events: function() {

			var self = this;

			// Carousel
			if ($.isFunction($.fn['themePluginCarousel'])) {

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

			}

		},

		show: function(i) {

			var self = this;

			self.$ajaxBoxContent.empty();
			self.$ajaxBox.removeClass('ajax-box-init').addClass('ajax-box-loading');

			$('html, body').animate({
				scrollTop: self.$ajaxBox.offset().top - 100
			}, 300, 'easeOutQuad');

			// Ajax
			$.ajax({
				url: self.pages[i],
				complete: function(data) {
				
					setTimeout(function() {

						self.$ajaxBoxContent.html(data.responseText);
						self.$ajaxBox.removeClass('ajax-box-loading');

						self.events();

					}, 1000);

				}
			});

		}

	}

	if($('#galleryAjaxBox').get(0)) {
		ajaxOnPagePortfolioDetails.build();
	}

}).apply( this, [ jQuery ]);