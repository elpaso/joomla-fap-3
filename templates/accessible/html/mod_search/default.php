<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_search
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2016 ItOpen di Alessandro Pasotti, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/../fap_utils.php';
$value = JRequest::getString('searchword', '');

$button_text = trim($button_text);
?>
<div class="search<?php echo $moduleclass_sfx ?> fap-search">
	<form action="<?php echo JRoute::_('index.php');?>" method="post" class="form-inline">
		<?php
			$output = '<div><label for="mod-search-searchword" class="element-invisible">' . $label . '</label> ';

            if ( ! $value ) {
                $klass = 'class="inputbox search-query form-control placeholder" ';
            } else {
                $klass = 'class="inputbox search-query form-control" "value="' . $value . '" ';
            }

			$output .= '<input ' . $klass . 'id="mod-search-searchword" name="searchword" maxlength="' . $maxlength . '" type="text" size="' . $width . '" ' . fap_placeholder($text) . ' />';

			if ($button) :
				if ($imagebutton) :
					$btn_output = ' <input type="image" value="' . $button_text . '" class="button" src="' . $img . '" onclick="this.form.searchword.focus();"/>';
				else :
					$btn_output = ' <button class="button btn btn-primary" onclick="this.form.searchword.focus();"><span class="icon-search"></span>' . ( $button_text ? ' ' : '<span class="hidden">'  .  JText::_('FAP_MOD_SEARCH') . '</span>') . $button_text . '</button>';
				endif;

				switch ($button_pos) :
					case 'top' :
						$output = $btn_output . '<br />' . $output;
						break;

					case 'bottom' :
						$output .= '<br />' . $btn_output;
						break;

					case 'right' :
						$output .= $btn_output;
						break;

					case 'left' :
					default :
						$output = $btn_output . $output;
						break;
				endswitch;

			endif;

			echo $output;
		?>
		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="option" value="com_search" />
		<input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
        </div>
	</form>
</div>
