<?php
/**
 * @version    $Id$
 * @package    JSN_EasySlider
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * JSN EasySlider system plugin.
 *
 * @package  JSN_EasySlider
 * @since    1.1.6
 */
class PlgSystemJSNEasySliderTemplate extends JPlugin
{
	/**
	 * Joomla application object.
	 *
	 * @var object
	 */
	private $_app = null;

	/**
	 * Component that rely on this system plugin for working properly.
	 *
	 * @var string
	 */
	private $_ext = 'com_easyslider';

	/**
	 * Register onAfterInitialise event handler.
	 *
	 * @return  void
	 */
	public function onAfterInitialise()
	{
		// Get Joomla application object
		$this->_app = JFactory::getApplication();

	}
}
