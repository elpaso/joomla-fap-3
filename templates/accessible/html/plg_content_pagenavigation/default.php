<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.pagenavigation
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @fap added clearfix class
 */

defined('_JEXEC') or die;
?>
<ul class="pager pagenav clearfix">
<?php if ($row->prev) : ?>
	<li class="previous">
		<a href="<?php echo $row->prev; ?>" rel="prev"><span class="icon-arrow-left"></span> <?php echo JText::_('JPREV'); ?></a>
	</li>
<?php endif; ?>
<?php if ($row->next) : ?>
	<li class="next">
		<a href="<?php echo $row->next; ?>" rel="next"><?php echo JText::_('JNEXT'); ?> <span class="icon-arrow-right"></span></a>
	</li>
<?php endif; ?>
</ul>
