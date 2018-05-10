/*
Name: 			Insurance
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.2.0
*/

(function( $ ) {

	'use strict';
	
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
			gridheight: 645,
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
					enable: false,
				},
				bullets: {
			        enable: true,
			        style: 'hesperiden custom-bullets-style-1',
			        h_align: 'center',
			        v_align: 'bottom',
			        v_offset: 35,
			        space: 5
			    }
			},
		});
	}
    
}).apply( this, [ jQuery ]);