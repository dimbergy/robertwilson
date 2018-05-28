<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: uninstall.imageshow.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
require_once dirname(__FILE__).DS.'subinstall'.DS.'subinstall.php';
function com_uninstall() 
{
    $objJSNSubInstaller 	= new JSNSubInstaller();
    $return 				= $objJSNSubInstaller->uninstall();
}