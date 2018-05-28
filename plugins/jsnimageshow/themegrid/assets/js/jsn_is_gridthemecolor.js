/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Theme Classic
 * @version $Id: jsn_is_gridthemecolor.js 14477 2012-07-27 10:07:17Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISGridThemeColor = {	
	colorSelector:function(){
		var rainBowPath = 'components/com_imageshow/assets/images/rainbow/';
		var active_thumbnail_border_color = new MooRainbow('active_thumbnail_border_color', {
			id: 'active_thumbnail_border_color',
			imgPath: rainBowPath,
			startColor:JSNISGridThemeColor.hextorgb($('thumbnail_border_color').value),
			onChange: function(color) {
				$('thumbnail_border_color').value = color.hex;
				$('span_thumbnail_active_state_color').setStyle('background', color.hex);
				$$('.jsn-themegrid-box').setStyle('border-color', color.hex);
			}
		});
		var active_background_color = new MooRainbow('active_background_color', {
			id:'active_background_color',
			imgPath: rainBowPath,
			startColor:JSNISGridThemeColor.hextorgb($('background_color').value),
			onChange: function(color) {
				$('background_color').value = color.hex;
				$('span_background_active_state_color').setStyle('background', color.hex);
				$('jsn-themegrid-container').setStyle('background-color', color.hex);
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
					var n1 = JSNISGridThemeColor.changeCharacterToNumber(stringArray[i]);				
				}else if(i == 2){
					var n2 = JSNISGridThemeColor.changeCharacterToNumber(stringArray[i]);
				}else if(i == 3){
					var n3 = JSNISGridThemeColor.changeCharacterToNumber(stringArray[i]);
				}else if(i == 4){
					var n4 = JSNISGridThemeColor.changeCharacterToNumber(stringArray[i]);
				}else if(i == 5){
					var n5 = JSNISGridThemeColor.changeCharacterToNumber(stringArray[i]);
				}else if(i == 6){
					var n6 = JSNISGridThemeColor.changeCharacterToNumber(stringArray[i]);
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