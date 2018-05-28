<?php
/**
 * @version    $Id$
 * @package    JSN_PageBuilder
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file.
defined('_JEXEC') || die('Restricted access');

include_once JPATH_ROOT . '/administrator/components/com_pagebuilder/defines.pagebuilder.php';
jimport('joomla.plugin.plugin');

/**
 * Plugin system of JSN Pagebuilder
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class plgSystemPagebuilder extends JPlugin
{
	public function onAfterInitialise()
	{
		$input   	= JFactory::getApplication()->input;
		$option  	= $input->getString('option', '');
		$preview 	= $input->getString('task', '');
		$secretKey 	= $input->getCmd('secret_key', '');

		$config 	= JFactory::getConfig();
		$secret 	= $config->get('secret');
		$flag 		= $input->getString('flag', '');
		$author 	= $input->getString('author', '');
		
		if (JFactory::getApplication()->isSite() && $option == 'com_pagebuilder' && $preview == 'preview.module')
		{
			if (md5($secret) != $secretKey)
			{
				die('Restricted access');
			}
			
			$result = $this->jsnRenderModule($input->getInt('moduleId', 0),$input->getString('showTitle', ''));
			if ( $result != false){
				echo $result;
			}			
			exit();
		}
		
		if (JFactory::getApplication()->isAdmin() && $option == 'com_media' && ( $flag == 'jsn_pagebuilder' || $author == 'jsn_pagebuilder' ))
		{
			$doc 	= JFactory::getDocument();
			$doc->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/media.css', 'text/css' );
		}
	}
	/**
	 * This method is to load neccessary access
	 * for PageBuilder need
	 *
	 * @return void
	 */
	public function onBeforeRender()
	{
		// Check if JoomlaShine extension framework is enabled?
		$framework = JTable::getInstance('Extension');
		$framework->load(
				array(
						'element'	=> 'jsnframework',
						'type'		=> 'plugin',
						'folder'	=> 'system'
				)
		);

		// Do nothing if JSN Extension framework not found.
		if ( !$framework->extension_id ) return;

		$app        = JFactory::getApplication();

        $tpl = $app->input->getInt('tp', 0);
		if ($app->isAdmin() || $tpl) return;

		// Get requested component, view and task
		$option		= $app->input->getCmd('option', '');
		$view		= $app->input->getCmd('view', '');
		$layout		= $app->input->getCmd('layout', '');
		$user		= JFactory::getUser();


		if ($app->isSite() && $option == 'com_content' && $view == 'form' && $layout == 'edit' && $user->get('id') > 0)
		{
			return;
		}

		$doc 	= JFactory::getDocument();

		if (strtolower(get_class($doc)) != "jdocumenthtml") return;

		if ($app->isSite() && $option == 'com_k2' && $view == 'item' && $app->input->getInt('id', 0)) {
			if (file_exists(JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/shortcode.php')) {
				if (class_exists('K2HelperUtilities'))
				{
					include_once JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/shortcode.php';
					$shortCodeRegex = JSNPagebuilderHelpersShortcode::getShortcodeRegex();
					JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_k2/models');
					$K2ModelItem = JModelLegacy::getInstance('k2modelitem');
					$k2Item = $K2ModelItem->getData();
					if (count($k2Item))
					{
						$metaDescItem = preg_replace("#{(.*?)}(.*?){/(.*?)}#s", '', $k2Item->introtext.' '.$k2Item->fulltext);
						$metaDescItem = strip_tags($metaDescItem);
						$k2params = K2HelperUtilities::getParams('com_k2');
						$metaDescItem = JSNPagebuilderHelpersShortcode::removeShortCode($metaDescItem, $shortCodeRegex);
						$metaDescItem = K2HelperUtilities::characterLimit($metaDescItem, $k2params->get('metaDescLimit', 150));

						if ($doc->getMetaData('og:description') != null) {
							$doc->setMetaData('og:description', $metaDescItem);
						}
						if ($doc->getDescription() != '') {
							$doc->setDescription($metaDescItem);
						}
					}
				}
			}
		}

		// Get PageBuilder configuration.
		$params 		= JSNConfigHelper::get('com_pagebuilder');
		// Check if it's enabled or not.
		$isEnabled		= $params->get('enable_pagebuilder', 1);

		// Do nothing if PageBuilder not enabled;
		if ( !$isEnabled ) {} ;

		// Register autoloaded classes
		JSN_Loader::register(JSNPB_ADMIN_ROOT . '/helpers' , 'JSNPagebuilderHelpers');
		JSN_Loader::register(JSNPB_ADMIN_ROOT . '/helpers/shortcode' , 'JSNPBShortcode');
		//JSN_Loader::register(JPATH_ROOT . '/plugins/pagebuilder/' , 'JSNPBShortcode');
		//JSN_Loader::register(JPATH_ROOT . '/administrator/components/com_pagebuilder/elements/' , 'JSNPBShortcode');
		JSN_Loader::register(JPATH_ROOT . '/plugins/jsnpagebuilder/defaultelements/' , 'JSNPBShortcode');

		//load ElementAssets
		self::loadElementAssets();
		/*
		 * Move all css files of PageBuilder
		 * to the end of css list
		 *
		 */
		$data	= $doc->getHeadData();
		$styleSheetList	=	$data['styleSheets'];
		$_tmpList		= 	array();
		if (count($styleSheetList)) {
			foreach ($styleSheetList as $cssUrl=>$css) {
				// Check if the file belongs to PageBuilder
				if (strpos($cssUrl, 'plugins/pagebuilder/') !== false || strpos( $cssUrl, 'com_pagebuilder') !== false) {
					$_tmpList[$cssUrl]	= $css;
					unset($styleSheetList[$cssUrl]);
				}
			}
		}

		$styleSheetList	= array_merge($styleSheetList, $_tmpList);

		$data['styleSheets']	= $styleSheetList;
		$doc->setHeadData($data);
	}

	/**
	 * Check the whole site content then replace found
	 * PageBuilder shortcodes by Elements
	 *
	 * @return  Changed HTML format
	 */
	public function onAfterRender()
	{
		// Get requested component, view and task
		$app        = JFactory::getApplication();
        $tpl        = $app->input->getInt('tp', 0);
		$option		= $app->input->getCmd('option', '');
		$view		= $app->input->getCmd('view', '');
		$layout		= $app->input->getCmd('layout', '');
		$user		= JFactory::getUser();
		// Remove scrollspy jQuery conflict
		if ($app->isAdmin() && $option == 'com_pagebuilder')
		{
			if ( $view == 'configuration')
			{
				$html = $app->getBody();

				if (preg_match_all("/\\$\('\.subhead'\)\.scrollspy\(\{[^\r\n]+\}\);/", $html, $matches, PREG_SET_ORDER))
				{
					$html = preg_replace("/\\$\('\.subhead'\)\.scrollspy\(\{[^\r\n]+\}\);/", '',  $html);
					$app->setBody($html);
				}
			}
		}

		if ($app->isAdmin() && $option == 'com_content' && $view == 'article' && $layout == 'edit' && $user->get('id') > 0)
		{
			$html = $app->getBody();
			$html = preg_replace("#<script src=\"(.*?)\/jquery.ui.sortable.min.js\" type=\"text\/javascript\"></script>#", '',  $html);
			$app->setBody($html);
		}

        if ($app->isAdmin() || $tpl) return;

		$doc 			= JFactory::getDocument();
		if (strtolower(get_class($doc)) != "jdocumenthtml") return;

		if ($app->isSite() && $option == 'com_content' && $view == 'form' && $layout == 'edit' && $user->get('id') > 0)
		{
			return;
		}

		// Check if JoomlaShine extension framework is enabled?
		$framework = JTable::getInstance('Extension');
		$framework->load(
			array(
				'element'	=> 'jsnframework',
				'type'		=> 'plugin',
				'folder'	=> 'system'
			)
		);

		// Do nothing if JSN Extension framework not found.
		if ( !$framework->extension_id ) return;

		// Require base shorcode element
		self::requireBaseShortCodeElement();

		global $JSNPbElements;
		$JSNPbElements		= new JSNPagebuilderHelpersElements();

		// Get PageBuilder configuration.
		$params 			= JSNConfigHelper::get('com_pagebuilder');
		// Check if it's enabled or not.
		$isEnabled			= $params->get('enable_pagebuilder', 1);

		// Do nothing if PageBuilder not enabled;
		if ( !$isEnabled ) {} ;

		$data	= $doc->getHeadData();

		/*JHtml::_('jquery.framework');
		$doc->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . 'js/joomlashine.noconflict.js', 'text/javascript');
		$doc->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/bootstrap3/js/bootstrap.min.js', 'text/javascript' );

		JHTML::stylesheet( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/bootstrap3/css/bootstrap.min.css', 'text/css' );
        JHTML::stylesheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/pagebuilder.css', 'text/css' );
		JHTML::stylesheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/jsn-gui-frontend.css', 'text/css' );
		JHTML::stylesheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/front_end.css', 'text/css' );
		JHTML::stylesheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/front_end_responsive.css', 'text/css' );*/

		// Store the assets before transforming.
		$inlineScriptBefore	= isset($data['script']['text/javascript']) ? $data['script']['text/javascript'] : '';
		$inlineStyleBefore	= isset($data['style']['text/css']) ? $data['style']['text/css'] : '';
		$scriptsBefore	= $data['scripts'];
		$styleSheetsBefore	= $data['styleSheets'];

		$scriptCount		= count($scriptsBefore);
		$styleSheetCount	= count($styleSheetsBefore);

		// Analyze page content and use PageBuilder to
		// transform code if Pb structure found.

		// Get the responsed body
		$content	= $app->getBody();
		// preg_replace falsely process $ symobols as commands in text copy.
		$content    = str_replace('$', '&dollar;', $content);
		$content	= str_replace('\\\\', 'JSN_PB_BACKSLASHES', $content);
		$body_content = '';
		preg_match("/<body.*\/body>/si", $content, $body_content);

		if (!isset($body_content[0])) return;

		$body_content   = $body_content[0];

		$helper				= new JSNPagebuilderHelpersBuilder();
		// Transform the content inside body tag only		
		$body_content	=	$helper->generateShortCode($body_content, false, 'frontend');
		// Apply the body content into page content

		$content = preg_replace("/(<body.*\/body>)/si", $body_content, $content);

		/*
		 * Arrange the assets loaded from PageBuilder
		 * Because onAfterRender not accept add assets by JFactory::getDocument()
		 * so we need under code to modify document's header
		 */
		$data	= $doc->getHeadData();

		$inlineScriptAfter	= isset($data['script']['text/javascript']) ? $data['script']['text/javascript'] : '';
		$inlineStyleAfter	= isset($data['style']['text/css']) ? $data['style']['text/css'] : '';
		$scriptsAfter		= $data['scripts'];
		$styleSheetsAfter	= $data['styleSheets'];

		// Separate assets of page builder.
		$pbInlineScript		= str_replace($inlineScriptBefore, '', $inlineScriptAfter);
		$pbInlineStyle		= str_replace($inlineStyleBefore, '', $inlineStyleAfter);
		$pbScripts			= array_splice($scriptsAfter, $scriptCount);
		$pbStyleSheets		= array_splice($styleSheetsAfter, $styleSheetCount);

		// Append PageBuilder's assets
		// Only support css file with type is "text/css"
		// and js type with type is "text/javascript"
		// in this period.
		$pbAssets		= array();
		if (count($pbStyleSheets)) {
			foreach ($pbStyleSheets as $css=>$v){
				$pbAssets[]	= '<link rel="stylesheet" href="' . $css . '" type="text/css" />';
			}
		}

		if (count($pbScripts)) {
			foreach ($pbScripts as $js=>$v) {
				$pbAssets[]	= '<script src="' . $js . '" type="text/javascript"></script>';
			}
		}

		if (trim($pbInlineScript) != '')
		{
			$pbAssets[]		= '<script type="text/javascript">' . $pbInlineScript . '</script>';
		}

		if (trim($pbInlineStyle) != '')
		{
			$pbAssets[]		= '<style>' . $pbInlineStyle . '</style>';
		}
		$pbAssets		= implode("\n", $pbAssets);

		// Append assets to content
		$content		= str_replace("</head>", $pbAssets . "</head>", $content);
		// preg_replace falsely process $ symobols as commands in text copy.

		$content = str_replace('&dollar;', '$', $content);
		$content	= str_replace('JSN_PB_BACKSLASHES', '\\\\', $content);
		// Render preview for administrator
		$preview	= $app->input->getCmd('preview', '');
		if ($option == 'com_content' && $preview == 'preview.module')
		{
			if (file_exists(JPATH_ROOT . '/administrator/components/com_pagebuilder/controllers/shortcode.php')) {
				include_once JPATH_ROOT . '/administrator/components/com_pagebuilder/controllers/shortcode.php';
				$shortCode				= new JSNPagebuilderControllerShortcode();
				$shortCode->preview();
				die;
			}
		}
		
		$app->setBody($content);
	}	

	private static function loadElementAssets()
	{
		//BEGIN compress
		$app	= JFactory::getApplication();
		$doc 	= JFactory::getDocument();
		// Require base shorcode element
		self::requireBaseShortCodeElement();

		$JSNPbElements      = new JSNPagebuilderHelpersElements();
		if ($doc instanceOf JDocumentHTML)
		{
			$content =  $doc->getBuffer('component');
			$check_method = method_exists('JModuleHelper','getModuleList');
			
			if ($check_method) {
				$modules = JModuleHelper::getModuleList();
			} else {
				$modules = self::getModuleList();
			}
			
			if (count($modules))
			{
				foreach ($modules as $module)
				{
					if (trim($module->content) != '')
					{
						$content .= $module->content;
					}

				}
			}

			$content = str_replace('$', '&dollar;', $content);
			$helper  = new JSNPagebuilderHelpersBuilder();

			$elementClass   =   $helper->getShortCodeClassBeforeRender($content);
			if (!$app->isAdmin()) {
				JHtml::_('jquery.framework');
				$doc->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . 'js/joomlashine.noconflict.js', 'text/javascript');
				$doc->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/bootstrap3/js/bootstrap.min.js', 'text/javascript' );
				//$doc->addScript( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/scrollreveal/scrollReveal.js', 'text/javascript' );

				$doc->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/bootstrap3/css/bootstrap.min.css', 'text/css' );
				$doc->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/pagebuilder.css', 'text/css' );
				$doc->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/jsn-gui-frontend.css', 'text/css' );
				$doc->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/front_end.css', 'text/css' );
				$doc->addStyleSheet( JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/front_end_responsive.css', 'text/css' );
			}

			if (is_array($elementClass) && count($elementClass) > 0) {
				foreach ($elementClass as $class) {
					$instance = New $class();
					if (method_exists($instance,'load_assets_frontend')) {
						$instance->load_assets_frontend();
					}
				}
			}
		}
	}

	private static function requireBaseShortCodeElement()
	{
		require_once JSNPB_ADMIN_ROOT . '/libraries/innotheme/shortcode/element.php';
		require_once JSNPB_ADMIN_ROOT . '/libraries/innotheme/shortcode/parent.php';
		require_once JSNPB_ADMIN_ROOT . '/libraries/innotheme/shortcode/child.php';
	}
	
	private static function getModuleList()
	{
		$app = JFactory::getApplication();
		$Itemid = $app->input->getInt('Itemid');
		$groups = implode(',', JFactory::getUser()->getAuthorisedViewLevels());
		$lang = JFactory::getLanguage()->getTag();
		$clientId = (int) $app->getClientId();
		
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true)
		->select('m.id, m.title, m.module, m.position, m.content, m.showtitle, m.params, mm.menuid')
		->from('#__modules AS m')
		->join('LEFT', '#__modules_menu AS mm ON mm.moduleid = m.id')
		->where('m.published = 1')
		->join('LEFT', '#__extensions AS e ON e.element = m.module AND e.client_id = m.client_id')
		->where('e.enabled = 1');
		
		$date = JFactory::getDate();
		$now = $date->toSql();
		$nullDate = $db->getNullDate();
		$query->where('(m.publish_up = ' . $db->quote($nullDate) . ' OR m.publish_up <= ' . $db->quote($now) . ')')
		->where('(m.publish_down = ' . $db->quote($nullDate) . ' OR m.publish_down >= ' . $db->quote($now) . ')')
		->where('m.access IN (' . $groups . ')')
		->where('m.client_id = ' . $clientId)
		->where('(mm.menuid = ' . (int) $Itemid . ' OR mm.menuid <= 0)');
		
		// Filter by language
		if ($app->isSite() && $app->getLanguageFilter())
		{
			$query->where('m.language IN (' . $db->quote($lang) . ',' . $db->quote('*') . ')');
		}
		
		$query->order('m.position, m.ordering');
		
		// Set the query
		$db->setQuery($query);
		
		try
		{
			$modules = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			return array();
		}
		
		return $modules;
	}	

	public function jsnRenderModule($moduleId, $showTitle){
		
		self::requireBaseShortCodeElement();
		// Register autoloaded classes
		JSN_Loader::register(JSNPB_ADMIN_ROOT . '/helpers' , 'JSNPagebuilderHelpers');

		if (file_exists(JPATH_ROOT . '/plugins/jsnpagebuilder/defaultelements/module/module.php')) {
			include_once JPATH_ROOT . '/plugins/jsnpagebuilder/defaultelements/module/module.php';
			$shortCode = new JSNPBShortcodeModule();
			
			$content = $shortCode->jsn_load_module( (int)$moduleId, '', $style = 'none', $showTitle );
			$document = JFactory::getDocument();
			
			$scripts = $document->_scripts;			
			$script = '';
			if ( count($scripts) ) {
				foreach ($scripts as $fileScript => $value) {
					$script .='<script src="' . $fileScript . '" type="text/javascript"></script>' . "\n";
				}
			}
			
			$styleSheets = $document->_styleSheets;
			$css = '';
			if ( count($styleSheets) ) {
				foreach ($styleSheets as $fileStyle => $value) {
					$css .='<link rel="stylesheet" href="' . $fileStyle . '" type="text/css">' . "\n";
				}
			}

			return $script . $css . $content;
		}
		
		return false;
	}
}
