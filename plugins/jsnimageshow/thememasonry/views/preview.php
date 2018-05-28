<?php
/**
 * @version    preview.php$
 * @package    4.9.2
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2015 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
$path = JUri::root(). 'plugins/jsnimageshow/thememasonry/assets';
?>
<style>

	/* ---- isotope ---- */

	.grid {
		background: none;
	}

	/* clear fix */
	.grid:after {
		content: '';
		display: block;
		clear: both;
	}

	/* ---- .grid-item ---- */

	.grid-sizer,
	.grid-item-demo {
		width: 33.333%;
	}

	.grid-item-demo {
		float: left;
	}

	.jsn-thememasonry-container .wrapper
	{
		width: 300px;
	}
	.grid-item {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}

	body { font-family: sans-serif; }

	/* ---- grid ---- */

	.grid {
		/* center */
		margin: 0 auto;
	}

	/* clearfix */
	.grid:after {
		content: '';
		display: block;
		clear: both;
	}

	/* ---- grid-item ---- */

	.grid-item {
		width: <?php echo '180px';//$items->column_width.'px';?>;
		height: 120px;
		float: left;
		background: #2898cc;
		border: 2px solid #333;
		border-color: hsla(0, 0%, 0%, 0.5);
		border-radius: 5px;
	}

	.grid-item--height2 { height: 200px; }
	.grid-item--height3 { height: 260px; }
	#jsn-preview-wrapper .wrapper{
		width: 920px;
	}
	.grid-item a {
		position: absolute;
		width: 100%;
		height: 100%;
		left: 0;
		top: 0;
	}
</style>
<div id="jsn-thememasonry-container"  class="jsn-thememasonry-container">
	<div class="demo">
		<div class="jsn-thememasonry-wrapper">
			<div class="grid">
				<div class="grid-sizer"></div>
				<div class="grid-item"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></a></div>
				<div class="grid-item grid-item--height2"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></a></div>
				<div class="grid-item grid-item--height3"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></a></div>
				<div class="grid-item grid-item--height2"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></a></div>
				<div class="grid-item"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></div></a>
				<div class="grid-item"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></div></a>
				<div class="grid-item"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></div></a>
				<div class="grid-item grid-item--height2"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></a></div>
				<div class="grid-item grid-item--height3"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></a></div>
				<div class="grid-item"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></div></a>
				<div class="grid-item grid-item--height2"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></a></div>
				<div class="grid-item"></div><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></a>
				<div class="grid-item grid-item--height2"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></a></div>
				<div class="grid-item"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></a></div>
				<div class="grid-item"><a href="javascript:void(0);" onclick="jQuery.JSNISThemeMasonry.openTab('thememasonry-layout-tab');"></a></div>
			</div>
		</div>
	</div>
</div>

