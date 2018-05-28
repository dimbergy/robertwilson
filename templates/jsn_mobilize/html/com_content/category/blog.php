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
defined('_JEXEC') or die('Restricted access');

// Load template framework


$app 		= JFactory::getApplication();
$template 	= $app->getTemplate();


if (JSNMobilizeTemplateHelper::isJoomla3()):
	JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
	JHtml::_('behavior.caption');
else :
	JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
endif;
?>
<div class="com-content <?php echo $this->pageclass_sfx; ?>">
<div class="category-blog">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<?php if (JSNMobilizeTemplateHelper::isJoomla3()): ?>
	<div class="page-header"><?php endif; ?>
	<h2 class="componentheading">
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h2>
	<?php if (JSNMobilizeTemplateHelper::isJoomla3()): ?>
	</div><?php endif; ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_category_title', 1) OR $this->params->get('page_subheading')) : ?>
	<h2 class="subheading">
		<?php echo $this->escape($this->params->get('page_subheading')); ?>
		<?php if ($this->params->get('show_category_title')) : ?>
			<span class="subheading-category"><?php echo $this->category->title;?></span>
		<?php endif; ?>
	</h2>
	<?php endif; ?>
	<?php if (JSNMobilizeTemplateHelper::isJoomla3()): ?>
	<?php if ($this->params->get('show_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
		<?php $this->category->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
		<?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
	<?php endif; ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_description', 1) || $this->params->get('show_description_image', 1)) :?>
		<div class="contentdescription clearafter">
		<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
			<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
		<?php endif; ?>
		<?php if ($this->params->get('show_description') && $this->category->description) : ?>
			<?php if (JSNMobilizeTemplateHelper::isJoomla3()): ?>
			<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
			<?php else : ?>
			<?php echo JHtml::_('content.prepare', $this->category->description); ?>
			<?php endif; ?>
		<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php $leadingcount=0 ; ?>
	<?php if (!empty($this->lead_items)) : ?>
	<div class="jsn-leading">
	<?php foreach ($this->lead_items as &$item) : ?>
		<div class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?> items-row">
			<?php
				$this->item = &$item;
				echo $this->loadTemplate('item');
			?>
		</div>
		<div class="clearfix"></div>
		<?php
			$leadingcount++;
		?>
	<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<?php
		$introcount=(count($this->intro_items));
		$counter=0;
	?>
	<?php if (!empty($this->intro_items)) : ?>
	<div class="row_separator"></div>
		<?php foreach ($this->intro_items as $key => &$item) : ?>
		<?php
			$key= ($key-$leadingcount)+1;
			$rowcount=( ((int)$key-1) %	(int) $this->columns) +1;
			$row = $counter / $this->columns ;

			if ($rowcount==1) : ?>
				<div class="items-row cols-<?php echo (int) $this->columns;?> <?php echo 'row-'.$row; ?> row-fluid">
				<?php endif; ?>
					<div class="span<?php echo round((12 / $this->columns));?>">
						<div class="item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
							<?php
							$this->item = &$item;
							echo $this->loadTemplate('item');
						?>
						</div>
						<?php $counter++; ?>
					</div>
					<?php if (($rowcount == $this->columns) or ($counter == $introcount)): ?>			
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
	
	<?php if (!empty($this->link_items)) : ?>
	<div class="row_separator"></div>
	<div class="blog_more clearafter">
		<?php echo $this->loadTemplate('links'); ?>
	</div>
	<?php endif; ?>
	
	<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="row_separator"></div>
	<div class="jsn-pagination-container">
		<?php echo $this->pagination->getPagesLinks(); ?>
		<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="jsn-pageinfo"><?php echo $this->pagination->getPagesCounter(); ?></p>
		<?php endif; ?>				
	</div>
	<?php endif; ?>

	<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
	<div class="row_separator"></div>
	<div class="cat-children">
	<?php if (JSNMobilizeTemplateHelper::isJoomla3()): ?><?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?><?php endif; ?>
		<h3><?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?></h3>
	<?php if (JSNMobilizeTemplateHelper::isJoomla3()): ?><?php endif; ?><?php endif; ?>
		<?php echo $this->loadTemplate('children'); ?>
	</div>
	<?php endif; ?>
	
</div>
</div>