/*
Name: 			Style Switcher Initializer
Written by: 	Okler Themes - (http://www.okler.net)
Version: 		2.0
*/

if (typeof localStorage !== "undefined") {
	if (localStorage.getItem('porto-skin.css') !== null && !document.querySelector('html').hasAttribute('data-style-switcher-options')) {

		var css = localStorage.getItem('porto-skin.css'),
		    head = document.head || document.getElementsByTagName('head')[0],
		    style = document.createElement('style');

		style.type = 'text/css';
		if (style.styleSheet) {
		    style.styleSheet.cssText = css;
		} else {
		    style.appendChild(document.createTextNode(css));
		}

		head.appendChild(style);

	}

	if (localStorage.getItem('porto-layout') !== null && !document.querySelector('html').hasAttribute('data-style-switcher-options')) {

		if (localStorage.getItem('porto-layout') == 'boxed') {
			document.querySelector('html').className += ' '+'boxed';
		}

	}
}