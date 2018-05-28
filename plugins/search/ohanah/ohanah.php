<?php
/**
 * @version		$Id: categories.php 21097 2011-04-07 15:38:03Z dextercowley $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

require_once JPATH_SITE.'/components/com_content/helpers/route.php';

class plgSearchOhanah extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	function onContentSearchAreas()
	{
		$lang = &JFactory::getLanguage();
		$lang->load('com_ohanah');

		static $areas = array(
			'events' => 'OHANAH_EVENTS'
		);
		return $areas;
	}

	function onContentSearch($text, $phrase='', $ordering='', $areas=null) {
		if (is_array($areas)) 
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}

		// load plugin params info
		$limit = $this->params->def('search_limit', 50);

		if(($text = trim($text)) == '') {
			return array();
		}

		$order = 'date';
		$direction = 'desc';
		switch ( $ordering ) 
		{
			case 'alpha':
				$order = 'title';
				$direction = 'asc';
				break;
			case 'category':
				$order = array('ohanah_category_id', 'title');
				$direction = 'desc';
				break;
			case 'popular':
				$order = 'title';
				$direction = 'asc';
				break;
			case 'newest':
				$order = 'created_on';
				$direction = 'desc';
				break;
			case 'oldest':
				$order = 'created_on';
				$direction = 'asc';
		}


		$events = KService::get('com://site/ohanah.model.events')
			->set('enabled', 1)
			->textToSearch($text)
			->limit($limit)
			->sort($order)
			->direction($direction)
			->getList();


		$params = JComponentHelper::getParams('com_ohanah');
		if ($params->get('itemid')) $itemid = '&Itemid='.$params->get('itemid'); else $itemid = ''; 

		$results = array();
		foreach($events as $event)
		{
			$results[] = (object) array(
				'href'			=> JRoute::_('index.php?option=com_ohanah&view=event&id='.$event->id.$itemid),
				'title'			=> $event->title,
				'created'		=> $event->date,
				'section'		=> KService::get('com://site/ohanah.model.categories')->id($event->ohanah_category_id)->getItem()->title,
				'text'			=> $event->description,
				'browsernav'	=> 0
			);
		}
		return $results;	
	}
}