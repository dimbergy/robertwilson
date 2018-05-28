<?php
/**
 * @version    $Id$
 * @package    JSN_PageBuilder
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */


// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');

$addtoken  = JSession::getFormToken();
?>

<div class="filter-search btn-group pull-right clear">
	<button type="button" class="btn btn-default" id="jsn-element-module-btn-reset"><?php echo JText::_('JSN_PAGEBUILDER_BUTTON_RESET', true); ?></button>
	<input type="text" value="" class="input-sm pull-left" placeholder="Search..." id="jsn-element-module-input-search">
</div> 
		
<form action="<?php echo JRoute::_('index.php?option=com_pagebuilder&view=selectmodule&tmpl=component'); ?>" method="post" name="adminForm" id="adminForm" class="clear">
    <div class="jsn-bootstrap">
        <div class="jscroll-inner">
            <div id="jsn-module-container" class="module-scroll-fade" data-start="0">
                
            </div>
        </div>
        <div class="loading-bar hide"></div>
        <a href="javascript:void(0);" class="jsn-add-more hide" id="jsn-module-load-more-btn"><?php echo JText::_('JSN_PAGEBUILDER_LOAD_MORE', true); ?></a>
    </div>
</form>

<script type="text/javascript">
    var timer = 0
	$('#jsn-element-module-input-search').keyup(function (e) {
		e.preventDefault();
		$('.jsn-bootstrap').find('.loading-bar').show();
		$('#jsn-module-container').attr('data-start', 0)
		clearTimeout(timer);
        timer = setTimeout(function () {
    		$("#jsn-module-container").html('');
        	getData();
        }, 500);
	});

    $('#jsn-element-module-btn-reset').click(function (e) {
		e.preventDefault();
		$('.jsn-bootstrap').find('.loading-bar').show();
		$("#jsn-module-container").html('');
		$('#jsn-module-container').attr('data-start', 0)
		$('#jsn-element-module-input-search').val('');
		getData();
	});

    function getData() {
    	
    	$('#jsn-module-load-more-btn').hide();
    	$('.jsn-bootstrap').find('.loading-bar').show();
    			
        var start         = $('#jsn-module-container').attr('data-start');
        var filter_search = $('#jsn-element-module-input-search').val();
        var token         = '<?php echo $addtoken ?>';
        // Post data to ajax
        $.post('index.php?option=com_pagebuilder&view=selectmodule&layout=data&'+token+'=1', {
            start  : start,
            search : filter_search
        }, function(data) {
        	
            var $content =  $(data).find(".jsn-module-content");
            
            if ($content.html().trim() == "") {
            	$('.jsn-bootstrap').find('.loading-bar').remove();
            }
            else {
                $("#jsn-module-container").append($content.html());
                $('.jsn-bootstrap').find('.loading-bar').hide();
                $('#jsn-module-load-more-btn').show();
                
                var length = $('#jsn-module-container').find('.jsn-element-module-item').length;
                $('#jsn-module-container').attr('data-start', length);
                
                if (length == parseInt($content.attr('data-total'))) {
                    $('#jsn-module-load-more-btn').hide();
                }
            }                
            return;                                 
        });
    
    }
    $(document).ready(function() { 
        getData();
        $('#jsn-module-load-more-btn').on('click', function(e) {
            e.preventDefault();
            getData();
        });
});
</script>
<script type="text/javascript">
(function($){
    $(window).ready(function(){
        
        $("#jsn-module-container").delegate('.jsn-item-type', "click", function(e) {
            var id = $(this).attr('id');
            var selected = $(this).find('div, div.editlinktip').attr('title');
            if (window.parent && typeof window.parent['setSelectModule'] == 'function') {
                window.parent['setSelectModule'](id + '-' +selected, '#param-module_name');
            }
        });
        });
  })(JoomlaShine.jQuery);
</script>