<?php
/**
 * @version    default.php$
 * @package    4.9.2
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2015 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted access');
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
?>
<script type="text/javascript">
	(function($){
		$(document).ready(function () {
			$('#jsn-is-thememasonry').tabs();
			$('#image-border-color-selector').ColorPicker({
				color: $('#image_border_color').val(),
				onShow: function (colpkr) {
					$(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					$(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					$('#image_border_color').val('#' + hex);
					$('#image-border-color-selector div').css('backgroundColor', '#' + hex);
					$('.grid-item').css('border-color', '#' + hex);
				}
			});
			$('#caption-background-color-selector').ColorPicker({
				color: $('#caption_background_color').val(),
				onShow: function (colpkr) {
					$(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					$(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					$('#caption_background_color').val('#' + hex);
					$('#caption-background-color-selector div').css('backgroundColor', '#' + hex);
				}
			});
			$('#image_click_action').on('change', function () {
				if ($(this).val() == 'open-image-link')
				{
					$('#jsn-open-link-in').css({'display': 'block'})
				}
				else{
					$('#jsn-open-link-in').css({'display': 'none'})
				}
			});
			$('#image_click_action').trigger('change');
			$('#layout_type').on('change', function () {
				if ($(this).val() == 'fixed')
				{
					$('#column_width').closest('.control-group').css({'display': 'block'})
				}
				else{
					$('#column_width').closest('.control-group').css({'display': 'none'})
				}
			});
			$('#layout_type').trigger('change');
			$('#pagination_type').on('change', function(){
				if ($(this).val() == 'all')
				{
					$('#number_load_image').closest('.control-group').css({'display': 'none'})
				}
				else
				{
					$('#number_load_image').closest('.control-group').css({'display': 'block'})
				}
			}).trigger('change');
		});

			setTimeout(function () {
				$('.jsn-thememasonry-container > .wrapper').mouseenter(function (e){
					$(this).css({'border':'1px solid #FFCD3F'})
				});
				$('.jsn-thememasonry-container > .wrapper').mouseleave(function (e) {
					$(this).css({'border': '1px solid #333'});
				});
				$('.grid-item').mouseenter(function (e) {
					$(this).css({'background':'#ffc61a'})
				});
				$('.grid-item').mouseleave(function (e) {
					$(this).css({'background': ' #2898cc'});
				});
			}, 200)
	})(jQuery)
</script>
<table class="jsn-showcase-theme-settings" style="height: 600px;">
	<tr>
		<td valign="top" id="jsn-theme-parameters-wrapper">
			<div id="jsn-is-thememasonry" class="jsn-tabs">
				<ul>
					<li><a href="#thememasonry-image-tab" class="thememasonry-image-tab"><?php echo JText::_('THEME_MASONRY_IMAGE')?></a></li>
					<li><a href="#thememasonry-caption-tab" class="thememasonry-caption-tab"><?php echo JText::_('THEME_MASONRY_CAPTION')?></a></li>
					<li><a href="#thememasonry-layout-tab" class="thememasonry-layout-tab"><?php echo JText::_('THEME_MASONRY_LAYOUT')?></a></li>
				</ul>
				<div id="thememasonry-image-tab" class="jsn-bootstrap">
					<div class="form-horizontal">
						<div class="row-fluid show-masonry">
							<!-- <div class="span6"> -->
							<div class="span12">
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_IMAGE_SOURCE');?>::<?php echo JText::_('THEME_MASONRY_IMAGE_SOURCE_DES'); ?>"><?php echo JText::_('THEME_MASONRY_IMAGE_SOURCE');?></label>
									<div class="controls">
										<?php echo $lists['imageSource']; ?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_IMAGE_BORDER');?>::<?php echo JText::_('THEME_MASONRY_IMAGE_BORDER_DES'); ?>"><?php echo JText::_('THEME_MASONRY_IMAGE_BORDER');?></label>
									<div class="controls">
										<input type="number" name="image_border" id="image_border" class="imagePanel input-mini" value="<?php echo $items->image_border; ?>" /> <?php echo JText::_('THEME_MASONRY_PIXEL');?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_IMAGE_ROUNDED_CORNER');?>::<?php echo JText::_('THEME_MASONRY_IMAGE_ROUNDED_CORNER_DES'); ?>"><?php echo JText::_('THEME_MASONRY_IMAGE_ROUNDED_CORNER');?></label>
									<div class="controls">
										<input type="number" name="image_rounded_corner" id="image_rounded_corner" class="imagePanel input-mini" value="<?php echo $items->image_rounded_corner; ?>" /> <?php echo JText::_('THEME_MASONRY_PIXEL');?>
									</div>
								</div>
							<!-- </div>
							<div class="span6"> -->
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_IMAGE_BORDER_COLOR');?>::<?php echo JText::_('THEME_MASONRY_IMAGE_BORDER_COLOR_DES'); ?>"><?php echo JText::_('THEME_MASONRY_IMAGE_BORDER_COLOR');?></label>
									<div class="controls">
										<input class="imagePanel input-mini" type="text" value="<?php echo (!empty($items->image_border_color))?$items->image_border_color:'#F0F0F0'; ?>" readonly="readonly" name="image_border_color" id="image_border_color" />
										<div class="color-selector" id="image-border-color-selector"><div style="background-color: <?php echo (!empty($items->image_border_color))?$items->image_border_color:'#F0F0F0'; ?>"></div></div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_IMAGE_CLICK_ACTION');?>::<?php echo JText::_('THEME_MASONRY_IMAGE_CLICK_ACTION_DES'); ?>"><?php echo JText::_('THEME_MASONRY_IMAGE_CLICK_ACTION');?></label>
									<div class="controls">
										<?php echo $lists['imageClickAction']; ?>
									</div>
								</div>
								<div id="jsn-open-link-in" class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_IMAGE_OPEN_LINK_IN');?>::<?php echo JText::_('THEME_MASONRY_IMAGE_OPEN_LINK_IN_DES'); ?>"><?php echo JText::_('THEME_MASONRY_IMAGE_OPEN_LINK_IN');?></label>
									<div class="controls">
										<?php echo $lists['openLinkIn']; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="thememasonry-caption-tab" class="jsn-bootstrap">
					<div class="form-horizontal">
						<div class="row-fluid show-masonry">
							<!-- <div class="span6"> -->
							<div class="span6">
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_SHOW_CAPTION');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_SHOW_CAPTION_DES'); ?>"><?php echo JText::_('THEME_MASONRY_CONTAINER_SHOW_CAPTION');?></label>
									<div class="controls">
										<?php echo $lists['showCaption'];?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_BACKGROUND_COLOR');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_BACKGROUND_COLOR_DES'); ?>"><?php echo JText::_('THEME_MASONRY_CONTAINER_BACKGROUND_COLOR'); ?></label>
									<div class="controls">
										<input class="input-mini" type="text" size="10" id="caption_background_color" readonly="readonly" name="caption_background_color" value="<?php echo $items->caption_background_color; ?>" />
										<div class="color-selector" id="caption-background-color-selector"><div style="background-color: <?php echo (!empty($items->caption_background_color))?$items->caption_background_color:'#000000'; ?>"></div></div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_BACKGROUND_OPACITY');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_BACKGROUND_OPACITY_DES'); ?>"><?php echo JText::_('THEME_MASONRY_CONTAINER_BACKGROUND_OPACITY');?></label>
									<div class="controls">
										<input type="hidden" id="caption_opacity" name="caption_opacity" class="input-mini" value="<?php echo $items->caption_opacity; ?>" />
										<div id="caption_opacity_slider" class="strip-param-slider"></div><div id="caption_opacity_slider_value" class="strip-param-slider-value"><?php echo $items->caption_opacity; ?>%</div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_SHOW_CAPTION_TITLE');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_SHOW_CAPTION_TITLE_DES'); ?>"><?php echo JText::_('THEME_MASONRY_CONTAINER_SHOW_CAPTION_TITLE');?></label>
									<div class="controls">
										<?php echo $lists['captionShowTitle']; ?>
									</div>
								</div>
							<!-- </div>
							<div class="span6"> -->
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_CAPTION_TITLE_CSS');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_CAPTION_TITLE_CSS_DES'); ?>"><?php echo JText::_('THEME_MASONRY_CONTAINER_CAPTION_TITLE_CSS'); ?></label>
									
									<div class="controls">
										<textarea class="input-xlarge" name="caption_title_css" rows="5"><?php echo $items->caption_title_css; ?></textarea>
										<?php //echo $lists['captionShowTitle']; ?>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_SHOW_CAPTION_DESCRIPTION');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_SHOW_CAPTION_DESCRIPTION_DES'); ?>"><?php echo JText::_('THEME_MASONRY_CONTAINER_SHOW_CAPTION_DESCRIPTION');?></label>
									<div class="controls">
										<?php echo $lists['captionShowDescription']; ?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_CAPTION_DESCRIPTION_LENGTH_LIMITATION');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_CAPTION_DESCRIPTION_LENGTH_LIMITATION_DES'); ?>"><?php echo JText::_('THEME_MASONRY_CONTAINER_CAPTION_DESCRIPTION_LENGTH_LIMITATION');?></label>
									<div class="controls">
										<input type="number" id="caption_description_length_limitation" name="caption_description_length_limitation" class="input-mini" value="<?php echo $items->caption_description_length_limitation; ?>" /> <?php echo JText::_('WORDS'); ?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_CAPTION_DESCRIPTION_CSS');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_CAPTION_DESCRIPTION_CSS_DES'); ?>"><?php echo htmlspecialchars(JText::_('THEME_MASONRY_CONTAINER_CAPTION_DESCRIPTION_CSS'));?></label>
									<div class="controls">
										<textarea class="input-xlarge" name="caption_description_css" rows="5"><?php echo $items->caption_description_css; ?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="thememasonry-layout-tab" class="jsn-bootstrap">
					<div class="form-horizontal">
						<div class="row-fluid show-masonry">
							<!-- <div class="span6"> -->
							<div class="span6">
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_LAYOUT_TYPE');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_LAYOUT_TYPE_DES'); ?>"><?php echo JText::_('THEME_MASONRY_CONTAINER_LAYOUT_TYPE');?></label>
									<div class="controls">
										<?php echo $lists['layoutType'];?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_COLUMN_WIDTH');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_COLUMN_WIDTH_DES'); ?>"><?php echo JText::_('THEME_MASONRY_CONTAINER_COLUMN_WIDTH');?></label>
									<div class="controls">
										<input type="number" id="column_width" name="column_width" class="input-mini visual-panel" value="<?php echo $items->column_width;?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_GUTTER');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_GUTTER_DES'); ?>"><?php echo JText::_('THEME_MASONRY_CONTAINER_GUTTER');?></label>
									<div class="controls">
										<input type="number" id="gutter" name="gutter" class="input-mini visual-panel" value="<?php echo $items->gutter;?>" />
									</div>
								</div>
							<!-- </div>
							<div class="span6"> -->
								
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_IS_FIT_WIDTH');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_IS_FIT_WIDTH_DES'); ?>"><?php echo JText::_('THEME_MASONRY_CONTAINER_IS_FIT_WIDTH');?></label>
									<div class="controls">
										<?php echo $lists['isFitWidth'];?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_CONTAINER_TRANSITION_DURATION');?>::<?php echo JText::_('THEME_MASONRY_CONTAINER_TRANSITION_DURATION_DES'); ?>"><?php echo JText::_('THEME_MASONRY_CONTAINER_TRANSITION_DURATION');?></label>
									<div class="controls">
										<input type="number" id="transition_duration" name="transition_duration" class="input-mini visual-panel" value="<?php echo $items->transition_duration;?>" />
									</div>
								</div>
<!--								<div class="control-group">-->
<!--									<label class="control-label hasTip" title="--><?php //echo JText::_('THEME_MASONRY_CONTAINER_FEATURE_IMAGE');?><!--::--><?php //echo JText::_('THEME_MASONRY_CONTAINER_FEATURE_IMAGE_DES'); ?><!--">--><?php //echo JText::_('THEME_MASONRY_CONTAINER_FEATURE_IMAGE');?><!--</label>-->
<!--									<div class="controls">-->
<!--										<input type="text" id="feature_image" name="feature_image" class="input-medium visual-panel" value="--><?php //echo $items->feature_image;?><!--" />-->
<!--									</div>-->
<!--								</div>-->
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_LAYOUT_PAGINATION_TYPE');?>::<?php echo JText::_('THEME_MASONRY_LAYOUT_PAGINATION_TYPE_DES'); ?>"><?php echo JText::_('THEME_MASONRY_LAYOUT_PAGINATION_TYPE');?></label>
									<div class="controls">
										<?php echo $lists['paginationType']; ?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hasTip" title="<?php echo JText::_('THEME_MASONRY_LAYOUT_NUMBER_LOAD_IMAGE');?>::<?php echo JText::_('THEME_MASONRY_LAYOUT_NUMBER_LOAD_IMAGE_DES'); ?>"><?php echo JText::_('THEME_MASONRY_LAYOUT_NUMBER_LOAD_IMAGE');?></label>
									<div class="controls">
										<input type="number" id="number_load_image" name="number_load_image" class="input-mini visual-panel" value="<?php echo $items->number_load_image;?>" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</td>
		
	<!-- </tr>
	<tr> -->
		<td id="jsn-preview-wrapper">
			<?php include dirname(__FILE__).DS.'preview.php'; ?>
		</td>
	</tr>
</table>
<input type="hidden" name="theme_name" value="<?php echo strtolower($this->_showcaseThemeName)?>" />
<input type="hidden" name="theme_id" value="<?php echo (int) @$items->theme_id ?>" />