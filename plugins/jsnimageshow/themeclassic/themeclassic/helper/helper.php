<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Theme Classic
 * @version $Id: helper.php 6642 2011-06-08 08:58:06Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
			$objJSNShowcaseTheme = JSNISFactory::getObj('classes.jsn_is_showcasetheme');
			$objJSNShowcaseTheme->importTableByThemeName($this->_showcaseThemeName);
			$objJSNShowcaseTheme->importModelByThemeName($this->_showcaseThemeName);
			$modelShowcaseTheme = JModel::getInstance($this->_showcaseThemeName);
			$items = $modelShowcaseTheme->_initData();

			JSNISFactory::importFile('classes.jsn_is_htmlselect');

			/**
			 * /////////////////////////////////////////////////////////Image Panel Begin////////////////////////////////////////////////////////////////////////////
			 */
			//Image Presentation Begin
				//Fit Begin
				$classImagePanel = 'imagePanel';
				$imgPanelPresentationMode = array(
					'0' => array('value' => 'fit-in',
					'text' => JText::_('FIT_IN')),
					'1' => array('value' => 'expand-out',
					'text' => JText::_('EXPAND_OUT'))
				);
				$lists['imgPanelPresentationMode'] = JHTML::_('select.genericList', $imgPanelPresentationMode, 'imgpanel_presentation_mode', 'class="inputbox '.$classImagePanel.'"', 'value', 'text', $items->imgpanel_presentation_mode );

				$imgPanelImgTransitionTypeFit = array(
					'0' => array('value' => 'random',
					'text' => JText::_('RANDOM')),
					'1' => array('value' => 'fade',
					'text' => JText::_('FADE')),
					'2' => array('value' => 'push',
					'text' => JText::_('PUSH')),
					'3' => array('value' => 'zoom',
					'text' => JText::_('ZOOM')),
					'4' => array('value' => 'flip3d',
					'text' => JText::_('3D_FLIP')),
					'5' => array('value' => 'page-curl',
					'text' => JText::_('PAGE_CURL')),
					'6' => array('value' => 'page-flip',
					'text' => JText::_('PAGE_FLIP'))
				);
				$lists['imgPanelImgTransitionTypeFit'] = JHTML::_('select.genericList', $imgPanelImgTransitionTypeFit, 'imgpanel_img_transition_type_fit', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', ($items->imgpanel_img_transition_type_fit!='')?$items->imgpanel_img_transition_type_fit:'random');

				$imgPanelImgClickActionFit = array(
					'0' => array('value' => 'no-action',
					'text' => JText::_('NO_ACTION')),
					'1' => array('value' => 'image-zooming',
					'text' => JText::_('IMAGE_ZOOMING')),
					'2' => array('value' => 'open-image-link',
					'text' => JText::_('OPEN_IMAGE_LINK'))
				);
				$lists['imgPanelImgClickActionFit'] = JHTML::_('select.genericList', $imgPanelImgClickActionFit, 'imgpanel_img_click_action_fit', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', ($items->imgpanel_img_click_action_fit!='')?$items->imgpanel_img_click_action_fit:'image-zooming');
				//Fit End

				//Expand Begin
				$imgPanelImgTransitionTypeExpand = array(
					'0' => array('value' => 'random',
					'text' => JText::_('RANDOM')),
					'1' => array('value' => 'cross-fade',
					'text' => JText::_('CROSS_FADE')),
					'2' => array('value' => 'linear-fade',
					'text' => JText::_('LINEAR_FADE')),
					'3' => array('value' => 'radial-fade',
					'text' => JText::_('RADIAL_FADE')),
					'4' => array('value' => 'black-dim',
					'text' => JText::_('BLACK_DIM')),
					'5' => array('value' => 'white-burn',
					'text' => JText::_('WHITE_BURN'))
				);
				$lists['imgPanelImgTransitionTypeExpand'] = JHTML::_('select.genericList', $imgPanelImgTransitionTypeExpand, 'imgpanel_img_transition_type_expand', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', ($items->imgpanel_img_transition_type_expand!='')?$items->imgpanel_img_transition_type_expand:'random' );
				$imgPanelImgMotionTypeExpand = array(
					'0' => array('value' => 'no-motion',
					'text' => JText::_('NO_MOTION')),
					'1' => array('value' => 'center-zoom-in',
					'text' => JText::_('ZOOM_IN')),
					'2' => array('value' => 'center-zoom-out',
					'text' => JText::_('ZOOM_OUT')),
					'3' => array('value' => 'center-random',
					'text' => JText::_('RANDOM'))
				);
				$lists['imgPanelImgMotionTypeExpand'] = JHTML::_('select.genericList', $imgPanelImgMotionTypeExpand, 'imgpanel_img_motion_type_expand', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', ($items->imgpanel_img_motion_type_expand!='')?$items->imgpanel_img_motion_type_expand:'center-random' );
				$imgPanelImgClickActionExpand = array(
					'0' => array('value' => 'no-action',
					'text' => JText::_('NO_ACTION')),
					'1' => array('value' => 'image-zooming',
					'text' => JText::_('IMAGE_ZOOMING')),
					'2' => array('value' => 'open-image-link',
					'text' => JText::_('OPEN_IMAGE_LINK'))
				);
				$lists['imgPanelImgClickActionExpand'] = JHTML::_('select.genericList', $imgPanelImgClickActionExpand, 'imgpanel_img_click_action_expand', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', ($items->imgpanel_img_click_action_expand!='')?$items->imgpanel_img_click_action_expand:'open-image-link' );
				//Expand End

				$openLinkIn = array(
					'0' => array('value' => 'current-browser',
					'text' => JText::_('CURRENT_BROWSER')),
					'1' => array('value' => 'new-browser',
					'text' => JText::_('NEW_BROWSER'))
				);
				$lists['imgPanelImgOpenLinkInExpand'] = JHTML::_('select.genericList', $openLinkIn, 'imgpanel_img_open_link_in_expand', 'class="inputbox" ', 'value', 'text', ($items->imgpanel_img_open_link_in_expand != '') ? $items->imgpanel_img_open_link_in_expand : 'current-browser' );
				$lists['imgPanelImgOpenLinkInFit'] = JHTML::_('select.genericList', $openLinkIn, 'imgpanel_img_open_link_in_fit', 'class="inputbox" ', 'value', 'text', ($items->imgpanel_img_open_link_in_fit != '') ? $items->imgpanel_img_open_link_in_fit : 'current-browser' );

			//Image Presentation End

			//Background Begin
			$imgPanelBgType = array(
				'0' => array('value' => 'solid-color',
				'text' => JText::_('SOLID_COLOR')),
				'1' => array('value' => 'linear-gradient',
				'text' => JText::_('LINEAR_GRADIENT')),
				'2' => array('value' => 'radial-gradient',
				'text' => JText::_('RADIAL_GRADIENT')),
				'3' => array('value' => 'pattern',
				'text' => JText::_('PATTERN')),
				'4' => array('value' => 'image',
				'text' => JText::_('IMAGE'))
			);
			$lists['imgPanelBgType'] = JHTML::_('select.genericList', $imgPanelBgType, 'imgpanel_bg_type', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', ($items->imgpanel_bg_type != '') ? $items->imgpanel_bg_type : 'linear-gradient'  );
			//Background End

			//Watermark Presentation Begin
			$lists['imgPanelShowWatermark'] = JSNISHTMLSelect::booleanlist('imgpanel_show_watermark','class="inputbox '.$classImagePanel.'"', $items->imgpanel_show_watermark);
			$imgPanelWatermarkPosition = array(
				'0' => array('value' => 'center',
				'text' => JText::_('CENTER')),
				'1' => array('value' => 'top-left',
				'text' => JText::_('TOP_LEFT')),
				'2' => array('value' => 'top-right',
				'text' => JText::_('TOP_RIGHT')),
				'3' => array('value' => 'bottom-left',
				'text' => JText::_('BOTTOM_LEFT')),
				'4' => array('value' => 'bottom-right',
				'text' => JText::_('BOTTOM_RIGHT'))
			);
			$lists['imgPanelWatermarkPosition'] = JHTML::_('select.genericList', $imgPanelWatermarkPosition, 'imgpanel_watermark_position', 'class="inputbox '.$classImagePanel.'" onChange="JSNISClassicTheme.ChangeWatermark();"'. '', 'value', 'text', ($items->imgpanel_watermark_position!='')?$items->imgpanel_watermark_position:'top-right' );
			//Watermark Presentation End

			//Overlay Effect Begin
			$imgPanelOverlayEffectType = array(
				'0' => array('value' => 'horizontal-floating-bar',
				'text' => JText::_('HORIZONTAL_FLOATING_BAR')),
				'1' => array('value' => 'vertical-floating-bar',
				'text' => JText::_('VERTICAL_FLOATING_BAR')),
				'2' => array('value' => 'winter-snow',
				'text' => JText::_('WINTER_SNOW')),
				'3' => array('value' => 'old-movie',
				'text' => JText::_('OLD_MOVIE')),
				'4' => array('value' => 'water-bubbles',
				'text' => JText::_('WATER_BUBBLES')),
				'5' => array('value' => 'sparkle',
				'text' => JText::_('SPARKLE'))
			);

			$lists['imgPanelOverlayEffectType'] = JHTML::_('select.genericList', $imgPanelOverlayEffectType, 'imgpanel_overlay_effect_type', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', $items->imgpanel_overlay_effect_type );

			$imgPanelShowOverlayEffect = array(
				'1' => array('value' => 'during',
				'text' => JText::_('OVERLAY_ON_DURING_SLIDESHOW')),
				'0' => array('value' => 'yes',
				'text' => JText::_('OVERLAY_ALWAYS_ON')),
				'2' => array('value' => 'no',
				'text' => JText::_('OVERLAY_OFF'))
			);

			$lists['imgPanelShowOverlayEffect'] = JHTML::_('select.genericList', $imgPanelShowOverlayEffect, 'imgpanel_show_overlay_effect', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', $items->imgpanel_show_overlay_effect );

			//Overlay Effect End

			//Inner Shawdow Begin
			$lists['imgPanelShowInnerShawdow'] = JSNISHTMLSelect::booleanlist('imgpanel_show_inner_shawdow','class="inputbox '.$classImagePanel.'"', $items->imgpanel_show_inner_shawdow);
			//Inner Shawdow End

			/**
			 * /////////////////////////////////////////////////////////Image Panel End////////////////////////////////////////////////////////////////////////////
			 */

			/**
			 * /////////////////////////////////////////////////////////////////////////////////Thumbnail Panel Begin////////////////////////////////////////////////////////////
			 */
				//General Begin
				$classThumbPanel = 'thumbnailPanel';
				$thumbPanelStatus = array(
					'0' => array('value' => 'auto',
					'text' => JText::_('AUTO_SHOW/HIDE')),
					'1' => array('value' => 'on',
					'text' => JText::_('ALWAYS_ON')),
					'2' => array('value' => 'off',
					'text' => JText::_('GLOBAL_OFF'))
				);

				$lists['thumbPanelShowPanel'] = JHTML::_('select.genericList', $thumbPanelStatus, 'thumbpanel_show_panel', 'class="inputbox '.$classThumbPanel.'" '. '', 'value', 'text', $items->thumbpanel_show_panel);
			 	$thumbPanelPanelPosition = array(
					'0' => array('value' => 'top',
					'text' => JText::_('TOP')),
					'1' => array('value' => 'bottom',
					'text' => JText::_('BOTTOM'))
				);
				$lists['thumbPanelPanelPosition'] 		= JHTML::_('select.genericList', $thumbPanelPanelPosition, 'thumbpanel_panel_position', 'class="inputbox '.$classThumbPanel.'" '. '', 'value', 'text', (!empty($items->thumbpanel_panel_position))?$items->thumbpanel_panel_position:'bottom' );
				$lists['thumbPanelCollapsiblePosition'] = JSNISHTMLSelect::booleanlist('thumbpanel_collapsible_position','class="inputbox '.$classThumbPanel.'"', $items->thumbpanel_collapsible_position);
				$thumbPanelThumbBrowsingMode = array(
					'0' => array('value' => 'pagination',
					'text' => JText::_('PAGINATION')),
					'1' => array('value' => 'sliding',
					'text' => JText::_('SLIDING'))
				);
				$lists['thumbPanelThumbBrowsingMode']   = JHTML::_('select.genericList', $thumbPanelThumbBrowsingMode, 'thumbpanel_thumb_browsing_mode', 'class="inputbox '.$classThumbPanel.'" onchange="JSNISClassicTheme.ShowcaseSwitchBrowsingMode();"'. '', 'value', 'text', $items->thumbpanel_thumb_browsing_mode );
				$lists['thumbPanelShowThumbStatus'] 	= JSNISHTMLSelect::booleanlist('thumbpanel_show_thumb_status','class="inputbox '.$classThumbPanel.'"', $items->thumbpanel_show_thumb_status);
				//General End

				//Thumbnail Begin
				$thumbPanelPresentationMode = array(
					'0' => array('value' => 'image',
					'text' => JText::_('IMAGE')),
					'1' => array('value' => 'number',
					'text' => JText::_('NUMBER'))
				);
				$lists['thumbPanelPresentationMode'] = JHTML::_('select.genericList', $thumbPanelPresentationMode, 'thumbpanel_presentation_mode', 'class="inputbox '.$classThumbPanel.'" '. '', 'value', 'text', $items->thumbpanel_presentation_mode );
				$lists['thumbPanelEnableBigThumb']   = JSNISHTMLSelect::booleanlist('thumbpanel_enable_big_thumb','class="inputbox '.$classThumbPanel.'"', $items->thumbpanel_enable_big_thumb);

				//Thumbnail End
			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////Thumbnail Panel End//////////////////////////////////////////////////////////////////////////////////
			 */
			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////Information Panel Begin//////////////////////////////////////////////////////////////////////////////////
			 */
				$classInfoPanel = 'informationPanel';
				//General Begin
				$infoPanelPanelPosition = array(
					'0' => array('value' => 'top',
					'text' => JText::_('TOP')),
					'1' => array('value' => 'bottom',
					'text' => JText::_('BOTTOM'))
				);
				$lists['infoPanelPanelPosition'] = JHTML::_('select.genericList', $infoPanelPanelPosition, 'infopanel_panel_position', 'class="inputbox '.$classInfoPanel.'" '. '', 'value', 'text', $items->infopanel_panel_position );

				$infoPanelPresentation = array(
					'0' => array('value' => 'auto',
					'text' => JText::_('AUTO_SHOW/HIDE')),
					'1' => array('value' => 'on',
					'text' => JText::_('ALWAYS_ON')),
					'2' => array('value' => 'off',
					'text' => JText::_('GLOBAL_OFF'))
				);
				$lists['infoPanelPresentation'] = JHTML::_('select.genericList', $infoPanelPresentation, 'infopanel_presentation', 'class="inputbox '.$classInfoPanel.'" '. '', 'value', 'text', $items->infopanel_presentation );
				//General End

				//Image Title Begin

			 	$lists['infoPanelShowTitle'] = JSNISHTMLSelect::booleanlist('infopanel_show_title','class="inputbox '.$classInfoPanel.'"', $items->infopanel_show_title);

				$infoPanelPanelClickAction = array(
					'0' => array('value' => 'no-action',
					'text' => JText::_('NO_ACTION')),
					'1' => array('value' => 'open-image-link',
					'text' => JText::_('OPEN_IMAGE_LINK'))
				);
				$lists['infoPanelPanelClickAction'] = JHTML::_('select.genericList', $infoPanelPanelClickAction, 'infopanel_panel_click_action', 'class="inputbox '.$classInfoPanel.'" '. '', 'value', 'text', $items->infopanel_panel_click_action );
				//Image Title End

				//Image Description Begin
				$lists['infoPanelShowDes'] = JSNISHTMLSelect::booleanlist('infopanel_show_des','class="inputbox '.$classInfoPanel.'"', $items->infopanel_show_des);
				//Image Description End

				//Link Begin
					$lists['infoPanelShowLink'] = JSNISHTMLSelect::booleanlist('infopanel_show_link','class="inputbox '.$classInfoPanel.'"', $items->infopanel_show_link);
				//Link End

				//Open link in begin
					$lists['infoPanelOpenLinkIn'] = JHTML::_('select.genericList', $openLinkIn, 'infopanel_open_link_in', 'class="inputbox" ', 'value', 'text', ($items->infopanel_open_link_in != '') ? $items->infopanel_open_link_in : 'current-browser' );
				//Open link in end
			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////Information Panel End//////////////////////////////////////////////////////////////////////////////////
			 */

			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////Toolbar Panel Begin//////////////////////////////////////////////////////////////////////////////////
			 */
				$classToolBarPanel = 'toolbarPanel';
				//General Begin
				$toolBarPanelPanelPosition = array(
					'0' => array('value' => 'top',
					'text' => JText::_('TOP')),
					'1' => array('value' => 'bottom',
					'text' => JText::_('BOTTOM'))
				);
				$lists['toolBarPanelPanelPosition'] = JHTML::_('select.genericList', $toolBarPanelPanelPosition, 'toolbarpanel_panel_position', 'class="inputbox '.$classToolBarPanel.'" '. '', 'value', 'text', ($items->toolbarpanel_panel_position!='')?$items->toolbarpanel_panel_position:'bottom' );

				$toolBarPanelPresentation = array(
					'0' => array('value' => 'auto',
					'text' => JText::_('AUTO_SHOW/HIDE')),
					'1' => array('value' => 'on',
					'text' => JText::_('ALWAYS_ON')),
					'2' => array('value' => 'off',
					'text' => JText::_('GLOBAL_OFF'))
				);
				$lists['toolBarPanelPresentation'] = JHTML::_('select.genericList', $toolBarPanelPresentation, 'toolbarpanel_presentation', 'class="inputbox '.$classToolBarPanel.'" '. '', 'value', 'text', ($items->toolbarpanel_presentation!=''?$items->toolbarpanel_presentation:'auto') );
				//General End

				//Functions Begin
			 	$lists['toolBarPanelShowImageNavigation'] 		= JSNISHTMLSelect::booleanlist('toolbarpanel_show_image_navigation','class="inputbox '.$classToolBarPanel.'"', $items->toolbarpanel_show_image_navigation);
				$lists['toolBarPanelSlideShowPlayer'] 			= JSNISHTMLSelect::booleanlist('toolbarpanel_slideshow_player','class="inputbox '.$classToolBarPanel.'"', $items->toolbarpanel_slideshow_player);
				$lists['toolBarPanelShowFullscreenSwitcher'] 	= JSNISHTMLSelect::booleanlist('toolbarpanel_show_fullscreen_switcher','class="inputbox '.$classToolBarPanel.'"', $items->toolbarpanel_show_fullscreen_switcher);
				$lists['toolBarPanelShowTooltip'] 				= JSNISHTMLSelect::booleanlist('toolbarpanel_show_tooltip','class="inputbox '.$classToolBarPanel.'"', $items->toolbarpanel_show_tooltip);
				//Functions End
			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////Toolbar Panel End//////////////////////////////////////////////////////////////////////////////////
			 */

			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////SlideShow Begin//////////////////////////////////////////////////////////////////////////////////
			 */
				$classSlideShowPanel = 'slideshowPanel';
				//Action on Slideshow Start Begin

				$lists['slideShowEnableKenBurnEffect'] = JSNISHTMLSelect::booleanlist('slideshow_enable_ken_burn_effect','class="inputbox '.$classSlideShowPanel.'"', $items->slideshow_enable_ken_burn_effect);
				$lists['slideShowHideThumbPanel']      = JSNISHTMLSelect::booleanlist('slideshow_hide_thumb_panel','class="inputbox '.$classSlideShowPanel.'"', $items->slideshow_hide_thumb_panel);
				$lists['slideShowHideImageNavigation'] = JSNISHTMLSelect::booleanlist('slideshow_hide_image_navigation','class="inputbox '.$classSlideShowPanel.'"', $items->slideshow_hide_image_navigation);

				//Action on Slideshow Start End

				//Slideshow Process Begin

				$lists['slideShowProcess'] 		= JSNISHTMLSelect::booleanlist('slideshow_auto_play','class="inputbox '.$classSlideShowPanel.'"', $items->slideshow_auto_play);
				$lists['slideShowShowStatus'] 	= JSNISHTMLSelect::booleanlist('slideshow_show_status','class="inputbox '.$classSlideShowPanel.'"', $items->slideshow_show_status);
				$lists['slideShowLooping'] 		= JSNISHTMLSelect::booleanlist('slideshow_looping','class="inputbox '.$classSlideShowPanel.'"', $items->slideshow_looping);
				//Slideshow Process End

			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////SlideShow End//////////////////////////////////////////////////////////////////////////////////
			 */

			$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');
			$lists['showlist'] 	= $objJSNShowlist->renderShowlistComboBox(null, 'Select showlist to see live view with', 'showlist_id', 'onchange="JSNISClassicTheme.EnableShowCasePreview();"');
