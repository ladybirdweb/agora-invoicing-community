/*
Name: 			Business Consulting
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.0.0
*/

(function( $ ) {

	'use strict';
	
	// Slider Options
	var sliderOptions = {
		sliderType: 'standard',
		sliderLayout: 'auto',
		delay: 5000,
		responsiveLevels: [1920, 1200, 992, 500],
		gridwidth: [1170, 970, 750],
		gridheight: 700,
		lazyType: "none",
		shadow: 0,
		spinner: "off",
		shuffle: "off",
		autoHeight: "on",
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
				style: "custom-arrows-style-1",
				left : {
			        container:"slider",
			        h_align:"left",
		            v_align:"center",
		            h_offset:0,
		            v_offset:0,
			    },
			    right : {
		            v_align:"center",
		            container:"slider",
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
		
	// Slider Init
	$('#revolutionSlider').revolution(sliderOptions);

}).apply( this, [ jQuery ]);