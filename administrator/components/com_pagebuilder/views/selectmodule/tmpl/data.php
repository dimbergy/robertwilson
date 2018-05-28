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

// No direct access
defined('_JEXEC') or die('Restricted access');

$limitstart     = JFactory::getApplication()->input->getInt('start', 0);
$key            = JFactory::getApplication()->input->getString('search', '');
$key            = urldecode($key);

$model          = new JSNPagebuilderModelSelectmodule;
$datas          = $model->getModules($limitstart,$key);

$modules        = $datas['modules'];
$total_modules  = $datas['total_modules'];

?>
<?php if (count($modules) > 0) { ?>
	<?php $html = '<div class="jsn-module-content" data-total="'.$total_modules.'">' ?>
	    <?php foreach ($modules as &$module) : ?>
	                    <?php 
	                   		
	                        $string = $this->escape(str_replace("\"", "'", $module->title ));
	                        $moduleType = $this->escape($string);
	                        $title = JHTML::_('string.truncate', $string, 30);
	                        $client = JApplicationHelper::getClientInfo(0);
	                        $lang = JFactory::getLanguage();
	                        $path = JPath::clean($client->path . '/modules/' . $module->module. '/' . $module->module. '.xml');
	                        if(file_exists($path)){
	                            $module->xml = simplexml_load_file($path);
	                        }else{
	                            $modile->xml = null;
	                        }
	                        //load language
	                        $lang->load($module->module. '.sys', $client->path, null, false, true) || $lang->load($module->module. 'sys', $client->path . '/modules/'. $module->module, null, false, true);
	                        $module->name = JText::_($module->name);
	                        //if description isset return description text, if not isset return text module is no description
	                        if(isset($module->xml) && $text = trim($module->xml->description)){
	                            $module->desc = JText::_($text);
	                        }  else {
	                            $module->desc = JText::_('JSN_PAGEBUILDER_LIB_SHORTCODE_MODULE_IS_NO_DES');
	                        }
	                        $shortDesc	= JHTML::_('string.truncate', ($this->escape($module->desc)), 40);
	                        
	                        $html .= '<div id="' . $module->id . '" class="jsn-item-type jsn-element-module-item">
	                            <div class="editlinktip btn jsn-module-item-btn"  title="' . $title. '"  data-module-title="' . $title. '">
	                        		<span>' . $title . '</span>
	                                <p>['. $moduleType .'] - '. $shortDesc .'</p>
	                            </div>
	                        </div>';
	                    ?>
	            <?php endforeach;?>
	            
	<?php $html .= '</div>' ?>
	<?php  echo $html; ?>
<?php } else { ?>
	<?php echo '<div class="jsn-module-content" data-total="'.$total_modules.'"> <div class="alert alert-block no-module">' . JText::_('JSN_PAGEBUILDER_ELEMENT_MODULE_NO_MODULE_SELECTED', true) . '</div></div>'; ?>
<?php } ?>
