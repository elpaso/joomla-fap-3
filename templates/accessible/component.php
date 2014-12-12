<?php
/**
* This file is part of
* Joomla! FAP 3
* @package   JoomlaFAP
* @author    Alessandro Pasotti
* @copyright    Copyright (C) 2014 Alessandro Pasotti http://www.itopen.it
* @license      GNU/AGPL

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('behavior.framework', true);


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



?>
<!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<meta name="language" content="<?php echo $this->language; ?>" />
<jdoc:include type="head" />

<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<?php if (file_exists(JPATH_THEMES.'/'.$this->template.'/css/skin_white.less') && !defined('LESS_IS_MORE')) { ?>
<!-- set options before less.js script -->
<script>
  less = {
    env: "development",
    logLevel: 2,
    async: false,
    fileAsync: false,
    poll: 1000,
    functions: {},
    dumpLineNumbers: "comments",
    relativeUrls: false,
    globalVars: {
      colors_definitions: '"<?php echo ( $this->params->get('override_theme') && file_exists(JPATH_THEMES.'/'.$this->template.'/css/'. $this->params->get('override_theme').'.css') ? 'colors_' . str_replace('_theme', '', $this->params->get('override_theme')) . '.less' : 'colors_Default.less' ) ?>"'
    }
  };
</script>
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/skin_white.less" type="text/css" rel="stylesheet/less" />
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/skin_black.less" type="text/css" rel="stylesheet/less" />
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $this->template;?>/js/less-1.7.5.min.js"></script>
<?php } else {
    /* loads the default skin or the override theme if set */
    if ($this->params->get('override_theme') && file_exists(JPATH_THEMES.'/'.$this->template.'/css/'. $this->params->get('override_theme').'.css')) { ?>
    <link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/<?php echo $this->params->get('override_theme').'.css' ?>" rel="stylesheet" type="text/css" />
<?php } else { ?>
    <link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/skin_white.css" type="text/css" rel="stylesheet" />
<?php } ?>
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/skin_black.css" type="text/css" rel="stylesheet" />
<?php } ?>
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/template_css.css" rel="stylesheet" type="text/css" />
<?php
    /* loads the additional custom theme if set */
    if ($this->params->get('custom_theme') && file_exists(JPATH_THEMES.'/'.$this->template.'/css/'. $this->params->get('custom_theme').'.css')) { ?>
    <link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/<?php echo $this->params->get('custom_theme').'.css' ?>" rel="stylesheet" type="text/css" />
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
/* ]]> */
</script>
<?php // set font_size & skin from session
if($fap_font_size = $session->get('fap_font_size')
    || $this->params->get('custom_css')
    || $this->params->get('base_font')
    || $this->params->get('article_header_font')
    )
    {
        ?>
<style type="text/css">
    <?php  if ($fap_font_size = $session->get('fap_font_size')) { ?>
    body#main {
        font-size: <?php echo $fap_font_size ?>%;
    }
    <?php
    } // end fap_font_size
    if ($this->params->get('custom_css')) {
        echo $this->params->get('custom_css');
    } // end custom_css
    if ($this->params->get('base_font')){ ?>
    body {
        font-family: <?php echo $this->params->get('base_font'); ?> !important;
    }
    <?php
    } // end base_font
    if ($this->params->get('article_header_font')){ ?>
    .item-title {
        font-family: <?php echo $this->params->get('article_header_font'); ?> !important;
    }
    <?php
    } // end article_header_font
    ?>
</style>
<?php } ?>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $this->template;?>/js/skin_alter.js"></script>
<?php // Fonts
    if ( ($this->params->get('base_font') && $this->params->get('base_font_google'))
        || ($this->params->get('article_header_font') && $this->params->get('article_header_font_google') )
    )
    {
        if( $this->params->get('base_font_google') ) {
            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://fonts.googleapis.com/css?family=" . urlencode($this->params->get('base_font_google')). "\" />";
        }
        if( $this->params->get('article_header_font_google') ) {
            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://fonts.googleapis.com/css?family=" . urlencode($this->params->get('article_header_font_google')). "\" />";
        }
    }
 ?>
</head>
<body class="contentpane <?php echo ($fap_skin_current ? $fap_skin_current : $this->params->get('default_skin')); ?>" id="component-body">
    <div id="all">
        <div id="main">
            <jdoc:include type="message" />
            <jdoc:include type="component" />
        </div>
    </div>
</body>
</html>
