/*
	Breakpoints.js
	version 1.0

    @fap: modified to add more events and better performances

	Creates handy events for your responsive design breakpoints

	Copyright 2011 XOXCO, Inc
	http://xoxco.com/

	Documentation for this plugin lives here:
	http://xoxco.com/projects/code/breakpoints

	Licensed under the MIT license:
	http://www.opensource.org/licenses/mit-license.php

*/
(function($) {

	var lastSize = 0;
	var lastBp = -1;
	var interval = null;

	$.fn.resetBreakpoints = function() {
		$(window).unbind('resize');
		if (interval) {
			clearInterval(interval);
		}
		lastSize = 0;
	};

	$.fn.setBreakpoints = function(settings) {
		var options = jQuery.extend({
							distinct: true,
							breakpoints: new Array(320,480,768,1024)
				    	},settings);

        // Sort once
        options.breakpoints.sort(function(a,b) { return (b-a) });

		interval = setInterval(function() {

			var w = $(window).width();
			var done = false;
            var currentBp = 0;
            var bpSet = false;

            // Return if not resized
            if (w == lastSize) {
                return;
            }

			for (var bp in options.breakpoints) {

				// fire onEnter when a browser expands into a new breakpoint
				// if in distinct mode, remove all other breakpoints first.
				if (!done && w >= options.breakpoints[bp] && lastSize < options.breakpoints[bp]) {
					if (options.distinct) {
						for (var x in options.breakpoints.sort(function(a,b) { return (b-a) })) {
							if ($('body').hasClass('breakpoint-' + options.breakpoints[x])) {
								$('body').removeClass('breakpoint-' + options.breakpoints[x]);
								$(window).trigger('exitBreakpoint' + options.breakpoints[x], true);
							}
						}
						done = true;
					}
					$('body').addClass('breakpoint-' + options.breakpoints[bp]);
					$(window).trigger('enterBreakpoint' + options.breakpoints[bp], true);
                    currentBp = options.breakpoints[bp];
                    bpSet = true;
				}

				// fire onExit when browser contracts out of a larger breakpoint
				if (w < options.breakpoints[bp] && lastSize >= options.breakpoints[bp]) {
					$('body').removeClass('breakpoint-' + options.breakpoints[bp]);
					$(window).trigger('exitBreakpoint' + options.breakpoints[bp], false);
                    currentBp = ( bp == options.breakpoints.length - 1 ) ? 0 : options.breakpoints[parseInt(bp)+1];
                    bpSet = true;
				}

				// if in distinct mode, fire onEnter when browser contracts into a smaller breakpoint
				if (
					options.distinct && // only one breakpoint at a time
					w >= options.breakpoints[bp] && // and we are in this one
					w < options.breakpoints[bp-1] && // and smaller than the bigger one
					lastSize > w && // and we contracted
					lastSize >0 &&  // and this is not the first time
					!$('body').hasClass('breakpoint-' + options.breakpoints[bp]) // and we aren't already in this breakpoint
					) {
					$('body').addClass('breakpoint-' + options.breakpoints[bp]);
					$(window).trigger('enterBreakpoint' + options.breakpoints[bp], false);
                    currentBp = options.breakpoints[bp];
                    bpSet = true;
				}

			}

            // Just set currentBp since this is the first loop @fap addition
            if (! bpSet && ! lastSize) {
                currentBp =  0;
                bpSet = true;
            }

			// set up for next call
			if (lastSize != w) {
                if(bpSet) {
                    // Trigger generic event, old bp and new bp reported
                    if ( !currentBp || lastBp > currentBp ){
                        $(window).trigger('changedBreakpoint', [lastBp, currentBp, false]);
                        $(window).trigger('reducingBreakpointTo', currentBp);
                    } else {
                        $(window).trigger('changedBreakpoint', [lastBp, currentBp, true]);
                        $(window).trigger('increasingBreakpointTo', currentBp);
                    }
                    lastBp = currentBp;
                }
				lastSize = w;
			}
		},250);
	};

})(jQuery);
