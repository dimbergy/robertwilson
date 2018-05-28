<?php
/**
 * @version     $Id
 * @package     JSNUniform
 * @subpackage  Plugin
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2015 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

class plgSystemUniform extends JPlugin
{
	public function onAfterRender()
	{
		$app = JFactory::getApplication();
		$input = $app->input;

		if ($app->isAdmin() && $input->getVar('option', '') == 'com_uniform')
		{
			$html = $app->getBody();
			if (($input->getVar('view', '') == 'forms') || ($input->getVar('view', '') == 'configuration') || ($input->getVar('view', '') == 'submissions') || ($input->getVar('view', '') == 'form'))
			{
				// Remove scrollspy jQuery conflict
				if (preg_match_all("/\\$\('\.subhead'\)\.scrollspy\(\{[^\r\n]+\}\);/", $html, $matches, PREG_SET_ORDER))
				{
					$html = preg_replace("/\\$\('\.subhead'\)\.scrollspy\(\{[^\r\n]+\}\);/", '',  $html);
					$app->setBody($html);
				}
				if (preg_match_all("/\\jQuery\('\.hasTooltip'\)\.tooltip\(\{[^\r\n]+\}\);/", $html, $matches, PREG_SET_ORDER))
				{
					$html = preg_replace("/\\jQuery\('\.hasTooltip'\)\.tooltip\(\{[^\r\n]+\}\);/", '',  $html);
					$app->setBody($html);
				}
			}
			if ($input->getVar('view', '') == 'form')
			{
				if (preg_match_all("/jQuery\('\[rel=tooltip\]'\)\.tooltip\(\);/", $html, $matches, PREG_SET_ORDER))
				{
					$html = preg_replace("/jQuery\('\[rel=tooltip\]'\)\.tooltip\(\);/", '', $html);
					$app->setBody($html);
				}
				//if (preg_match('#<script[^>]+src="[^"]*/js/template\.js[^"]*"[^>]*></script>#', $html, $match))
				//{
					//$html = preg_replace('#<script[^>]+src="[^"]*/js/template\.js[^"]*"[^>]*></script>#', '', $html);
					//$app->setBody($html);
				//}
				
			}
		}

	}
}