<?php
/**
* This file is part of
* Joomla! 3 FAP
* @package   JoomlaFAP
* @author    Alessandro Pasotti
* @copyright    Copyright (C) 2012-2014 Alessandro Pasotti http://www.itopen.it
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


defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Adds a moduletable div
 *
 * shadows: default false
 * rounded: default false
 *
 */
function modChrome_Accessible( $module, &$params, &$attribs ) {
	if (!empty ($module->content)) : ?>
		<div class="moduletable<?php
            echo htmlspecialchars($params->get('moduleclass_sfx'));
            echo " $module->module";
            if(@$attribs['shadows'] == 'true'){
                echo " shadows";
            }
            if(@$attribs['rounded'] == 'true'){
                echo " rounded";
            }
        ?> clearfix">
		<?php if ($module->showtitle != 0) :
			$heading_tag = JFactory::getDocument()->params->get('module_heading', 'div');
            $headerClass   = htmlspecialchars($params->get('header_class', 'module-title'));

		?>
			<<?php echo $heading_tag ?> class="<?php echo $headerClass ?>"><?php echo $module->title; ?></<?php echo $heading_tag ?>>
		<?php endif; ?>
			<div class="module-content">
				<?php echo $module->content; ?>
			</div>
		</div>
	<?php endif;
}

/**
 * Adds a moduletable div
 *
 * shadows: default
 * rounded: true
 *
 */
function modChrome_AccessibleRounded( $module, &$params, &$attribs ) {
    $attribs['rounded'] = 'true';
    modChrome_Accessible( $module, $params, $attribs );
}


/**
 * Adds a moduletable div
 *
 * shadows: true
 * rounded: true
 *
 */
function modChrome_AccessibleRoundedShadows( $module, &$params, &$attribs ) {
    $attribs['shadows'] = 'true';
    modChrome_AccessibleRounded( $module, $params, $attribs );
}


/**
 * Adds a moduletable div
 *
 * shadows: true
 * rounded: default
 *
 */
function modChrome_AccessibleShadows( $module, &$params, &$attribs ) {
    $attribs['shadows'] = 'true';
    modChrome_Accessible( $module, $params, $attribs );
}


/**
 * Adds a moduletable div
 *
 * shadows: false
 * rounded: default
 *
 */
function modChrome_AccessibleNoShadows( $module, &$params, &$attribs ) {
    $attribs['shadows'] = 'false';
    modChrome_Accessible( $module, $params, $attribs );
}


/**
 * Adds a moduletable div
 *
 * shadows: false
 * rounded: false
 *
 */
function modChrome_AccessibleNoRoundedNoShadows( $module, &$params, &$attribs ) {
    $attribs['rounded'] = 'false';
    modChrome_AccessibleNoShadows( $module, $params, $attribs );
}




?>
