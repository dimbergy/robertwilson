<?php
/**
 * @version     $Id$
 * @package     JSN_Mobilize
 * @subpackage  SystemPlugin
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// no direct access
defined('_JEXEC') or die;

$app 		= JFactory::getApplication();
$template 	= $app->getTemplate();


?>
<?php if (JSNMobilizeTemplateHelper::isJoomla3()): ?>
<div class="items-more">
<ul class="nav nav-tabs nav-stacked">
<?php else : ?>
<h2><?php echo JText::_('COM_CONTENT_MORE_ARTICLES'); ?></h2>
<ul>
<?php endif; ?>
<?php
	foreach ($this->link_items as &$item) :
?>
	<li>
		<a class="blogsection" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid)); ?>">
			<?php echo $item->title; ?></a>
	</li>
<?php endforeach; ?>
</ul>
<?php if (JSNMobilizeTemplateHelper::isJoomla3()): ?>
</div><?php endif; ?>
