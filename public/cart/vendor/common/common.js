/*
Plugin Name: 	BrowserSelector
Written by: 	Okler Themes - (http://www.okler.net)
Version: 		4.5.0
*/

(function($) {
	$.extend({

		browserSelector: function() {

			// jQuery.browser.mobile (http://detectmobilebrowser.com/)
			(function(a){(jQuery.browser=jQuery.browser||{}).mobile=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);

			// Touch
			var hasTouch = 'ontouchstart' in window || navigator.msMaxTouchPoints;

			var u = navigator.userAgent,
				ua = u.toLowerCase(),
				is = function (t) {
					return ua.indexOf(t) > -1;
				},
				g = 'gecko',
				w = 'webkit',
				s = 'safari',
				o = 'opera',
				h = document.documentElement,
				b = [(!(/opera|webtv/i.test(ua)) && /msie\s(\d)/.test(ua)) ? ('ie ie' + parseFloat(navigator.appVersion.split("MSIE")[1])) : is('firefox/2') ? g + ' ff2' : is('firefox/3.5') ? g + ' ff3 ff3_5' : is('firefox/3') ? g + ' ff3' : is('gecko/') ? g : is('opera') ? o + (/version\/(\d+)/.test(ua) ? ' ' + o + RegExp.jQuery1 : (/opera(\s|\/)(\d+)/.test(ua) ? ' ' + o + RegExp.jQuery2 : '')) : is('konqueror') ? 'konqueror' : is('chrome') ? w + ' chrome' : is('iron') ? w + ' iron' : is('applewebkit/') ? w + ' ' + s + (/version\/(\d+)/.test(ua) ? ' ' + s + RegExp.jQuery1 : '') : is('mozilla/') ? g : '', is('j2me') ? 'mobile' : is('iphone') ? 'iphone' : is('ipod') ? 'ipod' : is('mac') ? 'mac' : is('darwin') ? 'mac' : is('webtv') ? 'webtv' : is('win') ? 'win' : is('freebsd') ? 'freebsd' : (is('x11') || is('linux')) ? 'linux' : '', 'js'];

			c = b.join(' ');

			if ($.browser.mobile) {
				c += ' mobile';
			}

			if (hasTouch) {
				c += ' touch';
			}

			h.className += ' ' + c;

			// IE11 Detect
			var isIE11 = !(window.ActiveXObject) && "ActiveXObject" in window;

			if (isIE11) {
				$('html').removeClass('gecko').addClass('ie ie11');
				return;
			}

			// Dark and Boxed Compatibility
			if($('body').hasClass('dark')) {
				$('html').addClass('dark');
			}

			if($('body').hasClass('boxed')) {
				$('html').addClass('boxed');
			}

		}

	});

	$.browserSelector();

})(jQuery);

/*
Plugin Name: 	waitForImages
Written by: 	https://github.com/alexanderdickson/waitForImages
*/

/*! waitForImages jQuery Plugin - v2.0.2 - 2015-05-05
* https://github.com/alexanderdickson/waitForImages
* Copyright (c) 2015 Alex Dickson; Licensed MIT */
;(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // CommonJS / nodejs module
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {
    // Namespace all events.
    var eventNamespace = 'waitForImages';

    // CSS properties which contain references to images.
    $.waitForImages = {
        hasImageProperties: [
            'backgroundImage',
            'listStyleImage',
            'borderImage',
            'borderCornerImage',
            'cursor'
        ],
        hasImageAttributes: ['srcset']
    };

    // Custom selector to find `img` elements that have a valid `src`
    // attribute and have not already loaded.
    $.expr[':'].uncached = function (obj) {
        // Ensure we are dealing with an `img` element with a valid
        // `src` attribute.
        if (!$(obj).is('img[src][src!=""]')) {
            return false;
        }

        return !obj.complete;
    };

    $.fn.waitForImages = function () {

        var allImgsLength = 0;
        var allImgsLoaded = 0;
        var deferred = $.Deferred();

        var finishedCallback;
        var eachCallback;
        var waitForAll;

        // Handle options object (if passed).
        if ($.isPlainObject(arguments[0])) {

            waitForAll = arguments[0].waitForAll;
            eachCallback = arguments[0].each;
            finishedCallback = arguments[0].finished;

        } else {

            // Handle if using deferred object and only one param was passed in.
            if (arguments.length === 1 && $.type(arguments[0]) === 'boolean') {
                waitForAll = arguments[0];
            } else {
                finishedCallback = arguments[0];
                eachCallback = arguments[1];
                waitForAll = arguments[2];
            }

        }

        // Handle missing callbacks.
        finishedCallback = finishedCallback || $.noop;
        eachCallback = eachCallback || $.noop;

        // Convert waitForAll to Boolean
        waitForAll = !! waitForAll;

        // Ensure callbacks are functions.
        if (!$.isFunction(finishedCallback) || !$.isFunction(eachCallback)) {
            throw new TypeError('An invalid callback was supplied.');
        }

        this.each(function () {
            // Build a list of all imgs, dependent on what images will
            // be considered.
            var obj = $(this);
            var allImgs = [];
            // CSS properties which may contain an image.
            var hasImgProperties = $.waitForImages.hasImageProperties || [];
            // Element attributes which may contain an image.
            var hasImageAttributes = $.waitForImages.hasImageAttributes || [];
            // To match `url()` references.
            // Spec: http://www.w3.org/TR/CSS2/syndata.html#value-def-uri
            var matchUrl = /url\(\s*(['"]?)(.*?)\1\s*\)/g;

            if (waitForAll) {

                // Get all elements (including the original), as any one of
                // them could have a background image.
                obj.find('*').addBack().each(function () {
                    var element = $(this);

                    // If an `img` element, add it. But keep iterating in
                    // case it has a background image too.
                    if (element.is('img:uncached')) {
                        allImgs.push({
                            src: element.attr('src'),
                            element: element[0]
                        });
                    }

                    $.each(hasImgProperties, function (i, property) {
                        var propertyValue = element.css(property);
                        var match;

                        // If it doesn't contain this property, skip.
                        if (!propertyValue) {
                            return true;
                        }

                        // Get all url() of this element.
                        while (match = matchUrl.exec(propertyValue)) {
                            allImgs.push({
                                src: match[2],
                                element: element[0]
                            });
                        }
                    });

                    $.each(hasImageAttributes, function (i, attribute) {
                        var attributeValue = element.attr(attribute);
                        var attributeValues;

                        // If it doesn't contain this property, skip.
                        if (!attributeValue) {
                            return true;
                        }

                        // Check for multiple comma separated images
                        attributeValues = attributeValue.split(',');

                        $.each(attributeValues, function(i, value) {
                            // Trim value and get string before first
                            // whitespace (for use with srcset).
                            value = $.trim(value).split(' ')[0];
                            allImgs.push({
                                src: value,
                                element: element[0]
                            });
                        });
                    });
                });
            } else {
                // For images only, the task is simpler.
                obj.find('img:uncached')
                    .each(function () {
                    allImgs.push({
                        src: this.src,
                        element: this
                    });
                });
            }

            allImgsLength = allImgs.length;
            allImgsLoaded = 0;

            // If no images found, don't bother.
            if (allImgsLength === 0) {
                finishedCallback.call(obj[0]);
                deferred.resolveWith(obj[0]);
            }

            $.each(allImgs, function (i, img) {

                var image = new Image();
                var events =
                  'load.' + eventNamespace + ' error.' + eventNamespace;

                // Handle the image loading and error with the same callback.
                $(image).one(events, function me (event) {
                    // If an error occurred with loading the image, set the
                    // third argument accordingly.
                    var eachArguments = [
                        allImgsLoaded,
                        allImgsLength,
                        event.type == 'load'
                    ];
                    allImgsLoaded++;

                    eachCallback.apply(img.element, eachArguments);
                    deferred.notifyWith(img.element, eachArguments);

                    // Unbind the event listeners. I use this in addition to
                    // `one` as one of those events won't be called (either
                    // 'load' or 'error' will be called).
                    $(this).off(events, me);

                    if (allImgsLoaded == allImgsLength) {
                        finishedCallback.call(obj[0]);
                        deferred.resolveWith(obj[0]);
                        return false;
                    }

                });

                image.src = img.src;
            });
        });

        return deferred.promise();

    };
}));

/*
Plugin Name: 	Count To
Written by: 	Matt Huggins - https://github.com/mhuggins/jquery-countTo
*/

(function ($) {
	$.fn.countTo = function (options) {
		options = options || {};

		return $(this).each(function () {
			// set options for current element
			var settings = $.extend({}, $.fn.countTo.defaults, {
				from:            $(this).data('from'),
				to:              $(this).data('to'),
				speed:           $(this).data('speed'),
				refreshInterval: $(this).data('refresh-interval'),
				decimals:        $(this).data('decimals')
			}, options);

			// how many times to update the value, and how much to increment the value on each update
			var loops = Math.ceil(settings.speed / settings.refreshInterval),
				increment = (settings.to - settings.from) / loops;

			// references & variables that will change with each update
			var self = this,
				$self = $(this),
				loopCount = 0,
				value = settings.from,
				data = $self.data('countTo') || {};

			$self.data('countTo', data);

			// if an existing interval can be found, clear it first
			if (data.interval) {
				clearInterval(data.interval);
			}
			data.interval = setInterval(updateTimer, settings.refreshInterval);

			// initialize the element with the starting value
			render(value);

			function updateTimer() {
				value += increment;
				loopCount++;

				render(value);

				if (typeof(settings.onUpdate) == 'function') {
					settings.onUpdate.call(self, value);
				}

				if (loopCount >= loops) {
					// remove the interval
					$self.removeData('countTo');
					clearInterval(data.interval);
					value = settings.to;

					if (typeof(settings.onComplete) == 'function') {
						settings.onComplete.call(self, value);
					}
				}
			}

			function render(value) {
				var formattedValue = settings.formatter.call(self, value, settings);
				$self.html(formattedValue);
			}
		});
	};

	$.fn.countTo.defaults = {
		from: 0,               // the number the element should start at
		to: 0,                 // the number the element should end at
		speed: 1000,           // how long it should take to count between the target numbers
		refreshInterval: 100,  // how often the element should be updated
		decimals: 0,           // the number of decimal places to show
		formatter: formatter,  // handler for formatting the value before rendering
		onUpdate: null,        // callback method for every time the element is updated
		onComplete: null       // callback method for when the element finishes updating
	};

	function formatter(value, settings) {
		return value.toFixed(settings.decimals);
	}
}(jQuery));

/*
Plugin Name: 	afterResize.js
Written by: 	https://github.com/mcshaman/afterResize.js
Description: 	Simple jQuery plugin designed to emulate an 'after resize' event.
*/

( function( $ ) {
	"use strict";
	
	// Define default settings
	var defaults = {
		action: function() {},
		runOnLoad: false,
		duration: 500
	};
	
	// Define global variables
	var settings = defaults,
		running = false,
		start;
	
	var methods = {};
	
	// Initial plugin configuration
	methods.init = function() {
		
		// Allocate passed arguments to settings based on type
		for( var i = 0; i <= arguments.length; i++ ) {
			var arg = arguments[i];
			switch ( typeof arg ) {
				case "function":
					settings.action = arg;
					break;
				case "boolean":
					settings.runOnLoad = arg;
					break;
				case "number":
					settings.duration = arg;
					break;
			}
		}
	
		// Process each matching jQuery object
		return this.each(function() {
		
			if( settings.runOnLoad ) { settings.action(); }
			
			$(this).resize( function() {
				
				methods.timedAction.call( this );
				
			} );
		
		} );
	};
	
	methods.timedAction = function( code, millisec ) {
		
		var doAction = function() {
			var remaining = settings.duration;
			
			if( running ) {
				var elapse = new Date() - start;
				remaining = settings.duration - elapse;
				if( remaining <= 0 ) {
					// Clear timeout and reset running variable
					clearTimeout(running);
					running = false;
					// Perform user defined function
					settings.action();
				
					return;
				}
			}
			wait( remaining );
		};
		
		var wait = function( time ) {
			running = setTimeout( doAction, time );
		};
		
		// Define new action starting time
		start = new Date();
		
		// Define runtime settings if function is run directly
		if( typeof millisec === 'number' ) { settings.duration = millisec; }
		if( typeof code === 'function' ) { settings.action = code; }
		
		// Only run timed loop if not already running
		if( !running ) { doAction(); }
		
	};

	
	$.fn.afterResize = function( method ) {
		
		if( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ) );
		} else {
			return methods.init.apply( this, arguments );
		}
		
	};
	
})(jQuery);

/*
Plugin Name: 	matchHeight
Written by: 	Okler Themes - (http://www.okler.net)
Version: 		4.5.0

Based on:

	jquery.matchHeight-min.js v0.5.2
	Licensed under the terms of the MIT license.

*/

;(function($) {
    /*
    *  internal
    */

    var _previousResizeWidth = -1,
        _updateTimeout = -1;

    /*
    *  _rows
    *  utility function returns array of jQuery selections representing each row
    *  (as displayed after float wrapping applied by browser)
    */

    var _rows = function(elements) {
        var tolerance = 1,
            $elements = $(elements),
            lastTop = null,
            rows = [];

        // group elements by their top position
        $elements.each(function(){
            var $that = $(this),
                top = $that.offset().top - _parse($that.css('margin-top')),
                lastRow = rows.length > 0 ? rows[rows.length - 1] : null;

            if (lastRow === null) {
                // first item on the row, so just push it
                rows.push($that);
            } else {
                // if the row top is the same, add to the row group
                if (Math.floor(Math.abs(lastTop - top)) <= tolerance) {
                    rows[rows.length - 1] = lastRow.add($that);
                } else {
                    // otherwise start a new row group
                    rows.push($that);
                }
            }

            // keep track of the last row top
            lastTop = top;
        });

        return rows;
    };

    /*
    *  _parse
    *  value parse utility function
    */

    var _parse = function(value) {
        // parse value and convert NaN to 0
        return parseFloat(value) || 0;
    };

    /*
    *  _parseOptions
    *  handle plugin options
    */

    var _parseOptions = function(options) {
        var opts = {
            byRow: true,
            remove: false,
            property: 'height'
        };

        if (typeof options === 'object') {
            return $.extend(opts, options);
        }

        if (typeof options === 'boolean') {
            opts.byRow = options;
        } else if (options === 'remove') {
            opts.remove = true;
        }

        return opts;
    };

    /*
    *  matchHeight
    *  plugin definition
    */

    var matchHeight = $.fn.matchHeight = function(options) {
        var opts = _parseOptions(options);

        // handle remove
        if (opts.remove) {
            var that = this;

            // remove fixed height from all selected elements
            this.css(opts.property, '');

            // remove selected elements from all groups
            $.each(matchHeight._groups, function(key, group) {
                group.elements = group.elements.not(that);
            });

            // TODO: cleanup empty groups

            return this;
        }

        if (this.length <= 1)
            return this;

        // keep track of this group so we can re-apply later on load and resize events
        matchHeight._groups.push({
            elements: this,
            options: opts
        });

        // match each element's height to the tallest element in the selection
        matchHeight._apply(this, opts);

        return this;
    };

    /*
    *  plugin global options
    */

    matchHeight._groups = [];
    matchHeight._throttle = 80;
    matchHeight._maintainScroll = false;
    matchHeight._beforeUpdate = null;
    matchHeight._afterUpdate = null;

    /*
    *  matchHeight._apply
    *  apply matchHeight to given elements
    */

    matchHeight._apply = function(elements, options) {
        var opts = _parseOptions(options),
            $elements = $(elements),
            rows = [$elements];

        // take note of scroll position
        var scrollTop = $(window).scrollTop(),
            htmlHeight = $('html').outerHeight(true);

        // get hidden parents
        var $hiddenParents = $elements.parents().filter(':hidden');

        // cache the original inline style
        $hiddenParents.each(function() {
            var $that = $(this);
            $that.data('style-cache', $that.attr('style'));
        });

        // temporarily must force hidden parents visible
        $hiddenParents.css('display', 'block');

        // get rows if using byRow, otherwise assume one row
        if (opts.byRow) {

            // must first force an arbitrary equal height so floating elements break evenly
            $elements.each(function() {
                var $that = $(this),
                    display = $that.css('display') === 'inline-block' ? 'inline-block' : 'block';

                // cache the original inline style
                $that.data('style-cache', $that.attr('style'));

                $that.css({
                    'display': display,
                    'padding-top': '0',
                    'padding-bottom': '0',
                    'margin-top': '0',
                    'margin-bottom': '0',
                    'border-top-width': '0',
                    'border-bottom-width': '0',
                    'height': '100px'
                });
            });

            // get the array of rows (based on element top position)
            rows = _rows($elements);

            // revert original inline styles
            $elements.each(function() {
                var $that = $(this);
                $that.attr('style', $that.data('style-cache') || '');
            });
        }

        $.each(rows, function(key, row) {
            var $row = $(row),
                maxHeight = 0;

            // skip apply to rows with only one item
            if (opts.byRow && $row.length <= 1) {
                $row.css(opts.property, '');
                return;
            }

            // iterate the row and find the max height
            $row.each(function(){
                var $that = $(this),
                    display = $that.css('display') === 'inline-block' ? 'inline-block' : 'block';

                // ensure we get the correct actual height (and not a previously set height value)
                var css = { 'display': display };
                css[opts.property] = '';
                $that.css(css);

                // find the max height (including padding, but not margin)
                if ($that.outerHeight(false) > maxHeight)
                    maxHeight = $that.outerHeight(false);

                // revert display block
                $that.css('display', '');
            });

            // iterate the row and apply the height to all elements
            $row.each(function(){
                var $that = $(this),
                    verticalPadding = 0;

                // handle padding and border correctly (required when not using border-box)
                if ($that.css('box-sizing') !== 'border-box') {
                    verticalPadding += _parse($that.css('border-top-width')) + _parse($that.css('border-bottom-width'));
                    verticalPadding += _parse($that.css('padding-top')) + _parse($that.css('padding-bottom'));
                }

                // set the height (accounting for padding and border)
                $that.css(opts.property, maxHeight - verticalPadding);
            });
        });

        // revert hidden parents
        $hiddenParents.each(function() {
            var $that = $(this);
            $that.attr('style', $that.data('style-cache') || null);
        });

        // restore scroll position if enabled
        if (matchHeight._maintainScroll)
            $(window).scrollTop((scrollTop / htmlHeight) * $('html').outerHeight(true));

        return this;
    };

    /*
    *  matchHeight._applyDataApi
    *  applies matchHeight to all elements with a data-match-height attribute
    */

    matchHeight._applyDataApi = function() {
        var groups = {};

        // generate groups by their groupId set by elements using data-match-height
        $('[data-match-height], [data-mh]').each(function() {
            var $this = $(this),
                groupId = $this.attr('data-match-height') || $this.attr('data-mh');
            if (groupId in groups) {
                groups[groupId] = groups[groupId].add($this);
            } else {
                groups[groupId] = $this;
            }
        });

        // apply matchHeight to each group
        $.each(groups, function() {
            this.matchHeight(true);
        });
    };

    /*
    *  matchHeight._update
    *  updates matchHeight on all current groups with their correct options
    */

    var _update = function(event) {
        if (matchHeight._beforeUpdate)
            matchHeight._beforeUpdate(event, matchHeight._groups);

        $.each(matchHeight._groups, function() {
            matchHeight._apply(this.elements, this.options);
        });

        if (matchHeight._afterUpdate)
            matchHeight._afterUpdate(event, matchHeight._groups);
    };

    matchHeight._update = function(throttle, event) {
        // prevent update if fired from a resize event
        // where the viewport width hasn't actually changed
        // fixes an event looping bug in IE8
        if (event && event.type === 'resize') {
            var windowWidth = $(window).width();
            if (windowWidth === _previousResizeWidth)
                return;
            _previousResizeWidth = windowWidth;
        }

        // throttle updates
        if (!throttle) {
            _update(event);
        } else if (_updateTimeout === -1) {
            _updateTimeout = setTimeout(function() {
                _update(event);
                _updateTimeout = -1;
            }, matchHeight._throttle);
        }
    };

    /*
    *  bind events
    */

    // apply on DOM ready event
    $(matchHeight._applyDataApi);

    // update heights on load and resize events
    $(window).bind('load', function(event) {
        matchHeight._update(false, event);
    });

    // throttled update heights on resize events
    $(window).bind('resize orientationchange', function(event) {
        matchHeight._update(true, event);
    });

})(jQuery);

/*
Plugin Name: 	jQuery.pin
Written by: 	Okler Themes - (http://www.okler.net)
Version: 		4.5.0

Based on:

	https://github.com/webpop/jquery.pin
	Licensed under the terms of the MIT license.

*/
(function ($) {
    "use strict";
    $.fn.pin = function (options) {
        var scrollY = 0, elements = [], disabled = false, $window = $(window);

        options = options || {};

        var recalculateLimits = function () {
            for (var i=0, len=elements.length; i<len; i++) {
                var $this = elements[i];

                if (options.minWidth && $window.width() <= options.minWidth) {
                    if ($this.parent().is(".pin-wrapper")) { $this.unwrap(); }
                    $this.css({width: "", left: "", top: "", position: ""});
                    if (options.activeClass) { $this.removeClass(options.activeClass); }
                    disabled = true;
                    continue;
                } else {
                    disabled = false;
                }

                var $container = options.containerSelector ? $this.closest(options.containerSelector) : $(document.body);
                var offset = $this.offset();
                var containerOffset = $container.offset();
                var parentOffset = $this.parent().offset();

                if (!$this.parent().is(".pin-wrapper")) {
                    $this.wrap("<div class='pin-wrapper'>");
                }

                var pad = $.extend({
                  top: 0,
                  bottom: 0
                }, options.padding || {});

                $this.data("pin", {
                    pad: pad,
                    from: (options.containerSelector ? containerOffset.top : offset.top) - pad.top,
                    to: containerOffset.top + $container.height() - $this.outerHeight() - pad.bottom,
                    end: containerOffset.top + $container.height(),
                    parentTop: parentOffset.top
                });

                $this.css({width: $this.outerWidth()});
                $this.parent().css("height", $this.outerHeight());
            }
        };

        var onScroll = function () {
            if (disabled) { return; }

            scrollY = $window.scrollTop();

            var elmts = [];
            for (var i=0, len=elements.length; i<len; i++) {          
                var $this = $(elements[i]),
                    data  = $this.data("pin");

                if (!data) { // Removed element
                  continue;
                }

                elmts.push($this); 
                  
                var from = data.from - data.pad.bottom,
                    to = data.to - data.pad.top;
              
                if (from + $this.outerHeight() > data.end) {
                    $this.css('position', '');
                    continue;
                }
              
                if (from < scrollY && to > scrollY) {
                    !($this.css("position") == "fixed") && $this.css({
                        left: $this.offset().left,
                        top: data.pad.top
                    }).css("position", "fixed");
                    if (options.activeClass) { $this.addClass(options.activeClass); }
                } else if (scrollY >= to) {
                    $this.css({
                        left: "",
                        top: to - data.parentTop + data.pad.top
                    }).css("position", "absolute");
                    if (options.activeClass) { $this.addClass(options.activeClass); }
                } else {
                    $this.css({position: "", top: "", left: ""});
                    if (options.activeClass) { $this.removeClass(options.activeClass); }
                }
          }
          elements = elmts;
        };

        var update = function () { recalculateLimits(); onScroll(); };

        this.each(function () {
            var $this = $(this), 
                data  = $(this).data('pin') || {};

            if (data && data.update) { return; }
            elements.push($this);
            $("img", this).one("load", recalculateLimits);
            data.update = update;
            $(this).data('pin', data);
        });

        $window.scroll(onScroll);
        $window.resize(function () { recalculateLimits(); });
        recalculateLimits();

        $window.load(update);

        return this;
      };
})(jQuery);

/*
Plugin Name: 	smoothScroll for jQuery.
Written by: 	Okler Themes - (http://www.okler.net)
Version: 		4.5.0

Based on:

	SmoothScroll v1.4.0
	Licensed under the terms of the MIT license.

	People involved
	 - Balazs Galambosi (maintainer)
	 - Patrick Brunner  (original idea)
	 - Michael Herf     (Pulse Algorithm)

*/

(function($) {
	$.extend({

		smoothScroll: function() {

			(function () {
			  
			// Scroll Variables (tweakable)
			var defaultOptions = {

			    // Scrolling Core
			    frameRate        : 150, // [Hz]
			    animationTime    : 360, // [ms]
			    stepSize         : 100, // [px]

			    // Pulse (less tweakable)
			    // ratio of "tail" to "acceleration"
			    pulseAlgorithm   : true,
			    pulseScale       : 4,
			    pulseNormalize   : 1,

			    // Acceleration
			    accelerationDelta : 50,  // 50
			    accelerationMax   : 3,   // 3

			    // Keyboard Settings
			    keyboardSupport   : true,  // option
			    arrowScroll       : 50,    // [px]

			    // Other
			    touchpadSupport   : false, // ignore touchpad by default
			    fixedBackground   : true, 
			    excluded          : ''    
			};

			var options = defaultOptions;


			// Other Variables
			var isExcluded = false;
			var isFrame = false;
			var direction = { x: 0, y: 0 };
			var initDone  = false;
			var root = document.documentElement;
			var activeElement;
			var observer;
			var refreshSize;
			var deltaBuffer = [];
			var isMac = /^Mac/.test(navigator.platform);

			var key = { left: 37, up: 38, right: 39, down: 40, spacebar: 32, 
			            pageup: 33, pagedown: 34, end: 35, home: 36 };


			/***********************************************
			 * INITIALIZE
			 ***********************************************/

			/**
			 * Tests if smooth scrolling is allowed. Shuts down everything if not.
			 */
			function initTest() {
			    if (options.keyboardSupport) {
			        addEvent('keydown', keydown);
			    }
			}

			/**
			 * Sets up scrolls array, determines if frames are involved.
			 */
			function init() {
			  
			    if (initDone || !document.body) return;

			    initDone = true;

			    var body = document.body;
			    var html = document.documentElement;
			    var windowHeight = window.innerHeight; 
			    var scrollHeight = body.scrollHeight;
			    
			    // check compat mode for root element
			    root = (document.compatMode.indexOf('CSS') >= 0) ? html : body;
			    activeElement = body;
			    
			    initTest();

			    // Checks if this script is running in a frame
			    if (top != self) {
			        isFrame = true;
			    }

			    /**
			     * Please duplicate this radar for a Safari fix! 
			     * rdar://22376037
			     * https://openradar.appspot.com/radar?id=4965070979203072
			     * 
			     * Only applies to Safari now, Chrome fixed it in v45:
			     * This fixes a bug where the areas left and right to 
			     * the content does not trigger the onmousewheel event
			     * on some pages. e.g.: html, body { height: 100% }
			     */
			    else if (scrollHeight > windowHeight &&
			            (body.offsetHeight <= windowHeight || 
			             html.offsetHeight <= windowHeight)) {

			        var fullPageElem = document.createElement('div');
			        fullPageElem.style.cssText = 'position:absolute; z-index:-10000; ' +
			                                     'top:0; left:0; right:0; height:' + 
			                                      root.scrollHeight + 'px';
			        document.body.appendChild(fullPageElem);
			        
			        // DOM changed (throttled) to fix height
			        var pendingRefresh;
			        refreshSize = function () {
			            if (pendingRefresh) return; // could also be: clearTimeout(pendingRefresh);
			            pendingRefresh = setTimeout(function () {
			                if (isExcluded) return; // could be running after cleanup
			                fullPageElem.style.height = '0';
			                fullPageElem.style.height = root.scrollHeight + 'px';
			                pendingRefresh = null;
			            }, 500); // act rarely to stay fast
			        };
			  
			        setTimeout(refreshSize, 10);

			        addEvent('resize', refreshSize);

			        // TODO: attributeFilter?
			        var config = {
			            attributes: true, 
			            childList: true, 
			            characterData: false 
			            // subtree: true
			        };

			        observer = new MutationObserver(refreshSize);
			        observer.observe(body, config);

			        if (root.offsetHeight <= windowHeight) {
			            var clearfix = document.createElement('div');   
			            clearfix.style.clear = 'both';
			            body.appendChild(clearfix);
			        }
			    }

			    // disable fixed background
			    if (!options.fixedBackground && !isExcluded) {
			        body.style.backgroundAttachment = 'scroll';
			        html.style.backgroundAttachment = 'scroll';
			    }
			}

			/**
			 * Removes event listeners and other traces left on the page.
			 */
			function cleanup() {
			    observer && observer.disconnect();
			    removeEvent(wheelEvent, wheel);
			    removeEvent('mousedown', mousedown);
			    removeEvent('keydown', keydown);
			    removeEvent('resize', refreshSize);
			    removeEvent('load', init);
			}


			/************************************************
			 * SCROLLING 
			 ************************************************/
			 
			var que = [];
			var pending = false;
			var lastScroll = Date.now();

			/**
			 * Pushes scroll actions to the scrolling queue.
			 */
			function scrollArray(elem, left, top) {
			    
			    directionCheck(left, top);

			    if (options.accelerationMax != 1) {
			        var now = Date.now();
			        var elapsed = now - lastScroll;
			        if (elapsed < options.accelerationDelta) {
			            var factor = (1 + (50 / elapsed)) / 2;
			            if (factor > 1) {
			                factor = Math.min(factor, options.accelerationMax);
			                left *= factor;
			                top  *= factor;
			            }
			        }
			        lastScroll = Date.now();
			    }          
			    
			    // push a scroll command
			    que.push({
			        x: left, 
			        y: top, 
			        lastX: (left < 0) ? 0.99 : -0.99,
			        lastY: (top  < 0) ? 0.99 : -0.99, 
			        start: Date.now()
			    });
			        
			    // don't act if there's a pending queue
			    if (pending) {
			        return;
			    }  

			    var scrollWindow = (elem === document.body);
			    
			    var step = function (time) {
			        
			        var now = Date.now();
			        var scrollX = 0;
			        var scrollY = 0; 
			    
			        for (var i = 0; i < que.length; i++) {
			            
			            var item = que[i];
			            var elapsed  = now - item.start;
			            var finished = (elapsed >= options.animationTime);
			            
			            // scroll position: [0, 1]
			            var position = (finished) ? 1 : elapsed / options.animationTime;
			            
			            // easing [optional]
			            if (options.pulseAlgorithm) {
			                position = pulse(position);
			            }
			            
			            // only need the difference
			            var x = (item.x * position - item.lastX) >> 0;
			            var y = (item.y * position - item.lastY) >> 0;
			            
			            // add this to the total scrolling
			            scrollX += x;
			            scrollY += y;            
			            
			            // update last values
			            item.lastX += x;
			            item.lastY += y;
			        
			            // delete and step back if it's over
			            if (finished) {
			                que.splice(i, 1); i--;
			            }           
			        }

			        // scroll left and top
			        if (scrollWindow) {
			            window.scrollBy(scrollX, scrollY);
			        } 
			        else {
			            if (scrollX) elem.scrollLeft += scrollX;
			            if (scrollY) elem.scrollTop  += scrollY;                    
			        }
			        
			        // clean up if there's nothing left to do
			        if (!left && !top) {
			            que = [];
			        }
			        
			        if (que.length) { 
			            requestFrame(step, elem, (1000 / options.frameRate + 1)); 
			        } else { 
			            pending = false;
			        }
			    };
			    
			    // start a new queue of actions
			    requestFrame(step, elem, 0);
			    pending = true;
			}


			/***********************************************
			 * EVENTS
			 ***********************************************/

			/**
			 * Mouse wheel handler.
			 * @param {Object} event
			 */
			function wheel(event) {

			    if (!initDone) {
			        init();
			    }
			    
			    var target = event.target;
			    var overflowing = overflowingAncestor(target);

			    // use default if there's no overflowing
			    // element or default action is prevented   
			    // or it's a zooming event with CTRL 
			    if (!overflowing || event.defaultPrevented || event.ctrlKey) {
			        return true;
			    }
			    
			    // leave embedded content alone (flash & pdf)
			    if (isNodeName(activeElement, 'embed') || 
			       (isNodeName(target, 'embed') && /\.pdf/i.test(target.src)) ||
			       isNodeName(activeElement, 'object')) {
			        return true;
			    }

			    var deltaX = -event.wheelDeltaX || event.deltaX || 0;
			    var deltaY = -event.wheelDeltaY || event.deltaY || 0;
			    
			    if (isMac) {
			        if (event.wheelDeltaX && isDivisible(event.wheelDeltaX, 120)) {
			            deltaX = -120 * (event.wheelDeltaX / Math.abs(event.wheelDeltaX));
			        }
			        if (event.wheelDeltaY && isDivisible(event.wheelDeltaY, 120)) {
			            deltaY = -120 * (event.wheelDeltaY / Math.abs(event.wheelDeltaY));
			        }
			    }
			    
			    // use wheelDelta if deltaX/Y is not available
			    if (!deltaX && !deltaY) {
			        deltaY = -event.wheelDelta || 0;
			    }

			    // line based scrolling (Firefox mostly)
			    if (event.deltaMode === 1) {
			        deltaX *= 40;
			        deltaY *= 40;
			    }
			    
			    // check if it's a touchpad scroll that should be ignored
			    if (!options.touchpadSupport && isTouchpad(deltaY)) {
			        return true;
			    }

			    // scale by step size
			    // delta is 120 most of the time
			    // synaptics seems to send 1 sometimes
			    if (Math.abs(deltaX) > 1.2) {
			        deltaX *= options.stepSize / 120;
			    }
			    if (Math.abs(deltaY) > 1.2) {
			        deltaY *= options.stepSize / 120;
			    }
			    
			    scrollArray(overflowing, deltaX, deltaY);
			    event.preventDefault();
			    scheduleClearCache();
			}

			/**
			 * Keydown event handler.
			 * @param {Object} event
			 */
			function keydown(event) {

			    var target   = event.target;
			    var modifier = event.ctrlKey || event.altKey || event.metaKey || 
			                  (event.shiftKey && event.keyCode !== key.spacebar);
			    
			    // our own tracked active element could've been removed from the DOM
			    if (!document.contains(activeElement)) {
			        activeElement = document.activeElement;
			    }

			    // do nothing if user is editing text
			    // or using a modifier key (except shift)
			    // or in a dropdown
			    // or inside interactive elements
			    var inputNodeNames = /^(textarea|select|embed|object)$/i;
			    var buttonTypes = /^(button|submit|radio|checkbox|file|color|image)$/i;
			    if ( inputNodeNames.test(target.nodeName) ||
			         isNodeName(target, 'input') && !buttonTypes.test(target.type) ||
			         isNodeName(activeElement, 'video') ||
			         isInsideYoutubeVideo(event) ||
			         target.isContentEditable || 
			         event.defaultPrevented   ||
			         modifier ) {
			      return true;
			    }
			    
			    // spacebar should trigger button press
			    if ((isNodeName(target, 'button') ||
			         isNodeName(target, 'input') && buttonTypes.test(target.type)) &&
			        event.keyCode === key.spacebar) {
			      return true;
			    }
			    
			    var shift, x = 0, y = 0;
			    var elem = overflowingAncestor(activeElement);
			    var clientHeight = elem.clientHeight;

			    if (elem == document.body) {
			        clientHeight = window.innerHeight;
			    }

			    switch (event.keyCode) {
			        case key.up:
			            y = -options.arrowScroll;
			            break;
			        case key.down:
			            y = options.arrowScroll;
			            break;         
			        case key.spacebar: // (+ shift)
			            shift = event.shiftKey ? 1 : -1;
			            y = -shift * clientHeight * 0.9;
			            break;
			        case key.pageup:
			            y = -clientHeight * 0.9;
			            break;
			        case key.pagedown:
			            y = clientHeight * 0.9;
			            break;
			        case key.home:
			            y = -elem.scrollTop;
			            break;
			        case key.end:
			            var damt = elem.scrollHeight - elem.scrollTop - clientHeight;
			            y = (damt > 0) ? damt+10 : 0;
			            break;
			        case key.left:
			            x = -options.arrowScroll;
			            break;
			        case key.right:
			            x = options.arrowScroll;
			            break;            
			        default:
			            return true; // a key we don't care about
			    }

			    scrollArray(elem, x, y);
			    event.preventDefault();
			    scheduleClearCache();
			}

			/**
			 * Mousedown event only for updating activeElement
			 */
			function mousedown(event) {
			    activeElement = event.target;
			}


			/***********************************************
			 * OVERFLOW
			 ***********************************************/

			var uniqueID = (function () {
			    var i = 0;
			    return function (el) {
			        return el.uniqueID || (el.uniqueID = i++);
			    };
			})();

			var cache = {}; // cleared out after a scrolling session
			var clearCacheTimer;

			//setInterval(function () { cache = {}; }, 10 * 1000);

			function scheduleClearCache() {
			    clearTimeout(clearCacheTimer);
			    clearCacheTimer = setInterval(function () { cache = {}; }, 1*1000);
			}

			function setCache(elems, overflowing) {
			    for (var i = elems.length; i--;)
			        cache[uniqueID(elems[i])] = overflowing;
			    return overflowing;
			}

			//  (body)                (root)
			//         | hidden | visible | scroll |  auto  |
			// hidden  |   no   |    no   |   YES  |   YES  |
			// visible |   no   |   YES   |   YES  |   YES  |
			// scroll  |   no   |   YES   |   YES  |   YES  |
			// auto    |   no   |   YES   |   YES  |   YES  |

			function overflowingAncestor(el) {
			    var elems = [];
			    var body = document.body;
			    var rootScrollHeight = root.scrollHeight;
			    do {
			        var cached = cache[uniqueID(el)];
			        if (cached) {
			            return setCache(elems, cached);
			        }
			        elems.push(el);
			        if (rootScrollHeight === el.scrollHeight) {
			            var topOverflowsNotHidden = overflowNotHidden(root) && overflowNotHidden(body);
			            var isOverflowCSS = topOverflowsNotHidden || overflowAutoOrScroll(root);
			            if (isFrame && isContentOverflowing(root) || 
			               !isFrame && isOverflowCSS) {
			                return setCache(elems, getScrollRoot()); 
			            }
			        } else if (isContentOverflowing(el) && overflowAutoOrScroll(el)) {
			            return setCache(elems, el);
			        }
			    } while (el = el.parentElement);
			}

			function isContentOverflowing(el) {
			    return (el.clientHeight + 10 < el.scrollHeight);
			}

			// typically for <body> and <html>
			function overflowNotHidden(el) {
			    var overflow = getComputedStyle(el, '').getPropertyValue('overflow-y');
			    return (overflow !== 'hidden');
			}

			// for all other elements
			function overflowAutoOrScroll(el) {
			    var overflow = getComputedStyle(el, '').getPropertyValue('overflow-y');
			    return (overflow === 'scroll' || overflow === 'auto');
			}


			/***********************************************
			 * HELPERS
			 ***********************************************/

			function addEvent(type, fn) {
			    window.addEventListener(type, fn, false);
			}

			function removeEvent(type, fn) {
			    window.removeEventListener(type, fn, false);  
			}

			function isNodeName(el, tag) {
			    return (el.nodeName||'').toLowerCase() === tag.toLowerCase();
			}

			function directionCheck(x, y) {
			    x = (x > 0) ? 1 : -1;
			    y = (y > 0) ? 1 : -1;
			    if (direction.x !== x || direction.y !== y) {
			        direction.x = x;
			        direction.y = y;
			        que = [];
			        lastScroll = 0;
			    }
			}

			var deltaBufferTimer;

			if (window.localStorage && localStorage.SS_deltaBuffer) {
			    deltaBuffer = localStorage.SS_deltaBuffer.split(',');
			}

			function isTouchpad(deltaY) {
			    if (!deltaY) return;
			    if (!deltaBuffer.length) {
			        deltaBuffer = [deltaY, deltaY, deltaY];
			    }
			    deltaY = Math.abs(deltaY)
			    deltaBuffer.push(deltaY);
			    deltaBuffer.shift();
			    clearTimeout(deltaBufferTimer);
			    deltaBufferTimer = setTimeout(function () {
			        if (window.localStorage) {
			            localStorage.SS_deltaBuffer = deltaBuffer.join(',');
			        }
			    }, 1000);
			    return !allDeltasDivisableBy(120) && !allDeltasDivisableBy(100);
			} 

			function isDivisible(n, divisor) {
			    return (Math.floor(n / divisor) == n / divisor);
			}

			function allDeltasDivisableBy(divisor) {
			    return (isDivisible(deltaBuffer[0], divisor) &&
			            isDivisible(deltaBuffer[1], divisor) &&
			            isDivisible(deltaBuffer[2], divisor));
			}

			function isInsideYoutubeVideo(event) {
			    var elem = event.target;
			    var isControl = false;
			    if (document.URL.indexOf ('www.youtube.com/watch') != -1) {
			        do {
			            isControl = (elem.classList && 
			                         elem.classList.contains('html5-video-controls'));
			            if (isControl) break;
			        } while (elem = elem.parentNode);
			    }
			    return isControl;
			}

			var requestFrame = (function () {
			      return (window.requestAnimationFrame       || 
			              window.webkitRequestAnimationFrame || 
			              window.mozRequestAnimationFrame    ||
			              function (callback, element, delay) {
			                 window.setTimeout(callback, delay || (1000/60));
			             });
			})();

			var MutationObserver = (window.MutationObserver || 
			                        window.WebKitMutationObserver ||
			                        window.MozMutationObserver);  

			var getScrollRoot = (function() {
			  var SCROLL_ROOT;
			  return function() {
			    if (!SCROLL_ROOT) {
			      var dummy = document.createElement('div');
			      dummy.style.cssText = 'height:10000px;width:1px;';
			      document.body.appendChild(dummy);
			      var bodyScrollTop  = document.body.scrollTop;
			      var docElScrollTop = document.documentElement.scrollTop;
			      window.scrollBy(0, 1);
			      if (document.body.scrollTop != bodyScrollTop)
			        (SCROLL_ROOT = document.body);
			      else 
			        (SCROLL_ROOT = document.documentElement);
			      window.scrollBy(0, -1);
			      document.body.removeChild(dummy);
			    }
			    return SCROLL_ROOT;
			  };
			})();


			/***********************************************
			 * PULSE (by Michael Herf)
			 ***********************************************/
			 
			/**
			 * Viscous fluid with a pulse for part and decay for the rest.
			 * - Applies a fixed force over an interval (a damped acceleration), and
			 * - Lets the exponential bleed away the velocity over a longer interval
			 * - Michael Herf, http://stereopsis.com/stopping/
			 */
			function pulse_(x) {
			    var val, start, expx;
			    // test
			    x = x * options.pulseScale;
			    if (x < 1) { // acceleartion
			        val = x - (1 - Math.exp(-x));
			    } else {     // tail
			        // the previous animation ended here:
			        start = Math.exp(-1);
			        // simple viscous drag
			        x -= 1;
			        expx = 1 - Math.exp(-x);
			        val = start + (expx * (1 - start));
			    }
			    return val * options.pulseNormalize;
			}

			function pulse(x) {
			    if (x >= 1) return 1;
			    if (x <= 0) return 0;

			    if (options.pulseNormalize == 1) {
			        options.pulseNormalize /= pulse_(1);
			    }
			    return pulse_(x);
			}


			/***********************************************
			 * FIRST RUN
			 ***********************************************/

			var userAgent = window.navigator.userAgent;
			var isEdge    = /Edge/.test(userAgent); // thank you MS
			var isChrome  = /chrome/i.test(userAgent) && !isEdge; 
			var isSafari  = /safari/i.test(userAgent) && !isEdge; 
			var isMobile  = /mobile/i.test(userAgent);
			var isEnabledForBrowser = (isChrome || isSafari) && !isMobile;

			var wheelEvent;
			if ('onwheel' in document.createElement('div'))
			    wheelEvent = 'wheel';
			else if ('onmousewheel' in document.createElement('div'))
			    wheelEvent = 'mousewheel';

			if (wheelEvent && isEnabledForBrowser) {
			    addEvent(wheelEvent, wheel);
			    addEvent('mousedown', mousedown);
			    addEvent('load', init);
			}


			/***********************************************
			 * PUBLIC INTERFACE
			 ***********************************************/

			function SmoothScroll(optionsToSet) {
			    for (var key in optionsToSet)
			        if (defaultOptions.hasOwnProperty(key)) 
			            options[key] = optionsToSet[key];
			}
			SmoothScroll.destroy = cleanup;

			if (window.SmoothScrollOptions) // async API
			    SmoothScroll(window.SmoothScrollOptions)

			if (typeof define === 'function' && define.amd)
			    define(function() {
			        return SmoothScroll;
			    });
			else if ('object' == typeof exports)
			    module.exports = SmoothScroll;
			else
			    window.SmoothScroll = SmoothScroll;

			})();

		}

	});

	if (!$('html').hasClass('disable-smooth-scroll')) {
		$.smoothScroll();
	}

})(jQuery);

/*
Browser Workarounds
*/
if (/iPad|iPhone|iPod/.test(navigator.platform)) {

	// iPad/Iphone/iPod Hover Workaround
	$(document).ready(function($) {
		$('.thumb-info').attr('onclick', 'return true');
	});
}