<?php
/**
* @version		$Id:$
* @package		Plg_jFap
* @copyright	Copyright (C) 2011-2016 ItOpen. All rights reserved.
* @licence      GNU/GPL
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

    function onBeforeCompileHead(){
        # CDATA
        if (array_key_exists('text/javascript', JFactory::getDocument()->_script) &&
            strpos(JFactory::getDocument()->_script['text/javascript'], 'CDATA') === false){
                JFactory::getDocument()->_script['text/javascript'] = "/* <![CDATA[ */\n" .
                    JFactory::getDocument()->_script['text/javascript'] .
                    "\n/* ]]> */\n";
        }
    }

    function onAfterRender(){
        $mainframe = JFactory::getApplication();
        if ($mainframe->isAdmin()){
            return true;
        }
        $body = JResponse::getBody();
        $format = JRequest::getVar('format');
        if ($format=="feed"){
            return true;
        }

        // Old style tags B I
        $tags_regexp = array('/<b(\s+[^>]*)?>/', '/<\/b>/', '/<i(\s+[^>]*)?>/', '/<\/i>/');
        $tags_replace = array('<strong\1>', '</strong>', '<em\1>', '</em>');
        $body = preg_replace( $tags_regexp, $tags_replace, $body);

        $j3_voting_crap_regexp = array(
            '/<meta itemprop=.*?\/>/',
            '/<span class="content_vote">(.*?)<\/span>/is'
        );
        $body = preg_replace( $j3_voting_crap_regexp, array('', '<div>\1</div>'), $body);

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


        # CDATA (((?!CDATA).)*?) -> it's correct but may crash PHP!
        # Now done in onBeforeCompileHead
        #$body = preg_replace('#<script type="text/javascript">(((?!CDATA).)*?)</script>#ms', "<script type=\"text/javascript\">\n/*<![CDATA[*/\n\\1\n/*]]>*/\n</script>", $body);

        // Set external link class
        if (preg_match_all( "/<a[^>]*?href=[\"'](http[s]*:\/\/[^\"']+)[\"'][^>]*>/" , $body , $matches)){
            //vardie($matches);
            $string = array();
            $replace = array();
            foreach($matches[0] as $k => $m){
                if (strpos($matches[1][$k], JURI::root()) === false ){
                    $string[] = $m;
                    if(! strstr ( $m, 'class=') ){
                        $replace[] = substr($m, 0, -1) . ' class="external-link">';
                    } else {
                        $replace[] = preg_replace("/class=[\"'](.*?)[\"']/", 'class="\1 external-link"', $m);
                    }
                }
            }
            $body = str_replace($string, $replace, $body);
        }

        JResponse::setBody($body);
    }

}
