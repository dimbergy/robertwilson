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

include_once(JPATH_ROOT . '/administrator/components/com_pagebuilder/defines.pagebuilder.php');
include_once(JSNPB_ADMIN_PATH . 'helpers/shortcode.php');
include_once(JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/extensions.php');
include_once(JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/pagebuilder.php');
global $id, $option, $conf, $framework, $supported_list, $params, $random_id, $contentStatus;
array(
	$id = JRequest::getVar('id'),
	$option = JRequest::getVar('option'),
	$db = JFactory::getDbo(),
	$framework = JTable::getInstance('Extension'),
	$conf = JFactory::getConfig(),
	$supported_list = array('com_content', 'com_modules', 'com_k2'),
	$params = JSNConfigHelper::get('com_pagebuilder'),
	$random_id = JSNPagebuilderHelpersShortcode::generateRandomString()
);


/**
 * Plugin button for JSN Pagebuilder
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class plgButtonPageBuilder extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 *
	 * @param       object $subject The object to observe
	 * @param       array  $config  An array that holds the plugin configuration
	 *
	 * @since       1.5
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Display the button
	 *
	 * @return
	 */
	function onDisplay($name)
	{
		$app       = JFactory::getApplication();
		if ($app->isSite())
		{
			return;
		}
		
		include_once(JPATH_ROOT . '/administrator/components/com_pagebuilder/helpers/articles.php');
		$listId  = JSNPagebuilderHelpersArticles::getListArticleUedPagebuilderFromPlugin();
		$pbTotal = JSNPagebuilderHelpersArticles::getCountArticleUsedPageBuilderFromPlugin();
		JSNPagebuilderHelpersArticles::updateArticleUsedPageBuilderToPlugin();
		//Check if JoomlaShine extension framework is enabled?
		global $id, $option, $framework, $conf, $supported_list, $params, $random_id;
		// Check if JoomlaShine extension framework is enabled?
		
		$framework = JTable::getInstance('Extension');
		$framework->load(
			array(
				'element' => 'jsnframework',
				'type'    => 'plugin',
				'folder'  => 'system'
			)
		);
		$isInstalledPbExtension = false;
		$option                 = JRequest::getVar('option');

		$com_advancedmodules = JSNPagebuilderHelpersPagebuilder::checkComponentEnabled('com_advancedmodules') ? array('com_advancedmodules') : '';
		$flexiContent = JSNPagebuilderHelpersPagebuilder::checkComponentEnabled('com_flexicontent') ? array('com_flexicontent') : '';

		$supported_list = array('com_content', 'com_modules');
		if ($com_advancedmodules != '')
		{
			$supported_list = array_merge($supported_list, $com_advancedmodules );
		}
		if ( $flexiContent != '')
		{
			$supported_list = array_merge( $supported_list, $flexiContent);
		}

		if (isset($params))
		{
			if ($params->extension_support != '')
			{
				$supported_list = json_decode($params->extension_support);
//				$supported_list = array_merge(array_unique($supported_list), $supported_list_in_params);
			}
		}

		$installedPbExtensions = JSNPagebuilderHelpersExtensions::getPbExtensions();

		if (count($installedPbExtensions))
		{
			foreach ($installedPbExtensions as $installedPbExtension)
			{
				if ($option == "com_" . $installedPbExtension->element)
				{
					if (!$installedPbExtension->enabled)
					{
						$app->enqueueMessage(sprintf('Please enable the %s plugin before using. How to enable it, click <a href="%s" target="_blank">here</a>', 'JSN PageBuilder ' . strtoupper($installedPbExtension->element) . ' element', 'index.php?option=com_pagebuilder&view=configuration&s=maintainence&g=extensions'), 'warning');

						return;
					}
					else
					{
						$isInstalledPbExtension = true;
					}

					break;
				}
			}
		}

		if ($isInstalledPbExtension)
		{
			if (!in_array($option, $supported_list))
			{
				$app->enqueueMessage(sprintf('Please enable the JSN PageBuilder %s Extention Support before using. How to enable it, click <a href="%s" target="_blank">here</a>', strtoupper($installedPbExtension->element), 'index.php?option=com_pagebuilder&view=configuration&s=configuration&g=msgs'), 'warning');

				return;
			}
		}


		if ($framework->extension_id && in_array($option, $supported_list))
		{
			// Get PageBuilder configuration

			if (!in_array($option, $supported_list))
			{
				$isInstalled = JSNPagebuilderHelpersExtensions::checkInstalledPlugin(str_replace('com_', '', $option));
				if (!$isInstalled['isInstalled'])
				{
					return;
				}
			}
			// Check if it's enabled or not
			$isEnabled = $params->get('enable_pagebuilder', 1);
			if ($isEnabled)
			{
				$conf   = JFactory::getConfig();
				$editor = $conf->get('editor');
				
				JSNHtmlAsset::addInlineScript("
					var token = '".JSession::getFormToken()."';
					var save_button_title = '".JText::_('JSN_PAGEBUILDER_SAVE_BUTTON_TITLE')."';
					var cancel_button_title = '".JText::_('JSN_PAGEBUILDER_CANCEL_BUTTON_TITLE')."';
					var close_button_title = '".JText::_('JSN_PAGEBUILDER_CLOSE_BUTTON_TITLE')."';
				");
				
				// Inlcude the entry js file.
				JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/js/joomlashine.noconflict.js');
				JSNHtmlAsset::addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/jquery-ui/js/jquery-ui-1.10.3.custom.js');
				JSNHtmlAsset::addScript(JSNPB_PLG_SYSTEM_ASSETS_URL . '3rd-party/jquery-livequery/jquery.livequery.min.js');
				JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/js/builder-layout.js');
				JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/joomlashine/js/jsn-modal.js');
				JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/js/elements-lang.js');
				JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/js/jquery.alphanumeric/jquery.alphanumeric.js');
				JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/js/shortcodesetting/settings-handler.js');
				JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/js/handle.js');

				// Include supoported editor script
				switch ($editor)
				{
					case 'codemirror':
						JSNHtmlAsset::addScript(JURI::root(true) . '/plugins/editors-xtd/pagebuilder/assets/js/supported-editor/codemirror.js');
						break;
					case 'tinymce':
						JSNHtmlAsset::addScript(JURI::root(true) . '/plugins/editors-xtd/pagebuilder/assets/js/supported-editor/tiny-mce.js');
						break;
					case 'jce':
						JSNHtmlAsset::addScript(JURI::root(true) . '/plugins/editors-xtd/pagebuilder/assets/js/supported-editor/jce.js');
						break;
					case 'jckeditor':
						JSNHtmlAsset::addScript(JURI::root(true) . '/plugins/editors-xtd/pagebuilder/assets/js/supported-editor/jckeditor.js');
						break;
					case 'ckeditor':
						JSNHtmlAsset::addScript(JURI::root(true) . '/plugins/editors-xtd/pagebuilder/assets/js/supported-editor/ckeditor.js');
						break;
					case 'artofeditor':
						JSNHtmlAsset::addScript(JURI::root(true) . '/plugins/editors-xtd/pagebuilder/assets/js/supported-editor/artofeditor.js');
						break;
                    case 'arkeditor':
                        JSNHtmlAsset::addScript(JURI::root(true) . '/plugins/editors-xtd/pagebuilder/assets/js/supported-editor/arkeditor.js');
                        break;
					default:
						JSNHtmlAsset::addScript(JURI::root(true) . '/plugins/editors-xtd/pagebuilder/assets/js/supported-editor/default.js');
						break;
				}

				// fix tinymce J35
				$JVersion 	= new JVersion;
				$JVersion 	= $JVersion->getShortVersion();
				
				if (version_compare($JVersion, '3.5', '>='))
				{
					// fix tinymce J35
					if ($name == 'tinymce')
					{
						if ($option == 'com_modules' || $option == 'com_advancedmodules' )
						{
							$name = 'jform_content';
						}
						elseif ($option == 'com_content')
						{
							$name = 'jform_articletext';
						}
						elseif ($option == 'com_flexicontent')
						{
							$name = 'jform_text';
						}						
						elseif ($option == 'com_k2')
						{
							$name = 'text';
						}
					}
				}
				// Generate random string to assign to switcher button
				$random_id = JSNPagebuilderHelpersShortcode::generateRandomString();
				JSNHtmlAsset::addScript(JSNPB_ADMIN_URL . '/assets/js/entry.js');
				$js = "
					var token = '".JSession::getFormToken()."';
					var Pb_Ajax	= {};

					var JSNPbParams = {pbstrings:{}};
					JSNPbParams.rootUrl = '" . JUri::root() . "';
					JSNPbParams.pbstrings.COPY = '" . JText::_('copy') . "';
					JSNPbParams.pbstrings.EMPTY = '" . JText::_('(Untitled)') . "';
					JSNPbParams.pbstrings.ALERT_DELETE_ROW = '" . JText::_('Are you sure you want to delete the whole row including all elements it contains?') . "';
					JSNPbParams.pbstrings.ALERT_DELETE_COLUMN = '" . JText::_('Are you sure you want to delete the whole column including all elements it contains?') . "';
					JSNPbParams.pbstrings.ALERT_DELETE_ELEMENT = '" . JText::_('Are you sure you want to delete the element?') . "';
					var pb;
					var pbContentStatus_$random_id	= 'normal';

					// Set global pagebuilder instance
					var jsnpb_$random_id;

					// Method to switch between Joomla editor and PageBuilder
					function switchPagebuilder(id, status){
						(function ($){
							if (status == 'on') {
								jsnpb_$random_id	= new $.JSNPageBuilder(id);
								pbContentStatus_$random_id  = 'pb';
							}else{
								jsnpb_$random_id.transformToSource();
								pbContentStatus_$random_id  = 'normal';
							}
						})(JoomlaShine.jQuery);
					}

					// Entry button group structure which will be appended to the aditor
					var pbEntryButtons	= '<div class=\"jsn-form-bar pb-switcher-group\">'
							+ '<div id=\"pb-editor-switcher\" class=\"btn-group\">'
							+	'<button type=\"button\" class=\"switchmode-button pb-off btn active btn-success\" id=\"pagebuilder-off-$random_id\" >Default Editor</button>'
							+	'<button type=\"button\" class=\"switchmode-button pb-on btn\" id=\"pagebuilder-on-$random_id\" >PageBuilder</button>'
							+'</div>'
							+	'<input type=\"hidden\" id=\"jform_pb_total\" name=\"jform_pb_total\" value=\"$pbTotal\">'
							+	'<input type=\"hidden\" id=\"jform_content_id\" name=\"jform_content_id\" value=\"$id\">'
							+	'<input type=\"hidden\" id=\"jform_list_id\" name=\"jform_list_id\" value=\"$listId\">'
							+'<div class=\"pull-right\" id=\"top-btn-actions\">'
                                +'<!--<button class=\"btn btn-default page-custom-css\" onclick=\"return false;\">Custom CSS</button>-->'
                                +   '<input type=\"hidden\" id=\"pb_content_id\" name=\"pb_content_id\" value=\"$id\">'
                            +'</div>'
						+'</div>';

					(function ($){
						$(window).load(function (){
						$('body').find('ul#myTabTabs li:first').on('click', function(){
							$('.switchmode-button.pb-off').click();
						});
						if ($('#jform_module').length)
						   		{
									if ($('#jform_module').val() != 'mod_custom')
									{
									 	return false;
									}
							   }
							var editorHelper_$random_id	= new $.JSNPbEditorHelper();
							var buttonGroups	= editorHelper_$random_id.initEntryButtons('" . $name . "', pbEntryButtons);

							//Auto switch to PageBuilder if shortcode structure detected
							var _content	= $('#" . $name . "').val();
							if (_content.indexOf('[pb_row') >= 0) {
								//setTimeout(function (){
									$('.switchmode-button.pb-on', buttonGroups).click();
								//}, 500);
							}
						});
					})(JoomlaShine.jQuery);
				";


				JSNHtmlAsset::addInlineScript($js);
				JSNHtmlAsset::addStyle(JSNPB_FRAMEWORK_ASSETS . '/3rd-party/jquery-ui/css/ui-bootstrap/jquery-ui-1.9.0.custom.css');
				JSNHtmlAsset::addStyle(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-gui.css');
				JSNHtmlAsset::addStyle(JSNPB_PLG_SYSTEM_ASSETS_URL . 'css/pagebuilder.css');
				JSNHtmlAsset::addStyle(JSNPB_ADMIN_URL . '/assets/css/jsn-element-font.css');
				JSNHtmlAsset::addStyle(JSNPB_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-advanced-parameters.css'); // for text,
				JSNHtmlAsset::addStyle(JSNPB_ADMIN_URL . '/assets/css/settings.css');
			}
		}
	}

}
