/*
Name: 			Medical
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.0.0
*/

(function( $ ) {

	'use strict';

	// Ajax on Page
	var ajaxOnPageMedical = {

		pages: [],
		$ajaxBox: $('#porfolioAjaxBoxMedical'),
		$ajaxBoxContent: $('#porfolioAjaxBoxContentMedical'),

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

	if($('#porfolioAjaxBoxMedical').get(0)) {
		ajaxOnPageMedical.build();
	}
	

}).apply( this, [ jQuery ]);