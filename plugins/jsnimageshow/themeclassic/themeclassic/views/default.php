<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Theme Classic
 * @version $Id: default.php 6642 2011-06-08 08:58:06Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.html.pane');
$myTabs 		= JPane::getInstance('tabs',array('startOffset'=>0));
$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
$url 			= $objJSNUtils->overrideURL();
$user 			= JFactory::getUser();
?>
<script language="javascript" type="text/javascript">
	window.addEvent('domready', function()
	{
		JSNISClassicTheme.ShowcaseChangeBg();
		JSNISClassicTheme.visualFlash();
		JSNISClassicTheme.saveTabStatusCookie('jsnis-showcase-accordion-status-<?php echo $user->id.'-'.$items->theme_id;?>');

		var accImage 	= new JSNISAccordions('jsn-image-panel', {event: function(agr){JSNISClassicTheme.saveAccordionStatusCookie(agr, 'jsnis-showcase-accordion-status-<?php echo $user->id.'-'.$items->theme_id;?>');}, multiple: true, activeClass: 'down', showFirstElement: true, durationEffect: 300});
		var accThumb 	= new JSNISAccordions('jsn-thumb-panel', {event: function(agr){JSNISClassicTheme.saveAccordionStatusCookie(agr, 'jsnis-showcase-accordion-status-<?php echo $user->id.'-'.$items->theme_id;?>');}, multiple: true, activeClass: 'down', showFirstElement: true, durationEffect: 300});
		var accInfo 	= new JSNISAccordions('jsn-info-panel', {event: function(agr){JSNISClassicTheme.saveAccordionStatusCookie(agr, 'jsnis-showcase-accordion-status-<?php echo $user->id.'-'.$items->theme_id;?>');}, multiple: true, activeClass: 'down', showFirstElement: true, durationEffect: 300});
		var accToolbar 	= new JSNISAccordions('jsn-toolbar-panel', {event: function(agr){JSNISClassicTheme.saveAccordionStatusCookie(agr, 'jsnis-showcase-accordion-status-<?php echo $user->id.'-'.$items->theme_id;?>');}, multiple: true, activeClass: 'down', showFirstElement: true, durationEffect: 300});
		var accSlide 	= new JSNISAccordions('jsn-slideshow-panel', {event: function(agr){JSNISClassicTheme.saveAccordionStatusCookie(agr, 'jsnis-showcase-accordion-status-<?php echo $user->id.'-'.$items->theme_id;?>');}, multiple: true, activeClass: 'down', showFirstElement: true, durationEffect: 300});

		JSNISClassicTheme.openLinkIn('imgpanel_img_click_action_fit', 'jsn-img-open-link-in-fit');
		JSNISClassicTheme.openLinkIn('imgpanel_img_click_action_expand', 'jsn-img-open-link-in-expand');
		JSNISClassicTheme.openLinkIn('infopanel_panel_click_action' , 'jsn-info-open-link-in');

		JSNISClassicTheme.loadAccordionSettingCookie('jsnis-showcase-accordion-status-<?php echo $user->id.'-'.$items->theme_id;?>');

		$('toolbar-apply').addEvent('click', function()
		{
			var value = JSNISClassicTheme.accordionTabsSettings;

			if (value)
			{
				JSNISUtils.setCookie('jsnis-showcase-accordion-status-<?php echo $user->id.'-'.$items->theme_id;?>', JSON.encode(value), 0, 10);
			}

		});
	});
</script>

<!--  important -->

<input type="hidden" name="theme_name" value="<?php echo strtolower($this->_showcaseThemeName); ?>"/>
<input type="hidden" name="theme_id" value="<?php echo (int) $items->theme_id; ?>" />
<!--  important -->

<div class="jsnis-dgrey-heading jsnis-dgrey-heading-style">
	<h3 class="jsnis-element-heading"><?php echo JText::_('TITLE_SHOWCASE_THEME_SETTINGS'); ?></h3>
