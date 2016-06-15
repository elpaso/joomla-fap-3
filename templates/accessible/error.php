<?php
/**
* This file is part of
* Joomla! FAP 3
* @package   JoomlaFAP AccessiblePro
* @author    Alessandro Pasotti
* @copyright    Copyright (C) 2012-2015 Alessandro Pasotti http://www.itopen.it
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
# Disable LESS if set:
define ('LESS_IS_MORE', '1');
#define ('LESS_COLORS', 'colors_BlueBeige.less');


$app = JFactory::getApplication();

// Getting params from template
$params = $app->getTemplate(true)->params;
$template = $app->getTemplate(true);
$doc             = JFactory::getDocument();

/* include FAP template functions */
$tpl_name = $template->template;
require_once JPATH_THEMES.'/'.$tpl_name.'/fap_template.php';

JHtml::_('behavior.framework', true);
JHtml::_('jquery.framework');

header('Content-Type: text/html; charset=' . $this->_charset, true);


if($params->get('new_positions') == 'yes'){
    $new_positions = true;
    require_once(JPATH_THEMES.'/'.$tpl_name.'/aliases.php');
} else {
    function get_accessible_pos($pos){
        return $pos;
    }
    $new_positions = false;
}

$sitename = $app->getCfg('sitename');
// Logo file or site title param
if ($params->get('logo_file'))
{
    $logo = '<img src="'. JUri::root() . $params->get('logo_file') .'" alt="'. $sitename .'" class="img img-responsive"/>';
} else {
    $logo = null;
}


// Define wich columns are to be shown

$has_left_column = false;
$has_right_column = false;

$cols = 1;
$main_column_class = '';

if( $has_left_column ) {
    $cols ++;
    $main_column_class .= ' has-left-column';
}
if( $has_right_column ) {
    $cols ++;
    $main_column_class .= ' has-right-column';
}

/* Accessibility session storage */
$session = JFactory::getSession();

if($fap_skin_request = JRequest::getVar('fap_skin')){
    $fap_skin_current = $session->get('fap_skin_current');
    if(!$fap_skin_current){
        $fap_skin_current = $params->get('default_skin');
    }
    switch($fap_skin_request) {
        case 'reset':
            $fap_skin_current = $params->get('default_skin');
            $session->set('fap_font_size', 80);
        break;
        case 'liquid':
            if(strpos($fap_skin_current, ' liquid') !== false){
                $fap_skin_current = preg_replace('# liquid#', '', $fap_skin_current);
            } else {
                $fap_skin_current .= ' liquid';
            }
        break;
        case 'contrasthigh':
            if(strpos($fap_skin_current, 'white') !== false){
                $fap_skin_current = str_replace('white', 'black', $fap_skin_current);
            } else {
                $fap_skin_current = str_replace('black', 'white', $fap_skin_current);
            }
        break;
    }
    $session->set('fap_skin_current', $fap_skin_current);
}


if($fap_font_size_request = JRequest::getVar('fap_font_size')){
    $font_size = $session->get('fap_font_size');
    if(!$font_size){
        $font_size = 80;
    }
    if('increase' == $fap_font_size_request){
        $font_size += 5;
    } else {
        $font_size -= 5;
    }
    $font_size = max($font_size, 70);
    $font_size = min($font_size, 100);
    $session->set('fap_font_size', $font_size);
}


// End configuration, starts output
// xml prolog
echo '<?xml version="1.0" encoding="'. $this->_charset .'"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+ARIA 1.0//EN"
  "http://www.w3.org/WAI/ARIA/schemata/xhtml-aria-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta name="language" content="<?php echo $this->language; ?>" />
<meta name="viewport" content="<?php echo $params->get('viewport_string', 'width=device-width, initial-scale=1'); ?>"/>
<jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<?php if ( ! fap_load_less($template) ) {
    fap_load_theme( $template );
} ?>
<link href="<?php echo JURI::base();?>templates/<?php echo $tpl_name; ?>/css/<?php echo fap_css_name(JPATH_THEMES.'/'.$tpl_name.'/css/', 'template_css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/<?php echo fap_css_name(JPATH_THEMES.'/'.$this->template.'/css/', 'icomoon_valid'); ?>" rel="stylesheet" type="text/css" />
<?php
    /* loads the additional custom theme if set */
    if ($params->get('custom_theme') && $css_name = fap_css_name(JPATH_THEMES.'/'.$tpl_name.'/css/', $params->get('custom_theme'))) { ?>
    <link href="<?php echo JURI::base();?>templates/<?php echo $tpl_name; ?>/css/<?php echo $css_name; ?>" rel="stylesheet" type="text/css" />
<?php } ?>
<script type="text/javascript">
/* <![CDATA[ */
    var skin_default = '<?php echo $params->get('default_skin').($params->get('default_variant') ? ' ' . $params->get('default_variant') : ''); ?>';
    <?php if($fap_skin_current = $session->get('fap_skin_current')){ ?>
    var skin_current = "<?php echo $fap_skin_current; ?>";
    <?php } ?>
    <?php if($params->get('default_font_size')){ ?>
    var fs_default =  "<?php echo $params->get('default_font_size'); ?>";
    <?php } ?>
    var fap_text_external_link = "<?php echo JText::_('FAP_OPEN_IN_EXTERNAL_SITE') ?>";
