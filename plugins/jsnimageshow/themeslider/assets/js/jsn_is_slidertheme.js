/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Theme Classic
 * @version $Id: jsn_is_slidertheme.js 11850 2012-03-22 04:34:29Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
Element.implement(
{
    removeStyle: function(ele)
    {
		var browser = this.getBrowserInfo();
	 	if (browser.name == 'ie' && (browser.version == '8' || browser.version == '7'))
        {
        	regex  = new RegExp('[~;\\s]' + ele + '.*?[;~]', "i"); 
        }
        else
        {	
        	regex = new RegExp(ele+ '.*?;'); 
        }
        style = this.get('style')+';';
        this.set('style', style.replace(regex, ''));
    },
    
	getBrowserInfo: function(){
		var name	= '';
		var version = '';
		var ua 		= navigator.userAgent.toLowerCase();
		var match	= ua.match(/(opera|ie|firefox|chrome|version)[\s\/:]([\w\d\.]+)?.*?(safari|version[\s\/:]([\w\d\.]+)|$)/) || [null, 'unknown', 0];
		if (match[1] == 'version')
		{
			name = match[3];
		}
		else
		{
			name = match[1];
		}
		version = parseFloat((match[1] == 'opera' && match[4]) ? match[4] : match[2]);
		
		return {'name': name, 'version': version};
	}
    
}); 
var JSNISSliderTheme = {
	toggleNavAlignment: function(el)
	{
		var navSelectorValue = $(el).getProperty('value'); 
		var navSelector = $('jsn-themeslider-nav-image-selectors');
		
		if (navSelectorValue == 'hide') {
			navSelector.style.display = 'none';
		} else {
			navSelector.style.display = '';
		}
		
		/*var NavSlide = $('acc-nav-presentation').getParent();
		NavSlide.style.height = 'auto';
		var navSlideSize = NavSlide.getSize();
		NavSlide.style.height = NavSlide.getSize().y + 'px';*/
	},
	
	toggleCaptionOptions: function(el)
	{
		var navSelectorValue = $(el).getProperty('value'); 
		
		if (navSelectorValue == 'hide') {
			$$('.jsn-themeslider-caption').each(function(el){
				el.style.display = 'none';
			});
		} else {
			$$('.jsn-themeslider-caption').each(function(el){
				el.style.display = '';
			});
		}
		
		/*var NavSlide = $('acc-caption-presentation').getParent();
		NavSlide.style.height = 'auto';
		var navSlideSize = NavSlide.getSize();
		NavSlide.style.height = NavSlide.getSize().y + 'px';*/
	},
	
	toogleTab: function(panelID)
	{
		var objChain = new Chain();
		
		objChain.chain(
			function()// open tab
			{
				$$('.'+panelID).fireEvent('click'); 
			}
		);
		objChain.callChain();
		objChain.callChain();
	},
	
	visual: function()
	{
		this.addEvent2ChangeValueVisual('imagePanel');
		this.addEvent2ChangeValueVisual('informationPanel');
		this.addEvent2ChangeValueVisual('toolbarPanel');
		this.addEvent2ChangeValueVisual('slideshowPanel');
		this.addEvent2ChangeValueVisual('thumbnailPanel');	
	},
	
	addEvent2ChangeValueVisual: function(elementClass) 
	{
		$$('.'+elementClass).each(function(element)
		{
			var event = 'change';
			
			if (element.type == 'radio') event = 'click';
			
			element.addEvent(event, function()
			{
				JSNISSliderTheme.changeValueVisual(elementClass, element);
			});
			JSNISSliderTheme.changeValueVisual(elementClass, element);
		});
	},
	
	changeValueVisual: function(panel, element)
	{
		var name = element.name;
		
		if(element.type == 'radio')
		{
			if(element.checked == true)
			{
				var value = element.getProperty('value');
			}
		}
		else
		{
			var value = element.getProperty('value');
		}
		var obj = {name : name, value : value};
		JSNISSliderTheme.changeCaptionVisual(obj);
		JSNISSliderTheme.changeToolbarVisual(obj);
		JSNISSliderTheme.changeThumbnailVisual(obj);
	},
	
	changeCaptionVisual: function(obj)
	{
		var caption = $$('.slider-caption');
		var title = $$('.slider-title');
		var description = $$('.slider-description');
		var alink = $$('.slider-a-link');
		var plink = $$('.slider-link');
		if (obj.name == 'caption_show_caption')
		{
			if (obj.value == 'show')
			{
				caption.setStyle('display', 'block');
			}
			else
			{
				caption.setStyle('display', 'none');
			}	
		}
		else if (obj.name == 'caption_title_css') 
		{		
			title.removeProperty('style');
			if ($('caption_title_showyes').checked)
			{
				title.setStyle('display', 'block');
			}
			else
			{
				title.setStyle('display', 'none');
			}			
			var objCsstitle =this.parserCss(obj.value);
			title.setStyles(objCsstitle);
		}
		else if (obj.name == 'caption_description_css') 
		{			
			description.removeProperty('style');
			if ($('caption_description_showyes').checked)
			{
				description.setStyle('display', 'block');
			}
			else
			{
				description.setStyle('display', 'none');
			}			
			var objCssDescription = this.parserCss(obj.value);
			description.setStyles(objCssDescription);
		}
		else if (obj.name == 'caption_link_css') 
		{			
			alink.removeProperty('style');
			plink.removeProperty('style');
			if ($('caption_link_showyes').checked)
			{
				plink.setStyle('display', 'block');
			}
			else
			{
				plink.setStyle('display', 'none');
			}			
			var objCssLink = this.parserCss(obj.value);
			alink.setStyles(objCssLink);
			plink.setStyles(objCssLink);
		}
		else if (obj.name == 'caption_caption_opacity')
		{
			var opacity = obj.value/100;
			caption.setStyle('opacity', opacity);
		}
		else if (obj.name == 'caption_title_show')
		{
			if (obj.value == 'yes')
			{
				title.setStyle('display', 'block');
			}
			else
			{
				title.setStyle('display', 'none');
			}			
		}
		else if (obj.name == 'caption_description_show')
		{
			if (obj.value == 'yes')
			{
				description.setStyle('display', 'block');
			}
			else
			{
				description.setStyle('display', 'none');
			}			
		}	
		else if (obj.name == 'caption_link_show')
		{
			if (obj.value == 'yes')
			{
				plink.setStyle('display', 'block');
			}
			else
			{
				plink.setStyle('display', 'none');
			}			
		}		
	},
	changeToolbarVisual: function(obj) {
		var arrow = $$('.slider-slide-arrow');
		var sliderControl = $$('.slider-control');	
		if (obj.name == 'toolbar_navigation_arrows_presentation')
		{
			if (obj.value == 'hide')
			{
				arrow.setStyle('display', 'none');
			}
			else
			{
				arrow.setStyle('display', 'block');
			}	
		}	
		else if (obj.name == 'toolbar_slideshow_player_presentation')
		{
			if (obj.value == 'hide')
			{
				sliderControl.setStyle('display', 'none');
			}
			else
			{
				sliderControl.setStyle('display', 'block');
			}	
		}		
	},
	changeThumbnailVisual: function(obj) {
		var pagination = $$('.pagination');
		var container  = $$('.jsn-slider-preview-wrapper');
		var slideDot  = $$('.info_slide_dots');
		var slideNumber  = $$('.info_slide');
		var imageNumberSelect = $$('.image_number_select');
		
		if (obj.name == 'thumbnail_panel_presentation')
		{
			if (obj.value == 'hide')
			{
				pagination.setStyle('display', 'none');
				container.setStyle('height', '340px');
			}
			else
			{
				pagination.setStyle('display', 'block');
			}	
		}
		else if (obj.name == 'thumbnail_presentation_mode')
		{
			if (obj.value == 'dots')
			{
				slideNumber.setStyle('display', 'none');
				slideDot.setStyle('display', 'block');
			}
			else if (obj.value == 'numbers')
			{
				slideDot.setStyle('display', 'none');
				slideNumber.setStyle('display', 'block');				
			}			
		}	
		else if (obj.name == 'thumnail_panel_position')
		{
			if (obj.value == 'left')
			{
				slideDot.removeStyle('right');
				slideDot.setStyle('left', 15);
				slideNumber.removeStyle('right');
				slideNumber.setStyle('left', 15);
			}	
			else if (obj.value == 'right')
			{
				slideDot.removeStyle('left');
				slideDot.setStyle('right', 15);
				slideNumber.removeStyle('left');
				slideNumber.setStyle('right', 15);
			}	
			else if (obj.value == 'center')	
			{
				slideDot.removeStyle('right');
				slideDot.setStyle('left', '27%');
				slideNumber.removeStyle('right');
				slideNumber.setStyle('left', '27%');					
			}	
		}
		else if (obj.name == 'thumbnail_active_state_color')
		{
			imageNumberSelect.setStyle('background-color', obj.value);
		}	
	},	
	trim: function(str, chars) {
		return this.ltrim(this.rtrim(str, chars), chars);
	},
	ltrim: function(str, chars) {
		chars = chars || "\\s";
		return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
	},
	rtrim: function(str, chars) {
		chars = chars || "\\s";
		return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
	},
	parserCss: function(str) {
		objCsstitle = {};
		var css = str.split(';');
		var length = css.length;
		var index  = 0;
		for (var i = 0; i < length; i++)
		{
			var value = css[i].replace(/(\r\n|\n|\r)/gm,"");
			if (value != '')
			{	
				var tmpCss = value.split(':');
				objCsstitle [this.trim(tmpCss[0], " ")] = this.trim(tmpCss[1], " ");
				index++;
			}
		}
		
		return objCsstitle;
	},
	colorSelector:function(){
		var rainBowPath = 'components/com_imageshow/assets/images/rainbow/';
		var active_state_color = new MooRainbow('active_state_color', {
			id: 'activestatecolor',
			imgPath: rainBowPath,
			startColor:JSNISSliderTheme.hextorgb($('thumbnail_active_state_color').value),
			onChange: function(color) {
				$('thumbnail_active_state_color').value = color.hex;
				$('thumbnail_active_state_color').onchange();
				$('span_thumbnail_active_state_color').setStyle('background', color.hex);
			}
		});
	},
	changeCharacterToNumber: function (n) {
		if (n == "a") { n = 10; }
		if (n == "b") { n = 11; }
		if (n == "c") { n = 12; }
		if (n == "d") { n = 13; }
		if (n == "e") { n = 14; }
		if (n == "f") { n = 15; }
		
		return n;
	},	
	hextorgb: function (strPara) {
		var casechanged=strPara.toLowerCase(); 
		var stringArray=casechanged.split("");
		if(stringArray[0] == '#'){
			for(var i = 1; i < stringArray.length; i++){			
				if(i == 1 ){
					var n1 = JSNISSliderTheme.changeCharacterToNumber(stringArray[i]);				
				}else if(i == 2){
					var n2 = JSNISSliderTheme.changeCharacterToNumber(stringArray[i]);
				}else if(i == 3){
					var n3 = JSNISSliderTheme.changeCharacterToNumber(stringArray[i]);
				}else if(i == 4){
					var n4 = JSNISSliderTheme.changeCharacterToNumber(stringArray[i]);
				}else if(i == 5){
					var n5 = JSNISSliderTheme.changeCharacterToNumber(stringArray[i]);
				}else if(i == 6){
					var n6 = JSNISSliderTheme.changeCharacterToNumber(stringArray[i]);
				}			
			}
			var returnval = ((16 * n1) + (1 * n2));
			var returnval1 = 16 * n3 + n4;
			var returnval2 = 16 * n5 + n6;
			return new Array(((16 * n1) + (1 * n2)), ((16 * n3) + (1 * n4)), ((16 * n5) + (1 * n6)));
		}
		return new Array(255, 0, 0);
	}	
};