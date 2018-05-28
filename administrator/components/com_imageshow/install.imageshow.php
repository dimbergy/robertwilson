<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: install.imageshow.php 9545 2011-11-10 01:58:24Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
require_once dirname(__FILE__).DS.'classes'.DS.'jsn_is_factory.php';
require_once dirname(__FILE__).DS.'subinstall'.DS.'subinstall.php';
require_once dirname(__FILE__).DS.'classes'.DS.'jsn_is_upgradedbutil.php';
@ini_set('display_errors', 0);
function com_install()
{
	$parent 			= JInstaller::getInstance();
	$manifest 			= $parent->getManifest();
	$session 			= JFactory::getSession();
    $objJSNSubInstaller	= new JSNSubInstaller();
    $ret 				= $objJSNSubInstaller->install();
	$errorArray 		= $objJSNSubInstaller->getError();
	$session->set('jsn_install_error', $errorArray);

	$resultCheckManifestFile = checkManifestFileExist();
	if ($resultCheckManifestFile == true)
	{
		beforeUpgradeImageShow();
		$objUpgradeDBUtil	= new JSNISUpgradeDBUtil($manifest);
		$objUpgradeDBUtil->executeUpgradeDB();
	}
}

function checkManifestFileExist()
{
	jimport('joomla.filesystem.file');
	$pathOldManifestFile		= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'com_imageshow.xml';
	$pathNewManifestFile 		= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'imageshow.xml';
	if (JFile::exists($pathNewManifestFile) || JFile::exists($pathOldManifestFile))
	{
		return true;
	}
	return false;
}

function beforeUpgradeImageShow()
{
	$objJSNBackUp = JSNISFactory::getObj('classes.jsn_is_backup');
	$objJSNBackUp->createBackUpFileForMigrate();
	$objJSNBackUp->setSourceFromVersion3xx();
}
