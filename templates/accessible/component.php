<?php
/**
* This file is part of
* Joomla! FAP 3
* @package   JoomlaFAP
* @author    Alessandro Pasotti
* @copyright    Copyright (C) 2014-2015 Alessandro Pasotti http://www.itopen.it
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
define ('LESS_IS_MORE', '1');
#define ('LESS_COLORS', 'colors_BlueBeige.less');

JHtml::_('behavior.framework', true);
JHtml::_('jquery.framework');

/* include FAP template functions */
require_once JPATH_THEMES.'/'.$this->template.'/fap_template.php';


if($this->params->get('new_positions') == 'yes'){
	require_once(JPATH_THEMES.'/'.$this->template.'/aliases.php');
} else {
	function get_accessible_pos($pos){
		return $pos;
	}
}

// xml prolog
echo '<?xml version="1.0" encoding="'. $this->_charset .'"?' .'>';


$cols = 1;
if ($this->countModules(get_accessible_pos('right'))
	&& JRequest::getCmd('layout') != 'form'
	&& JRequest::getCmd('task') != 'edit') {
	$cols += 1;
}

if($this->countModules(get_accessible_pos('left or inset or user4'))) {
	$cols += 1;
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

// End configuration, starts output
// xml prolog
echo '<?xml version="1.0" encoding="'. $this->_charset .'"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+ARIA 1.0//EN"
  "http://www.w3.org/WAI/ARIA/schemata/xhtml-aria-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta name="language" content="<?php echo $this->language; ?>" />
<meta name="viewport" content="<?php echo $this->params->get('viewport_string', 'width=device-width, initial-scale=1'); ?>"/>
<jdoc:include type="head" />
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
    var fs_default =  "<?php echo $this->params->get('default_font_size'); ?>";
    <?php } ?>
    var fap_text_external_link = "<?php echo JText::_('FAP_OPEN_IN_EXTERNAL_SITE') ?>";
/* ]]> */
</script>
<?php fap_extra_styles( $this, $session); ?>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $this->template;?>/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $this->template;?>/js/fap.js"></script>
<?php fap_extra_fonts( $this ); ?>
</head>
<body class="contentpane <?php echo ($this->params->get('transitions') ? 'transitions ': '') ?><?php echo ($fap_skin_current ? $fap_skin_current : $this->params->get('default_skin').($this->params->get('default_variant') ? ' ' . $this->params->get('default_variant') : ''));?>" id="component-body">
    <div id="all">
        <div id="main">
            <jdoc:include type="message" />
            <jdoc:include type="component" />
        </div>
    </div>
</body>
</html>
