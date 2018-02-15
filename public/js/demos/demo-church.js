/*
Name: 			Church
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.0.0
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
	* Contact Form
	*/
	$('#contactFormMessage').validate({
		submitHandler: function(form) {

			var $form = $(form),
				$messageSuccess = $('#contactFormMessageSuccess'),
				$messageError = $('#contactFormMessageError'),
				$submitButton = $(this.submitButton),
				$errorMessage = $('#contactFormMessageErrorMessage'),
				submitButtonText = $submitButton.val();

			$submitButton.val( $submitButton.data('loading-text') ? $submitButton.data('loading-text') : 'Loading...' ).attr('disabled', true);

			// Ajax Submit
			$.ajax({
				type: 'POST',
				url: $form.attr('action'),
				data: {
					name: $form.find('#contactName').val(),
					email: $form.find('#contactEmail').val(),
					message: $form.find('#contactMessage').val(),
					subject: 'Talk to Us Received'
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