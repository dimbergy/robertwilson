/**
 * @version    ${FILE_NAME}$
 * @package    4.9.2
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2015 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
(function($)
{
    $.fn.jsnthememasonry = function (ID, options) {
        if (options.image_click_action == 'show-original-image') {
            if (typeof jsnThemeCarouseljQuery == "function" || typeof jsnThemeFlowjQuery == "function" || typeof jsnThemeStripjQuery == 'function') {
            	jQuery(this).find('.jsn-fancybox-item').fancybox({
                    'titlePosition': 'over',
                    'titleFormat': function (title, currentArray, currentIndex, currentOpts) {
                        return '<div class="jsn-thememasonry-gallery-info-' + ID + '">' + title + '</div>';
                        
                    },
                    openEffect: 'fade',
                    prevEffect: 'fade',
                    nextEffect: 'fade'
                });
            }
            else {
            	jQuery(this).find('.jsn-fancybox-item').fancybox({
                    'titlePosition': 'over',
                    'titleFormat': function (title, currentArray, currentIndex, currentOpts) {
                        return '<div class="jsn-thememasonry-gallery-info-' + ID + '">' + title + '</div>';
                    },
                    openEffect: 'fade',
                    prevEffect: 'fade',
                    nextEffect: 'fade'
                });
            }
        }
    }
})(jsnThemeMasonryjQuery)