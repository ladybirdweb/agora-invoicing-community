/*
Name: 			Corporate Law Office
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	4.5.0
*/

(function( $ ) {

	'use strict';

	// Slider
	$('#revolutionSlider').revolution({
		sliderType: 'standard',
		sliderLayout: 'fullwidth',
		delay: 9000,
		gridwidth: 1170,
		gridheight: 650,
		spinner: 'spinner3',
		disableProgressBar: 'on',
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
				style: "uranus",
				enable: true,
				hide_onmobile: false,
				hide_onleave: false,
				tmp: '',
				left: {
					h_align: "left",
					v_align: "center",
					h_offset: 20,
					v_offset: 0
				},
				right: {
					h_align: "right",
					v_align: "center",
					h_offset: 20,
					v_offset: 0
				}
			},
			bullets: {
				enable: true,
				hide_onmobile: true,
				style: "dione",
				hide_onleave: false,
				direction: "horizontal",
				h_align: "center",
				v_align: "bottom",
				h_offset: 20,
				v_offset: 30,
				space: 5,
				tmp: '<span class="tp-bullet-img-wrap">  <span class="tp-bullet-image"></span></span><span class="tp-bullet-title">{{title}}</span>'
			}
		}
	});

}).apply( this, [ jQuery ]);