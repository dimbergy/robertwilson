<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Theme Classic
 * @version $Id: themeclassic.php 6644 2011-06-08 09:22:38Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
JTable::addIncludePath(JPATH_ROOT.DS.'plugins'.DS.'jsnimageshow'.DS.'themeclassic'.DS.'themeclassic'.DS.'tables');
class ThemeClassic
{
	var $_pluginName 	= 'themeclassic';
	var $_pluginType 	= 'jsnimageshow';

	function &getInstance()
	{
		static $themeClassic;
		if ($themeClassic == null){
			$themeClassic = new ThemeClassic();
		}
		return $themeClassic;
	}

	function ThemeClassic()
	{
		$pathModelShowcaseTheme = JPATH_PLUGINS.DS.$this->_pluginType.DS.$this->_pluginName.DS.$this->_pluginName.DS.'models';
		$pathTableShowcaseTheme = JPATH_PLUGINS.DS.$this->_pluginType.DS.$this->_pluginName.DS.$this->_pluginName.DS.'tables';
		JModel::addIncludePath($pathModelShowcaseTheme);
		JTable::addIncludePath($pathTableShowcaseTheme);
	}

	function _prepareSaveData($data)
	{
		if(!empty($data))
		{
			$imgPanelBackgroundValue = $data['imgpanel_bg_value'];
			if(count($imgPanelBackgroundValue) == 2 && $imgPanelBackgroundValue[1] != ''){
				$data['imgpanel_bg_value'] = implode(',', $imgPanelBackgroundValue);
			}else{
				$data['imgpanel_bg_value'] = $imgPanelBackgroundValue[0];
			}

			return $data;
		}
		return false;
	}

	function _initData()
	{
		$cid				= JRequest::getVar('cid', array(0), '', 'array');
		$showcaseID			= (int) $cid[0];
		$showcaseTable 		= JTable::getInstance('showcase', 'Table');
		$showcaseThemeTable = JTable::getInstance($this->_pluginName, 'Table');

		if($showcaseTable->load($showcaseID))
		{
			if(!$showcaseThemeTable->load((int) $showcaseTable->theme_id))
			{
				$showcaseThemeTable =& JTable::getInstance($this->_pluginName, 'Table');// need to load default value when theme record has been deleted
				$showcaseThemeTable->load(0);
			}
		}

		return $showcaseThemeTable;
	}

