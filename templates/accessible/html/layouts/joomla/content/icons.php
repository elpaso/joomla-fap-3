<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @fap no cog, fap_utils and hide text from icons
 */

defined('JPATH_BASE') or die;

$canEdit = $displayData['params']->get('access-edit');
require_once(dirname(__FILE__) . '../../../../fap_utils.php');

?>

	<?php if (empty($displayData['print'])) : ?>

		<?php if ($canEdit || $displayData['params']->get('show_print_icon') || $displayData['params']->get('show_email_icon')) : ?>
				<ul class="actions">
					<?php if ($displayData['params']->get('show_print_icon')) : ?>
						<li class="print-icon"> <?php echo fap_add_onkeypress(JHtml::_('fapicon.print_popup', $displayData['item'], $displayData['params'])); ?> </li>
					<?php endif; ?>
					<?php if ($displayData['params']->get('show_email_icon')) : ?>
						<li class="email-icon"> <?php echo fap_add_onkeypress(JHtml::_('fapicon.email', $displayData['item'], $displayData['params'])); ?> </li>
					<?php endif; ?>
					<?php if ($canEdit) : ?>
						<li class="edit-icon"> <?php echo fap_add_onkeypress(JHtml::_('fapicon.edit', $displayData['item'], $displayData['params'])); ?> </li>
					<?php endif; ?>
				</ul>
		<?php endif; ?>

	<?php else : ?>

		<div class="pull-right">
			<?php echo JHtml::_('icon.print_screen', $displayData['item'], $displayData['params']); ?>
		</div>

	<?php endif; ?>

