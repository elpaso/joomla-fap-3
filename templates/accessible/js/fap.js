/**
* Accessibility tools for Joomla! FAP
*
* @requires jQuery
*
* This file is part of
* Joomla! FAP
* @copyright 2011-2014 ItOpen http://www.itopen.it
* @author    Alessandro Pasotti
* @licence   GNU/GPL v. 3
*
*/


// Globals for skin and font size
var fs_default   = fs_default || 80;
var prefs_loaded = false;
var fs_current   = fs_default;
var skin_current = skin_default || '';

/**
 * On ENTER
 */
function fap_handle_keypress(event, action){
    if(event.keyCode && event.keyCode == 13){
        action();
        return false;
    }
}

function fap_prefs_load(){
    if(!prefs_loaded){

        var c = jQuery.cookie('joomla_fs');
        fs_current = c ? c : fs_default;
        fap_fs_set(fs_current);

        var s = jQuery.cookie('joomla_skin');
        skin_current = s ? s : skin_default;
        fap_skin_set(skin_current);

        prefs_loaded = true;
    }
    return false;
}

function fap_prefs_save(){
    jQuery.cookie('joomla_fs', fs_current, {expires: 365, path : '/'})
    jQuery.cookie('joomla_skin', skin_current, {expires: 365, path : '/'})
    return false;
}

function fap_fs_change(diff){
    fs_current = parseInt(fs_current) + parseInt(diff * 5);
    if(fs_current > 150){
        fs_current = 150;
    }else if(fs_current < 70){
        fs_current = 70;
    }
    fap_fs_set(fs_current);
    return false;
}

function fap_skin_change(skin){
    if (skin_current.search(' ') != -1) {
        variant = skin_current.substr(skin_current.search(' '));
        skin_current = skin_current.substr(0, skin_current.search(' '));
    } else {
        variant = '';
    }
    if (skin == 'swap'){
      if (skin_current.search('white') != -1){
          skin = 'black';
      } else {
          skin = 'white';
      }
    }
    if (skin.search(' ') == -1) {
      skin = skin + variant;
    }
    skin_current = skin;
    fap_skin_set(skin);
    fap_prefs_save();
    return false;
}


function fap_skin_set_variant(variant){
    skin = skin_current
    if (skin.search(' ') != -1) {
        skin = skin.substr(0, skin.search(' '));
    }
    if (variant && skin_current.search(variant) == -1) {
      skin += ' ' + variant;
    }
    skin_current = skin;
    fap_skin_change(skin);
    return false;
}


function fap_fs_set(fs){
    fs_current = fs;
    jQuery('body').css('font-size', fs + '%');
    return false;
}

/**
 * Set the skin
 * @deprecated migrate to jquery ASAP
 */
function fap_skin_set(skin){
    jQuery(['white', 'black', 'liquid']).each(function(k,v){
        jQuery('body').removeClass(v);
    });
    jQuery('body').addClass(skin);
    return false;
}



/**
 * Transforms a menu in an accessible one
 *
 * @param string - menu_selector that identifies the main UL
 */
