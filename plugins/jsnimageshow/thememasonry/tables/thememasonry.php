<?php
/**
 * @version    thememasonry.php$
 * @package    JSNIMAGESHOW
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2015 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

defined('_JEXEC') or die('Restricted access');
class TableThemeMasonry extends JTable
{
	var $theme_id                               = null;
	var $image_border			                = '2';
	var $image_rounded_corner	                = '5';
	var $image_border_color		                = '#333';
	var $image_click_action		                = 'show-original-image';
	var $image_source			                = 'thumbnail';
	var $open_link_in							= 'current_browser';
	var $show_caption                           = 'yes';
	var $caption_background_color               = '#000000';
	var $caption_opacity                        = '75';
	var $caption_show_title                     = 'yes';
	var $caption_title_css						= "padding: 5px;\nfont-family: Verdana;\nfont-size: 12px;\nfont-weight: bold;\ntext-align: left;\ncolor: #E9E9E9;";
	var $caption_show_description               = 'yes';
	var $caption_description_length_limitation  = '50';
	var $caption_description_css				= "padding: 5px;\nfont-family: Arial;\nfont-size: 11px;\nfont-weight: normal;\ntext-align: left;\ncolor: #AFAFAF;";
	var $layout_type                            = 'fixed';
	var $column_width                           = '180';
	var $gutter                                 = '5';
	var $is_fit_width                           = 'true';
	var $transition_duration                    = '0.4';
	var $feature_image                          = '';
	var $pagination_type                        = 'all';
	var $number_load_image                      = '6';

	function __construct(& $db)
	{
		parent::__construct('#__imageshow_theme_masonry', 'theme_id', $db);
	}
}
?>