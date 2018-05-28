<?php
/**
 * @version    thememasonry.php$
 * @package    4.9.2
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2015 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

defined('_JEXEC') or die('Restricted access');
if(!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}
JTable::addIncludePath(JPATH_ROOT.DS.'plugins'.DS.'jsnimageshow'.DS.'thememasonry'.DS.'tables');
class ThemeMasonry
{
	var $_pluginName        = 'thememasonry';
	var $_pluginType        = 'jsnimageshow';
	var $_table             = 'theme_masonry';

	function &getInstance()
	{
		static $themeMasonry;
		if($themeMasonry == null)
		{
			$themeMasonry = new ThemeMasonry();
		}
		return $themeMasonry;
	}

	function __construct()
	{
		$pathModelShowcaseTheme = JPATH_PLUGINS.DS.$this->_pluginType.DS.$this->_pluginName.DS.'models';
		$pathTableShowcaseTheme = JPATH_PLUGINS.DS.$this->_pluginType.DS.$this->_pluginName.DS.'tables';
		JModelLegacy::addIncludePath($pathModelShowcaseTheme);
		JTable::addIncludePath($pathTableShowcaseTheme);
	}

	function _prepareSaveData($data)
	{
		if (!empty($data))
		{
			return $data;
		}
		return false;
	}

	function getTable($themeID = 0)
	{
		$showcaseThemeTable = JTable::getInstance($this->_pluginName, 'Table');
		if (!$showcaseThemeTable->load((int) $themeID))
		{
			// need to load default value when theme record has been deleted
			$showcaseThemeTable = JTable::getInstance($this->_pluginName, 'Table');
			$showcaseThemeTable->load(0);
		}
		return $showcaseThemeTable;
	}

	function _prepareDataJSON($themeID, $URL)
	{
		return true;
	}
}