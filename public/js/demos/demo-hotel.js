/*
Name: 			Hotel
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.0.0
*/

(function( $ ) {

	'use strict';

	// Slider
	$('#revolutionSlider').revolution({
		sliderType: 'standard',
		sliderLayout: 'fullwidth',
		delay: 5000,
		gridwidth: 1170,
		gridheight: 530,
		spinner: 'off',
		disableProgressBar: 'on',
		parallax:{
			type:"on",
			levels:[5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85],
			origo:"enterpoint",
			speed:400,
			bgparallax:"on",
			disable_onmobile:"off"
		},
		navigation: {
			keyboardNavigation:"off",
			keyboard_direction: "horizontal",
			mouseScrollNavigation:"off",
			onHoverStop:"on",
			touch:{
				touchenabled:"on",
				swipe_threshold: 75,
				swipe_min_touches: 1,
				swipe_direction: "horizontal",
				drag_block_vertical: false
			}
			,
			bullets: {
				enable:true,
				hide_onmobile:true,
				hide_under:778,
				style:"hermes",
				hide_onleave:false,
				direction:"horizontal",
				h_align:"center",
				v_align:"bottom",
				h_offset:0,
				v_offset:20,
				space:5,
				tmp:''
			}
		}
	});

	// Header
	var $headerWrapper = $('#headerBookNow');

	$headerWrapper.on('mousedown', function() {
		$headerWrapper.addClass('open');
	});

	$(document).mouseup(function(e) {
		if (!$headerWrapper.is(e.target) && $headerWrapper.has(e.target).length === 0 && !$(e.target).parents('.datepicker').get(0)) {
			$headerWrapper.removeClass('open');
		}
	});

	// DatePicker
	$('#bookNowArrivalHeader').datepicker({
		defaultDate: '+1d',
		startDate: '+1d',
		autoclose: true,
		orientation: (($('html[dir="rtl"]').get(0)) ? 'bottom right' : 'bottom'),
		container: '#header',
		rtl: (($('html[dir="rtl"]').get(0)) ? true : false)
	});

	$('#bookNowDepartureHeader').datepicker({
		defaultDate: '+2d',
		startDate: '+2d',
		autoclose: true,
		orientation: (($('html[dir="rtl"]').get(0)) ? 'bottom right' : 'bottom'),
		container: '#header',
		rtl: (($('html[dir="rtl"]').get(0)) ? true : false)
	});

	$(document).scroll(function(){
		$('#bookNowArrivalHeader, #bookNowDepartureHeader').datepicker('hide').blur();
	});

	$('#bookNowArrival').datepicker({
		defaultDate: '+1d',
		startDate: '+1d',
		autoclose: true,
		orientation: (($('html[dir="rtl"]').get(0)) ? 'bottom right' : 'bottom'),
		container: '#bookForm',
		rtl: (($('html[dir="rtl"]').get(0)) ? true : false)
	});

	$('#bookNowDeparture').datepicker({
		defaultDate: '+2d',
		startDate: '+2d',
		autoclose: true,
		orientation: (($('html[dir="rtl"]').get(0)) ? 'bottom right' : 'bottom'),
		container: '#bookForm',
		rtl: (($('html[dir="rtl"]').get(0)) ? true : false)
	});

	// Book Form
	$('#bookFormHeader').validate({
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

	$('#bookForm').validate({
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

	// Custom Video
	$('.play-video-custom').magnificPopup({
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,

		fixedContentPos: false
	});

}).apply( this, [ jQuery ]);