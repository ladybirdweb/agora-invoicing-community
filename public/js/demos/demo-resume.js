/*
Name: 			Resume
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.0.0
*/

(function( $ ) {

	'use strict';

	// About Me
	$('#aboutMeMoreBtn').on('click', function() {
		$(this).hide();
		$('#aboutMeMore').toggleClass('about-me-more-visible');
		return false;
	});

	/*
	* Timeline
	*/
	var timelineHeightAdjust = {
		$timeline: $('#timeline'),
		$timelineBar: $('#timeline .timeline-bar'),
		$firstTimelineItem: $('#timeline .timeline-box').first(),
		$lastTimelineItem: $('#timeline .timeline-box').last(),

		build: function() {
			var self = this;

			self.adjustHeight();
		},
		adjustHeight: function() {
			var self                = this,
				calcFirstItemHeight = self.$firstTimelineItem.outerHeight(true) / 2,
				calcLastItemHeight  = self.$lastTimelineItem.outerHeight(true) / 2;

			// Set Timeline Bar Top and Bottom
			self.$timelineBar.css({
				top: calcFirstItemHeight,
				bottom: calcLastItemHeight
			});
		}
	}

	if( $('#timeline').get(0) ) {
		setTimeout(function(){
			// Adjust Timeline Height On Resize
			$(window).afterResize(function() {
				timelineHeightAdjust.build();
			});
		}, 1000);

		timelineHeightAdjust.build();
	}

	// Contact Form
	$('#callSendMessage').validate({
		submitHandler: function(form) {

			var $form = $(form),
				$messageSuccess = $('#contactSuccess'),
				$messageError = $('#contactError'),
				$submitButton = $(this.submitButton),
				$errorMessage = $('#contactErrorMessage'),
				submitButtonText = $submitButton.val();

			$submitButton.val( $submitButton.data('loading-text') ? $submitButton.data('loading-text') : 'Loading...' ).attr('disabled', true);

			// Ajax Submit
			$.ajax({
				type: 'POST',
				url: $form.attr('action'),
				data: {
					name: $form.find('#callName').val(),
					subject: $form.find('#callSubject').val(),
					message: $form.find('#message').val(),
					email: 'you@domain.com'
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
	* Header Image Anim
	*/
	var lastScrollTop = 0;

	$(window).on('scroll', function(){
	   var st = $(this).scrollTop();
	   
	   if (st > lastScrollTop){
	   		$('img[custom-anim]').css({
	   			transform: 'translate(0, -'+ st +'px)'
	   		});
	   } else {
	      $('img[custom-anim]').css({
	   			transform: 'translate(0, '+ -Math.abs(st) +'px)'
	   		});
	   }
	   lastScrollTop = st;
	});

	/*
	* Menu Movement
	*/
	var menuFloatingAnim = {
		$menuFloating: $('#header.header-floating .header-container > .header-row'),

		build: function() {
			var self = this;

			self.init();
		},
		init: function(){
			var self  = this,
				divisor = 0;

			$(window).scroll(function() {
			    var scrollPercent = 100 * $(window).scrollTop() / ($(document).height() - $(window).height()),
			    	st = $(this).scrollTop();

				divisor = $(document).height() / $(window).height();

			    self.$menuFloating.find('.header-column > .header-row').css({
			    	transform : 'translateY( calc('+ scrollPercent +'vh - '+ st / divisor +'px) )' 
			    });
			});
		}
	}

	if( $('.header-floating').get(0) ) {
		if( $(window).height() > 700 ) {
			menuFloatingAnim.build();
		}
	}

}).apply( this, [ jQuery ]);