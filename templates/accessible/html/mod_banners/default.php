<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2015 ItOpen. All rights reserved.
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_ROOT . '/components/com_banners/helpers/banner.php';
$baseurl = JUri::base();
?>

<?php

$_doc = JFactory::getDocument();
$_template_params = JFactory::getApplication()->getTemplate(true)->params;
$_template_name = JFactory::getApplication()->getTemplate();

// This is the flexslider banner for jspro only
if ($_template_params->get('banner_slideshow_enabled', 'no') == 'yes') {

    JHtml::_('jquery.framework');

    $_doc->addScript( $baseurl . 'templates/'. $_template_name
        . '/jspro/flexslider/jquery.flexslider'
        . (JDEBUG ? '' : '-min') . '.js' );

    $_style_url = $baseurl . 'templates/'. $_template_name. '/jspro/flexslider/flexslider.css';

    $_doc->addStyleSheet( $_style_url );

    $img_num = 0;

    $_id = $module->id;
    $max_width = $max_height = 0;

    $show_text = $_template_params->get('banner_slideshow_show_text', 'no') == 'yes';
    $banner_slideshow_transition_type = $_template_params->get('banner_slideshow_transition_type', 'fade');
    $banner_slideshow_interval = (int) $_template_params->get('banner_slideshow_interval', '3000');
    $banner_slideshow_transition_duration = (int) $_template_params->get('banner_slideshow_transition_duration', '300');
    $banner_slideshow_auto_start = $_template_params->get('banner_slideshow_auto_start', 'false') == 'false' ? 'false' : 'true';


if (!function_exists('show_slider_text')){
    function show_slider_text($item, $show_text){
                        if ($show_text) : ?>
                           <p class="slide-caption"><?php echo $item->name; ?></p>
                            <?php if ($item->description): ?>
                            <p class="slide-caption-desc"><?php echo str_replace( array('<p>', '</p>'), '', $item->description); ?></p>
                            <?php endif;
                        endif;
    }
}

?>
<div class="ap-slideshow flexslider loader <?php echo $banner_slideshow_transition_type ?>" id="ap-slideshow-<?php echo $_id; ?>">
    <div class="slides">
    <?php foreach ($list as $item) : ?>
    <?php $link = JRoute::_('index.php?option=com_banners&task=click&id=' . $item->id);?>
    <?php $imageurl = $item->params->get('imageurl');?>
        <?php $width = $item->params->get('width');?>
        <?php $height = $item->params->get('height');?>
        <?php if (BannerHelper::isImage($imageurl)) :?>
        <div>
            <?php // Image based banner ?>
            <?php $alt = $item->params->get('alt');?>
            <?php $alt = $alt ? $alt : $item->name; ?>
            <?php $alt = $alt ? $alt : JText::_('MOD_BANNERS_BANNER'); ?>
            <?php if ($item->clickurl) :?>
                <?php // Wrap the banner in a link?>
                <?php $target = $params->get('target', 1);?>
                <?php if ($target == 1) :?>
                    <?php // Open in a new window?>
                    <a
                        href="<?php echo $link; ?>" target="_blank"
                        title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
                        <img
                            src="<?php echo $baseurl . $imageurl;?>"
                            alt="<?php echo $alt;?>"
                            title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>"
                            <?php if (!empty($width)) echo 'width ="' . $width . '"';?>
                            <?php if (!empty($height)) echo 'height ="' . $height . '"';?>
                        />
                    </a>
                    <?php show_slider_text($item, $show_text); ?>
                <?php elseif ($target == 2):?>
                    <?php // Open in a popup window?>
                    <a
                        href="<?php echo $link;?>" onclick="window.open(this.href, '',
                            'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');
                            return false"
                        title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
                        <img
                            src="<?php echo $baseurl . $imageurl;?>"
                            alt="<?php echo $alt;?>"
                            title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>"
                            <?php if (!empty($width)) echo 'width ="' . $width . '"';?>
                            <?php if (!empty($height)) echo 'height ="' . $height . '"';?>
                        />
                    </a>
                    <?php show_slider_text($item, $show_text); ?>
                <?php else :?>
                    <?php // Open in parent window?>
                    <a
                        href="<?php echo $link;?>"
                        title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
                        <img
                            src="<?php echo $baseurl . $imageurl;?>"
                            alt="<?php echo $alt;?>"
                            title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>"
                            <?php if (!empty($width)) echo 'width ="' . $width . '"';?>
                            <?php if (!empty($height)) echo 'height ="' . $height . '"';?>
                        />
                    </a>
                    <?php show_slider_text($item, $show_text); ?>
                <?php endif;?>
            <?php else :?>
                <?php // Just display the image if no link specified?>
                <img
                    src="<?php echo $baseurl . $imageurl;?>"
                    alt="<?php echo $alt;?>"
                    title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>"
                    <?php if (!empty($width)) echo 'width ="' . $width . '"';?>
                    <?php if (!empty($height)) echo 'height ="' . $height . '"';?>
                />
                <?php show_slider_text($item, $show_text); ?>
            <?php endif;?>
        </div>
        <?php else: // not an image ?>
            <?php if ($item->custombannercode): ?>
            <div>
                <?php echo $item->custombannercode; ?>
            </div>
            <?php endif;?>
        <?php endif;?>
        <?php
            $max_width = max($max_width, $width);
            $max_height = max($max_height, $height);
        ?>
