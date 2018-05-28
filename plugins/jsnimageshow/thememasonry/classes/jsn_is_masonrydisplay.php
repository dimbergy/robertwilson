<?php
/**
 * @version    jsn_is_masonrydisplay.php$
 * @package    4.9.2
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2015 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
if (!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}
class JSNISMasonryDisplay extends JObject
{
	var $_themename     = 'thememasonry';
	var $_themetype     = 'jsnimageshow';
	var $_assetsPath    = 'plugins/jsnimageshow/thememasonry/assets/';

	public function __construct(){}

	public function display($args)
	{
		$string = '';
		$args->uri  = JUri::base();
		$string .= $this->standardLayout($args);
		$string .= $this->displaySEOContent($args);
		return $string;
	}

	public function standardLayout($args)
	{
		$objJSNShowList         = JSNISFactory::getObj('classes.jsn_is_showlist');
		$showlistInfo           = $objJSNShowList->getShowListByID($args->showlist['showlist_id'], true);
		$dataObj                = $objJSNShowList->getShowlist2JSON($args->uri, $args->showlist['showlist_id']);
		$images                 = $dataObj->showlist->images->image;
		$document               = JFactory::getDocument();
		$plugin                 = false;

		if (!count($images)) return '';
		if (!empty($showlistInfo['image_loading_order']))
		{
			switch ($showlistInfo['image_loading_order'])
			{
				case 'backward':
					krsort($images);
					$images = array_values($images);
					break;
				case 'random':
					shuffle($images);
					break;
				case 'forward':
					ksort($images);
			}
		}

		$pluginOpenTagDiv = '';
		$pluginCloseTagDiv = '';

		if (isset($args->plugin) && $args->plugin == true)
		{
			$plugin = true;
		}

		$path = JPath::clean(JPATH_PLUGINS . '/' . $this->_themetype. '/' .$this->_themename. '/' .'models');
		JModelLegacy::addIncludePath($path);
		$model = JModelLegacy::getInstance($this->_themename);
		$themeData = $model->getTable($args->theme_id);
		$themeDataJson = json_encode($themeData);
		$descriptionLenghtLimit	= (int) trim($themeData->caption_description_length_limitation);

		$jsResize = '';

		$document->addStyleSheet(JUri::root() . $this->_assetsPath . 'css/style.css');
		$document->addStyleSheet(JUri::root() . $this->_assetsPath . 'css/fancybox/jquery.fancybox-1.3.4.css');
		$this->loadjQuery();
		$document->addScript(JUri::root() . $this->_assetsPath . 'js/jsn_is_noconflict.js');
		$document->addScript(JUri::root() . $this->_assetsPath . 'js/imagesloaded.min.js');
		$document->addScript(JUri::root() . $this->_assetsPath . 'js/masonry.min.js');
		$document->addScriptDeclaration('
			if (typeof jQuery.fancybox != "function") {
				document.write(\'<script type="text\/javascript" src="'. JUri::root() .$this->_assetsPath.'js'.'/jquery.fancybox-1.3.4.js"><\/script>\');
			}
		');
		$document->addScript(JUri::root() . $this->_assetsPath . 'js/jsn_is_thememasonry.js');

		$percent  	= strpos($args->width, '%');

		//if ($plugin)
		//{
		$pluginOpenTagDiv 	= '<div style="max-width:'  .$args->width . ((!$percent) ? 'px' : '') . '; margin: 0 auto;">';
		$pluginCloseTagDiv 	= '</div>';
		$percent = true;
		$args->width = '100%';
		//}
		$imageFeatures      = array();
		$gutter 			= !empty($themeData->gutter) ? $themeData->gutter : '0';
		$columnWidth 		= !empty($themeData->column_width) && $themeData->column_width != '0' ? $themeData->column_width : "\".jsn-is-msnry-grid-sizer\"";
		$isFitWidth 		= !empty($themeData->is_fit_width) ? $themeData->is_fit_width : 'true';
		$transitionDuration = !empty($themeData->transition_duration) && $themeData->transition_duration != '0' ? $themeData->transition_duration.'s' : '0';
		$imageSource 		= ($themeData->image_source == 'thumbnail') ? 'thumbnail' : 'image';
		$imageLink 			= ($themeData->image_click_action == 'show-original-image') ? 'image' : 'link';
		$openLinkIn 		= ($themeData->open_link_in == 'current_browser') ? '' : 'target="_blank"';
		$layoutType         = !empty($themeData->layoutType) ? $themeData->layoutType : 'fixed';
		$paginationType     = !empty($themeData->pagination_type) ? $themeData->pagination_type : 'all';
		$number_load_image  = !empty($themeData->number_load_image) ? $themeData->number_load_image : '6';
		$fitwidth           = $isFitWidth == true && $layoutType == 'fluid' ? "jsn-is-msnry-fluid-width" : "";
		$width 			   	= ($percent === false) ? $args->width . 'px' : $args->width;
		$wrapID 			= 'jsn-' . $this->_themename . '-container-' . $args->random_number;
		$galleryID 			= 'jsn-' . $this->_themename . '-gallery-' . $args->random_number;
		$fitwidth           = $isFitWidth == true && $themeData->layout_type == "fluid" ? "jsn-is-msnry-fluid-width" : "";
		$feature_image      = $themeData->feature_image;
		if ($themeData->is_fit_width == true && $themeData->layout_type == "fluid")
		{
			$columnWidth = "\".jsn-is-msnry-grid-sizer\"";
		}
		$html = '<script type="text/javascript">
					jsnThemeMasonryjQuery(function () {
						jsnThemeMasonryjQuery(window).load(function () {
							var paginationType = "'.$paginationType.'";
							var numberLoadImage = "'.$number_load_image.'";
							var layoutType = "'.$layoutType.'";
					        var border = "'.$themeData->image_border.'";
					        function msrImgResize()
					        {
						        if (layoutType == "fixed") {
						            jsnThemeMasonryjQuery("#' . $galleryID . ' img").each(function () {
						                var maxWidth = ' . $columnWidth . ';
						                var ratio = 0;
						                var img = jsnThemeMasonryjQuery(this);
						                if (img.width() > maxWidth) {
						                    ratio = img.height() / img.width();
						                    img.css("height", (maxWidth * ratio) + "px");
						                    img.css("width", maxWidth - (border * 2) + "px")
						                }
						            });
						        }
					    	}
							msrImgResize()

					        var container = document.querySelector("#'.$galleryID .'.jsn-is-msnry-grid");
					            var $grid = new Masonry(container, {
					                itemSelector: ".jsn-is-msnry-grid-item",
					                percentPosition: false,
					                columnWidth: ' . $columnWidth . ',
					                animate: true,
					                isFitWidth: ' . $isFitWidth . ',
					                gutter: ' . $gutter . ',
					                transitionDuration: "' . $transitionDuration . '",
					                isResizeBound: true,
					                isInitLayout: true
					            });
					        function jsnMasonryInit() {
					            imagesLoaded(container, function () {
					                $grid.layout();
					                jsnThemeMasonryjQuery("#' . $wrapID . '").css("visibility", "visible");
					                jsnThemeMasonryjQuery("#'. $wrapID .' ").removeClass("jsnmsr-loading").addClass("jsnmsr-loaded");
					            });
					            jsnThemeMasonryjQuery(window).on("resize", function(){
									$grid.layout();
								});
					        }
					        function jsnMasonryLoadMore(){
						        var img = jsnThemeMasonryjQuery("#'.$wrapID.' .jsn-is-msnry-grid-item");
						        jsnThemeMasonryjQuery("#'.$wrapID.' .jsnmsr-loading").hide();
								img.hide();
								var currentIndex = 0;
								var countImg =  img.length;
								var lastclick;
								var lastTimeout;
								var lastScroll = 0;
					            jsnThemeMasonryjQuery("#'.$wrapID.' .jsn-is-loadmore a").on("click", function () {
					                lastclick = Date.now();
									var fromIndex = currentIndex;
									currentIndex += parseFloat(numberLoadImage);
									if (currentIndex > countImg - 1)
									{
										currentIndex = countImg;
										jsnThemeMasonryjQuery("#'.$wrapID.' .jsn-is-loadmore").hide();
										jsnThemeMasonryjQuery("#'.$wrapID.' .jsnmsr-loading").hide();
									}
					                var items = "";
									for ( var i=fromIndex; i < currentIndex; i++ ) {
										items += jsnThemeMasonryjQuery(img[i]).show();
										jsnMasonryInit();
									}
					            }).trigger("click");
					            if ( paginationType == "load_more")
					            {
					                jsnThemeMasonryjQuery("#'.$wrapID.' .jsnmsr-loading").hide();
					            	jsnThemeMasonryjQuery("#'.$wrapID.' .jsn-is-loadmore a").click(function(){
					            	jsnThemeMasonryjQuery("#'.$wrapID.' .jsnmsr-loading").show();
					            	    clearTimeout(lastTimeout)
										lastTimeout = setTimeout(function(){
											jsnThemeMasonryjQuery("#'.$wrapID.' .jsnmsr-loading").hide();
										}, 500)
					            	})
					            }
					            if ( paginationType == "infinite_scroll")
					            {
					                jsnThemeMasonryjQuery("#'.$wrapID.' .jsn-is-loadmore").hide();

					                jsnThemeMasonryjQuery(window).on("scroll", function(e) {

					                    clearTimeout(lastTimeout);
										lastTimeout = setTimeout(function(){
											jsnThemeMasonryjQuery("#'.$wrapID.' .jsnmsr-loading").hide();
										}, 500);

									    var currentScroll = jsnThemeMasonryjQuery(this).scrollTop();
										if (currentScroll > lastScroll)
										{
											if(Date.now() - lastclick > 2000)
						                    {
						                        jsnThemeMasonryjQuery("#'.$wrapID.' .jsnmsr-loading").show();
						                        jsnThemeMasonryjQuery("#' . $wrapID . ' .jsn-is-loadmore a").trigger("click")
						                    }
						                    lastScroll = currentScroll
										}
										else
										{
								           jsnThemeMasonryjQuery("#'.$wrapID.' .jsnmsr-loading").hide();
								           lastScroll = currentScroll
										}
					                })
					            }
					        }

					        jsnMasonryInit();
					        if ( paginationType != "all")
					        {
					            jsnMasonryLoadMore();
					        }

					        jsnThemeMasonryjQuery("ul.nav-tabs li").on("click", function () {
					        	jsnThemeMasonryjQuery("#'. $wrapID .' ").addClass("jsnmsr-loading").removeClass("jsnmsr-loaded");
					        	var img = jsnThemeMasonryjQuery("#'.$wrapID.' .jsn-is-msnry-grid-item");
								img.hide();
				        		setTimeout(function(){
				        			img.show();
			                        var container = document.querySelector("#'.$galleryID .'.jsn-is-msnry-grid");
						            var $grid = new Masonry(container, {
						                itemSelector: ".jsn-is-msnry-grid-item",
						                percentPosition: false,
						                columnWidth: ' . $columnWidth . ',
						                animate: true,
						                isFitWidth: ' . $isFitWidth . ',
						                gutter: ' . $gutter . ',
						                transitionDuration: "' . $transitionDuration . '",
						                isResizeBound: true,
						                isInitLayout: true
						            });
				        			jsnMasonryInit();
				        			msrImgResize();
				        		},500)
					        });
					        jsnThemeMasonryjQuery("#'.$galleryID.'").jsnthememasonry("'.$args->random_number.'", '.$themeDataJson.');
					    });
					});
				</script>';
		$css = 'html, body {overflow-x: hidden;} ' ."\n";
		$css .= '#'. $wrapID. '{width:'. $args->width .';margin: 0 auto;}';
		$css .= '#'.$wrapID . '{
			margin: 0 auto;
			visibility: hidden;
		}';
		if ($gutter != '' && ($gutter != '0' || $gutter != 0))
		{
			$css .= '#'.$wrapID . ' .jsn-is-msnry-grid-item {margin-bottom: ' . $gutter . 'px; margin-left: ' . $gutter . 'px; overflow: hidden; }' . "\n";
		}
		$css .= '#'.$galleryID . ' .jsn-is-msnry-grid-item {border:'.$themeData->image_border.'px solid '.$themeData->image_border_color.'}'. "\n";

		$css .= '#'.$galleryID . ' .jsn-is-msnry-grid-item {
				-webkit-border-radius: '.$themeData->image_rounded_corner.'px;
				-moz-border-radius: '.$themeData->image_rounded_corner.'px;
				border-radius: '.$themeData->image_rounded_corner.'px;
				}'. "\n";

		$css .= '#'.$galleryID . ' .jsn-is-msnry-grid-item img {
				-webkit-border-radius: '. ($themeData->image_rounded_corner / 2) .'px;
				-moz-border-radius: ' . ($themeData->image_rounded_corner / 2) .'px;
				border-radius: '.($themeData->image_rounded_corner / 2).'px;
				}'. "\n";
		$css .= '
		@media only screen and (max-width: 640px) {
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-item,
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-sizer
		    {
		        width: 100%;
		    }
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid
		    {
		        width: 100% !important;
		    }
		}
		@media (min-width:640px) and (max-width:959px){
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-item,
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-sizer
		    {
		        width: calc(33.33333% - '.$gutter.'px);
		    }
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-item.jsn-is-msnry-grid-item-width2
		    {
		        width: calc(66.66666% - '.$gutter.'px) !important;
		    }
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid
		    {
		        width: 100% !important;
		    }
		}
		@media (min-width:960px) and (max-width:1200px){
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-item,
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-sizer
		    {
		        width: calc(33.33333% - '.$gutter.'px);
		    }
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-item.jsn-is-msnry-grid-item-width2
		    {
		        width: calc(66.66666% - '.$gutter.'px) !important;
		    }
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid
		    {
		        width: 100% !important;
		    }
		}
		@media (min-width:1201px) and (max-width:1600px){
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-item,
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-sizer
		    {
		        width: calc(25% - '.$gutter.'px);
		    }
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-item.jsn-is-msnry-grid-item-width2
		    {
		        width: calc(50% - '.$gutter.'px) !important;
		    }
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid
		    {
		        width: 100% !important;
		    }
		}
		@media  (min-width:1601px) and (max-width:1920px){
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-item,
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-sizer
		    {
		        width: calc(20% - '.$gutter.'px);
		    }
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-item.jsn-is-msnry-grid-item-width2
		    {
		        width: calc(40% - '.$gutter.'px) !important;
		    }
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid
		    {
		        width: 100% !important;
		    }
		}
		@media (min-width:1921px){
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-item,
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-sizer
		    {
		        width: calc(16.6666667% - '.$gutter.'px);
		    }
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid-item.jsn-is-msnry-grid-item-width2
		    {
		        width: calc(33.3333334% - '.$gutter.'px) !important;
		    }
		    #'.$wrapID.'.jsn-is-msnry-fluid-width .jsn-is-msnry-grid
		    {
		        width: 100% !important;
		    }
		}';
//		if( $columnWidth != "\".jsn-is-msnry-grid-sizer\"" && $columnWidth != 0)
//		{
//
//			if($gutter != 0)
//			{
//				$css .= '#'.$galleryID . ' .jsn-is-msnry-grid-item {
//					width: '.(int)$columnWidth.'px;
//				}';
//				$width = ((int)$columnWidth * 2) + (int)$gutter;
//				$css .= '#'.$galleryID . ' .jsn-is-msnry-grid-item.jsn-is-msnry-grid-item-width2{
//					width: '.$width.'px !important;
//				}';
//				$css .= '#'.$galleryID . ' .jsn-is-msnry-grid-item.jsn-is-msnry-grid-item-width2 img{
//				width: '.$width.'px !important;
//				height: auto !important;
//				}';
//			}
//			else
//			{
//				$css .= '#'.$galleryID . ' .jsn-is-msnry-grid-item {
//					width: '.(int)$columnWidth.'px;
//				}';
//				$width = ((int)$columnWidth * 2);
//				$css .= '#'.$galleryID . ' .jsn-is-msnry-grid-item.jsn-is-msnry-grid-item-width2{
//				width: '.$width.'px !important;
//				}';
//				$css .= '#'.$galleryID . ' .jsn-is-msnry-grid-item.jsn-is-msnry-grid-item-width2 img{
//				width: '.$width.'px !important;
//				height: auto !important;
//				}';
//			}
//
//		}
		if($themeData->show_caption == 'yes' && ($themeData->caption_show_title == 'yes' || $themeData->caption_show_description == 'yes'))
		{
			$backgroundColor	= $this->hex2rgb($themeData->caption_background_color);
			$backgroundOpacity	= (float) $themeData->caption_opacity / 100;
			$css				.= '.jsn-thememasonry-gallery-info-'.$args->random_number.' {padding:5px;display:block;background-color:rgb(' . $backgroundColor . ');background-color:rgba(' . $backgroundColor . ',' . $backgroundOpacity . ');}';
			$css .= '.jsn-thememasonry-gallery-info-title-'.$args->random_number.' {' . $themeData->caption_title_css . '}';
			$css .= '.jsn-thememasonry-gallery-info-description-'.$args->random_number.' {' . $themeData->caption_description_css . '}';
		}
		$document->addStyleDeclaration($css);
		$html .= $pluginOpenTagDiv. '<div id="'.$wrapID.'" class="jsnmsr-loading '.$fitwidth.'">';
		$html .= '<div id="'.$galleryID.'" class="jsn-is-msnry-grid fluid">';
		$html .= '<div class="jsn-is-msnry-grid-sizer"></div>';
		if($feature_image)
		{
			$feature_image = explode(',', $feature_image);

			foreach($feature_image as $imageFeature)
			{
				$imageFeatures[] = (int) $imageFeature - 1;
			}
		}
		foreach ($images as $i => $image)
		{
			$caption ='';
			$title = htmlspecialchars($image->title, ENT_QUOTES);
			if ($themeData->show_caption == 'yes')
			{
				if ($themeData->caption_show_title == 'yes' && $image->title != '')
				{
					$caption .= '<div class="jsn-thememasonry-gallery-info-title-' . $args->random_number . '">' . $title . '</div>';
				}
				if($themeData->caption_show_description == 'yes' && $image->description != '')
				{
					$desc  		= $this->_wordLimiter($image->description, $descriptionLenghtLimit);
					$caption 	.= '<div class="jsn-thememasonry-gallery-info-description-' . $args->random_number . '">' . $desc . '</div>';
				}

				$caption = htmlspecialchars($caption, ENT_QUOTES);
			}
			if ($themeData->image_click_action == 'no-action')
			{
				$imageClickAction = '';
				$openLinkIn = '';
			}
			else
			{
				$imageClickAction = 'href="' . $image->$imageLink . '"';
			}
			$imageWidth2 = "";
			if( $feature_image)
			{
				$feature_image = explode(',', $feature_image);
			}

			if (in_array($i, $imageFeatures))
			{
				$imageWidth2 = "jsn-is-msnry-grid-item-width2";
			}
			$alt = htmlentities($image->title, ENT_QUOTES, 'UTF-8', false);
			$html .= '<div class="jsn-is-msnry-grid-item '.$imageWidth2.'">';
			$html .= '<a class="jsn-fancybox-item" rel="gallery' .$wrapID. '"'. $imageClickAction.' '.$openLinkIn.' title="' . $title . '" rev=\'' . $caption . '\'>';
			$html .= '<img class="jsn-ismsnry-'.$i.'" src="'.$image->$imageSource.'" alt="'.$alt.'"/>';
			$html .= '</a>';
			$html .= '</div>';
		}
		$html .= '</div>';
		if ( $paginationType != 'all')
		{
			$html .= '<div class="jsnmsr-loading" style="margin-bottom: 15px"></div>';
			$html .= '<div class="jsn-is-loadmore"><a class="btn btn-primary" href="javascript:void(0);">' . JText::_("Load More") . '</a></div>';
		}
		$html .= '</div>' . $pluginCloseTagDiv;
		return $html;
	}

	private function _wordLimiter($str, $limit = 100, $endChar = '&#8230;')
	{
		if (trim($str) == '')
		{
			return $str;
		}
		$append = '';
		$str 	= strip_tags(trim($str), '<b><i><s><strong><em><strike><u><br><span>');
		$words 	= explode(" ", $str);
		if(count($words) > $limit)
		{
			$append = $endChar;
		}

		return implode(" ", array_splice($words, 0, $limit)) . $append;
	}

	private function hex2rgb($hex)
	{
		$hex = str_replace("#", "", $hex);
		if (strlen($hex) == 3)
		{
			$r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
			$g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
			$b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
		}
		else
		{
			$r = hexdec(substr($hex, 0, 2));
			$g = hexdec(substr($hex, 2, 2));
			$b = hexdec(substr($hex, 4, 2));
		}
		$rgb = array($r, $g, $b);
		return implode(",", $rgb);
	}

	function loadjQuery()
	{
		$loadJoomlaDefaultJQuery = true;
		if(class_exists('JSNConfigHelper'))
		{
			$objConfig = JSNConfigHelper::get('com_imageshow');
			if ($objConfig->get('jquery_using') != 'joomla_default')
			{
				$objUtils = JSNISFactory::getObj('classes.jsn_is_utils');

				if (method_exists($objUtils, 'loadJquery'))
				{
					$objUtils->loadJquery();
				}
				else
				{
					$document = JFactory::getDocument();
					$document->addScript(JUri::root() . $this->_assetsPath . '/js/jsn_is_jquery_safe.js');
					JHtml::_('jquery.framework');
				}
			}
		}
	}

	function displaySEOContent($args)
	{
		$html = '<div class="jsn-'.$this->_themename.'-seocontent">'."\n";

		if (count($args->images))
		{
			$html .= '<div>';
			$html .= '<p>'. @$args->showlist['showlist_title'] . '</p>';
			$html .= '<p>'. @$args->showlist['description'] . '</p>';
			$html .= '<ul>';

			for ($i = 0, $n = count($args->images); $i < $n; $i++)
			{
				$row = & $args->images[$i];
				$html .= '<li>';
				if ($row->image_title != '')
				{
					$html .= '<p>' . $row->image_title . '</p>';
				}
				if ($row->image_description != '')
				{
					$html .= '<p>' . $row->image_description . '</p>';
				}
				if ($row->image_link != '')
				{
					$html .= '<p><a href="'.$row->image_link.'">' . $row->image_link . '</a></p>';
				}
				$html .= '</li>';
			}
			$html .= '</ul></div>';
		}
		$html .= '</div>'. "\n";
		return $html;
	}
}