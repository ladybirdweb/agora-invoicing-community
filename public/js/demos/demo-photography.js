/*
Name: 			Photography
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	6.0.0
*/

// Demo Config
theme.PluginScrollToTop.initialize = function() {};

(function($) {

	'use strict';

	// Slider Options
	var sliderOptions = {
		sliderType: 'standard',
		sliderLayout: 'fullscreen',
		fullScreenOffsetContainer: '#header',
		delay: 5000,
		disableProgressBar: 'on',
		gridwidth: 1170,
		gridheight: 500,
		lazyType: "none",
		shadow: 0,
		spinner: "off",
		shuffle: "off",
		autoHeight: "off",
		fullScreenAlignForce: "off",
		fullScreenOffset: "",
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
			mouseScrollNavigation: "on",
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
				style: "arrows-portfolio-detail-1",
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

	if( $('.photography-demo-3').get(0) ) {
		sliderOptions.fullScreenOffsetContainer = null;
		sliderOptions.navigation.arrows.style = 'arrows-side-header-1';
		sliderOptions.navigation.arrows.hide_onleave = false;
	}

	if( $('.photography-demo-2').get(0) ) {
		sliderOptions.fullScreenOffsetContainer 	   = null;
		sliderOptions.navigation.arrows.style          = 'arrows-transparent-header-photography-1';
		sliderOptions.navigation.arrows.hide_onleave   = false;
		sliderOptions.navigation.arrows.left.h_offset  = 20;
		sliderOptions.navigation.arrows.right.h_offset = 20;
	}

	if( $('.rev_about_us').get(0) ) {
		sliderOptions.navigation.mouseScrollNavigation = false;
	}

	// Slider Init
	$(window).on('load', function(){
		$('#revolutionSlider').revolution(sliderOptions);
	});

	/*
	Custom Portfolio Details Load More 
	*/
	var portfolioDetailLoadMore = {

		pages: 0,
		currentPage: 0,
		$wrapper: $('#portfolioDetailLoadMoreWrapper'),
		$btn: $('#portfolioDetailLoadMore'),
		$btnWrapper: $('#portfolioDetailLoadMoreBtnWrapper'),
		$loader: $('#portfolioDetailLoadMoreLoader'),

		build: function() {

			var self = this

			self.pages = self.$wrapper.data('total-pages');

			if(self.pages <= 1) {

				self.$btnWrapper.remove();
				return;

			} else {

				// init isotope
				self.$wrapper.isotope();

				self.$btn.on('click', function() {
					self.loadMore();
				});

				// Lazy Load
				if(self.$btn.hasClass('btn-portfolioDetail-lazy-load')) {
					self.$btn.appear(function() {
						self.$btn.trigger('click');
					}, {
						data: undefined,
						one: false,
						accX: 0,
						accY: 0
					});
				}

			}

		},
		loadMore: function() {

			var self = this;

			self.$btn.hide();
			self.$loader.show();

			// Ajax
			$.ajax({
				url: 'ajax/demo-photography-portfolio-ajax-on-page-load-more.html',
				complete: function(data) {

					var $items = $(data.responseText);

					setTimeout(function() {

						self.$wrapper.append($items)

						self.$wrapper.isotope('appended', $items);

						self.$wrapper.isotope('layout');

						self.currentPage++;

						if(self.currentPage < self.pages) {
							self.$btn.show().blur();
						} else {
							self.$btnWrapper.remove();
						}

						self.$wrapper.on( 'layoutComplete', function( laidOutItems ) {

							// Carousel
							$(function() {
								$('[data-plugin-carousel]:not(.manual), .owl-carousel:not(.manual)').each(function() {
									var $this = $(this),
										opts;

									var pluginOptions = theme.fn.getOptions($this.data('plugin-options'));
									if (pluginOptions)
										opts = pluginOptions;

									$this.themePluginCarousel(opts);
									setTimeout(function(){
										$this.owlCarousel('refresh');
									}, 1);
								});
							});

							// Lazy Load
 							$(function() {
		 						$('[data-plugin-lazyload]:not(.manual)').each(function() {
	 								var $this = $(this),
	 									opts;

	 								var pluginOptions = theme.fn.getOptions($this.data('plugin-options'));
	 								if (pluginOptions)
	 									opts = pluginOptions;

	 								$this.themePluginLazyLoad(opts);
	 							});
	 						});
						});

						// Ajax On Page
						ajaxOnPagePortfolioDetails.build();

						self.$loader.hide();

						// Refresh Parallax
						$(window).trigger('scroll');

					}, 1000);

				}
			});

		}

	}

	if($('#portfolioDetailLoadMoreWrapper').get(0)) {
		portfolioDetailLoadMore.build();
	}

	/*
	Custom Portfolio Infinite Scroll 
	*/
	var portfolioInfiniteScroll = {

		pages: 0,
		currentPage: 0,
		$wrapper: $('.portfolioInfiniteScrollWrapper'),
		$btn: $('#portfolioInfiniteScrollLoadMore'),
		$btnWrapper: $('#portfolioInfiniteScrollLoadMoreBtnWrapper'),
		$loader: $('#portfolioInfiniteScrollLoadMoreLoader'),

		build: function() {

			var self = this

			self.pages = self.$wrapper.data('total-pages');

			if(self.pages <= 1) {

				self.$btnWrapper.remove();
				return;

			} else {

				// init isotope
				self.$wrapper.isotope();

				self.$btn.on('click', function() {
					self.loadMore();
				});

				// Lazy Load
				if(self.$btn.hasClass('btn-portfolioInfiniteScroll-lazy-load')) {
					self.$btn.appear(function() {
						self.$btn.trigger('click');
					}, {
						data: undefined,
						one: false,
						accX: 0,
						accY: 0
					});
				}

			}

		},
		loadMore: function() {

			var self = this;

			self.$btn.hide();
			self.$loader.show();

			// Ajax
			$.ajax({
				url: 'ajax/demo-photography-portfolio-infinite-scroll-load-more.html',
				complete: function(data) {

					var $items = $(data.responseText);

					setTimeout(function() {

						self.$wrapper.append($items)

						self.$wrapper.isotope('appended', $items);

						self.$wrapper.isotope('layout');

						self.currentPage++;

						if(self.currentPage < self.pages) {
							self.$btn.show().blur();
						} else {
							self.$btnWrapper.remove();
						}

						self.$wrapper.on( 'layoutComplete', function( laidOutItems ) {
							// Add new items to gallery
							self.$wrapper.find('.item-appended').each(function(){
								var imgsrc      = $(this).find('.thumb-info-background').attr('data-src'),
									imgThumbsrc = $(this).find('.thumb-info-background').attr('data-thumb-src'),
									clone       = $('#thumbGalleryDetail .owl-item > div').first().clone(),
									cloneThumb  = $('#thumbGalleryThumbs .owl-item > div').first().clone();

								// Create images
								clone.find('img').attr('src',imgsrc);
								cloneThumb.find('img').attr('src',imgThumbsrc);

								// Add images
								$('#thumbGalleryDetail').owlCarousel().trigger('add.owl.carousel', [clone]);					
								$('#thumbGalleryThumbs').owlCarousel().trigger('add.owl.carousel', [cloneThumb]);

								$(this).removeClass('item-appended');
							});

							// Carousel
							$(function() {
								$('[data-plugin-carousel]:not(.manual), .owl-carousel:not(.manual)').each(function() {
									var $this = $(this),
										opts;

									var pluginOptions = theme.fn.getOptions($this.data('plugin-options'));
									if (pluginOptions)
										opts = pluginOptions;

									$this.themePluginCarousel(opts);
									setTimeout(function(){
										$this.owlCarousel('refresh');
									}, 1);
								});
							});

							// Lightbox - Clicked Item
							$('a[href="#photographyLightbox"]').on('click', function(){
								if($('.grid-sizer').get(0)) {
									clickedItem = $(this).parent().parent().index() - 1;
								} else {
									clickedItem = $(this).parent().parent().index();
								}
							});

							// Lightbox
							$('.popup-with-move-anim').magnificPopup(portfolioLightboxOptions);

						});



						self.$loader.hide();

						// Refresh Parallax
						$(window).trigger('scroll');

					}, 1000);

				}
			});

		}

	}

	if($('.portfolioInfiniteScrollWrapper').get(0)) {
		portfolioInfiniteScroll.build();
	}

	/*
	* Ajax on Page
	*/
	var ajaxOnPagePortfolioDetails = {

		pages: [],
		$ajaxBox: $('#porfolioDetailsAjaxBox'),
		$ajaxBoxContent: $('#porfolioDetailsAjaxBoxContent'),

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

			// Slider
			sliderOptions.navigation.mouseScrollNavigation = false;
			$('#revolutionSlider').revolution(sliderOptions);

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

	if($('#porfolioDetailsAjaxBox').get(0)) {
		ajaxOnPagePortfolioDetails.build();
	}

	/*
	* Portfolio Grid - Init isotope
	*/
	$(window).on('load', function() {
		var $portfolioGrid = $('#portfolioGrid'),
			$ourBlog       = $('#ourBlog'),
			gridSizer      = $portfolioGrid.attr('data-grid-sizer');

		// Portfolio Grid
		if( $portfolioGrid.get(0) ) {
			$portfolioGrid.isotope({
				itemSelector: '.isotope-item',
				masonry: {
					columnWidth: gridSizer,
					gutter: 0
				}
			});

			$portfolioGrid.isotope('layout');			
		}

		if( $ourBlog.get(0) ) {
			$ourBlog.isotope({
				itemSelector: '.isotope-item',
				masonry: {
					gutter: 0
				}
			});

			$ourBlog.isotope('layout');			
		}
	});

	/*
	* Horizontal Scroll
	*/
	var $horizontalScroll = {
		$horizontalScrollWrapper : $('#horizontalScrollBox'),
		$horizontalScrollContent : $('#horizontalScrollBox .content'),
		$horizontalScrollItem    : $('#horizontalScrollBox .content li'),

		build: function(){
			var self = this;

			self.setContentWidth();
			self.buttonNavigation();
			self.disableEnableButtons();
		},
		setContentWidth: function(){
			var self       = this,
				totalWidth = 0;

			$(self.$horizontalScrollItem).each(function(e){
				var boxImageWidth = $(this).outerWidth(true);

				totalWidth = (totalWidth + boxImageWidth) + 2.45;
			});

			// If Mozilla Firefox
		    if($('html.gecko').get(0)) {
		    	totalWidth = totalWidth + 2.7;
		    }

			self.$horizontalScrollContent.width( totalWidth );
		},
		isElementInView: function (element, fullyInView) {
	        var pageLeft = $(window).scrollLeft(),
	        	pageRight = pageLeft + $(window).width(),
	        	elementLeft = $(element).offset().left,
	        	elementRight = elementLeft + $(element).width();

	        if (fullyInView === true) {
	            return ((pageLeft < elementLeft) && (pageRight > elementRight));
	        } else {
	            return ((elementLeft <= pageRight) && (elementRight >= pageLeft));
	        }
	    },
	    buttonNavigation: function() {
	    	var self            = this,
	    		nextButton      = self.$horizontalScrollWrapper.find('.custom-portfolio-navigation .next'),
	    		prevButton      = self.$horizontalScrollWrapper.find('.custom-portfolio-navigation .prev'),
				totalItems      = self.$horizontalScrollItem.length - 1,
				screen          = 0,
				distance 		= 0,
				index           = 0,
				flag            = false,
				atualItem;

			prevButton.on('click', function(){
				if( !flag ){
			    	flag = true;

			        if( index > 0 ) {
			        	index--;
			        }
					atualItem  = self.$horizontalScrollItem.eq(index);
				    
					// Check if Element in View
					var isElementInView = self.isElementInView(atualItem, false);
				    if( !isElementInView ) {
				    	self.$horizontalScrollItem.each(function(){
				    		var inView = self.isElementInView($(this), false);

				    		if( inView ) {
				    			atualItem = $(this);
				    			return false;
				    		}
				    	});
				    }

				    // If Demo 3
				    if($('.photography-demo-3').get(0)) {
				    	screen = (($(window).width() - 255 ) - atualItem.outerWidth(true)) / 2;
				    } else {
				    	screen    = ($(window).width() - atualItem.outerWidth(true)) / 2;
				    }

				    distance  = (atualItem.position().left - screen);

		    		$('#horizontalScrollBox').animate({ scrollLeft: distance }, 300, function(){
			    		flag = false;
			    	});
				}
			});

			nextButton.on('click', function(){
				if( !flag ){
			        flag = true;

			        if( index < totalItems ) {
			        	index++;
			        }
			        atualItem = self.$horizontalScrollItem.eq(index);
				    
			        // Check if Element in View
				    var isElementInView = self.isElementInView(atualItem, false);
				    if( !isElementInView ) {
				    	self.$horizontalScrollItem.each(function(){
				    		var inView = self.isElementInView($(this), false);

				    		if( inView ) {
				    			atualItem = $(this);
				    			index = $(this).index();
				    			return false;
				    		}
				    	});
				    }

				    // If Demo 3
				    if($('.photography-demo-3').get(0)) {
				    	screen = (($(window).width() - 255 ) - atualItem.outerWidth(true)) / 2;
				    } else {
				    	screen    = ($(window).width() - atualItem.outerWidth(true)) / 2;
				    }
				    
				    distance  = (atualItem.position().left - screen);
		    		
		    		$('#horizontalScrollBox').animate({ scrollLeft: distance }, 300, function(){
			    		flag = false;
			    	});
				}
			});
	    },
	    disableEnableButtons: function(){
	    	var self = this,
	    		nextButton      = self.$horizontalScrollWrapper.find('.custom-portfolio-navigation .next'),
	    		prevButton      = self.$horizontalScrollWrapper.find('.custom-portfolio-navigation .prev');

	    	self.$horizontalScrollWrapper.on('scroll', function(){
	    		if( $(this).scrollLeft() == 0 ) {
		    		prevButton.addClass('disable-button');
		    	} else {
		    		prevButton.removeClass('disable-button');
		    	}

		    	if( $(this).scrollLeft() > ((self.$horizontalScrollContent.width() - $(window).width()) - 1) ) {
		    		nextButton.addClass('disable-button');
		    	} else {
		    		nextButton.removeClass('disable-button');
		    	}
	    	});
	    }
	}

	if($('#horizontalScrollBox').get(0)) {
		$horizontalScroll.build();

		var $window = $(window);

		// Mousewheel horizontal scroll
		$('#horizontalScrollBox').mousewheel(function(event, delta) {
			if ($window.width() > 991) {
				this.scrollLeft -= (delta * 60);
				event.preventDefault();
			}
		});

		// Build $horizontalScroll on resize
		$(window).afterResize(function() {
			$horizontalScroll.setContentWidth();
			
			if($('.photography-demo-2').get(0)) {
				$(".thumb-info-wrapper img").css('transition','ease all 5s');
				$(".thumb-info-wrapper img").on("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function(e){
				    $horizontalScroll.setContentWidth();
				});
			}
		});

		if($('.photography-demo-2').get(0)) {
			$(window).on('resize', function(){
				$(".thumb-info-wrapper img").css('transition','none');
			});	
		}
	}

	/*
	* Lightbox
	*/
	var clickedItem,
		removeShowThumbsTimeout,
		portfolioLightboxOptions = {
		type: 'inline',

		fixedContentPos: true,
		fixedBgPos: true,

		overflowY: 'hidden',

		closeBtnInside: true,
		preloader: false,

		midClick: true,
		removalDelay: 300,
		mainClass: 'photography-portfolio-gallery',

		callbacks: {
			open: function(){
				$('#thumbGalleryDetail').owlCarousel().trigger('refresh.owl.carousel');
				$('#thumbGalleryDetail').owlCarousel().trigger('to.owl.carousel', [clickedItem, 0]);

				removeShowThumbsTimeout = setTimeout(function(){
					$('#thumbGalleryThumbs').removeClass('show-thumbs');
				}, 3000);

				$(document).on('keydown', function( event ) {
				    if(event.keyCode == 37) {
				        $('#thumbGalleryDetail').trigger('prev.owl')
				    }
				    if(event.keyCode == 39) {
				        $('#thumbGalleryDetail').trigger('next.owl')
				    }
				});

				
			},
			close: function(){
				clearTimeout(removeShowThumbsTimeout);
				$('#thumbGalleryThumbs').addClass('show-thumbs');
				$(document).off('keydown');
			}
		}
	}

	if($('a[href="#photographyLightbox"]').get(0)) {
		$('a[href="#photographyLightbox"]').on('click', function(){
			if($('.grid-sizer').get(0)) {
				clickedItem = $(this).parent().parent().index() - 1;
			} else {
				clickedItem = $(this).parent().parent().index();
			}
		});

		$('a[href="#photographyLightbox"]').magnificPopup(portfolioLightboxOptions);
	}

	/*
	Thumb Gallery
	*/
	if( $('#photographyLightbox').get(0) ) {
		var $thumbGalleryDetail = $('#thumbGalleryDetail'),
			$thumbGalleryThumbs = $('#thumbGalleryThumbs'),
			flag = false,
			duration = 300;

		$thumbGalleryDetail
			.owlCarousel({
				items: 1,
				margin: 10,
				nav: true,
				dots: false,
				loop: false,
				navText: [],
				rtl: (($('html[dir="rtl"]').get(0)) ? true : false),
				onRefreshed: function(e){
					setTimeout(function(){
						$('.mfp-wrap.photography-portfolio-gallery').css('opacity',1);
					}, 300);
				}
			})
			.on('changed.owl.carousel', function(e) {
				if (!flag) {
					flag = true;
					$thumbGalleryThumbs.trigger('to.owl.carousel', [e.item.index-1, duration, true]);

					// add class to active thumb
					$thumbGalleryThumbs.find('.owl-item').removeClass('active-thumb');
					$thumbGalleryThumbs.find('.owl-item:eq('+ e.item.index +')').addClass('active-thumb');

					flag = false;
				}
			});

		$thumbGalleryThumbs
			.owlCarousel({
				margin: 15,
				items: 15,
				nav: false,
				center: false,
				dots: false,
				pagination: false,
				rtl: (($('html[dir="rtl"]').get(0)) ? true : false)
			})
			.on('click', '.owl-item', function() {
				$thumbGalleryDetail.trigger('to.owl.carousel', [$(this).index(), duration, true]);

				// add class to active thumb
				$thumbGalleryThumbs.find('.owl-item').removeClass('active-thumb');
				$(this).addClass('active-thumb');
			});

		// Set first item with active-thumb
		$thumbGalleryThumbs.find('.owl-item:eq(0)').addClass('active-thumb');

	}

	if( $('#portfolioSliderWithThumbs').get(0) ) {
		var $thumbGalleryDetail = $('#thumbGalleryDetail'),
			$thumbGalleryThumbs = $('#thumbGalleryThumbs'),
			flag = false,
			duration = 300;

		$thumbGalleryDetail
			.owlCarousel({
				items: 1,
				margin: 10,
				nav: true,
				dots: false,
				loop: false,
				navText: [],
				rtl: (($('html[dir="rtl"]').get(0)) ? true : false),
				onRefreshed: function(e){
					setTimeout(function(){
						$('.mfp-wrap.photography-portfolio-gallery').css('opacity',1);
					}, 300);
				}
			})
			.on('changed.owl.carousel', function(e) {
				if (!flag) {
					flag = true;
					$thumbGalleryThumbs.trigger('to.owl.carousel', [e.item.index-1, duration, true]);

					// add class to active thumb
					$thumbGalleryThumbs.find('.owl-item').removeClass('active-thumb');
					$thumbGalleryThumbs.find('.owl-item:eq('+ e.item.index +')').addClass('active-thumb');

					flag = false;
				}
			});

		$thumbGalleryThumbs
			.owlCarousel({
				margin: 15,
				items: 8,
				nav: false,
				center: false,
				dots: false,
				pagination: false,
				rtl: (($('html[dir="rtl"]').get(0)) ? true : false),
				responsive:{
			        0:{
			            items:2
			        },
			        300:{
			            items:3
			        },
			        767:{
			            items:6,
			        },
			        991:{
			            items:7,
			        },
			        1200:{
			            items:8,
			        }
			    }
			})
			.on('click', '.owl-item', function() {
				$thumbGalleryDetail.trigger('to.owl.carousel', [$(this).index(), duration, true]);

				// add class to active thumb
				$thumbGalleryThumbs.find('.owl-item').removeClass('active-thumb');
				$(this).addClass('active-thumb');
			});

			// Set first item with active-thumb
			$thumbGalleryThumbs.find('.owl-item:eq(0)').addClass('active-thumb');

		$(document).on('keydown', function( event ) {
		    if(event.keyCode == 37) {
		        $('#thumbGalleryDetail').trigger('prev.owl')
		    }
		    if(event.keyCode == 39) {
		        $('#thumbGalleryDetail').trigger('next.owl')
		    }
		});

	}

	/*
	Side Menu Navigation
	*/
	var sideHeaderNavigation = {
		$mainNav: $('#mainNav'),
		$mainNavItem: $('#mainNav li'),

		build: function(){
			var self = this;

			self.menuNav();
		},
		menuNav: function(){
			var self = this;

			self.$mainNavItem.on('click', function(e){
				var currentMenuItem = $(this),
					currentMenu 	= $(this).parent(),
					nextMenu        = $(this).find('ul').first(),
					isSubMenu       = currentMenuItem.hasClass('dropdown') || currentMenuItem.hasClass('dropdown-submenu'),
					isBack          = currentMenuItem.hasClass('back-button');

				if( isSubMenu ) {
					currentMenu.addClass('next-menu');
					nextMenu.addClass('visible');
				}

				if( isBack ) {
					currentMenu.parent().parent().removeClass('next-menu');
					currentMenu.removeClass('visible');
				}

				e.stopPropagation();
			});
		}
	}

	if( $('.photography-demo-3').get(0) ) {
		sideHeaderNavigation.build();
	}

}).apply(this, [jQuery]);