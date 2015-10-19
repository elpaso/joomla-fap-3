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

// no direct access
defined('_JEXEC') or die('Restricted access');

/* read template params, for default skin */
$tpl_params = $app->getTemplate(true)->params;

$short = $params->get('short_labels') == 'yes';
?>
<script type="text/javascript">
/* <![CDATA[ */
<?php

    // Class to use (fa, plain, img)

    // Use FA?
    if ('no' == $params->get('accessibility_icons'))
    {
        $use_im = false;
        $accessibility_class_suffix = 'plain';
    }
    elseif ($params->get('accessibility_icons_use_icomoon') == 'yes')
    {
        $accessibility_class_suffix = 'im';
        $use_im = true;

    }
    else
    {
        $use_im = false;
        $accessibility_class_suffix = 'img';
    }

    if ( $use_im ){
        JFactory::getDocument()->addStyleSheet( JURI::root() . 'media/mod_accessibility_links/style.css');
    }

    $im_class = '';

    if('no' == $params->get('accessibility_icons')) { ?>
            document.write('<div id="accessibility-links" class="accessibility-<?php echo $accessibility_class_suffix; ?>">');
                document.write('<form method="post" action="">');
                    document.write('<div>');
                        <?php if($params->get('tools_label')) { ?>
                        document.write('<span class="accessibility-text"><?php  echo  $params->get('tools_label'); ?></span>');
                        <?php } ?>
                        document.write('<button type="button" name="fap_font_size" value="decrease" id="decrease" accesskey="D" onclick="fap_fs_change(-1); return false;" onkeypress="return fap_handle_keypress(event, function(){fs_change(-1);});" title="<?php echo JText::_('MOD_ACCESSIBILITY_DECREASE_SIZE'); ?> [D]"><?php echo JText::_('MOD_ACCESSIBILITY_SMALLER' . ($short ? '_SHORT' : '') ); ?></button>');
                        document.write('<button type="button"  name="fap_font_size" value="increase" id="increase" accesskey="A" onclick="fap_fs_change(1); return false;" onkeypress="return fap_handle_keypress(event, function(){fs_change(1);});" title="<?php echo JText::_('MOD_ACCESSIBILITY_INCREASE_SIZE'); ?> [A]"><?php echo JText::_('MOD_ACCESSIBILITY_BIGGER'. ($short ? '_SHORT' : '') ); ?></button>');
                        document.write('<button type="button" name="fap_skin" value="contrasthigh" id="contrasthigh" accesskey="X" onclick="fap_skin_change(\'swap\');return false;" onkeypress="return fap_handle_keypress(event, function(){skin_change(\'swap\');});" title="<?php echo JText::_('MOD_ACCESSIBILITY_HIGH_CONTRAST'); ?> [X]"><?php echo JText::_('MOD_ACCESSIBILITY_CONTRAST'. ($short ? '_SHORT' : '') ); ?></button>');
                        <?php if('yes' == $params->get('liquid_variant')) { ?>
                        document.write('<button class="hidden-small" type="button" name="fap_skin" value="liquid" id="liquid" accesskey="L" onclick="fap_skin_set_variant(\'liquid\'); return false;" onkeypress="return fap_handle_keypress(event, function(){fap_skin_set_variant(\'liquid\');});" title="<?php echo JText::_('MOD_ACCESSIBILITY_SET_VARIABLE_WIDTH'); ?> [L]"><?php echo JText::_('MOD_ACCESSIBILITY_VARIABLE_WIDTH'. ($short ? '_SHORT' : '') ); ?></button>');
                        <?php } ?>
                        document.write('<button type="button" name="reset" id="reset" value="<?php echo JText::_('MOD_ACCESSIBILITY_RESET'); ?>" accesskey="Z" onclick="fap_skin_change(\'<?php echo $tpl_params->get('default_skin'); ?>\'); fap_skin_set_variant(\'\'); fap_fs_set(fs_default); return false;" onkeypress="return fap_handle_keypress(event, function(){skin_change(\'<?php echo $tpl_params->get('default_skin'); ?>\'); fap_skin_set_variant(\'\'); fap_fs_set(fs_default);});" title="<?php echo JText::_('MOD_ACCESSIBILITY_REVERT_STYLES_TO_DEFAULT'); ?> [Z]"><?php echo JText::_('MOD_ACCESSIBILITY_RESET'. ($short ? '_SHORT' : '') ); ?></button>');
                    document.write('</div>');
                document.write('</form>');
            document.write('</div>');
            <?php } else { ?>
            document.write('<div id="accessibility-links" class="accessibility-<?php echo $accessibility_class_suffix; ?>">');
                document.write('<form method="post" action="">');
                    document.write('<div class="has-icons">');
                        <?php if($params->get('tools_label')) { ?>
                        document.write('<span class="accessibility-label"><?php  echo  $params->get('tools_label'); ?></span>');
                        <?php } ?>
                        document.write('<span class="accessibility-icon"><button type="submit" name="fap_font_size" value="decrease" id="decrease" accesskey="D" onclick="fap_fs_change(-1); return false;" onkeypress="return fap_handle_keypress(event, function(){fs_change(-1);});" title="<?php echo JText::_('MOD_ACCESSIBILITY_DECREASE_SIZE'); ?> [D]"><?php if($use_im) echo '<em class="' . $im_class . ' icon_fap-zoom-out"></em>' ?><span><?php echo JText::_('MOD_ACCESSIBILITY_DECREASE_SIZE'); ?></span></button></span>');
                        document.write('<span class="accessibility-icon"><button type="submit" name="fap_font_size" value="increase" id="increase" accesskey="A" onclick="fap_fs_change(1); return false;" onkeypress="return fap_handle_keypress(event, function(){fs_change(1);});" title="<?php echo JText::_('MOD_ACCESSIBILITY_INCREASE_SIZE'); ?> [A]" ><?php if($use_im) echo '<em class="' . $im_class . ' icon_fap-zoom-in"></em>' ?><span><?php echo JText::_('MOD_ACCESSIBILITY_INCREASE_SIZE'); ?></span></button></span>');
                        document.write('<span class="accessibility-label"><?php echo JText::_('MOD_ACCESSIBILITY_CONTRAST'); ?></span>');
                        document.write('<span class="accessibility-icon"><button type="submit" name="fap_skin" value="contrasthigh" id="contrasthigh" accesskey="X" onclick="fap_skin_change(\'swap\'); return false;" onkeypress="return fap_handle_keypress(event, function(){skin_change(\'swap\');});" title="<?php echo JText::_('MOD_ACCESSIBILITY_HIGH_CONTRAST'); ?> [X]"><?php if($use_im) echo '<em class="' . $im_class . ' icon_fap-contrast"></em>' ?><span><?php echo JText::_('MOD_ACCESSIBILITY_HIGH_CONTRAST'); ?></span></button></span>');
                        <?php if('yes' == $params->get('liquid_variant')) { ?>
                        document.write('<span class="hidden-small accessibility-label"><?php echo JText::_('MOD_ACCESSIBILITY_LAYOUT'); ?></span>');
                        document.write('<span class="hidden-small accessibility-icon"><button type="submit" name="fap_skin" value="liquid" id="layouttext" accesskey="L" onclick="fap_skin_set_variant(\'liquid\'); return false;" onkeypress="return fap_handle_keypress(event, function(){fap_skin_set_variant(\'liquid\');});" title="<?php echo JText::_('MOD_ACCESSIBILITY_SET_VARIABLE_WIDTH'); ?> [L]" ><?php if($use_im) echo '<em class="' . $im_class . ' icon_fap-embed"></em>' ?><span><?php echo JText::_('MOD_ACCESSIBILITY_SET_VARIABLE_WIDTH'); ?></span></button></span>');
                        <?php } ?>
                        document.write('<span class=" accessibility-label"><?php echo JText::_('MOD_ACCESSIBILITY_RESET'); ?></span>');
                        document.write('<span class=" accessibility-icon"><button type="submit" name="fap_skin" value="reset" id="reset" accesskey="Z" onclick="fap_skin_change(\'<?php echo $tpl_params->get('default_skin'); ?>\'); fap_skin_set_variant(\'\'); fap_fs_set(fs_default); return false;" onkeypress="return fap_handle_keypress(event, function(){skin_change(\'<?php echo $tpl_params->get('default_skin'); ?>\'); fap_skin_set_variant(\'\'); fap_fs_set(fs_default);});" title="<?php echo JText::_('MOD_ACCESSIBILITY_REVERT_STYLES_TO_DEFAULT'); ?> [Z]"><?php if($use_im) echo '<em class="' . $im_class . ' icon_fap-undo"></em>' ?><span><?php echo JText::_('MOD_ACCESSIBILITY_REVERT_STYLES_TO_DEFAULT'); ?></span></button></span>');
                    document.write('</div>');
                document.write('</form>');
            document.write('</div>');
            <?php } ?>
/* ]]> */
</script>
<noscript><h2><?php echo JText::_('MOD_ACCESSIBILITY_NOSCRIPT'); ?></h2></noscript>
