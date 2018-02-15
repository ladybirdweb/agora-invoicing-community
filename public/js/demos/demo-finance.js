/*
Name: 			Finance
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.0.0
*/
// Demo Config
(function($) {

	'use strict';

	// Slider Options
	var sliderOptions = {
		sliderType: 'standard',
		sliderLayout: 'fullscreen',
		fullScreenOffsetContainer: '#header, #home-intro',
		delay: 10000,
		responsiveLevels: [4096, 1200, 991, 500],
		gridwidth: [1140, 960, 720, 540],
		gridheight: 650,
		lazyType: "none",
		shadow: 0,
		spinner: "off",
		shuffle: "off",
		autoHeight: "off",
		fullScreenAlignForce: "off",
		fullScreenOffset: "",
		disableProgressBar: "on",
		hideThumbsOnMobile: "off",
		hideSliderAtLimit: 0,
		hideCaptionAtLimit: 0,
		hideAllCaptionAtLilmit: 0,
		debugMode: false,
		fallbacks: {
			simplifyAll: "off",
			nextSlideOnWindowFocus: "off",
			disableFocusListener: false,
		},
		navigation: {
			keyboardNavigation: 'off',
			keyboard_direction: 'horizontal',
			mouseScrollNavigation: 'off',
			onHoverStop: 'off',
			touch: {
				touchenabled: 'on',
				swipe_threshold: 75,
				swipe_min_touches: 1,
				swipe_direction: 'horizontal',
				drag_block_vertical: false
			},
			arrows: {
				enable: true,
				hide_onmobile: true,
				hide_under: 991,
				hide_onleave: true,
				hide_delay: 200,
				hide_delay_mobile: 1200,
				left: {
					h_align: 'left',
					v_align: 'center',
					h_offset: 10,
					v_offset: 0
				},
				right: {
					h_align: 'right',
					v_align: 'center',
					h_offset: 10,
					v_offset: 0
				}
			}
		}
	}

	// Slider Init
	$('#revolutionSlider').revolution(sliderOptions);

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
	Contact Form Message - Home Page
	*/
	$('#callSendMessage').validate({
		submitHandler: function(form) {

			var $form = $(form),
				$messageSuccess = $('#contactFormSuccess'),
				$messageError = $('#contactFormError'),
				$submitButton = $(this.submitButton),
				$errorMessage = $('#contactFormErrorMessage'),
				submitButtonText = $submitButton.val();

			$submitButton.val( $submitButton.data('loading-text') ? $submitButton.data('loading-text') : 'Loading...' ).attr('disabled', true);

			// Ajax Submit
			$.ajax({
				type: 'POST',
				url: $form.attr('action'),
				data: {
					name: $form.find('#name').val(),
					email: $form.find('#email').val(),
					phone: $form.find('#phone').val(),
					subject: 'Finance - Home Contact Message',
					message: $form.find('#discuss').val(),
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
	Contact Form Message - Contact Us Page
	*/
	$('#contactForm').validate({
		submitHandler: function(form) {

			var $form = $(form),
				$messageSuccess = $('#contactFormSuccess'),
				$messageError = $('#contactFormError'),
				$submitButton = $(this.submitButton),
				$errorMessage = $('#contactFormErrorMessage'),
				submitButtonText = $submitButton.val();

			$submitButton.val( $submitButton.data('loading-text') ? $submitButton.data('loading-text') : 'Loading...' ).attr('disabled', true);

			// Ajax Submit
			$.ajax({
				type: 'POST',
				url: $form.attr('action'),
				data: {
					name: $form.find('#name').val(),
					email: $form.find('#email').val(),
					phone: $form.find('#phone').val(),
					selectSample: $form.find('#selectSample').val(),
					subject: 'Finance - Contact Message',
					message: $form.find('#message').val()
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

}).apply(this, [jQuery]);