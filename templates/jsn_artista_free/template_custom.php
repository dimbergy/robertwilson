<?php
$jsnutils	= JSNTplUtils::getInstance();
$doc		= $this->_document;

// Count module instances
$doc->hasRight		= $jsnutils->countModules('right');
$doc->hasLeft		= $jsnutils->countModules('left');
$doc->hasPromo		= $jsnutils->countModules('promo');
$doc->hasPromoLeft	= $jsnutils->countModules('promo-left');
$doc->hasPromoRight	= $jsnutils->countModules('promo-right');
$doc->hasInnerLeft	= $jsnutils->countModules('innerleft');
$doc->hasInnerRight	= $jsnutils->countModules('innerright');

// Define template colors
$doc->templateColors = array('blue', 'red', 'green', 'violet', 'orange', 'grey');

if (isset($doc->sitetoolsColorsItems))
{
	$this->_document->templateColors = $doc->sitetoolsColorsItems;
}

// Apply K2 style
if ($jsnutils->checkK2())
{
	$doc->addStylesheet($doc->templateUrl . "/ext/k2/jsn_ext_k2.css");
}

// Start generating custom styles
$customCss	= '';

// Process TPLFW v2 parameter
if (isset($doc->customWidth))
{
	if ($doc->customWidth != 'responsive')
	{
		$customCss .= '
	#jsn-pos-topbar,
	#jsn-topheader-inner,
	#jsn-header-inner,
	#jsn-promo-inner,
	#jsn-pos-content-top,
	#jsn-content_inner,
	#jsn-pos-content-bottom-above,
	#jsn-content-bottom-inner,
	#jsn-pos-content-bottom-under,
	#jsn-usermodules3-inner,
	#jsn-footer-inner {
		width: ' . $doc->customWidth . ';
		min-width: ' . $doc->customWidth . ';
	}';
	}
}

$doc->addStyleDeclaration(trim($customCss, "\n"));
