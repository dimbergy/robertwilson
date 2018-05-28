<?php
/**
 * @version    thememasonry.php$
 * @package    JSNIMAGESHOW
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2015 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
if (!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_factory.php');

class plgJSNImageshowThemeMasonry extends JPlugin
{
	var $_showcaseThemeName = 'thememasonry';
	var $_showcaseThemeType = 'jsnimageshow';
	var $_pathAssets        = 'plugins/jsnimageshow/thememasonry/assets/';
	var $_tableName         = 'theme_masonry';

	function onLoadJSNShowcaseTheme($name, $themeID = 0)
	{
		if ($name != $this->_showcaseThemeName)
		{
			return false;
		}

		JPlugin::loadLanguage('plg_' . $this->_showcaseThemeType . '_' . $this->_showcaseThemeName);

		ob_start();
		$document = JFactory::getDocument();
		$document->addStyleSheet(JUri::root() . $this->_pathAssets . 'css/admin/style.css');
		$document->addScript(JUri::root() . $this->_pathAssets . 'js/imagesloaded.min.js');
		$document->addScript(JUri::root() . $this->_pathAssets . 'js/masonry.min.js');
		$document->addScript(JUri::root() . $this->_pathAssets . 'js/jsn_is_thememasonry_setting.js');
		include(dirname(__FILE__) .DS.'helper'.DS.'helper.php');
		include(dirname(__FILE__) .DS.'views'.DS.'default.php');
		return ob_get_clean();
	}

	function onDisplayJSNShowcaseTheme($args)
	{
		if ($args->theme_name != $this->_showcaseThemeName)
		{
			return false;
		}
		JPlugin::loadLanguage('plg_' . $this->_showcaseThemeType . '_' . $this->_showcaseThemeName);
		$basePath           = JPATH_PLUGINS . DS . $this->_showcaseThemeType . DS . $this->_showcaseThemeName;
		$objThemeDisplay    = JSNISFactory::getObj('classes.jsn_is_masonrydisplay', null, null, $basePath);
		$result             = $objThemeDisplay->display($args);
		return $result;
	}

	function onExtensionBeforeUninstall($eid)
	{
		$query = 'DROP TABLE IF EXISTS `#__imageshow_'. $this->_tableName . '`';
		$db = JFactory::getDbo();
		$db->setQuery($query);
		$db->execute();
	}

	public function getLanguageJSNPlugin()
	{
		$language = array();
		$language['admin']['file'] = array('plg_' . $this->_showcaseThemeType . '_' . $this->_showcaseThemeName . '.ini');
		$language['admin']['path'] = array(dirname(__FILE__) . '/languages');
		return $language;
	}

	function listThememasonryTable()
	{
		return array('#__imageshow_theme_masonry');
	}
}