/* ]]> */
</script>
<?php fap_extra_styles( $template, $session); ?>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $tpl_name;?>/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $tpl_name;?>/js/fap.js"></script>
<?php // Responsive
if ($params->get('responsive_enabled') == 'yes') { ?>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $tpl_name;?>/js/breakpoints.js"></script>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $tpl_name;?>/js/SlickNav-master/jquery.slicknav.min.js"></script>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $tpl_name;?>/js/responsive.js"></script>
<?php fap_load_responsive( $template ); ?>
<?php } //-- end responsive ?>
<?php fap_extra_fonts( $template ); ?>
<?php fap_extra_head( $template ); ?>
</head>
<body class="<?php echo ($params->get('transitions') ? 'transitions ': '') ?><?php echo ($fap_skin_current ? $fap_skin_current : $params->get('default_skin').($params->get('default_variant') ? ' ' . $params->get('default_variant') : ''));?>" id="main">
    <div id="menu-top-bg"></div>
    <div id="wrapper" class="<?php echo $main_column_class; ?>">
        <div role="banner">
            <div class="hidden">
                <a id="up"></a>
                <div><?php echo $this->description; ?></div>
                <!-- accesskeys here! -->
                <ul>
                    <li><a accesskey="P" href="#main-content"><?php echo JText::_('FAP_SKIP_TO_CONTENT'); ?></a></li>
                    <li><a accesskey="M" href="#main-menu"><?php echo JText::_('FAP_JUMP_TO_MAIN_NAVIGATION_AND_LOGIN'); ?></a></li>
                </ul>
            </div>

            <?php if ($logo) { ?>
            <div id="logo">
                <a href="<?php echo $this->baseurl; ?>" title="<?php echo JText::_('FAP_BACK_TO_HOME_PAGE'); ?>">
                    <?php echo $logo;?> <?php if ($params->get('sitedescription')) { echo '<div class="site-description">'. htmlspecialchars($params->get('sitedescription')) .'</div>'; } ?>
                </a>
            </div>
            <?php } ?>

        </div><?php // ./banner end ?>
        <?php
        /*********************************************************
         *
         * Main central area
         *
         */ ?>
        <div role="main" id="main-<?php echo $cols ;?>" class="maincomponent">

            <a id="main-content" class="hidden"></a>
            <div class="padding">
                <jdoc:include type="message" />

                <!-- Begin Content -->
                <h1 class="page-header"><?php echo JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'); ?></h1>
                <div class="well">
                    <div class="row-fluid">
                        <div class="span6">
                            <p><strong><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></strong></p>
                            <p><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
                            <ul>
                                <li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
                                <li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
                                <li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
                                <li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
                            </ul>
                        </div>
                        <div class="span6">
                            <?php if (JModuleHelper::getModule('search')) : ?>
                                <p><strong><?php echo JText::_('JERROR_LAYOUT_SEARCH'); ?></strong></p>
                                <p><?php echo JText::_('JERROR_LAYOUT_SEARCH_PAGE'); ?></p>
                                <?php echo $doc->getBuffer('module', 'search'); ?>
                            <?php endif; ?>
                            <p><?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?></p>
                            <p><a href="<?php echo $this->baseurl; ?>/index.php" class="btn"><span class="icon-home"></span> <?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></p>
                        </div>
                    </div>
                    <hr />
                    <p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
                    <blockquote>
                        <span class="label label-inverse"><?php echo $this->error->getCode(); ?></span> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8');?>
                    </blockquote>
                    <?php if ($this->debug) : ?>
                        <?php echo $this->renderBacktrace(); ?>
                    <?php endif; ?>
                </div>
                <!-- End Content -->

            </div>
        </div>

        <?php if ($params->get('back_to_top_enabled') == 'yes'){ ?>
        <a href="#" class="scroll-to-top button" style="display:none"><?php echo JText::_('FAP_SCROLL_TO_TOP') ?>&nbsp;<em class="icon-uparrow"></em></a>
        <?php } ?>
        <?php /* Siete pregati di non rimuovere questo codice! */
        echo fap_footer( $template );
        ?>
    </div>
    <jdoc:include type="modules" name="debug" />
</body>
</html>
