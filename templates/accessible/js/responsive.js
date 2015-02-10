/**
* Responsive menus & other goodies
*
* @requires jQuery
*
* This file is part of
* Joomla! FAP
*
* @copyright 2014 ItOpen http://www.itopen.it
* @author    Alessandro Pasotti
* @licence   GNU/GPL v. 3
*
* From Bootstrap:
*
* Extra small devices Phones (<768px)
* Small devices Tablets (≥768px)
* Medium devices Desktops (≥992px)
* Large devices Desktops (≥1200px)
*
*/

jQuery(function($){

    // Hide slick menu when window size greater or equal to
    var SLICK_HIDE_BREAKPOINT = 768;
    var BREAKPOINTS = [
            // 0     -> xs
            768,  // -> sm
            992,  // -> md
            1200  // -> lg
        ];

    // Bootstrap-like bp classes
    var BP_ALIASES = [
        'xs',
        'sm',
        'md',
        'lg'
    ];

    // Activate responsiveness
    $(window).setBreakpoints({
    // use only largest available vs use all available
        distinct: true,
    // array of widths in pixels where breakpoints
    // should be triggered
        breakpoints: BREAKPOINTS.slice()
    });

    // Responsive top menu
    $('body').prepend('<div class="slicknav_menu_wrapper visible-xs-block"></div>');

    $('#menu-top').slicknav({
        prependTo: '.slicknav_menu_wrapper',
        closedSymbol: '<i class="icon-arrow-right"></i>',
        openedSymbol: '<i class="icon-arrow-down"></i>'
    });
    // Remove AK
    $('.slicknav_menu_wrapper a[accesskey]').each(function(k, e){
        $(e).attr('accesskey', null);
    });

    // Create search
    if(jQuery('.fap-search').length){
        jQuery('.fap-search').clone().addClass('visible-small-block').hide().appendTo('.slicknav_menu_wrapper');
        jQuery('.slicknav_menu .slicknav_btn').after('<a class="slicknav_btn_search slicknav_btn" title="Cerca"><i class="icon icon-search"></i></a>');
        jQuery('.slicknav_menu .slicknav_btn_search').click(
            function(evt){
                jQuery('.slicknav_menu_wrapper .fap-search').slideToggle('fast');
            }
        );
    }

    $(window).bind('changedBreakpoint', function(event, lastBp, currentBp, expanding){
        //console.log('changedBreakpoint ' +  lastBp + ' to ' + currentBp + (expanding ? ' expanding' : ' schrinking'));
        if ( currentBp >=  SLICK_HIDE_BREAKPOINT  && expanding ) {
            // Remove fap-small alias class
            $('body').removeClass('fap-small');
        } else if ( currentBp <  SLICK_HIDE_BREAKPOINT && ! expanding) {
            // Add fap-small alias class
            $('body').addClass('fap-small');
        }
        // Get current bp index
        var cl = BP_ALIASES[BREAKPOINTS.indexOf(currentBp) != -1 ? BREAKPOINTS.indexOf(currentBp) + 1 : 0];
        $('body').addClass('fap-' + cl);
        $(BP_ALIASES).each(function(k, al){
            if(al != cl){
                $('body').removeClass('fap-' + al);
            }
        });
    });

});