function fap_accessible_menu(menu_selector){
    var $menu = jQuery(menu_selector);
    $menu.find('a:not(:first)').attr('tabindex', '-1');
    $menu.find('li.level-0 > a:last').addClass('last');
    $menu.find('li.level-0 a:first').addClass('first');
    $menu.find('li.level-0.deeper').attr('aria-haspopup', 'true').attr('aria-expanded', 'false');

    // Touch events
    $menu.find('li.level-0.deeper > a').on({
        'touchstart' : function(e){
            $this = jQuery(this),
            e.preventDefault();
            if($this.is(":focus")){
                $this.blur();
            } else {
                $this.focus();
            }
        }
    });

    $menu.find('a').on("keydown", function(e) {
        var $menu = jQuery(this);
        var keyCode = e.charCode || e.which || e.keyCode,
            keyString = String.fromCharCode(keyCode).toLowerCase(),
            ourIndex = -1,
            currentItem = this,
            $this = jQuery(this),
            $nextItem, $prevItem,
            $menuitems = $menu.find('li[role="menuitem"]:visible');

        if (keyCode === 9) {
            return true;
        }

        if (e.ctrlKey || e.shiftKey || e.altKey || e.metaKey) {
            // not interested
            return;
        }

        switch (keyCode) {
        // 39 dx
        case 39:
            if ($this.closest('.level-0').next().find('a:visible').length){
                $this.closest('.level-0').next().find('a:visible').first().focus();
            }
            e.preventDefault();
        break;
        // 37 sx
        case 37:
            if ($this.closest('.level-0').prev().find('a:visible').length){
                $this.closest('.level-0').prev().find('a:visible').first().focus();
            }
            e.preventDefault();
        break;
        // 40 down
        case 40:
            // Move to first submenu
            if ($this.parent().hasClass('deeper')){
                $this.next().find('a[role="menuitem"]:visible').first().focus();
            } else {
                // Next menu item
                if ($this.parent().next().find('a[role="menuitem"]:visible').length){
                    $this.parent().next().find('a[role="menuitem"]:visible').first().focus();
                } else {
                    /**
                     * Recursively checks if the element has sel_1 parent with sel_2
                     * descendants traversing the tree up towards the root of
                     * the tree
                     *
                     * @return: first matched element or null
                     */
                    var find_up_next = function($element, sel_1, sel_2){
                        if ( ! $element.closest(sel_1).length ){
                            return null;
                        }
                        if ( $element.closest(sel_1).next().find(sel_2).length ){
                            return $element.closest(sel_1).next().find(sel_2).first();
                        }
                        return find_up_next($element.closest(sel_1).parent(), sel_1, sel_2);
                    };
                    // Outside then down...
                    $next = find_up_next($this, '.deeper', 'a[role="menuitem"]:visible');
                    if( $next ){
                        $next.focus();
                    }
                }
            }
            e.preventDefault();
        break;
        // 38 up
        case 38:
            // Search for sibling previous menuitem
            if ($this.parent().prev().find('a[role="menuitem"]:visible').length){
                $this.parent().prev().find('a[role="menuitem"]:visible').last().focus();
            } else {
                // We are the first item that contains a submenu
                if ( $this.parent().hasClass('deeper') ){
                    $this.parent().parent().closest('.deeper').find('a[role="menuitem"]:visible').first().focus();
                } else if ( $this.parent().closest('.deeper').find('a:visible').length ) {
                    $this.parent().closest('.deeper').find('a[role="menuitem"]:visible').first().focus();
                }
            }
            e.preventDefault();
        break;
        case 32: // Space: just like ENTER
            if ('A' == $this.prop('tagName')){
                window.location=$this.prop('href');
                e.preventDefault();
            }
        break;
        }
    }).on('blur', function(e) {
        var $this = jQuery(this);
        // Close all menus if none has focus, probably should be a top-menu event
        $this.closest('[role="menubar"]').find('[aria-expanded="true"]').attr('aria-expanded', 'false');
    }).on('focus', function(e) {
        var $this = jQuery(this);
        if ($this.closest('[aria-haspopup]').attr('aria-haspopup') == "true") {
            $this.closest('[aria-haspopup]').attr('aria-expanded', 'true');
        }
    });
}


// Decorate external links
function fap_decorate_external_links(){
    jQuery('a.external-link').filter(function(k, e){
        $e = jQuery(e);
        if(!$e.children().length){
            // Set in index.php HEAD
            var title = fap_text_external_link;
            $e.prepend('<em class="icon-out-2"></em>');
            if ( $e.attr('title') ){
                $e.attr('title', $e.attr('title') +  ' - ' + title);
            } else {
                $e.attr('title', title);
            }
        }
    });
}

jQuery(function($){
    fap_prefs_load();
    fap_accessible_menu( '#menu-top' );
    fap_accessible_menu( '[role=complementary] .nav.menu' );
    fap_decorate_external_links();

    $( window ).unload(function() {
        fap_prefs_save();
    });
});
