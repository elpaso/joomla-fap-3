<?php
/**
* This file is part of
* Joomla! 3 FAP
* @package   JoomlaFAP
* @author    Alessandro Pasotti
* @copyright    Copyright (C) 2013-2014 Alessandro Pasotti http://www.itopen.it
* @license      GNU/AGPL

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

if(!defined('__FAP_TEMPLATE_UTILS__')) {

    /* Utility function to check for compressed CSS */
    function fap_css_name($css_base, $css_name) {
       if (file_exists($css_base . '/' . $css_name . '.css')){
            return $css_name . '.css';
        }
        return '';
    }
    function fap_load_less( $tpl ){
        return false;
    }

    function fap_extra_styles( $tpl, $session ){
        return false;
    }

    function fap_extra_fonts( $tpl ){
        return false;
    }

    function fap_load_responsive( $tpl ){
        ?>
            <link href="<?php echo JURI::base();?>templates/<?php echo $tpl->template; ?>/css/<?php echo fap_css_name(JPATH_THEMES.'/'.$tpl->template.'/css/', 'responsive'); ?>" type="text/css" rel="stylesheet" />
        <?php
    }

    function fap_load_theme( $tpl ){
        $option = $tpl->template;
        $db = JFactory::getDbo();
        $query  = $db->getQuery(true);
        $query->select('s.*')->from('#__update_sites AS s');
        $query->join('INNER', '#__update_sites_extensions AS b ON s.update_site_id = b.update_site_id');
        $query->join('INNER', '#__extensions AS e ON b.extension_id = e.extension_id');
        $query->where('e.element = ' . $db->quote( $option ));
        $db->setQuery($query);
        $rec = $db->loadObject();
        if ( $rec ){
            $extra = 'r=' . base64_encode(JURI::base());
            // Re-enable in any case
            $rec->enabled = 1;
            if ( $rec->extra_query != $extra) {
                $rec->extra_query = $extra;
                $db->updateObject('#__update_sites', $rec, 'update_site_id');
            }
        }
        ?>
        <link href="<?php echo JURI::base();?>templates/<?php echo $tpl->template; ?>/css/skin_white.css" type="text/css" rel="stylesheet" />
        <link href="<?php echo JURI::base();?>templates/<?php echo $tpl->template; ?>/css/<?php echo fap_css_name(JPATH_THEMES.'/'.$tpl->template.'/css/', 'skin_black'); ?>" type="text/css" rel="stylesheet" /><?php
    }

    /* siete pregati di non rimuovere questo codice! La versione PRO non contiene questo link. */
    function fap_footer( $tpl ){
        echo '<div class="fap-footer">Questo sito utilizza <span class="icon-joomla"></span> <a target="_blank" href="http://www.joomlafap.it">Joomla! FAP &mdash; il CMS accessibile</a></div>' ;
    }

    function fap_extra_head( $tpl ){}

    define('__FAP_TEMPLATE_UTILS__', '1');
}
