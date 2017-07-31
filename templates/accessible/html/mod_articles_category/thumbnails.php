<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @FAP         Added images
 */

defined('_JEXEC') or die;


function get_image_from_article($id){
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    // Select some fields
    $query->select('introtext, `fulltext`');
    $query->where("id = " . (int)$id);
    $query->from('#__content');
    $db->setQuery($query);
    $db->query();
    if($db->getNumRows()){
        $result = $db->loadRow();
        if (preg_match('/<img.*?\/>/',  $result[0], $img)) {
            return str_replace('pull-center', '', $img[0]);
        }
        if (preg_match('/<img.*?\/>/',  $result[1], $img)) {
            return str_replace('pull-center', '', $img[0]);
        }
    }
    return null;
}

?>

<ul class="unstyled category-module<?php echo $moduleclass_sfx; ?>">
    <?php if ($grouped) : ?>
        <?php foreach ($list as $group_name => $group) : ?>
        <li>
            <div class="mod-articles-category-group"><?php echo $group_name;?></div>
            <ul class="unstyled">
                <?php foreach ($group as $item) : ?>
                    <li class="clearfix">
                        <?php if ($params->get('link_titles') == 1) : ?>
                            <a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
                                <?php echo $item->title; ?>
                            </a>
                        <?php else : ?>
                            <?php echo $item->title; ?>
                        <?php endif; ?>

                        <?php if ($item->displayHits) : ?>
                            <span class="mod-articles-category-hits">
                                (<?php echo $item->displayHits; ?>)
                            </span>
                        <?php endif; ?>

                        <?php if ($params->get('show_author')) : ?>
                            <span class="mod-articles-category-writtenby">
                                <?php echo $item->displayAuthorName; ?>
                            </span>
                        <?php endif;?>

                        <?php if ($item->displayCategoryTitle) : ?>
                            <span class="mod-articles-category-category">
                                (<?php echo $item->displayCategoryTitle; ?>)
                            </span>
                        <?php endif; ?>

                        <?php if ($item->displayDate) : ?>
                            <span class="mod-articles-category-date"><?php echo $item->displayDate; ?></span>
                        <?php endif; ?>

                        <?php if ($params->get('show_introtext')) : ?>
                            <p class="mod-articles-category-introtext">
                                <?php echo $item->displayIntrotext; ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($params->get('show_readmore')) : ?>
                            <p>
                                <a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
                                    <?php if ($item->params->get('access-view') == false) : ?>
                                        <?php echo JText::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE'); ?>
                                    <?php elseif ($readmore = $item->alternative_readmore) : ?>
                                        <?php echo $readmore; ?>
                                        <?php echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
                                            <?php if ($params->get('show_readmore_title', 0) != 0) : ?>
                                                <?php echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit')); ?>
                                            <?php endif; ?>
                                    <?php elseif ($params->get('show_readmore_title', 0) == 0) : ?>
                                        <?php echo JText::sprintf('MOD_ARTICLES_CATEGORY_READ_MORE_TITLE'); ?>
                                    <?php else : ?>
                                        <?php echo JText::_('MOD_ARTICLES_CATEGORY_READ_MORE'); ?>
                                        <?php echo JHtml::_('string.truncate', ($item->title), $params->get('readmore_limit')); ?>
                                    <?php endif; ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <?php endforeach; ?>
    <?php else : ?>
        <?php foreach ($list as $item) : ?>
            <li class="clearfix">
                <?php // ABP: image pull left
                $image = get_image_from_article($item->id);
                if ( !$image ) {
                    $images = json_decode($item->images);
                    if ($images && $images->image_intro){
                        $image = $images->image_intro;
                        $alt = $images->image_intro_alt ? $images->image_intro_alt : ( $images->image_intro_caption ? $images->image_intro_caption : $item->title );
                        $pull = $images->float_intro ? (" pull-" . $images->float_intro) : '';
                        $image = "<img src=\"$images->image_intro\" alt=\"$alt\" class=\"mod-articles-category-intro-image $pull\" />";
                    }
                } else { // Add class, default pull left
                    if (strpos($image, 'class') === false){
                        $image = str_replace('src="', "class=\"pull-left mod-articles-category-intro-image\" src=\"", $image);
                    } else {
                        $image = str_replace('class="', "class=\"pull-left mod-articles-category-intro-image ", $image);
                    }

                }
                if ( $image ){
                    echo $image;
                }
                ?>
                <?php if ($params->get('link_titles') == 1) : ?>
                    <a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
                        <?php echo $item->title; ?>
                    </a>
                <?php else : ?>
                    <?php echo $item->title; ?>
                <?php endif; ?>

                <?php if ($item->displayHits) : ?>
                    <span class="mod-articles-category-hits">
                        (<?php echo $item->displayHits; ?>)
                    </span>
                <?php endif; ?>

                <?php if ($params->get('show_author')) : ?>
                    <span class="mod-articles-category-writtenby">
                        <?php echo $item->displayAuthorName; ?>
                    </span>
                <?php endif;?>

                <?php if ($item->displayCategoryTitle) : ?>
                    <span class="mod-articles-category-category">
                        (<?php echo $item->displayCategoryTitle; ?>)
                    </span>
                <?php endif; ?>

                <?php if ($item->displayDate) : ?>
                    <span class="mod-articles-category-date">
                        <?php echo $item->displayDate; ?>
                    </span>
                <?php endif; ?>

                <?php ?>

                <?php if ($params->get('show_introtext')) : ?>
                    <p class="mod-articles-category-introtext">
                        <?php echo $item->displayIntrotext; ?>
                    </p>
                <?php endif; ?>

                <?php
                // ABP: remove spaces for Chrome newline problem

                if ($params->get('show_readmore')) : ?>
                    <p class="readmore">
                        <a href="<?php echo $item->link; ?>"><?php if ($item->params->get('access-view') == false) : ?><?php echo JText::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE'); ?><?php elseif ($readmore = $item->alternative_readmore) : ?><?php echo $readmore; ?><?php echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit')); ?><?php elseif ($params->get('show_readmore_title', 0) == 0) : ?><?php echo JText::sprintf('MOD_ARTICLES_CATEGORY_READ_MORE_TITLE'); ?><?php else : ?><?php echo JText::_('MOD_ARTICLES_CATEGORY_READ_MORE'); ?><?php echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit')); ?><?php endif; ?></a></p>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