</div>
<table width="100%" class="jsnis-showcase-theme-settings" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign="top"><div class="jsnis-showcase-theme-detail"> <?php echo $myTabs->startPane('jsn-showcase-tabs'); ?>
				<?php
					echo $myTabs->endPanel();
					echo $myTabs->startPanel(JText::_('IMAGE_PANEL'),'image-panel jsnis-theme-panel');
				?>
				<div id="jsn-image-panel" class="jsn-accordion">
					<div class="jsn-accordion-control"> <span><?php echo JText::_('EXPAND_ALL');?></span>&nbsp;&nbsp;|&nbsp; <span><?php echo JText::_('COLLAPSE_ALL');?></span> </div>
					<div class="jsn-accordion-title" id="image-panel-image-presentation">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('IMAGE_PRESENTATION'); ?> </div>
					<div class="jsn-accordion-pane" id="acc-image-presentation">
						<table class="admintable" width="100%" >
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_DEFAULT_PRESENTATION_MOD');?>::<?php echo JText::_('DES_DEFAULT_PRESENTATION_MOD'); ?>"><?php echo JText::_('TITLE_DEFAULT_PRESENTATION_MOD'); ?></span></td>
									<td><?php echo $lists['imgPanelPresentationMode']; ?></td>
								</tr>
								<tr>
									<td class="key" valign="top"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_PRESENTATION_MODE_CONFIGURATION');?>::<?php echo JText::_('DES_PRESENTATION_MODE_CONFIGURATION'); ?>"><?php echo JText::_('TITLE_PRESENTATION_MODE_CONFIGURATION'); ?></span></td>
									<td>
										<table class="admintable" width="100%">
											<tbody>
												<tr>
													<td colspan="2" class="key jsnis-theme-fit-panel"><?php echo JText::_('FIT_IN');?></td>
												</tr>
												<tr>
													<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_IMAGE_TRANSITION_TYPE');?>::<?php echo JText::_('DES_IMAGE_TRANSITION_TYPE'); ?>"><?php echo JText::_('TITLE_IMAGE_TRANSITION_TYPE'); ?></span></td>
													<td><?php echo $lists['imgPanelImgTransitionTypeFit']; ?></td>
												</tr>
												<tr>
													<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_IMAGE_CLICK_ACTION');?>::<?php echo JText::_('DES_IMAGE_CLICK_ACTION'); ?>"><?php echo JText::_('TITLE_IMAGE_CLICK_ACTION'); ?></span></td>
													<td><?php echo $lists['imgPanelImgClickActionFit']; ?></td>
												</tr>
												<tr id="jsn-img-open-link-in-fit">
													<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_IMAGE_OPEN_LINK_IN');?>::<?php echo JText::_('DES_IMAGE_OPEN_LINK_IN'); ?>"><?php echo JText::_('TITLE_IMAGE_OPEN_LINK_IN'); ?></span></td>
													<td><?php echo $lists['imgPanelImgOpenLinkInFit']; ?></td>
												</tr>
												<tr>
													<td colspan="2" class="key jsnis-theme-expand-panel"><?php echo JText::_('EXPAND_OUT');?></td>
												</tr>
												<tr>
													<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_IMAGE_TRANSITION_TYPE_EXPAND');?>::<?php echo JText::_('DES_IMAGE_TRANSITION_TYPE_EXPAND'); ?>"><?php echo JText::_('TITLE_IMAGE_TRANSITION_TYPE_EXPAND'); ?></span></td>
													<td><?php echo $lists['imgPanelImgTransitionTypeExpand']; ?></td>
												</tr>
												<tr>
													<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_IMAGE_MOTION_TYPE');?>::<?php echo JText::_('DES_IMAGE_MOTION_TYPE'); ?>"><?php echo JText::_('TITLE_IMAGE_MOTION_TYPE'); ?></span></td>
													<td><?php echo $lists['imgPanelImgMotionTypeExpand']; ?></td>
												</tr>
												<tr>
													<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_IMAGE_CLICK_ACTION');?>::<?php echo JText::_('DES_IMAGE_CLICK_ACTION'); ?>"><?php echo JText::_('TITLE_IMAGE_CLICK_ACTION'); ?></span></td>
													<td><?php echo $lists['imgPanelImgClickActionExpand']; ?></td>
												</tr>
												<tr id="jsn-img-open-link-in-expand">
													<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_IMAGE_OPEN_LINK_IN');?>::<?php echo JText::_('DES_IMAGE_OPEN_LINK_IN'); ?>"><?php echo JText::_('TITLE_IMAGE_OPEN_LINK_IN'); ?></span></td>
													<td><?php echo $lists['imgPanelImgOpenLinkInExpand']; ?></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="image-panel-background">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('BACKGROUND'); ?> </div>
					<div id="acc-background" class="jsn-accordion-pane">
						<table class="admintable" border="0" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_BACKGROUND_TYPE');?>::<?php echo JText::_('DES_BACKGROUND_TYPE'); ?>"><?php echo JText::_('TITLE_BACKGROUND_TYPE'); ?></span></td>
									<td><?php echo $lists['imgPanelBgType']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_BACKGROUND_VALUE');?>::<?php echo JText::_('DES_BACKGROUND_VALUE'); ?>"><?php echo JText::_('TITLE_BACKGROUND_VALUE'); ?></span></td>
									<td class="showcase-input-field"><?php $imgpanel_bg_value = explode(',', $items->imgpanel_bg_value); ?>
										<div id="imgpanel-bg-input">
											<input class="<?php echo $classImagePanel; ?>" type="text" style="<?php echo ($items->imgpanel_bg_type == 'linear-gradient' || $items->imgpanel_bg_type == 'radial-gradient' || $items->imgpanel_bg_type == 'solid-color')?'width: 78px;':'width: 100%;'; ?>;" value="<?php echo @$imgpanel_bg_value[0]; ?>" name="imgpanel_bg_value[]" id="imgpanel_bg_value_first" onchange="JSNISClassicTheme.changeValueFlash('imagePanel', this);" readonly="readonly"/>
											<input class="<?php echo $classImagePanel; ?>" type="text" value="<?php echo @$imgpanel_bg_value[1]; ?>" name="imgpanel_bg_value[]" id="imgpanel_bg_value_last" readonly="readonly" style='<?php echo ($items->imgpanel_bg_type == 'linear-gradient' or $items->imgpanel_bg_type == 'radial-gradient')?'display:"";width:78px; ':'display: none'; ?>;' onchange="JSNISClassicTheme.changeValueFlash('imagePanel', this);"/>
											<div id="wrap-color" style="<?php echo ($items->imgpanel_bg_type == 'linear-gradient' || $items->imgpanel_bg_type == 'radial-gradient' || $items->imgpanel_bg_type == 'solid-color')?'display:"";':'display:none'; ?>">
												<p id="solid_value" style="<?php echo ($items->imgpanel_bg_type == 'solid-color')?'display:"";':'display: none'; ?>;"> <a href="" id="solid_link"><span id="span_solidpanel_bg_value_first" class="jsnis-icon-view-color" style='<?php echo 'background-color:'.$imgpanel_bg_value[0];?>;'></span><span class="color-selection"><?php echo JText::_('SELECT_COLOR')?></span></a> </p>
												<p id="gradient_value" style="<?php echo ($items->imgpanel_bg_type == 'linear-gradient' || $items->imgpanel_bg_type == 'radial-gradient')?'display:"";':'display: none'; ?>;"> <a href="" id="gradient_link_1"><span id="span_imgpanel_bg_value_first" class="jsnis-icon-view-color" style='<?php echo 'background-color:'.@$imgpanel_bg_value[0];?>;<?php echo ($items->imgpanel_bg_type == 'linear-gradient' || $items->imgpanel_bg_type == 'radial-gradient')?'display:""':'display: none'; ?>;'></span><span class="color-selection"><?php echo JText::_('SELECT_COLOR')?></span></a> <a href="" id="gradient_link_2"><span id="span_imgpanel_bg_value_last" class="jsnis-icon-view-color" style='<?php echo 'background-color:'.@$imgpanel_bg_value[1]; ?>;<?php echo ($items->imgpanel_bg_type == 'linear-gradient' or $items->imgpanel_bg_type == 'radial-gradient')?'display:""':'display: none'; ?>;'></span><span class="color-selection"><?php echo JText::_('SELECT_COLOR')?></span></a> </p>
											</div>
										</div></td>
									<td nowrap="nowrap" width="5"><p id="pattern_title" style="<?php echo ($items->imgpanel_bg_type == 'pattern')?'display:"";':'display: none'; ?>;"><?php echo JText::_('SELECT_PATTERN')?>:&nbsp; <span id="pattern_value" style="<?php echo ($items->imgpanel_bg_type == 'pattern')?'display:"";':'display: none'; ?>;"> <a class="modal" rel="{handler: 'iframe', size: {x: 590, y: 320}}" href="index.php?option=com_imageshow&controller=media&tmpl=component&act=pattern&e_name=text&event=loadMedia&theme=<?php echo $this->_showcaseThemeName; ?>"><?php echo JText::_('PREDEFINED')?></a>&nbsp;&nbsp;-&nbsp; <a class="modal" rel="{handler: 'iframe', size: {x: 590, y: 410}}" href="index.php?option=com_imageshow&controller=media&tmpl=component&act=custom&e_name=text&event=loadMedia&theme=<?php echo $this->_showcaseThemeName; ?>"><?php echo JText::_('CUSTOM')?></a> </span> </p>
										<p id="image_title" style="<?php echo ($items->imgpanel_bg_type == 'image')?'display:"";':'display: none'; ?>;"> <span id="background_value" style="<?php echo ($items->imgpanel_bg_type == 'image')?'display:"";':'display: none'; ?>;"> <a class="modal" rel="{handler: 'iframe', size: {x: 590, y: 410}}" href="index.php?option=com_imageshow&controller=media&tmpl=component&act=custom&e_name=text&event=loadMedia&theme=<?php echo $this->_showcaseThemeName; ?>"> <?php echo JText::_('SELECT_IMAGE')?> </a> </span> </p></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="image-panel-inner-shadow">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('INNER_SHADOW'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SHOW_INNER_SHADOW');?>::<?php echo JText::_('DES_SHOW_INNER_SHADOW'); ?>"><?php echo JText::_('TITLE_SHOW_INNER_SHADOW');?></span></td>
									<td><?php echo $lists['imgPanelShowInnerShawdow']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_INNER_SHADOW_COLOR');?>::<?php echo JText::_('DES_INNER_SHADOW_COLOR'); ?>"><?php echo JText::_('TITLE_INNER_SHADOW_COLOR');?></span></td>
									<td class="showcase-input-field"><input class="<?php echo $classImagePanel; ?>" type="text" size="15" value="<?php echo (!empty($items->imgpanel_inner_shawdow_color))?$items->imgpanel_inner_shawdow_color:'#000000'; ?>" readonly="readonly" name="imgpanel_inner_shawdow_color" id="imgpanel_inner_shawdow_color" onchange="JSNISClassicTheme.changeValueFlash('imagePanel', this);"/>
										<a href="" id="imgpanel_inner_shawdow_color_link"> <span id="span_imgpanel_inner_shawdow_color" class="jsnis-icon-view-color" style="<?php echo ($items->imgpanel_inner_shawdow_color!='')?'background:'.$items->imgpanel_inner_shawdow_color.';':'background:#000000;'; ?>"></span> <span class="color-selection"><?php echo JText::_('SELECT_COLOR')?></span> </a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="image-panel-watermark">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('WATERMARK'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" border="0" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SHOW_WATERMARK');?>::<?php echo JText::_('DES_SHOW_WATERMARK'); ?>"><?php echo JText::_('TITLE_SHOW_WATERMARK'); ?></span></td>
									<td><?php echo $lists['imgPanelShowWatermark']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_WATERMARK_PATH');?>::<?php echo JText::_('DES_WATERMARK_PATH'); ?>"><?php echo JText::_('TITLE_WATERMARK_PATH'); ?></span></td>
									<td><div id="images-graphic-watermark">
											<input class="<?php echo $classImagePanel; ?>" type="text" size="50" value="<?php echo $items->imgpanel_watermark_path; ?>" name="imgpanel_watermark_path" readonly="readonly" id="imgpanel_watermark_path" onchange="JSNISClassicTheme.changeValueFlash('imagePanel', this);" />
										</div></td>
									<td width="5" nowrap="nowrap"><p id="watermark-title"><a class="modal" rel="{handler: 'iframe', size: {x: 590, y: 410}}" href="index.php?option=com_imageshow&controller=media&tmpl=component&act=watermark&e_name=text&event=loadMedia&theme=<?php echo $this->_showcaseThemeName; ?>"><?php echo JText::_('SELECT_WATERMARK')?></a></p></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_WATERMARK_POSITION');?>::<?php echo JText::_('DES_WATERMARK_POSITION'); ?>"><?php echo JText::_('TITLE_WATERMARK_POSITION'); ?></span></td>
									<td><?php echo $lists['imgPanelWatermarkPosition']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_WATERMARK_OFFSET');?>::<?php echo JText::_('DES_WATERMARK_OFFSET'); ?>"><?php echo JText::_('TITLE_WATERMARK_OFFSET'); ?></span></td>
									<td><input class="<?php echo $classImagePanel; ?>" type="text" size="5" value="<?php echo ($items->imgpanel_watermark_offset!='')?$items->imgpanel_watermark_offset:10; ?>" name="imgpanel_watermark_offset" id="imgpanel_watermark_offset" <?php echo ($items->imgpanel_watermark_position =='center')?'disabled="disabled"':''; ?> onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /> px</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_WATERMARK_OPACITY');?>::<?php echo JText::_('DES_WATERMARK_OPACITY'); ?>"><?php echo JText::_('TITLE_WATERMARK_OPACITY'); ?></span></td>
									<td><input class="<?php echo $classImagePanel; ?>" type="text" size="5" value="<?php echo ($items->imgpanel_watermark_opacity!='')?$items->imgpanel_watermark_opacity:75; ?>" name="imgpanel_watermark_opacity" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /> %</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="image-panel-overlay-effect">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('OVERLAY_EFFECT'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SHOW_IMG_OVERLAY_EFFECT');?>::<?php echo JText::_('DES_SHOW_IMG_OVERLAY_EFFECT'); ?>"><?php echo JText::_('TITLE_SHOW_IMG_OVERLAY_EFFECT');?></span></td>
									<td><?php echo $lists['imgPanelShowOverlayEffect']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_IMG_OVERLAY_EFFECT_TYPE');?>::<?php echo JText::_('DES_IMG_OVERLAY_EFFECT_TYPE'); ?>"><?php echo JText::_('TITLE_IMG_OVERLAY_EFFECT_TYPE');?></span></td>
									<td><?php echo $lists['imgPanelOverlayEffectType']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php
					echo $myTabs->endPanel();
					echo $myTabs->startPanel(JText::_('THUMBNAIL_PANEL'),'thumb-panel jsnis-theme-panel');
			?>
				<div id="jsn-thumb-panel" class="jsn-accordion">
					<div class="jsn-accordion-control"> <span><?php echo JText::_('EXPAND_ALL');?></span>&nbsp;&nbsp;|&nbsp; <span><?php echo JText::_('COLLAPSE_ALL');?></span> </div>
					<div class="jsn-accordion-title" id="thumb-panel-general">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('GENERAL'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" border="0" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SHOW_THUMBNAIL_PANEL');?>::<?php echo JText::_('DES_SHOW_THUMBNAIL_PANEL'); ?>"><?php echo JText::_('TITLE_SHOW_THUMBNAIL_PANEL'); ?></span></td>
									<td><?php echo $lists['thumbPanelShowPanel']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_THUMBNAIL_PANEL_POSITION');?>::<?php echo JText::_('DES_THUMBNAIL_PANEL_POSITION'); ?>"><?php echo JText::_('TITLE_THUMBNAIL_PANEL_POSITION'); ?></span></td>
									<td><?php echo $lists['thumbPanelPanelPosition']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_COLLAPSIBLE_THUMBNAIL_PANEL');?>::<?php echo JText::_('DES_COLLAPSIBLE_THUMBNAIL_PANEL'); ?>"><?php echo JText::_('TITLE_COLLAPSIBLE_THUMBNAIL_PANEL'); ?></span></td>
									<td><?php echo $lists['thumbPanelCollapsiblePosition']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_PANEL_BACKGROUND_COLOR');?>::<?php echo JText::_('DES_PANEL_BACKGROUND_COLOR'); ?>"><?php echo JText::_('TITLE_PANEL_BACKGROUND_COLOR'); ?></span></td>
									<td class="showcase-input-field"><input class="<?php echo $classThumbPanel; ?>" type="text" size="15" value="<?php echo (!empty($items->thumbpanel_thumnail_panel_color))?$items->thumbpanel_thumnail_panel_color:'#000000'; ?>" readonly="readonly" name="thumbpanel_thumnail_panel_color" id="thumbpanel_thumnail_panel_color" onchange="JSNISClassicTheme.changeValueFlash('thumbnailPanel', this);"/>
										<a href="" id="thumnail_panel_color"><span id="span_thumnail_panel_color" class="jsnis-icon-view-color" style="<?php echo (!empty($items->thumbpanel_thumnail_panel_color))?'background:'.$items->thumbpanel_thumnail_panel_color.';':'background:#000000;'; ?>"></span><span class="color-selection"><?php echo JText::_('SELECT_COLOR')?></span></a></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_THUMBNAIL_NORMAL_STATE_COLOR');?>::<?php echo JText::_('DES_THUMBNAIL_NORMAL_STATE_COLOR'); ?>"><?php echo JText::_('TITLE_THUMBNAIL_NORMAL_STATE_COLOR'); ?></span></td>
									<td class="showcase-input-field"><input class="<?php echo $classThumbPanel; ?>" type="text" size="15" value="<?php echo (!empty($items->thumbpanel_thumnail_normal_state))?$items->thumbpanel_thumnail_normal_state:'#ffffff'; ?>" readonly="readonly" name="thumbpanel_thumnail_normal_state" id="thumbpanel_thumnail_normal_state" onchange="JSNISClassicTheme.changeValueFlash('thumbnailPanel', this);"/>
										<a href="" id="thumnail_normal_state"><span id="span_thumnail_normal_state" class="jsnis-icon-view-color" style="<?php echo (!empty($items->thumbpanel_thumnail_normal_state))?'background:'.$items->thumbpanel_thumnail_normal_state.';':'background:#ffffff;'; ?>"></span><span class="color-selection"><?php echo JText::_('SELECT_COLOR')?></span></a></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_ACTIVE_STATE_COLOR');?>::<?php echo JText::_('DES_ACTIVE_STATE_COLOR'); ?>"><?php echo JText::_('TITLE_ACTIVE_STATE_COLOR'); ?></span></td>
									<td class="showcase-input-field"><input class="<?php echo $classThumbPanel; ?>" type="text" size="15" value="<?php echo (!empty($items->thumbpanel_active_state_color))?$items->thumbpanel_active_state_color:'#ff6200'; ?>" readonly="readonly" name="thumbpanel_active_state_color" id="thumbpanel_active_state_color" onchange="JSNISClassicTheme.changeValueFlash('thumbnailPanel', this);"/>
										<a href="" id="active_state_color"><span id="span_thumbpanel_active_state_color" class="jsnis-icon-view-color" style="<?php echo (!empty($items->thumbpanel_active_state_color))?'background:'.$items->thumbpanel_active_state_color.';':'background:#ff6200;'; ?>"></span><span class="color-selection"><?php echo JText::_('SELECT_COLOR')?></span></a></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="thumb-panel-thumbnail">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('THUMBNAIL'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" border="0" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SHOW_THUMBNAILS_STATUS');?>::<?php echo JText::_('DES_SHOW_THUMBNAILS_STATUS'); ?>"><?php echo JText::_('TITLE_SHOW_THUMBNAILS_STATUS'); ?></span></td>
									<td><?php echo $lists['thumbPanelShowThumbStatus']; ?></td>
								</tr>
								<tr>
									<td class="key" nowrap="nowrap" ><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_THUMBNAILS_PRESENTATION_MODE');?>::<?php echo JText::_('DES_THUMBNAILS_PRESENTATION_MODE'); ?>"><?php echo JText::_('TITLE_THUMBNAILS_PRESENTATION_MODE');?></span></td>
									<td><?php echo $lists['thumbPanelPresentationMode']; ?></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_THUMBNAILS_BROWSING_MODE');?>::<?php echo JText::_('DES_THUMBNAILS_BROWSING_MODE'); ?>"><?php echo JText::_('TITLE_THUMBNAILS_BROWSING_MODE'); ?></span></td>
									<td><?php echo $lists['thumbPanelThumbBrowsingMode']; ?></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_THUMBNAIL_ROW');?>::<?php echo JText::_('DES_THUMBNAIL_ROW'); ?>"><?php echo JText::_('TITLE_THUMBNAIL_ROW'); ?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="5" value="<?php echo ($items->thumbpanel_thumb_row!='')?$items->thumbpanel_thumb_row:1; ?>" name="thumbpanel_thumb_row" id="thumbpanel_thumb_row" <?php echo ($items->thumbpanel_thumb_browsing_mode =='sliding')?'readonly="readonlye"':''; ?> onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_THUMBNAIL_WIDTH');?>::<?php echo JText::_('DES_THUMBNAIL_WIDTH'); ?>"><?php echo JText::_('TITLE_THUMBNAIL_WIDTH'); ?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="5" value="<?php echo ($items->thumbpanel_thumb_width!='')?$items->thumbpanel_thumb_width:50; ?>" name="thumbpanel_thumb_width" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /> px</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_THUMBNAIL_HEIGHT');?>::<?php echo JText::_('DES_THUMBNAIL_HEIGHT'); ?>"><?php echo JText::_('TITLE_THUMBNAIL_HEIGHT'); ?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="5" value="<?php echo ($items->thumbpanel_thumb_height!='')?$items->thumbpanel_thumb_height:40; ?>" name="thumbpanel_thumb_height" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /> px</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_THUMBNAIL_BORDER');?>::<?php echo JText::_('DES_THUMBNAIL_BORDER'); ?>"><?php echo JText::_('TITLE_THUMBNAIL_BORDER');?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="5" value="<?php echo ($items->thumbpanel_border!='')?$items->thumbpanel_border:1; ?>" name="thumbpanel_border" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /> px</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_THUMBNAIL_OPACITY');?>::<?php echo JText::_('DES_THUMBNAIL_OPACITY'); ?>"><?php echo JText::_('TITLE_THUMBNAIL_OPACITY');?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="5" value="<?php echo ($items->thumbpanel_thumb_opacity!='')?$items->thumbpanel_thumb_opacity:50; ?>" name="thumbpanel_thumb_opacity" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /> %</td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="thumb-panel-big-thumbnail">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('BIG_THUMBNAIL'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_ENABLE_BIG_THUMBNAIL');?>::<?php echo JText::_('DES_ENABLE_BIG_THUMBNAIL'); ?>"><?php echo JText::_('TITLE_ENABLE_BIG_THUMBNAIL'); ?></span></td>
									<td><?php echo $lists['thumbPanelEnableBigThumb']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_BIG_THUMBNAIL_SIZE');?>::<?php echo JText::_('DES_BIG_THUMBNAIL_SIZE'); ?>"><?php echo JText::_('TITLE_BIG_THUMBNAIL_SIZE'); ?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="5" value="<?php echo ($items->thumbpanel_big_thumb_size!='')?$items->thumbpanel_big_thumb_size:150; ?>" name="thumbpanel_big_thumb_size" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /> px</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_BIG_THUMBNAIL_BORDER');?>::<?php echo JText::_('DES_BIG_THUMBNAIL_BORDER'); ?>"><?php echo JText::_('TITLE_BIG_THUMBNAIL_BORDER'); ?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="5" value="<?php echo ($items->thumbpanel_thumb_border!='')?$items->thumbpanel_thumb_border:2; ?>" name="thumbpanel_thumb_border" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /> px</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_BIG_THUMBNAIL_COLOR');?>::<?php echo JText::_('DES_BIG_THUMBNAIL_COLOR'); ?>"><?php echo JText::_('TITLE_BIG_THUMBNAIL_COLOR'); ?></span></td>
									<td class="showcase-input-field"><input class="<?php echo $classThumbPanel; ?>" type="text" size="15" value="<?php echo (!empty($items->thumbpanel_big_thumb_color))?$items->thumbpanel_big_thumb_color:'#ffffff'; ?>" readonly="readonly" name="thumbpanel_big_thumb_color" id="thumbpanel_big_thumb_color" onchange="JSNISClassicTheme.changeValueFlash('thumbnailPanel', this);"/>
										<a href="" id="big_thumb_color"><span id="span_thumbpanel_big_thumb_color" class="jsnis-icon-view-color" style="<?php echo (!empty($items->thumbpanel_big_thumb_color))?'background:'.$items->thumbpanel_big_thumb_color.';':'background:#ffffff;'; ?>"></span><span class="color-selection"><?php echo JText::_('SELECT_COLOR')?></span></a></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php
					echo $myTabs->endPanel();
					echo $myTabs->startPanel(JText::_('INFORMATION_PANEL'),'info-panel jsnis-theme-panel');
			?>
				<div id="jsn-info-panel" class="jsn-accordion">
					<div class="jsn-accordion-control"> <span><?php echo JText::_('EXPAND_ALL');?></span>&nbsp;&nbsp;|&nbsp; <span><?php echo JText::_('COLLAPSE_ALL');?></span> </div>
					<div class="jsn-accordion-title" id="info-panel-general">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('GENERAL'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_INFO_PANEL_PRESENTATION');?>::<?php echo JText::_('DES_INFO_PANEL_PRESENTATION'); ?>"><?php echo JText::_('TITLE_INFO_PANEL_PRESENTATION'); ?></span></td>
									<td><?php echo $lists['infoPanelPresentation']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_INFO_PANEL_POSITION');?>::<?php echo JText::_('DES_INFO_PANEL_POSITION'); ?>"><?php echo JText::_('TITLE_INFO_PANEL_POSITION'); ?></span></td>
									<td><?php echo $lists['infoPanelPanelPosition']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_PANEL_BACKGROUND_COLOR');?>::<?php echo JText::_('DES_PANEL_BACKGROUND_COLOR'); ?>"><?php echo JText::_('TITLE_PANEL_BACKGROUND_COLOR'); ?></span></td>
									<td class="showcase-input-field"><input class="<?php echo $classInfoPanel; ?>" type="text" size="15" value="<?php echo (!empty($items->infopanel_bg_color_fill))?$items->infopanel_bg_color_fill:'#000000'; ?>" readonly="readonly" name="infopanel_bg_color_fill" id="infopanel_bg_color_fill" onchange="JSNISClassicTheme.changeValueFlash('informationPanel', this);"/>
										<a href="" id="bg_color_fill"><span id="span_bg_color_fill" class="jsnis-icon-view-color" style="<?php echo (!empty($items->infopanel_bg_color_fill))?'background:'.$items->infopanel_bg_color_fill.';':'background:#000000;'; ?>"></span><span class="color-selection"><?php echo JText::_('Select_Color')?></span></a></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_PANEL_CLICK_ACTION');?>::<?php echo JText::_('DES_PANEL_CLICK_ACTION'); ?>"><?php echo JText::_('TITLE_PANEL_CLICK_ACTION'); ?></span></td>
									<td><?php echo $lists['infoPanelPanelClickAction']; ?></td>
								</tr>
								<tr id="jsn-info-open-link-in">
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_INFO_OPEN_LINK_IN');?>::<?php echo JText::_('DES_INFO_OPEN_LINK_IN'); ?>"><?php echo JText::_('TITLE_INFO_OPEN_LINK_IN'); ?></span></td>
									<td><?php echo $lists['infoPanelOpenLinkIn']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="info-panel-title">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('TITLE'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SHOW_TITLE');?>::<?php echo JText::_('DES_SHOW_TITLE'); ?>"><?php echo JText::_('TITLE_SHOW_TITLE'); ?></span></td>
									<td><?php echo $lists['infoPanelShowTitle']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_TITLE_CSS');?>::<?php echo JText::_('DES_TITLE_CSS'); ?>"><?php echo JText::_('TITLE_TITLE_CSS'); ?></span></td>
									<td><textarea  class="<?php echo $classInfoPanel; ?>" cols="37" rows="5" name="infopanel_title_css"><?php echo $items->infopanel_title_css; ?></textarea></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="info-panel-description">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('DESCRIPTION'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SHOW_DESCRIPTION');?>::<?php echo JText::_('DES_SHOW_DESCRIPTION'); ?>"><?php echo JText::_('TITLE_SHOW_DESCRIPTION'); ?></span></td>
									<td><?php echo $lists['infoPanelShowDes']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_DESCRIPTION_LENGTH_LIMITATION');?>::<?php echo JText::_('DES_DESCRIPTION_LENGTH_LIMITATION'); ?>"><?php echo JText::_('TITLE_DESCRIPTION_LENGTH_LIMITATION'); ?></span></td>
									<td><input  class="<?php echo $classInfoPanel; ?>" type="text" size="5" value="<?php echo ($items->infopanel_des_lenght_limitation!='')?$items->infopanel_des_lenght_limitation:50; ?>" name="infopanel_des_lenght_limitation" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /> <?php echo JText::_('WORDS'); ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_DESCRIPTION_CSS');?>::<?php echo JText::_('DES_DESCRIPTION_CSS'); ?>"><?php echo JText::_('TITLE_DESCRIPTION_CSS'); ?></span></td>
									<td><textarea class="<?php echo $classInfoPanel; ?>" cols="37" rows="5" name="infopanel_des_css"><?php echo $items->infopanel_des_css; ?></textarea></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="info-panel-link">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('LINK'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SHOW_LINK');?>::<?php echo JText::_('DES_SHOW_LINK'); ?>"><?php echo JText::_('TITLE_SHOW_LINK'); ?></span></td>
									<td><?php echo $lists['infoPanelShowLink']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_LINK_CSS');?>::<?php echo JText::_('DES_LINK_CSS'); ?>"><?php echo JText::_('TITLE_LINK_CSS'); ?></span></td>
									<td><textarea  class="<?php echo $classInfoPanel; ?>" cols="40" rows="5" name="infopanel_link_css"><?php echo $items->infopanel_link_css; ?></textarea></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php
					echo $myTabs->endPanel();
					echo $myTabs->startPanel(JText::_('TOOLBAR_PANEL'),'toolbar-panel jsnis-theme-panel');
			?>
				<div id="jsn-toolbar-panel" class="jsn-accordion">
					<div class="jsn-accordion-control"> <span><?php echo JText::_('EXPAND_ALL');?></span>&nbsp;&nbsp;|&nbsp; <span><?php echo JText::_('COLLAPSE_ALL');?></span> </div>
					<div class="jsn-accordion-title" id="toolbar-panel-general">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('GENERAL'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_TOOLBAR_PANEL_PRESENTATION');?>::<?php echo JText::_('DES_TOOLBAR_PANEL_PRESENTATION'); ?>"><?php echo JText::_('TITLE_TOOLBAR_PANEL_PRESENTATION');?></span></td>
									<td><?php echo $lists['toolBarPanelPresentation']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_TOOLBAR_PANEL_POSITION');?>::<?php echo JText::_('DES_TOOLBAR_PANEL_POSITION'); ?>"><?php echo JText::_('TITLE_TOOLBAR_PANEL_POSITION');?></span></td>
									<td><?php echo $lists['toolBarPanelPanelPosition']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_TOOLBAR_PANEL_SHOW_TOOLTIP');?>::<?php echo JText::_('DES_TOOLBAR_PANEL_SHOW_TOOLTIP'); ?>"><?php echo JText::_('TITLE_TOOLBAR_PANEL_SHOW_TOOLTIP');?></span></td>
									<td><?php echo $lists['toolBarPanelShowTooltip']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="toolbar-panel-functions">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('FUNCTIONS'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SHOW_IMAGE_NAVIGATION');?>::<?php echo JText::_('DES_SHOW_IMAGE_NAVIGATION'); ?>"><?php echo JText::_('TITLE_SHOW_IMAGE_NAVIGATION'); ?></span></td>
									<td><?php echo $lists['toolBarPanelShowImageNavigation']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SHOW_SLIDESHOW_PLAYER');?>::<?php echo JText::_('DES_SHOW_SLIDESHOW_PLAYER'); ?>"><?php echo JText::_('TITLE_SHOW_SLIDESHOW_PLAYER');?></span></td>
									<td><?php echo $lists['toolBarPanelSlideShowPlayer']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SHOW_FULLSCREEN_SWITCHER');?>::<?php echo JText::_('DES_SHOW_FULLSCREEN_SWITCHER'); ?>"><?php echo JText::_('TITLE_SHOW_FULLSCREEN_SWITCHER');?></span></td>
									<td><?php echo $lists['toolBarPanelShowFullscreenSwitcher'];?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php
					echo $myTabs->endPanel();
					echo $myTabs->startPanel(JText::_('SLIDESHOW'),'slideshow-panel jsnis-theme-panel');
			?>
				<div id="jsn-slideshow-panel" class="jsn-accordion">
					<div class="jsn-accordion-control"> <span><?php echo JText::_('EXPAND_ALL');?></span>&nbsp;&nbsp;|&nbsp; <span><?php echo JText::_('COLLAPSE_ALL');?></span> </div>
					<div class="jsn-accordion-title" id="slideshow-panel-slideshow-presentation">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('SLIDESHOW_PRESENTATION'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_KENBURN');?>::<?php echo JText::_('DES_KENBURN'); ?>"><?php echo JText::_('TITLE_KENBURN');?></span></td>
									<td><?php echo $lists['slideShowEnableKenBurnEffect']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SHOWSTATUS');?>::<?php echo JText::_('DES_SHOWSTATUS'); ?>"><?php echo JText::_('TITLE_SHOWSTATUS');?></span></td>
									<td><?php echo $lists['slideShowShowStatus']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SLIDE_HIDE_THUMBNAIL_PANEL');?>::<?php echo JText::_('DES_SLIDE_HIDE_THUMBNAIL_PANEL'); ?>"><?php echo JText::_('TITLE_SLIDE_HIDE_THUMBNAIL_PANEL'); ?></span></td>
									<td><?php echo $lists['slideShowHideThumbPanel']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SLIDE_HIDE_IMAGE_NAVIGATION');?>::<?php echo JText::_('DES_SLIDE_HIDE_IMAGE_NAVIGATION'); ?>"><?php echo JText::_('TITLE_SLIDE_HIDE_IMAGE_NAVIGATION');?></span></td>
									<td><?php echo $lists['slideShowHideImageNavigation']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="slideshow-panel-slideshow-process">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('SLIDESHOW_PROCESS'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SLIDE_TIMING');?>::<?php echo JText::_('DES_SLIDE_TIMING'); ?>"><?php echo JText::_('TITLE_SLIDE_TIMING');?></span></td>
									<td><input class="<?php echo $classSlideShowPanel; ?>" type="text" size="5" value="<?php echo ($items->slideshow_slide_timing!='')?$items->slideshow_slide_timing:8; ?>" name="slideshow_slide_timing" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /> <?php echo JText::_('SECONDS'); ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_AUTO_PLAY');?>::<?php echo JText::_('DES_AUTO_PLAY'); ?>"><?php echo JText::_('TITLE_AUTO_PLAY');?></span></td>
									<td><?php echo $lists['slideShowProcess']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE_SLIDESHOW_LOOPING');?>::<?php echo JText::_('DES_SLIDESHOW_LOOPING'); ?>"><?php echo JText::_('TITLE_SLIDESHOW_LOOPING');?></span></td>
									<td><?php echo $lists['slideShowLooping']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php
					echo $myTabs->endPanel();
				echo $myTabs->endPane();
			?>
			</div></td>
		<td valign="top" style="width:571px;"><div class="jsn-preview-wrapper">
				<?php include dirname(__FILE__).DS.'preview.php'; ?>
			</div></td>
	</tr>
</table>
<div style="clear:both;"></div>
