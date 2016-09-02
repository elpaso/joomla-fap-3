<?php
/**
* This file is part of
* Joomla! 3 FAP
* @package   JoomlaFAP
* @author    Alessandro Pasotti
* @copyright    Copyright (C) 2013-2014 Alessandro Pasotti http://www.itopen.it
* @license      GNU/GPL

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

if(!defined('__FAP_UTILS__')) {
    /**
     * Add onkeypress to onclick events
     *
     * @TODO move to pure js jQuery behavior solution
     */
    function fap_add_onkeypress($html){
        return preg_replace('|onclick="(.*?)"|mus', 'onclick="\1" onkeypress="return fap_handle_keypress(event, function(){\1;})"', $html);
    }


    /**
     * Create the placeholder-like attributes and JS code for input
     * elements
     *
     */
    function fap_placeholder($text){
        return 'value="' . $text . '"  onblur="if (this.value==\'\'){ jQuery(this).addClass(\'placeholder\'); this.value=\'' . $text . '\';}" onfocus="if(this.value==\'' . $text . '\'){ jQuery(this).removeClass(\'placeholder\'); this.value=\'\';}"';
    }

    /**
     * Fix filter: remove onchange and add submit
     */
    function fap_fix_filter($html){
        return str_replace('onchange="this.form.submit()"', '', $html) . '<button type="submit" class="btn">' . JText::_('FAP_FORM_SUBMIT'). '</button>';
    }


    /**
     * Load language file from template
     */
    function fap_load_tpl_lang(){
        $app = JFactory::getApplication();
        $template = $app->getTemplate();
        $lang = JFactory::getLanguage();
        $extension = 'tpl_' . $template;
        $base_dir = JPATH_SITE;
        $language_tag = $lang->getTag();
        $reload = false;
        $lang->load($extension, $base_dir, $language_tag, $reload);
    }

    /**
     * @package     Joomla.Site
     * @subpackage  com_content
     *
     * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
     * @license     GNU General Public License version 2 or later; see LICENSE.txt
     */

    /**
     * Content Component HTML Helper
     *
     * @package     Joomla.Site
     * @subpackage  com_content
     * @since       1.5
     * @fap removed text
     */
    abstract class JHtmlFapIcon
    {
        /**
         * Method to generate a link to the create item page for the given category
         *
         * @param   object     $category  The category information
         * @param   JRegistry  $params    The item parameters
         * @param   array      $attribs   Optional attributes for the link
         * @param   boolean    $legacy    True to use legacy images, false to use icomoon based graphic
         *
         * @return  string  The HTML markup for the create item link
         */
        public static function create($category, $params, $attribs = array(), $legacy = false)
        {
            JHtml::_('bootstrap.tooltip');

            $uri = JUri::getInstance();

            $url = 'index.php?option=com_content&task=article.add&return=' . base64_encode($uri) . '&a_id=0&catid=' . $category->id;

            if ($params->get('show_icons'))
            {
                if ($legacy)
                {
                    $text = JHtml::_('image', 'system/new.png', JText::_('JNEW'), null, true);
                }
                else
                {
                    $text = '<span class="icon-plus"></span>&#160;' . JText::_('JNEW') . '&#160;';
                }
            }
            else
            {
                $text = JText::_('JNEW') . '&#160;';
            }

            // Add the button classes to the attribs array
            if (isset($attribs['class']))
            {
                $attribs['class'] = $attribs['class'] . ' btn btn-primary';
            }
            else
            {
                $attribs['class'] = 'btn btn-primary';
            }

            $button = JHtml::_('link', JRoute::_($url), $text, $attribs);

            $output = '<span class="hasTooltip" title="' . JHtml::tooltipText('COM_CONTENT_CREATE_ARTICLE') . '">' . $button . '</span>';

            return $output;
        }

        /**
         * Method to generate a link to the email item page for the given article
         *
         * @param   object     $article  The article information
         * @param   JRegistry  $params   The item parameters
         * @param   array      $attribs  Optional attributes for the link
         * @param   boolean    $legacy   True to use legacy images, false to use icomoon based graphic
         *
         * @return  string  The HTML markup for the email item link
         */
        public static function email($article, $params, $attribs = array(), $legacy = false)
        {
            require_once JPATH_SITE . '/components/com_mailto/helpers/mailto.php';

            $uri      = JUri::getInstance();
            $base     = $uri->toString(array('scheme', 'host', 'port'));
            $template = JFactory::getApplication()->getTemplate();
            $link     = $base . JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid), false);
            $url      = 'index.php?option=com_mailto&tmpl=component&template=' . $template . '&link=' . MailToHelper::addLink($link);

            $status = 'width=400,height=350,menubar=yes,resizable=yes';

            if ($params->get('show_icons'))
            {
                if ($legacy)
                {
                    $text = JHtml::_('image', 'system/emailButton.png', JText::_('JGLOBAL_EMAIL'), null, true);
                }
                else
                {
                    $text = '<span class="icon-envelope"></span>&#160;<span class="hidden">' . JText::_('JGLOBAL_EMAIL') . '</span>';
                }
            }
            else
            {
                $text = JText::_('JGLOBAL_EMAIL');
            }

            $attribs['title']   = JText::_('JGLOBAL_EMAIL');
            $attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";

            $output = JHtml::_('link', JRoute::_($url), $text, $attribs);

            return $output;
        }

        /**
         * Display an edit icon for the article.
         *
         * This icon will not display in a popup window, nor if the article is trashed.
         * Edit access checks must be performed in the calling code.
         *
         * @param   object     $article  The article information
         * @param   JRegistry  $params   The item parameters
         * @param   array      $attribs  Optional attributes for the link
         * @param   boolean    $legacy   True to use legacy images, false to use icomoon based graphic
         *
         * @return  string	The HTML for the article edit icon.
         * @since   1.6
         */
        public static function edit($article, $params, $attribs = array(), $legacy = false)
        {
            $user = JFactory::getUser();
            $uri  = JUri::getInstance();

            // Ignore if in a popup window.
            if ($params && $params->get('popup'))
            {
                return;
            }

            // Ignore if the state is negative (trashed).
            if ($article->state < 0)
            {
                return;
            }

            JHtml::_('bootstrap.tooltip');

            // Show checked_out icon if the article is checked out by a different user
            if (property_exists($article, 'checked_out') && property_exists($article, 'checked_out_time') && $article->checked_out > 0 && $article->checked_out != $user->get('id'))
            {
                $checkoutUser = JFactory::getUser($article->checked_out);
                $button       = JHtml::_('image', 'system/checked_out.png', null, null, true);
                $date         = JHtml::_('date', $article->checked_out_time);
                $tooltip      = JText::_('JLIB_HTML_CHECKED_OUT') . ' :: ' . JText::sprintf('COM_CONTENT_CHECKED_OUT_BY', $checkoutUser->name) . ' <br /> ' . $date;

                return '<span class="hasTooltip" title="' . JHtml::tooltipText($tooltip. '', 0) . '">' . $button . '</span>';
            }

            $url = 'index.php?option=com_content&task=article.edit&a_id=' . $article->id . '&return=' . base64_encode($uri);

            if ($article->state == 0)
            {
                $overlib = JText::_('JUNPUBLISHED');
            }
            else
            {
                $overlib = JText::_('JPUBLISHED');
            }

            $date   = JHtml::_('date', $article->created);
            $author = $article->created_by_alias ? $article->created_by_alias : $article->author;

            $overlib .= '<br />';
            $overlib .= $date;
            $overlib .= '<br />';
            $overlib .= JText::sprintf('COM_CONTENT_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));

            if ($legacy)
            {
                $icon = $article->state ? 'edit.png' : 'edit_unpublished.png';
                if (strtotime($article->publish_up) > strtotime(JFactory::getDate())
                    || ((strtotime($article->publish_down) < strtotime(JFactory::getDate())) && $article->publish_down != '0000-00-00 00:00:00'))
                {
                    $icon = 'edit_unpublished.png';
                }
                $text = JHtml::_('image', 'system/' . $icon, JText::_('JGLOBAL_EDIT'), null, true);
            }
            else
            {
                $icon = $article->state ? 'edit' : 'eye-close';
                if (strtotime($article->publish_up) > strtotime(JFactory::getDate())
                    || ((strtotime($article->publish_down) < strtotime(JFactory::getDate())) && $article->publish_down != '0000-00-00 00:00:00'))
                {
                    $icon = 'eye-close';
                }
                $text = '<span class="hasTooltip icon-' . $icon . ' tip" title="' . JHtml::tooltipText(JText::_('COM_CONTENT_EDIT_ITEM'), $overlib, 0) . '"></span>&#160;<span class="hidden">' . JText::_('JGLOBAL_EDIT') . '</span>';
            }

            $output = JHtml::_('link', JRoute::_($url), $text, $attribs);

            return $output;
        }

        /**
         * Method to generate a popup link to print an article
         *
         * @param   object     $article  The article information
         * @param   JRegistry  $params   The item parameters
         * @param   array      $attribs  Optional attributes for the link
         * @param   boolean    $legacy   True to use legacy images, false to use icomoon based graphic
         *
         * @return  string  The HTML markup for the popup link
         */
        public static function print_popup($article, $params, $attribs = array(), $legacy = false)
        {
            $app = JFactory::getApplication();
            $input = $app->input;
            $request = $input->request;

            $url  = ContentHelperRoute::getArticleRoute($article->slug, $article->catid);
            $url .= '&tmpl=component&print=1&layout=default&page=' . @ $request->limitstart;

            $status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

            // checks template image directory for image, if non found default are loaded
            if ($params->get('show_icons'))
            {
                if ($legacy)
                {
                    $text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), null, true);
                }
                else
                {
                    $text = '<span class="icon-print"></span>&#160;<span class="hidden">' . JText::_('JGLOBAL_PRINT') . '</span>';
                }
            }
            else
            {
                $text = JText::_('JGLOBAL_PRINT');
            }

            $attribs['title']   = JText::_('JGLOBAL_PRINT');
            $attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";
            $attribs['rel']     = 'nofollow';

            return JHtml::_('link', JRoute::_($url), $text, $attribs);
        }

        /**
         * Method to generate a link to print an article
         *
         * @param   object     $article  Not used, @deprecated for 4.0
         * @param   JRegistry  $params   The item parameters
         * @param   array      $attribs  Not used, @deprecated for 4.0
         * @param   boolean    $legacy   True to use legacy images, false to use icomoon based graphic
         *
         * @return  string  The HTML markup for the popup link
         */
        public static function print_screen($article, $params, $attribs = array(), $legacy = false)
        {
            // Checks template image directory for image, if none found default are loaded
            if ($params->get('show_icons'))
            {
                if ($legacy)
                {
                    $text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), null, true);
                }
                else
                {
                    $text = '<span class="icon-print"></span>&#160;' . JText::_('JGLOBAL_PRINT') . '&#160;';
                }
            }
            else
            {
                $text = JText::_('JGLOBAL_PRINT');
            }

            return '<a href="#" onclick="window.print();return false;">' . $text . '</a>';
        }

    }

    define('__FAP_UTILS__', 1);

}
