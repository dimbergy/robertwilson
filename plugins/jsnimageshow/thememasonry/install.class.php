<?php
/**
 * @version    install.class.php$
 * @package    JSNIMAGESHOW
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2015 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC')  or die('Restricted access');
class plgjsnimageshowThemeMasonryInstallerScript
{
	public function __construct(){}

	public function preflight($mode, $parent)
	{
		$this->_updateSchema();
	}

	private function _updateSchema()
	{
		$row = JTable::getInstance('extension');
		$eid = $row->find(array('element' => 'thememasonry', 'type' => 'plugin'));
		if($eid)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('version_id');
			$query->from('#__schemas');
			$query->where('extension_id = ' . $eid);
			$db->setQuery($query);
			$version = $db->loadResult();

			if (is_null($version))
			{
				$info = $this->_getInfo($eid);
				$info = json_decode($info->manifest_cache);
				$query = $db->getQuery(true);
				$query->delete();
				$query->from('#__schemas');
				$query->where('extension_id = ' . $eid);
				$db->setQuery($query);
				if($db->execute())
				{
					$query->clear();
					$query->insert($db->quoteName('#__schemas'));
					$query->columns(array($db->quoteName('extension_id'), $db->quoteName('version_id')));
					$query->values($eid . ', ' . $db->quote($info->version));
					$db->setQuery($query);
					$db->execute();
				}
			}

		}
	}

	private function _getInfo($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__extensions'));
		$query->where('element = \'thememasonry\' AND type=\'plugin\' AND folder=\'imageshow\' AND extension_id = ' .$id);
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
}