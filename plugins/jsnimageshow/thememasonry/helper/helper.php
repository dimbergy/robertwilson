<?php
/**
 * @version    helper.php$
 * @package    4.9.2
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2015 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

defined('_JEXEC') or die('Restricted access');
$objJSNShowcaseTheme = JSNISFactory::getObj('classes.jsn_is_showcasetheme');
$objJSNShowcaseTheme->importTableByThemeName($this->_showcaseThemeName);
$objJSNShowcaseTheme->importModelByThemeName($this->_showcaseThemeName);
$modelShowcaseTheme = JModelLegacy::getInstance($this->_showcaseThemeName);
$items = $modelShowcaseTheme->getTable($themeID);

JSNISFactory::importFile('classes.jsn_is_htmlselect');

$lists['showCaption'] = JHTML::_('jsnselect.booleanlist', 'show_caption', 'class="inputbox"', $items->show_caption, 'JYES', 'JNO', false, 'yes', 'no');
$lists['captionShowTitle'] = JHTML::_('jsnselect.booleanlist', 'caption_show_title', 'class="inputbox"', $items->caption_show_title, 'JYES', 'JNO', false, 'yes', 'no');
$lists['captionShowDescription'] = JHTML::_('jsnselect.booleanlist', 'caption_show_description', 'class="inputbox"', $items->caption_show_description, 'JYES', 'JNO', false, 'yes', 'no');
$lists['percentPosition'] = JHTML::_('jsnselect.booleanlist', 'percent_position', 'class="inputbox"', $items->percent_position, 'JYES', 'JNO', false, 'true', 'false');
$lists['isFitWidth'] = JHTML::_('jsnselect.booleanlist', 'is_fit_width', 'class="inputbox"', $items->is_fit_width, 'JYES', 'JNO', false, 'true', 'false');
$lists['isResizeBound'] = JHTML::_('jsnselect.booleanlist', 'is_resize_bound', 'class="inputbox"', $items->is_resize_bound, 'JYES', 'JNO', false, 'true', 'false');
$lists['isInitLayout'] = JHTML::_('jsnselect.booleanlist', 'is_init_layout', 'class="inputbox"', $items->is_init_layout, 'JYES', 'JNO', false, 'true', 'false');
$imageSource = array(
	'0' => array('value' => 'thumbnail', 'text' => JText::_('THEME_MASONRY_IMAGE_SOURCE_THUMBNAIL')),
	'1' => array('value' => 'original_image', 'text' => JText::_('THEME_MASONRY_IMAGE_SOURCE_ORIGINAL_IMAGE'))
);
$lists['imageSource'] = JHTML::_('select.genericList', $imageSource, 'image_source', 'class="inputbox"', 'value', 'text', ($items->image_source == '') ? 'thumbnail' : $items->image_source);
$imageClickAction = array(
	'0' => array('value' => 'no-action', 'text' => JText::_('THEME_MASONRY_IMAGE_CLICK_ACTION_NO_ACTION')),
	'1' => array('value' => 'show-original-image', 'text' => JText::_('THEME_MASONRY_IMAGE_CLICK_ACTION_SHOW_ORIGINAL_IMAGE')),
	'2' => array('value' => 'open-image-link', 'text' => JText::_('THEME_MASONRY_IMAGE_CLICK_ACTION_OPEN_IMAGE_LINK'))
);

$lists['imageClickAction'] = JHTML::_('select.genericList', $imageClickAction, 'image_click_action', 'class="inputbox"', 'value', 'text', ($items->image_click_action == '') ? 'no-action' : $items->image_click_action);
$openLinkIn = array(
	'0' => array('value' => 'current_browser', 'text' => JText::_('THEME_MASONRY_IMAGE_OPEN_LINK_IN_CURRENT_BROWSER')),
	'1' => array('value' => 'new_browser', 'text' => JText::_('THEME_MASONRY_IMAGE_OPEN_LINK_IN_NEW_BROWSER'))
);
$lists['openLinkIn'] = JHTML::_('select.genericList', $openLinkIn, 'open_link_in', 'class="inputbox"', 'value', 'text', ($items->open_link_in == '')?'current_browser':$items->open_link_in);
$layout = array(
	'0' => array('value' => 'fixed', 'text' => JText::_('THEME_MASONRY_LAYOUT_FIXED')),
	'1' => array('value' => 'fluid', 'text' => JText::_('THEME_MASONRY_LAYOUT_FLUID'))
);
$lists['layoutType'] = JHTML::_('select.genericList', $layout, 'layout_type', 'class="inputbox"', 'value', 'text', ($items->layout_type == '')?'current_browser':$items->layout_type);
$pageType = array(
	'0' => array('value' => 'all', 'text' => JText::_('THEME_MASONRY_LAYOUT_PAGINATION_TYPE_ALL')),
	'1' => array('value' => 'infinite_scroll', 'text' => JText::_('THEME_MASONRY_LAYOUT_PAGINATION_TYPE_INFINITE_SCROLL')),
	'2' => array('value' => 'load_more', 'text' => JText::_('THEME_MASONRY_LAYOUT_PAGINATION_TYPE_LOAD_MORE'))
);
$lists['paginationType'] = JHTML::_('select.genericList', $pageType, 'pagination_type', 'class="inputbox input-medium"', 'value', 'text', ($items->pagination_type == '')? 'all' : $items->pagination_type);