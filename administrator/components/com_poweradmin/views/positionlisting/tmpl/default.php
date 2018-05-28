<?php
/**
 * @version     $Id: default.php 16024 2012-09-13 11:55:37Z hiepnv $
 * @package     JSN_Poweradmin
 * @subpackage  Config
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
if ($this->currentSiteTemplate != null)
{
	if (preg_match('/^jsn_([^_]+)(_free|_pro)?$/', (string) $this->currentSiteTemplate->template, $match))
	{
		echo '<script type="text/javascript">var JoomlaShine = {};JoomlaShine.jQuery = window.jQuery.noConflict();</script>';
	}
}	

JSNPositionsHelper::render($this->jsnrender);