<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @copyright   Copyright (C) 2013 - 2014 Alessandro Pasotti - changed placeholder to title
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');

?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-inline">
    <?php if ($params->get('pretext')) : ?>
        <div class="pretext">
            <p><?php echo $params->get('pretext'); ?></p>
        </div>
    <?php endif; ?>
    <div class="userdata">
        <div id="form-login-username" class="control-group">
            <div class="control-label">
                <?php if (!$params->get('usetext')) : ?>
                    <span class="icon-user hasTooltip" title="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>"></span>
                    <label for="modlgn-username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME'); ?></label>
                <?php else: ?>
                    <label for="modlgn-username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label>
                <?php endif; ?>
            </div>
            <div class="controls">
                <?php if (!$params->get('usetext')) : ?>
                        <input id="modlgn-username" type="text" name="username" class="input-small form-control" tabindex="0" size="18" title="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
                <?php else: ?>
                    <input id="modlgn-username" type="text" name="username" class="input-small form-control" tabindex="0" size="18" title="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
                <?php endif; ?>
            </div>
        </div>
        <div id="form-login-password" class="control-group">
            <div class="control-label">
                <?php if (!$params->get('usetext')) : ?>
                    <span class="icon-lock hasTooltip" title="<?php echo JText::_('JGLOBAL_PASSWORD') ?>"></span>
                    <label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
                <?php else: ?>
                    <label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
                <?php endif; ?>
            </div>
            <div class="controls">
                <?php if (!$params->get('usetext')) : ?>
                    <input id="modlgn-passwd" type="password" name="password" class="input-small form-control" tabindex="0" size="18" title="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
                <?php else: ?>
                    <input id="modlgn-passwd" type="password" name="password" class="input-small form-control" tabindex="0" size="18" title="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
                <?php endif; ?>
            </div>
        </div>
        <?php if (count($twofactormethods) > 1): ?>
        <div id="form-login-secretkey" class="control-group">
            <div class="control-label">
                <?php if (!$params->get('usetext')) : ?>
                    <span class="icon-star hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>"></span>
                    <label for="modlgn-secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?>
                        <span class="hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
                            <span class="icon-help"></span>
                        </span>
                    </label>
                <?php else: ?>
                    <label for="modlgn-secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY') ?></label>
                <?php endif; ?>
            </div>
            <div class="controls">
                <?php if (!$params->get('usetext')) : ?>
                    <input id="modlgn-secretkey" type="text" name="secretkey" class="input-small form-control" tabindex="0" size="18" title="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
                <?php else: ?>
                    <input id="modlgn-secretkey" type="text" name="secretkey" class="input-small form-control" tabindex="0" size="18" title="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
                    <span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
                        <span class="icon-help"></span>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
        <div id="form-login-remember" class="control-group checkbox">
            <div class="control-label">
                <label for="modlgn-remember" class="control-label"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label>
                <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox form-control" value="yes"/>
            </div>
        </div>
        <?php endif; ?>
        <div id="form-login-submit" class="control-group clearfix">
            <div class="controls fap-submit">
                <button type="submit" tabindex="0" name="Submit" class="btn btn-primary"><?php echo JText::_('JLOGIN') ?></button>
            </div>
        </div>
        <?php
            $usersConfig = JComponentHelper::getParams('com_users'); ?>
            <ul class="unstyled">
            <?php if ($usersConfig->get('allowUserRegistration')) : ?>
                <li>
                    <a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
                    <?php echo JText::_('MOD_LOGIN_REGISTER'); ?> <span class="icon-arrow-right"></span></a>
                </li>
            <?php endif; ?>
                <li>
                    <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
                      <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?> <span class="icon-arrow-right"></span></a>
                </li>
                <li>
                    <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?> <span class="icon-arrow-right"></span></a>
                </li>

            </ul>
        <input type="hidden" name="option" value="com_users" />
        <input type="hidden" name="task" value="user.login" />
        <input type="hidden" name="return" value="<?php echo $return; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
    <?php if ($params->get('posttext')) : ?>
        <div class="posttext">
            <p><?php echo $params->get('posttext'); ?></p>
        </div>
    <?php endif; ?>
</form>
