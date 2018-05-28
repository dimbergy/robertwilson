<?php
/**
 * @version		$Id: latest_item.php 1492 2012-02-22 17:40:09Z joomlaworks@gmail.com $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<!-- Start K2 Item Layout -->

<div class="latestItemView span<?php echo round(12 / $this->params->get('latestItemsCols'));?>"> 
	
	<!-- Plugins: BeforeDisplay --> 
	<?php echo $this->item->event->BeforeDisplay; ?> 
	
	<!-- K2 Plugins: K2BeforeDisplay --> 
	<?php echo $this->item->event->K2BeforeDisplay; ?>
	<div class="latestItemHeader">

		<div class="catItemHeader">
			<?php if($this->item->params->get('latestItemTitle')): ?>
			<!-- Item title -->
			<h2 class="latestItemTitle">
				<?php if ($this->item->params->get('latestItemTitleLinked')): ?>
				<a href="<?php echo $this->item->link; ?>"> <?php echo $this->item->title; ?> </a>
				<?php else: ?>
				<?php echo $this->item->title; ?>
				<?php endif; ?>
			</h2>
			<?php endif; ?>	
			<div class="jsn-article-toolbar">
				<div class="jsn-article-info">

					<?php if($this->item->params->get('latestItemDateCreated')): ?>
					<!-- Date created -->
					<div class="latestItemDateCreated">
						<i class="fa fa-calendar"></i>
						<?php echo JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3')); ?>
					</div>
					<?php endif; ?>
					
					<?php if($this->item->params->get('latestItemCategory')): ?>
					<!-- Item category name -->
					<div class="latestItemCategory"><i class="fa fa-folder-open-o"></i><a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a> </div>
					<?php endif; ?>
					<?php if($this->item->params->get('latestItemCommentsAnchor') && ( ($this->item->params->get('comments') == '2' && !$this->user->guest) || ($this->item->params->get('comments') == '1')) ): ?>
					<!-- Anchor link to comments below -->
					<div class="latestItemCommentsLink">
						<?php if(!empty($this->item->event->K2CommentsCounter)): ?>
						<!-- K2 Plugins: K2CommentsCounter --> 
						<?php echo $this->item->event->K2CommentsCounter; ?>
						<?php else: ?>
						<?php if($this->item->numOfComments > 0): ?>
						<i class="fa fa-comments-o"></i><a href="<?php echo $this->item->link; ?>#itemCommentsAnchor"> <?php echo $this->item->numOfComments; ?> <?php echo ($this->item->numOfComments>1) ? JText::_('K2_COMMENTS') : JText::_('K2_COMMENT'); ?> </a>
						<?php else: ?>
						<i class="fa fa-comments-o"></i><a href="<?php echo $this->item->link; ?>#itemCommentsAnchor"> <?php echo JText::_('K2_BE_THE_FIRST_TO_COMMENT'); ?> </a>
						<?php endif; ?>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php if($this->item->params->get('catItemImage') && !empty($this->item->image)|| $this->item->params->get('catItemDateCreated')): ?>
			<!-- Item Image -->
			<div class="catItemImageBlock"> 
				<?php if($this->item->params->get('catItemImage') && !empty($this->item->image)): ?>
				<!-- Image -->
				<span class="catItemImage"> <a href="<?php echo $this->item->link; ?>" title="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>"> <img src="<?php echo $this->item->imageXLarge; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px; height:auto;" /> </a> </span>
				<div class="clr"></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	
	<!-- Plugins: AfterDisplayTitle --> 
	<?php echo $this->item->event->AfterDisplayTitle; ?> 
	
	<!-- K2 Plugins: K2AfterDisplayTitle --> 
	<?php echo $this->item->event->K2AfterDisplayTitle; ?>
	<div class="clr"></div>
	<div class="latestItemBody"> 
		
		<!-- Plugins: BeforeDisplayContent --> 
		<?php echo $this->item->event->BeforeDisplayContent; ?> 
		
		<!-- K2 Plugins: K2BeforeDisplayContent --> 
		<?php echo $this->item->event->K2BeforeDisplayContent; ?>
		
		<?php if($this->item->params->get('latestItemIntroText')): ?>
		<!-- Item introtext -->
		<div class="latestItemIntroText"> <?php echo $this->item->introtext; ?> </div>
		<?php endif; ?>

		<div class="clr"></div>
		
		<!-- Plugins: AfterDisplayContent --> 
		<?php echo $this->item->event->AfterDisplayContent; ?> 
		
		<!-- K2 Plugins: K2AfterDisplayContent --> 
		<?php echo $this->item->event->K2AfterDisplayContent; ?>
		<div class="clr"></div>
	</div>
	<?php if($this->item->params->get('latestItemCategory') || $this->item->params->get('latestItemTags')): ?>
	<div class="latestItemLinks">
		<?php if($this->item->params->get('latestItemTags') && count($this->item->tags)): ?>
		<!-- Item tags -->
		<div class="latestItemTagsBlock">
			<ul class="latestItemTags"> 
				<?php foreach ($this->item->tags as $tag): ?>
				<li><a href="<?php echo $tag->link; ?>"><?php echo $tag->name; ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
		<div class="clr"></div>
	</div>
	<?php endif; ?>
	<div class="clr"></div>
	<?php if ($this->item->params->get('latestItemReadMore')): ?>
	<!-- Item "read more..." link -->
	<div class="latestItemReadMore"> <a class="k2ReadMore" href="<?php echo $this->item->link; ?>">read more <i class="fa fa-long-arrow-right"></i> </a> </div>
	<?php endif; ?>
	<div class="clr"></div>
	<?php if($this->params->get('latestItemVideo') && !empty($this->item->video)): ?>
	<!-- Item video -->
	<div class="latestItemVideoBlock">
		<h3><?php echo JText::_('K2_RELATED_VIDEO'); ?></h3>
		<span class="latestItemVideo<?php if($this->item->videoType=='embedded'): ?> embedded<?php endif; ?>"><?php echo $this->item->video; ?></span> </div>
	<?php endif; ?>
	<div class="clr"></div>
	
	<!-- Plugins: AfterDisplay --> 
	<?php echo $this->item->event->AfterDisplay; ?> 
	
	<!-- K2 Plugins: K2AfterDisplay --> 
	<?php echo $this->item->event->K2AfterDisplay; ?>
	<div class="clr"></div>
</div>
<!-- End K2 Item Layout --> 
