<?php
/**
* @version		$Id:$
* @package		Plg_jFap
* @copyright	Copyright (C) 2011-2014 ItOpen. All rights reserved.
* @licence      GNU/AGPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Joomla! jFap plugin
 *
 * @package		jFap
 * @subpackage	System
 */
class  plgSystemJFap extends JPlugin
{
    function onAfterRender(){
        $mainframe = JFactory::getApplication();
        if ($mainframe->isAdmin()){
            return true;
        }
        $body = JResponse::getBody();

        # Too hungry:
        #$style_regexp = '@<span[^>]*>@is';
        #$style_replace = ''

        // Old style tags B I
        $tags_regexp = array('/<b(\s+[^>]*)?>/', '/<\/b>/', '/<i(\s+[^>]*)?>/', '/<\/i>/');
        $tags_replace = array('<strong\1>', '</strong>', '<em\1>', '</em>');
        $body = preg_replace( $tags_regexp, $tags_replace, $body);

        # J3 itemprop & required
        $j3_forms_regexp = array(
                '/itemprop="[^"]+"/is',
                '/itemscope/is',
                '/itemtype="[^"]+"/is',
                '/required aria-required="true"/is',
                '/type="email"/is'
            );
        $j3_forms_replace = array(
                '',
                '',
                '',
                'aria-required="true"',
                'type="text"'
            );
        $body = preg_replace( $j3_forms_regexp, $j3_forms_replace, $body);

        # Remove style from span
        $style_regexp = '@<span([^>]*?)\sstyle=(["\']).*?\2([^>]*?)>@is';
        $style_replace = '<span\1\3>';
		$img_regexp = '@<img([^>]*?)\sborder=(["\']).*?\2([^>]*?)>@is';
        $img_replace = '<img\1\3>';
        # Dublin Core MD
        $dc_desc_regexp = '#<meta name="description"#';
        $dc_desc_replace = '<meta name="DC.Description"';
        $body = preg_replace(
					array($dc_desc_regexp,
						//'/target=[\'"](_blank|_new)+/',
						$style_regexp,
						'/(<meta name="generator" content=")([^"]+)"/',
						$img_regexp
						),
					 array($dc_desc_replace,
						//'onclick="window.open(this.href);return false;" onkeypress="handle_keypress(event, function(evt){ window.open(this.href); });return false;',
						$style_replace,
						'\1\2 - Versione FAP"',
						$img_replace
						),
					$body);


        # onkeypress
        # Already in the accessibility links
        #$body = preg_replace('|onclick="(.*?)"|mus', 'onclick="\1" onkeypress="\1"', $body);

        // Set external link class
        if (preg_match_all( "/<a.*?href=[\"']http:\/\/[^\"']+[\"'].*?>/" , $body , $matches)){
            $string = array();
            $replace = array();
            foreach($matches[0] as $m){
                $string[] = $m;
                if(! strstr ( $m, 'class=') ){
                    $replace[] = substr($m, 0, -1) . ' class="external-link">';
                } else {
                    $replace[] = preg_replace("/class=[\"'](.*?)[\"']/", 'class="\1 external-link"', $m);
                }
            }
            $body = str_replace($string, $replace, $body);
        }
        JResponse::setBody($body);
    }

}
