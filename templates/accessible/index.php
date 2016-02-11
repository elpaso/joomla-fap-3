<?php
/**
* This file is part of
* Joomla! FAP 3
* @package   JoomlaFAP
* @author    Alessandro Pasotti
* @copyright    Copyright (C) 2012-2016 Alessandro Pasotti http://www.itopen.it
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
//define ('LESS_IS_MORE', '1');
define ('LESS_COLORS', 'colors_Green.less');

/* include FAP template functions */
require_once JPATH_THEMES.'/'.$this->template.'/fap_template.php';

JHtml::_('behavior.framework', true);
JHtml::_('jquery.framework');

if($this->params->get('new_positions') == 'yes'){
    $new_positions = true;
    require_once(JPATH_THEMES.'/'.$this->template.'/aliases.php');
} else {
    function get_accessible_pos($pos){
        return $pos;
    }
    $new_positions = false;
}

$app = JFactory::getApplication();
$sitename = $app->getCfg('sitename');
// Logo file or site title param
if ($this->params->get('logo_file'))
{
    $logo = '<img src="'. JUri::root() . $this->params->get('logo_file') .'" alt="'. $sitename .'" />';
} else {
    $logo = null;
}

// Define wich columns are to be shown

$has_left_column = $this->countModules(get_accessible_pos('left or inset or user4'));
$has_right_column = $this->countModules(get_accessible_pos('right'))
        && JRequest::getCmd('layout') != 'form'
        && JRequest::getCmd('layout') != 'edit';

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
        $fap_skin_current = $this->params->get('default_skin');
    }
    switch($fap_skin_request) {
        case 'reset':
            $fap_skin_current = $this->params->get('default_skin');
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

// Load icomoon icons
$doc = JFactory::getDocument();
$doc->addStyleSheet($this->baseurl.'/media/jui/css/icomoon.css');


// End configuration, starts output
// xml prolog
echo '<?xml version="1.0" encoding="'. $this->_charset .'"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+ARIA 1.0//EN"
  "http://www.w3.org/WAI/ARIA/schemata/xhtml-aria-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta name="language" content="<?php echo $this->language; ?>" />
<jdoc:include type="head" />
<meta name="viewport" content="<?php echo $this->params->get('viewport_string', 'width=device-width, initial-scale=1'); ?>"/>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<?php if ( ! fap_load_less($this) ) {
    fap_load_theme( $this );
} ?>
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/<?php echo fap_css_name(JPATH_THEMES.'/'.$this->template.'/css/', 'template_css'); ?>" rel="stylesheet" type="text/css" />
<?php
    /* loads the additional custom theme if set */
    if ($this->params->get('custom_theme') && $css_name = fap_css_name(JPATH_THEMES.'/'.$this->template.'/css/', $this->params->get('custom_theme'))) { ?>
    <link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/<?php echo $css_name; ?>" rel="stylesheet" type="text/css" />
<?php } ?>
<script type="text/javascript">
/* <![CDATA[ */
    var skin_default = '<?php echo $this->params->get('default_skin').($this->params->get('default_variant') ? ' ' . $this->params->get('default_variant') : ''); ?>';
    <?php if($fap_skin_current = $session->get('fap_skin_current')){ ?>
    var skin_current = "<?php echo $fap_skin_current; ?>";
    <?php } ?>
    <?php if($this->params->get('default_font_size')){ ?>
    var  fs_default =  "<?php echo $this->params->get('default_font_size'); ?>";
    <?php } ?>
    var fap_text_external_link = "<?php echo JText::_('FAP_OPEN_IN_EXTERNAL_SITE') ?>";
/* ]]> */
</script>
<?php fap_extra_styles( $this, $session); ?>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $this->template;?>/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $this->template;?>/js/fap.js"></script>
<?php // Responsive
if ($this->params->get('responsive_enabled') == 'yes') { ?>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $this->template;?>/js/breakpoints.js"></script>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $this->template;?>/js/SlickNav-master/jquery.slicknav.min.js"></script>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $this->template;?>/js/responsive.js"></script>
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/<?php echo fap_css_name(JPATH_THEMES.'/'.$this->template.'/css/', 'responsive'); ?>" type="text/css" rel="stylesheet" />
<?php } //-- end responsive ?>
<?php fap_extra_fonts( $this); ?>
</head>
<body class="<?php echo ($fap_skin_current ? $fap_skin_current : $this->params->get('default_skin').($this->params->get('default_variant') ? ' ' . $this->params->get('default_variant') : ''));?>" id="main">
    <div id="wrapper" class="<?php echo $main_column_class; ?>">
        <div role="banner">
            <div class="hidden">
                <a id="up"></a>
                <div><?php echo $this->description; ?></div>
                <!-- accesskey goes here! -->
                <ul>
                    <li><a accesskey="P" href="#main-content"><?php echo JText::_('FAP_SKIP_TO_CONTENT'); ?></a></li>
                    <li><a accesskey="M" href="#main-menu"><?php echo JText::_('FAP_JUMP_TO_MAIN_NAVIGATION_AND_LOGIN'); ?></a></li>
                </ul>
            </div>
            <?php if ($this->countModules(get_accessible_pos('topmost'))) { ?>
            <div id="top" class="clearfix">
                <div class="padding">
                <jdoc:include type="modules" name="topmost" />
                </div>
            </div>
            <?php } ?>
            <?php if ($logo) { ?>
            <div id="logo">
                <a href="<?php echo $this->baseurl; ?>" title="<?php echo JText::_('FAP_BACK_TO_HOME_PAGE'); ?>">
                    <?php echo $logo;?> <?php if ($this->params->get('sitedescription')) { echo '<div class="site-description">'. htmlspecialchars($this->params->get('sitedescription')) .'</div>'; } ?>
                </a>
            </div>
            <?php } ?>
            <?php if ($this->countModules(get_accessible_pos('breadcrumb'))) { ?>
            <div id="pathway">
                <div class="padding">
                <jdoc:include type="modules" name="breadcrumb" />
                <?php if($new_positions){ ?>
                <jdoc:include type="modules" name="position-0" />
                <?php } ?>
                </div>
            </div>
            <?php } ?>
            <?php // Banner from component or CSS
            if ($this->countModules(get_accessible_pos('banner'))) { ?>
            <div id="banner">
                <div class="padding">
                <jdoc:include type="modules" name="banner" style="accessible" />
                </div>
            </div>
            <?php } ?>
            <?php if ($this->countModules(get_accessible_pos('user3'))) { ?>
            <div id="menu-top" class="menu-top clearfix">
                <div class="padding">
                <jdoc:include type="modules" name="user3" style="accessible" />
                <?php if($new_positions){ ?>
                <jdoc:include type="modules" name="position-1" style="accessible" />
                <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div><?php // ./banner end ?>
        <div role="main" id="main-<?php print $cols ;?>" class="maincomponent">
          <?php if ($this->countModules(get_accessible_pos('pathway'))){ ?>
          <div id="center-module">
              <div class="padding">
                  <jdoc:include type="modules" name="pathway" />
              </div>
          </div>
          <?php } ?>
          <?php if ($this->countModules(get_accessible_pos('center'))){ ?>
          <div id="center-module">
              <div class="padding">
                  <jdoc:include type="modules" name="center" style="accessible" />
                  <?php if($new_positions){ ?>
                  <jdoc:include type="modules" name="position-3" style="accessible" />
                  <?php } ?>
              </div>
          </div>
          <?php } ?>
            <a id="main-content" class="hidden"></a>
            <div class="padding">
            <jdoc:include type="message" />
            <?php if ($this->countModules(get_accessible_pos('top')) && JRequest::getCmd('task') != 'edit') { ?>
            <jdoc:include type="modules" name="top" style="accessible" />
            <?php } ?>
            <jdoc:include type="component" style="accessible"/>
            <?php if ($this->countModules(get_accessible_pos('user1 or user2'))) { ?>
            <div id="user12" class="clearfix">
                <?php if ($this->countModules(get_accessible_pos('user1 or user2')) && ! $this->countModules(get_accessible_pos('user1 and user2'))) { ?>
                <div class="userfull">
                    <jdoc:include type="modules" name="user1" style="accessible" />
                    <jdoc:include type="modules" name="user2" style="accessible" />
                </div>
                <?php } ?>
                <?php if ($this->countModules(get_accessible_pos('user1 and user2'))) { ?>
                <div class="column_left pull-left">
                    <jdoc:include type="modules" name="user1" style="accessible" />
                </div>
                <div class="column_right pull-right">
                    <jdoc:include type="modules" name="user2" style="accessible" />
                </div>
                <?php } ?>
            </div>
            <?php } ?>
            <?php if ($this->countModules(get_accessible_pos('bottom')) && JRequest::getCmd('task') != 'edit') { ?>
            <jdoc:include type="modules" name="bottom" style="accessible" />
            <?php if($new_positions){ ?>
                <jdoc:include type="modules" name="position-2" style="accessible" />
            <?php } ?>
            <?php } ?>
            </div>
        </div>
        <?php if ( $has_left_column || $has_right_column ) { ?>
        <div role="complementary">
            <?php if ( $has_left_column ) { ?>
            <div id="sidebar-left">
                <div class="padding">
                    <a id="main-menu" class="hidden"></a>
                    <?php if ($this->countModules(get_accessible_pos('user4'))) { ?>
                    <div id="searchbox">
                        <jdoc:include type="modules" name="user4" style="accessible" />
                    </div>
                    <?php } ?>
                    <jdoc:include type="modules" name="left" style="accessible" />
                    <?php if ($this->countModules(get_accessible_pos('inset'))) { ?>
                    <div class="inset">
                        <jdoc:include type="modules" name="inset" style="accessible" />
                    </div>
                    <?php } ?>
                    <?php if($new_positions){ ?>
                        <jdoc:include type="modules" name="position-8" style="accessible" />
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if ( $has_right_column ) { ?>
            <div id="sidebar-right">
                <div class="padding">
                    <jdoc:include type="modules" name="right" style="accessible" />
                    <?php if($new_positions){ ?>
                    <jdoc:include type="modules" name="position-7" style="accessible" />
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div><?php // ./complementary end ?>
         <?php } ?>
        <div id="footer">
            <div class="padding">
                <?php if ($this->countModules(get_accessible_pos('footer'))) { ?>
                <jdoc:include type="modules" name="footer" style="accessible" />
                <?php } ?>
            </div>

        </div>
        <?php /* Siete pregati di non rimuovere questo codice! */
        echo fap_footer( $this );
        ?>
    </div>
    <jdoc:include type="modules" name="debug" />
</body>
</html>
