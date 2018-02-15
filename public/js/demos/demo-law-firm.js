/*
Name: 			Law Firm
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.0.0
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
		parallax: {
			type: 'off',
			bgparallax: 'off'
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

	// Combination Filters
	if($('#combinationFilters').get(0)) {

		$(window).on('load', function() {

			var $grid = $('.team-list').isotope({
				itemSelector: '.isotope-item'
			});

			var filters = {};

			$('.filters').on('click', 'a', function(e) {
				
				e.preventDefault();
				
				var $this = $(this);

				var $buttonGroup = $this.parents('.team-filter-group');
				var filterGroup = $buttonGroup.attr('data-filter-group');
				
				filters[filterGroup] = $this.parent().attr('data-option-value');
				
				var filterValue = concatValues(filters);
				
				$grid.isotope({
					filter: filterValue
				});
			});

			$('.team-filter-group').each(function(i, buttonGroup) {
				var $buttonGroup = $(buttonGroup);
				$buttonGroup.on('click', 'a', function() {
					$buttonGroup.find('.active').removeClass('active');
					$(this).addClass('active');
				});
			});

			var concatValues = function(obj) {
				var value = '';
				for (var prop in obj) {
					value += obj[prop];
				}
				return value;
			}

			$(window).on('resize', function() {
				setTimeout(function() {
					$grid.isotope('layout');
				}, 300);
			});

		});

		

	}

}).apply( this, [ jQuery ]);