	function _prepareDataJSON($showcaseID, $URL)
	{
		//$showcaseID 		= JRequest::getInt('showcase_id');
		$showcaseTable 		= JTable::getInstance('showcase', 'Table');
		$showcaseThemeTable = JTable::getInstance($this->_pluginName, 'Table');

		if($showcaseTable->load($showcaseID))
		{
			if (!$showcaseThemeTable->load((int) $showcaseTable->theme_id))
			{
				$showcaseThemeTable = JTable::getInstance($this->_pluginName, 'Table');// need to load default value when theme record has been deleted
				$showcaseThemeTable->load(0);
			}
		}

		$row =& $showcaseThemeTable;

		$showcaseObject = new stdClass();

		//image-panel
		$imagePanelObj 								= new stdClass();
		$imagePanelObj->{'default-presentation'}	= $row->imgpanel_presentation_mode;
		$imagePanelObj->{'background-type'} 		= $row->imgpanel_bg_type;
		$imagePanelObj->{'background-value'} 		= (strstr($row->imgpanel_bg_value, '#')== false and $row->imgpanel_bg_value!='') ? $URL.$row->imgpanel_bg_value : $row->imgpanel_bg_value;
		$imagePanelObj->{'show-watermark'} 			= $row->imgpanel_show_watermark;
		$imagePanelObj->{'watermark-path'} 			= ($row->imgpanel_watermark_path != null && $row->imgpanel_watermark_path != '') ? $URL.$row->imgpanel_watermark_path : '';
		$imagePanelObj->{'watermark-opacity'} 		= $row->imgpanel_watermark_opacity;
		$imagePanelObj->{'watermark-position'} 		= $row->imgpanel_watermark_position;
		$imagePanelObj->{'watermark-offset'} 		= $row->imgpanel_watermark_offset;
		$imagePanelObj->{'show-inner-shadow'} 		= $row->imgpanel_show_inner_shawdow;
		$imagePanelObj->{'inner-shadow-color'} 		= ($row->imgpanel_inner_shawdow_color != '') ? $row->imgpanel_inner_shawdow_color : '' ;
		$imagePanelObj->{'show-overlay'} 			= ($row->imgpanel_show_overlay_effect == 2) ? 'no' : $row->imgpanel_show_overlay_effect;
		$imagePanelObj->{'overlay-type'} 			= $row->imgpanel_overlay_effect_type;

			//fitin-settings object
				$fitinSettingObj = new stdClass();
				$fitinSettingObj->{'transition-type'} 	= $row->imgpanel_img_transition_type_fit;
				$fitinSettingObj->{'transition-timing'} = 2;
				$fitinSettingObj->{'click-action'} 		= $row->imgpanel_img_click_action_fit;
				$fitinSettingObj->{'open-link-in'} 		= $row->imgpanel_img_open_link_in_fit;

				$imagePanelObj->{'fitin-settings'} 		= $fitinSettingObj;
			//end fittin-settings object

			//expandout-settings object
				$expandOutSettingObj 						= new stdClass();
				$expandOutSettingObj->{'transition-type'} 	= $row->imgpanel_img_transition_type_expand;
				$expandOutSettingObj->{'transition-timing'} = 2;
				$expandOutSettingObj->{'motion-type'} 		= $row->imgpanel_img_motion_type_expand;
				$expandOutSettingObj->{'motion-timing'} 	= 3;
				$expandOutSettingObj->{'click-action'} 		= $row->imgpanel_img_click_action_expand;
				$expandOutSettingObj->{'open-link-in'} 		= $row->imgpanel_img_open_link_in_expand;

				$imagePanelObj->{'expandout-settings'} = $expandOutSettingObj;
			//end expandout-settings object

		$showcaseObject->{'image-panel'} = $imagePanelObj;
		//end image-panel

		//thumbnail panel
		$thumbnailPanelObj 									= new stdClass();
		$thumbnailPanelObj->{'show-panel'} 					= $row->thumbpanel_show_panel;
		$thumbnailPanelObj->{'panel-position'} 				= $row->thumbpanel_panel_position;
		$thumbnailPanelObj->{'collapsible-panel'} 			= $row->thumbpanel_collapsible_position;
		$thumbnailPanelObj->{'background-color'} 			= $row->thumbpanel_thumnail_panel_color;
		$thumbnailPanelObj->{'thumbnail-row'} 				= $row->thumbpanel_thumb_row;
		$thumbnailPanelObj->{'thumbnail-width'} 			= $row->thumbpanel_thumb_width;
		$thumbnailPanelObj->{'thumbnail-height'} 			= $row->thumbpanel_thumb_height;
		$thumbnailPanelObj->{'thumbnail-opacity'} 			= $row->thumbpanel_thumb_opacity;
		$thumbnailPanelObj->{'active-state-color'} 			= $row->thumbpanel_active_state_color;
		$thumbnailPanelObj->{'normal-state-color'} 			= $row->thumbpanel_thumnail_normal_state;
		$thumbnailPanelObj->{'thumbnails-browsing-mode'} 	= $row->thumbpanel_thumb_browsing_mode;
		$thumbnailPanelObj->{'thumbnails-presentation-mode'} = $row->thumbpanel_presentation_mode;
		$thumbnailPanelObj->{'thumbnail-border'} 			= $row->thumbpanel_border;
		$thumbnailPanelObj->{'show-thumbnails-status'} 		= $row->thumbpanel_show_thumb_status;
		$thumbnailPanelObj->{'enable-big-thumbnail'} 		= $row->thumbpanel_enable_big_thumb;
		$thumbnailPanelObj->{'big-thumbnail-size'} 			= $row->thumbpanel_big_thumb_size;
		$thumbnailPanelObj->{'big-thumbnail-color'} 		= $row->thumbpanel_big_thumb_color;
		$thumbnailPanelObj->{'big-thumbnail-border'} 		= $row->thumbpanel_thumb_border;

		$showcaseObject->{'thumbnail-panel'} 				= $thumbnailPanelObj;
		//end thumbnail panel

		//information-panel
		$informationPanelObj 							= new stdClass();
		$informationPanelObj->{'panel-presentation'} 	= $row->infopanel_presentation;
		$informationPanelObj->{'panel-position'} 		= $row->infopanel_panel_position;
		$informationPanelObj->{'background-color-fill'} = $row->infopanel_bg_color_fill;
		$informationPanelObj->{'show-title'} 			= $row->infopanel_show_title;
		$informationPanelObj->{'click-action'} 			= $row->infopanel_panel_click_action;
		$informationPanelObj->{'open-link-in'} 			= $row->infopanel_open_link_in;
		$informationPanelObj->{'title-css'} 			= ($row->infopanel_title_css!='')?$row->infopanel_title_css:'';
		$informationPanelObj->{'show-description'} 		= $row->infopanel_show_des;
		$informationPanelObj->{'description-length-limitation'} = $row->infopanel_des_lenght_limitation;
		$informationPanelObj->{'description-css'} 				= ($row->infopanel_des_css!='')?$row->infopanel_des_css:'';
		$informationPanelObj->{'show-link'}						= $row->infopanel_show_link;
		$informationPanelObj->{'link-css'} 						= ($row->infopanel_link_css!='')?$row->infopanel_link_css:'';

		$showcaseObject->{'information-panel'} = $informationPanelObj;
		//end information-panel

		//toobar-panel
		$toolbarPanelObj = new stdClass();
		$toolbarPanelObj->{'panel-position'} 		= $row->toolbarpanel_panel_position;
		$toolbarPanelObj->{'panel-presentation'} 	= $row->toolbarpanel_presentation;
		$toolbarPanelObj->{'show-image-navigation'} 	= $row->toolbarpanel_show_image_navigation;
		$toolbarPanelObj->{'show-slideshow-player'} 	= $row->toolbarpanel_slideshow_player;
		$toolbarPanelObj->{'show-fullscreen-switcher'} 	= $row->toolbarpanel_show_fullscreen_switcher;
		$toolbarPanelObj->{'show-tooltip'} 				= $row->toolbarpanel_show_tooltip;

		$showcaseObject->{'toolbar-panel'} 				= $toolbarPanelObj;
		// end toobar-panel

		//slideshow panel
		$slidePanelObj = new stdClass();
		$slidePanelObj->{'image-presentation'} 		= ($row->slideshow_enable_ken_burn_effect == 'yes') ? 'expand-out' : $row->imgpanel_presentation_mode;
		$slidePanelObj->{'show-thumbnail-panel'} 	= ($row->slideshow_hide_thumb_panel == 'yes') ? 'off' : $row->thumbpanel_show_panel;
		$slidePanelObj->{'show-image-navigation'} 	= ($row->slideshow_hide_image_navigation == 'yes') ? 'no' : $row->toolbarpanel_show_image_navigation;
		$slidePanelObj->{'show-watermark'} 			= $row->imgpanel_show_watermark;
		$slidePanelObj->{'show-status'} 			= $row->slideshow_show_status;
		$slidePanelObj->{'show-overlay'} 			= ($row->imgpanel_show_overlay_effect == 'during') ? 'yes' : $row->imgpanel_show_overlay_effect;
		$slidePanelObj->{'slide-timing'} 		= $row->slideshow_slide_timing;
		$slidePanelObj->{'auto-play'} 			= $row->slideshow_auto_play;
		$slidePanelObj->{'slideshow-looping'} 	= $row->slideshow_looping;
		$slidePanelObj->{'enable-kenburn'} 		= $row->slideshow_enable_ken_burn_effect;

		$showcaseObject->{'slideshow'} = $slidePanelObj;
		//end slideshow panel

		return $showcaseObject;
	}
}