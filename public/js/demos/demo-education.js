/*
Name: 			Education
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
			responsiveLevels: [4096,1200,992,576],
			gridwidth:[1100,920,680,500],
			gridheight: 740,
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
					enable: true
				}
			},
		});
	}

	/*
	Countdown
	*/
	if( $('#countdown').get(0) ) {
		var countdown_date  = $('#countdown').data('countdown-date');

		$('#countdown').countdown(countdown_date).on('update.countdown', function(event) {
			var $this = $(this).html(event.strftime(
				'<span class="days text-color-primary"><span class="text-color-primary">%D</span> DAY%!d</span> '
				+ '<span class="hours text-color-primary"><span class="text-color-primary">%H</span> HOURS</span> '
				+ '<span class="minutes text-color-primary"><span class="text-color-primary">%M</span> MIN</span> '
				+ '<span class="seconds text-color-primary"><span class="text-color-primary">%S</span> SEC</span> '
			));
		});
	}

	/*
	* Register Form Validation
	*/
	$('#registerForm').validate({
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

}).apply( this, [ jQuery ]);