<?php
    $img_num++;
    endforeach;
?>
    </div><?php // END slides ?>
</div><?php // END ap-slideshow


$_js = <<< _JSF_
jQuery(window).load(function() {
  var flex_target = jQuery('.flexslider');
  flex_target.flexslider({
    animation: "$banner_slideshow_transition_type",
    slideshowSpeed: $banner_slideshow_interval,
    animationSpeed: $banner_slideshow_transition_duration,
    pauseOnHover: true,
    slideshow: $banner_slideshow_auto_start, // autostart
    pausePlay: true,
    controlNav: true,
    pauseOnHover: true,
    pauseOnAction: true,
    selector: ".slides > div",
    start: function(slider) {
        flex_target.removeClass('loader');
        // In case of smoothHeight we need to call it twice!
        // flex_target.flexslider('resize');
        flex_target.flexslider('resize');
        jQuery('body').on("skinchanged", function(){
            flex_target.flexslider('resize');
        });
        if ( "$banner_slideshow_transition_type" == 'slide'){
            flex_target.find('.slide-caption, .slide-caption-desc').hide();
            setTimeout(function(){
                    flex_target.find('.slide-caption, .slide-caption-desc').fadeIn();
                },
                $banner_slideshow_transition_duration
            );
        }
    }
  });
});
_JSF_;

    $_doc->addScriptDeclaration($_js);
?>
<?php if ($footerText) : ?>
<div class="bannerfooter">
    <?php echo $footerText; ?>
</div>
<?php endif; ?>

<?php } else { // end slideshow, start normal banner ?>
<div class="bannergroup<?php echo $moduleclass_sfx ?>">
<?php if ($headerText) : ?>
	<?php echo $headerText; ?>
<?php endif; ?>

<?php foreach ($list as $item) : ?>
	<div class="banneritem">
		<?php $link = JRoute::_('index.php?option=com_banners&task=click&id=' . $item->id);?>
		<?php if ($item->type == 1) :?>
			<?php // Text based banners ?>
			<?php echo str_replace(array('{CLICKURL}', '{NAME}'), array($link, $item->name), $item->custombannercode);?>
		<?php else:?>
			<?php $imageurl = $item->params->get('imageurl');?>
			<?php $width = $item->params->get('width');?>
			<?php $height = $item->params->get('height');?>
			<?php if (BannerHelper::isImage($imageurl)) :?>
				<?php // Image based banner ?>
				<?php $alt = $item->params->get('alt');?>
				<?php $alt = $alt ? $alt : $item->name; ?>
				<?php $alt = $alt ? $alt : JText::_('MOD_BANNERS_BANNER'); ?>
				<?php if ($item->clickurl) :?>
					<?php // Wrap the banner in a link?>
					<?php $target = $params->get('target', 1);?>
					<?php if ($target == 1) :?>
						<?php // Open in a new window?>
						<a
							href="<?php echo $link; ?>" target="_blank"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="' . $width . '"';?>
								<?php if (!empty($height)) echo 'height ="' . $height . '"';?>
							/>
						</a>
					<?php elseif ($target == 2):?>
						<?php // Open in a popup window?>
						<a
							href="<?php echo $link;?>" onclick="window.open(this.href, '',
								'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');
								return false"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="' . $width . '"';?>
								<?php if (!empty($height)) echo 'height ="' . $height . '"';?>
							/>
						</a>
					<?php else :?>
						<?php // Open in parent window?>
						<a
							href="<?php echo $link;?>"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="' . $width . '"';?>
								<?php if (!empty($height)) echo 'height ="' . $height . '"';?>
							/>
						</a>
					<?php endif;?>
				<?php else :?>
					<?php // Just display the image if no link specified?>
					<img
						src="<?php echo $baseurl . $imageurl;?>"
						alt="<?php echo $alt;?>"
						<?php if (!empty($width)) echo 'width ="' . $width . '"';?>
						<?php if (!empty($height)) echo 'height ="' . $height . '"';?>
					/>
				<?php endif;?>
			<?php elseif (BannerHelper::isFlash($imageurl)) :?>
				<object
					classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
					codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
					<?php if (!empty($width)) echo 'width ="' . $width . '"';?>
					<?php if (!empty($height)) echo 'height ="' . $height . '"';?>
				>
					<param name="movie" value="<?php echo $imageurl;?>" />
					<embed
						src="<?php echo $imageurl;?>"
						loop="false"
						pluginspage="http://www.macromedia.com/go/get/flashplayer"
						type="application/x-shockwave-flash"
						<?php if (!empty($width)) echo 'width ="' . $width . '"';?>
						<?php if (!empty($height)) echo 'height ="' . $height . '"';?>
					/>
				</object>
			<?php endif;?>
		<?php endif;?>
		<div class="clr"></div>
	</div>
<?php
    endforeach;
?>
</div>

<?php if ($footerText) : ?>
<div class="bannerfooter">
    <?php echo $footerText; ?>
</div>
<?php endif; ?>

<?php } // end slideshow else ?>
