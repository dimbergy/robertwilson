<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: install.class.php 16527 2012-09-28 04:42:23Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class com_imageshowInstallerScript
{
	private $_currentVersion = '';
	private $_currentEdition = '';
	private $_parent 		 = null;
	private $_manifest 		 = null;
	private $_mainframe		 = null;
	private $_db     		 = null;

	public function __construct()
	{
		$this->_db 		  			= JFactory::getDBO();
		$this->_mainframe			= JFactory::getApplication();
	}

	public function preflight($mode, $parent)
	{
		$this->_parent 				= $parent->getParent();
		$this->_manifest    		= $this->_parent->getManifest();
		$this->_currentVersion    	= $this->_manifest->version;
		$this->_currentEdition      = $this->_manifest->edition;	
		$errorFolders				= $this->_checkFolderUnwritable();
		if(count($errorFolders)){
			$msg = '</li><li>Following folder(s) must have Writable permission during installation process:</li><li><br/></li>';
			foreach ($errorFolders as $folder){
				$msg .= '<li>'.$folder;
				if($folder == '/language' || $folder == '/administrator/language')
					$msg .= ' (including all subfolders)';
				$msg .= '</li>'	;
			}
			$msg .= '<li><br/></li><li>Please set Writable permission to appropriate folder(s) and reinstall the extension.';
			$this->_parent->abort($msg);
			return false;
		}
		if($this->_checkManifestFileExist())
		{
			$file = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_readxmldetails.php';
			if(JFile::exists($file))
			{
				include_once($file);
				$objectReadxmlDetail	= new JSNISReadXmlDetails();
				$info		 		    = $objectReadxmlDetail->parserXMLDetails();
				$this->_previousVersion	= $info['version'];
				$tmpCurrentVersion 		= (float) str_replace('.', '', $this->_currentVersion);
				$tmpPrevioustVersion 	= (float) str_replace('.', '', $this->_previousVersion);
				if ($tmpCurrentVersion < $tmpPrevioustVersion)
				{
					$msg = JText::sprintf('You cannot install an older version %s on top of the newer version %s', $this->_currentVersion, $this->_previousVersion);
					$this->_parent->abort($msg);
					return false;
				}
			}

			$fileUpgrade = $this->_parent->getPath('source').DS.'admin'.DS.'subinstall'.DS.'upgrade.helper.php';
			if(JFile::exists($fileUpgrade))
			{
				require_once $fileUpgrade;
				$objUpgradeHelper	= new JSNUpgradeHelper($this->_manifest);
				$objUpgradeHelper->executeUpgrade();
			}
			$this->_updateSchema($this->_previousVersion);
			$this->_updateMenu();
		}
		else // new install, clear session about previous core version
		{
			$session = JFactory::getSession();
			$session->set('preversion', null, 'jsnimageshow');
		}
		return true;
	}

	public function postflight($type, $parent)
	{
		//$this->_onInstallImageSource();
		$installer				= $parent->getParent();
		$objJoolaVersion    	= new JVersion();
		$packageFile 			= JPATH_ROOT.DS.'tmp'.DS.'jsn_imageshow_'.str_replace(' ', '_' , JString::strtolower($this->_currentEdition)).'_j'.$objJoolaVersion->RELEASE.'_'.$this->_currentVersion.'_install.zip';
		$packageExtDir 			= $installer->getPath('source');
		$this->_removeFile($packageFile);
		$this->_removeFolder($packageExtDir);
		$this->_onAfterSetupImageshow();
		$this->copyManifestFile();
		$redirect = JRequest::getBool('redirect', true);

		if ($redirect) {
			$this->_mainframe->redirect('index.php?option=com_imageshow&controller=installer&task=installcore&from=jinstaller');
		}
	}

	private function _removeFile($path)
	{
		if (JFile::exists($path))
		{
			JFile::delete($path);
		}
	}

	private function _removeFolder($path)
	{
		if (JFolder::exists($path))
		{
			JFolder::delete($path);
		}
	}

	private function _removeAllThemes()
	{
		jimport('joomla.installer.installer');
		require_once dirname(__FILE__).DS.'classes'.DS.'jsn_is_showcasetheme.php';
		$objJSNTheme 	= new JSNISShowcaseTheme();
		$listThemes	 	= $objJSNTheme->listThemes(false);
		$installer 		= new JInstaller();
		$extentsion 	= JTable::getInstance('extension');
		JPluginHelper::importPlugin('jsnimageshow');
		$dispatcher 	= JDispatcher::getInstance();
		if (count($listThemes))
		{
			foreach ($listThemes as $theme)
			{
				$id = trim($theme['id']);
				$extentsion->load($id);
				$extentsion->protected = 0;
				$extentsion->store();
				$dispatcher->trigger('onExtensionBeforeUninstall', array('eid' => $id));
				$result = $installer->uninstall('plugin', $id);
			}
			$this->_mainframe->enqueueMessage('Successfully removed all JSN ImageShow Theme plugins', 'message');
		}
	}

	public function uninstall()
	{
		$this->_removeAllThemes();
		$this->_removeAllImageSources();
	}
	private function _checkFolderUnwritable()
	{
		$folders = array();
		if(!is_writable(JPATH_ROOT.DS.'administrator'.DS.'components')){
			array_push($folders,'/administrator/components');	
		}	
		if(!is_writable(JPATH_ROOT.DS.'components')){
			array_push($folders,'/components');	
		}		
		if(!is_writable(JPATH_ROOT.DS.'plugins')){
			array_push($folders,'/plugins');
		}
		if(!is_writable(JPATH_ROOT.DS.'modules')){
			array_push($folders,'/modules');
		}	
		if(!is_writable(JPATH_ROOT.DS.'plugins'.DS.'content')){
			array_push($folders,'/plugins/content');
		}
		if(!is_writable(JPATH_ROOT.DS.'plugins'.DS.'system')){
			array_push($folders,'/plugins/system');	
		}	
		if(!is_writable(JPATH_ROOT.DS.'plugins'.DS.'editors-xtd')){
			array_push($folders,'/plugins/editors-xtd');
		}	
		if(!$this->_checkFolderLangPermission(JPATH_ROOT.DS.'language')){
			array_push($folders,'/language');
		}	
		if(!$this->_checkFolderLangPermission(JPATH_ROOT.DS.'administrator'.DS.'language')){
			array_push($folders,'/administrator/language');	
		}		
		return $folders;
	}
	private function _checkFolderLangPermission($base)
	{
		jimport('joomla.filesystem.folder');
		$listLanguageSupported = array('en-GB'=>'en-GB', 'de-DE'=>'de-DE', 'pl-PL'=>'pl-PL', 'fr-FR'=>'fr-FR', 'nl-NL'=>'nl-NL', 'pt-PT'=>'pt-PT', 'it-IT' => 'it-IT');
		$folders 	= JFolder::folders($base, '.', false, true);
		$check = true;
		foreach ($folders as $folder)
		{
			if (array_key_exists(basename($folder), $listLanguageSupported))
			{
				if (!is_writable($folder))
				{
					$check = false;
					break;
				}
			}
		}
		return $check;
	}
	private function _checkManifestFileExist()
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

	private function _updateMenu()
	{
		$query  = "UPDATE #__menu SET title = 'COM_IMAGESHOW' WHERE title = 'COM_IMAGESHOWS' AND link = 'index.php?option=com_imageshow'";
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	private function _onAfterSetupImageshow()
	{
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_update.php';
		$objJSNUpdate = new JSNISUpdate();
		$objJSNUpdate->eventUpdate('after');
	}

	/*private function _onInstallImageSource()
	{
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_factory.php';
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_installimagesource.php';
		$objJSNInstallImageSource = new JSNISInstallImageSource();
		$packageExtDir 			  = $this->_parent->getPath('source');
		$objJSNInstallImageSource->triggerFunction('onInstall', $packageExtDir);
		$this->_onMigrateImageSourceDB();
	}*/

	private function _removeAllImageSources()
	{
		jimport('joomla.installer.installer');
		require_once dirname(__FILE__).DS.'classes'.DS.'jsn_is_factory.php';
		require_once dirname(__FILE__).DS.'classes'.DS.'jsn_is_source.php';
		$objJSNSource 	= new JSNISSource();
		$listImageSources = $objJSNSource->getListSources();
		$installer 		= new JInstaller();
		$extentsion 	= JTable::getInstance('extension');

		if (count($listImageSources))
		{
			foreach ($listImageSources as $imageSource)
			{
				if(isset($imageSource->pluginInfo->extension_id))
				{
					$extentsion->load($imageSource->pluginInfo->extension_id);
					$extentsion->protected = 0;
					$extentsion->store();
					$result = $installer->uninstall('plugin', $imageSource->pluginInfo->extension_id);
				}
			}
			$this->_mainframe->enqueueMessage('Successfully removed all JSN ImageShow Image Source plugins', 'message');
		}
	}
	
	private function _updateSchema($preVersion)
	{
		$row = JTable::getInstance('extension');
		$eid = $row->find(array('element' => 'com_imageshow', 'type' => 'component'));
		if ($eid)
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('version_id')
				->from('#__schemas')
				->where('extension_id = ' . $eid);
			$db->setQuery($query);
			$version = $db->loadResult();
			if (is_null($version))
			{
				$query = $db->getQuery(true);
				$query->delete()
					->from('#__schemas')
					->where('extension_id = ' . $eid);
				$db->setQuery($query);	
				if ($db->Query())
				{
					$query->clear();
					$query->insert($db->quoteName('#__schemas'));
					$query->columns(array($db->quoteName('extension_id'), $db->quoteName('version_id')));
					$query->values($eid . ', ' . $db->quote($preVersion));
					$db->setQuery($query);
					$db->Query();
				}				
			}
		}
	}	
	
	public function copyManifestFile()
	{
		$pathSrcManifestFile 		= JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_imageshow' . DS . 'imageshow.xml';
		$pathDestManifestFile     	= JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_imageshow' . DS . 'com_imageshow.xml';
		JFile::copy($pathSrcManifestFile, $pathDestManifestFile);
	}	
}