/**
 * @version     $Id$
 * @package     JSN.ImageShow
 * @subpackage  JSN.ThemeCarousel
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) @JOOMLASHINECOPYRIGHTYEAR@ JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 * 
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

(function($) {
	$.fn.jsnthemestrip = function(ID, options) {
		
		if (typeof options.slideshow_auto_play != 'undefined')
		{
			if (options.slideshow_auto_play == 'yes')
			{
				options.slideshow_auto_play = true;
			}
			else
			{
				options.slideshow_auto_play = false;
			}
		}
		else
		{
			options.slideshow_auto_play = false;
		}	
		
		if (typeof options.slideshow_delay_time != 'undefined')
		{
			options.slideshow_delay_time = options.slideshow_delay_time;	
		}
		else
		{
			options.slideshow_delay_time = 3000;
		}	
		
		this.elastislide({speed: options.slideshow_sliding_speed, orientation: options.image_orientation, minItems: options.slideshow_min_items, orientation: options.image_orientation, width: options.image_width, height: options.image_height, space: options.image_space, image_border: options.image_border, image_shadow: options.image_shadow, container_border: options.container_border, container_side_fade: options.container_side_fade, autoSlide: options.slideshow_auto_play, delayTime: options.slideshow_delay_time});
		if(options.image_click_action == 'show-original-image') {
			if (typeof jsnThemeCarouseljQuery == "function" || typeof jsnThemeFlowjQuery == "function")
			{
                jQuery(this).find('a').fancybox({
                    'titlePosition': 'over',
                    'titleFormat': function (title, currentArray, currentIndex, currentOpts) {
                        return '<div class="jsn-themestrip-gallery-info-' + ID + '">' + title + '</div>';
                    },
                    openEffect: 'fade',
                    prevEffect: 'fade',
                    nextEffect: 'fade'
                });
			}	
			else
			{
                jQuery(this).find('a').fancybox({
                    'titlePosition': 'over',
                    'titleFormat': function (title, currentArray, currentIndex, currentOpts) {
                        return '<div class="jsn-themestrip-gallery-info-' + ID + '">' + title + '</div>';
                    },
                    openEffect: 'fade',
                    prevEffect: 'fade',
                    nextEffect: 'fade'
                });
			}
		}

        if (options.slideshow_auto_play) {

            var images = jQuery(this).find('a');
            var current_index = 0;
            var interval;

            function playSlideShow() {
                interval = setInterval(function () {
                    if (!images.eq(current_index).length)
                        current_index = 0;
                    images.eq(current_index).trigger('click', {autonext: true});
                    current_index++;
                }, options.slideshow_delay_time);
            }

            jQuery(this).find('a').click(function (e, options) {
                if (options && options.autonext) {
                }
                else {
                    current_index = $(e.currentTarget).parent().index() + 1;
                    playSlideShow();
                }
            });

            jQuery('#fancybox-close, #fancybox-overlay').click(function (e) {
                clearInterval(interval)
            });
            jQuery(window).keyup(function (e) {
                if (e.keyCode == 27) {
                    clearInterval(interval);
                }
            });
        }
	};
})(jsnThemeStripjQuery);
