<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
//JHtml::_('formbehavior.chosen', 'select');

require_once(dirname(__FILE__) . '/../../fap_utils.php');



if ( ! $this->escape($this->filter) ) {
    $klass = 'class="inputbox form-control placeholder"';
} else {
    $klass = 'class="inputbox form-control" value="' . $this->escape($this->filter) . '"';
}

JHtml::_('behavior.caption');
?>
<div class="archive<?php echo $this->pageclass_sfx;?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<div class="page-header">
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
</div>
<?php endif; ?>
<form id="adminForm" action="<?php echo JRoute::_('index.php')?>" method="post" class="form-inline">
	<fieldset class="filters">
        <div class="filter-search control-group">
            <?php if ($this->params->get('filter_field') != 'hide') : ?>
            <div class="control-label">
                <label class="filter-search-lbl element-invisible" for="filter-search"><?php echo JText::_('COM_CONTENT_TITLE_FILTER_LABEL') . '&#160;'; ?></label>
            </div>
            <div class="controls">
                <input type="text"<?php echo $klass; ?> name="filter-search" id="filter-search" <?php echo fap_placeholder(JText::_('COM_CONTENT_TITLE_FILTER_LABEL')); ?>/>
            </div>
            <?php endif; ?>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label class="" for="month"><?php echo JText::_('FAP_COM_CONTENT_DATE_FILTER_LABEL') . '&#160;'; ?></label>
            </div>
            <div class="controls">
            <?php echo $this->form->monthField; ?>
            <?php echo $this->form->yearField; ?>
            <?php echo $this->form->limitField; ?>
            </div>
        </div>
        <div class="button-bar fap-submit">
            <button type="submit" class="btn btn-primary" style="vertical-align: top;"><?php echo JText::_('FAP_FILTER_BUTTON'); ?></button>
            <input type="hidden" name="view" value="archive" />
            <input type="hidden" name="option" value="com_content" />
            <input type="hidden" name="limitstart" value="0" />
        </div>
	</fieldset>

	<?php echo $this->loadTemplate('items'); ?>
</form>
</div>
