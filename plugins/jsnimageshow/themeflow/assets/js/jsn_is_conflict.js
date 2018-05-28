if (typeof jQuery.noConflict() == 'function') {	
	var jsnThemeFlowjQuery = jQuery.noConflict();
	jsnThemeFlowjQuery.curCSS = jsnThemeFlowjQuery.css;
}
try {
	if (JSNISjQueryBefore && JSNISjQueryBefore.fn.jquery) {
		jQuery = JSNISjQueryBefore;
	}
} catch (e) {
	console.log(e);
}