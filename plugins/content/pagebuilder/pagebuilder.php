<?php
/**
 * @version     $Id
 * @package     JSNPagebuilder
 * @subpackage  Plugin
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) @JOOMLASHINECOPYRIGHTYEAR@ JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

/**
 * Pagebuilder Content Plugin
 *
 * @package     Joomla.Plugin
 *
 * @subpackage  Content.joomla
 *
 * @since       1.6
 */
class plgContentPagebuilder extends JPlugin
{

    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    public function onContentBeforeDisplay($context, &$article)
    {
        $app = JFactory::getApplication();
        if ($app->isSite()){
        	include_once JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/shortcode.php';
        	$shortCodeRegex = JSNPagebuilderHelpersShortcode::getShortcodeRegex();
        	
        	if (isset($article->fulltext) && $article->fulltext != '' && $article->introtext != '')
        	{
        		$result = JSNPagebuilderHelpersShortcode::removeShortCode($article->introtext, $shortCodeRegex);
        		if ($result)
        		{
        			$article->introtext = $result;
        		}
        	}
        }
                
    }
}
