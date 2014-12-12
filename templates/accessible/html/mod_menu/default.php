<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2014 ItOpen, All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @fap added chevron arrow down to deeper
 * @fap added WAI-ARIA
 * @fap added level
 */

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
?>
<?php // The menu class is deprecated. Use nav instead. ?>
<div role="navigation">
<ul role="menubar"  class="nav menu<?php echo $class_sfx;?>"<?php
	$tag = '';

	if ($params->get('tag_id') != null)
	{
		$tag = $params->get('tag_id') . '';
		echo ' id="' . $tag . '"';
	}
?>>
<?php

$level = 0;
foreach ($list as $i => &$item)
{
	$class = 'item-' . $item->id;

	if (($item->id == $active_id) OR ($item->type == 'alias' AND $item->params->get('aliasoptions') == $active_id))
	{
		$class .= ' current';
	}

	if (in_array($item->id, $path))
	{
		$class .= ' active';
	}
	elseif ($item->type == 'alias')
	{
		$aliasToId = $item->params->get('aliasoptions');

		if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
		{
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path))
		{
			$class .= ' alias-parent-active';
		}
	}

	if ($item->type == 'separator')
	{
		$class .= ' divider';
	}

	if ($item->deeper)
	{
		$class .= ' deeper';
	}

	if ($item->parent)
	{
		$class .= ' parent';
	}

    $class .= ' level-' . $level;

	if (!empty($class))
	{
		$class = ' class="' . trim($class) . '"';
	}

    if( $item->deeper ){
        echo '<li role="presentation" ' . $class . '>';
    } else  {
        echo '<li ' . $class . '>';
    }


	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
		case 'heading':
			require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
			break;
	endswitch;

	// The next item is deeper.
	if ($item->deeper)
	{
        $level++;
		echo '<ul role="menu" class="nav-child unstyled small level-'.$level.'">';
	}
	elseif ($item->shallower)
	{
		// The next item is shallower.
        $level -= $item->level_diff;
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	else
	{
		// The next item is on the same level.
		echo '</li>';
	}
}
?></ul>
</div>
