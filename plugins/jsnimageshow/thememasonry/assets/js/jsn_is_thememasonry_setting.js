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
(function($){
    $.extend({
        JSNISThemeMasonry: {

            ops:{},
            initialize : function (options)
            {
                $.extend(options, $.JSNISThemeMasonry.ops);
                var self = $.JSNISThemeMasonry;
            },


            openTab: function(panelID)
            {
                $('.' + panelID).trigger('click');
            }
        }
    });
    $.fn.adminMansonry = function (wrapper, settingWrapper, settings) {
        settings = typeof settings == 'undefined' ? {} : settings;
        var defaultSetting = {
            itemSelector: '.grid-item',
            gutter: parseFloat(settingWrapper.find('input#gutter').val()),
            percentPosition:false,
            columnWidth: parseFloat(settingWrapper.find('input#column_width').val()) != 0 ? parseFloat(settingWrapper.find('input#column_width').val()) : '.grid-sizer',
            isFitWidth: settingWrapper.find('input[name^="is_fit_width"]:checked').val()

        }
        
        defaultSetting = $.extend(true, defaultSetting, settings);
        var $grid = $('.grid').imagesLoaded(function (){
            $grid.masonry(defaultSetting);
        });
    };
    $.fn.initSliderSetting = function(SliderElement, minVal, maxVal, stepVal, unit)
    {
        $('#'+SliderElement+'_slider')[0].slide = null;
        $('#'+SliderElement+'_slider').slider({
            value:parseInt($('#'+SliderElement).val()),
            min: minVal,
            max: maxVal,
            step: stepVal,
            slide: function( event, ui ) {
                $('#'+SliderElement+'_slider_value').html(ui.value+unit);
                $('#'+SliderElement).val(ui.value);
                $('#'+SliderElement).trigger('change');
            }
        });
    };
    $(document).ready( function() {
        $('input[name="general_overall_height"]').parent().parent().hide();
        var wrapper = $('.jsn-thememasonry-wrapper');
        var paramWrapper = $('#jsn-theme-parameters-wrapper');
        if (wrapper.length > 0) {
            $.fn.adminMansonry(wrapper, paramWrapper);
            paramWrapper.find('input#gutter').on('change', function () {
                $.fn.adminMansonry(wrapper, paramWrapper);
                var gutter = $(this).val();
                wrapper.find('div.grid-item').css({'margin-bottom': gutter + 'px'})
            });
            paramWrapper.find('input#gutter').trigger('change');
            paramWrapper.find('input#column_width').on('change', function () {
            	//var changeWidth = paramWrapper.find('input#column_width').val();
            	//$('#jsn-thememasonry-container').find('div.grid-item').css("width", changeWidth); 
                $.fn.adminMansonry(wrapper, paramWrapper);
            });
            
            /*paramWrapper.find('#layout_type').on('change', function () {
            	if (paramWrapper.find('#layout_type').val() == 'fluid'){
            		paramWrapper.find('input#column_width').val(180)
            		$('#jsn-thememasonry-container').find('div.grid-item').css("width", "180px");
                    $.fn.adminMansonry(wrapper, paramWrapper);
            	}
            });*/
            
            paramWrapper.find('input#column_width').trigger('change');
            paramWrapper.find('input#image_border').on('change', function () {
                var border = $(this).val();
                wrapper.find('div.grid-item').css({'border': parseFloat(border) + 'px solid'})
            });
            paramWrapper.find('input#image_border').trigger('change');
            paramWrapper.find('input#image_rounded_corner').on('change', function () {
                var rounded = $(this).val();
                wrapper.find('div.grid-item').css({'border-radius': parseFloat(rounded) + 'px', '-moz-border-radius': parseFloat(rounded) + 'px', '-webkit-border-radius': parseFloat(rounded) + 'px'})
            });
            paramWrapper.find('input#image_rounded_corner').trigger('change');

            paramWrapper.find('input[name^="is_fit_width"]').on('change', function () {
                $.fn.adminMansonry(wrapper, paramWrapper);
            });
            paramWrapper.find('input[name^="is_fit_width"]').trigger('change');
            paramWrapper.find('input#image_border_color').on('change', function () {
                wrapper.find('.grid-item').css({'border-color': $(this).val() });
            });
            paramWrapper.find('input#image_border_color').trigger('change');
        }
        $.fn.initSliderSetting("caption_opacity", 0, 100, 1, '%');
    });

})(jQuery);