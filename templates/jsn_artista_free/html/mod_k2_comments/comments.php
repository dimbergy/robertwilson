<?php
/**
 * @version		$Id$
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div id="k2ModuleBox<?php echo $module->id; ?>" class="k2LatestCommentsBlock">
	<?php if(count($comments)): ?>
	<ul>
		<?php foreach ($comments as $key=>$comment):	?>
		<li class="<?php echo ($key%2) ? "odd" : "even"; if(count($comments)==$key+1) echo ' lastItem'; ?>">

			<div class="jsn-cmt-item">
				
				<div class="cmt-header">

					<?php if($comment->userImage): ?>
					<div class="cmt-avatar">
						<a href="<?php echo $comment->link; ?>" title="<?php echo K2HelperUtilities::cleanHtml($comment->commentText); ?>"> 
							<img src="<?php echo $comment->userImage; ?>" alt="<?php echo JFilterOutput::cleanText($comment->userName); ?>" style="width:<?php echo $lcAvatarWidth; ?>px;" /> 
						</a>
					</div>
					<?php endif; ?>

					<div class="cmt-header-ct">

						<div class="cmt-title">

							<?php if($params->get('commentLink')): ?>
								<a href="<?php echo $comment->link; ?>">
									<?php if($params->get('itemTitle')): ?>
										<span class="cmt-title-txt"><?php echo substr($comment->title, 0,18); ?>...</span>
									<?php endif; ?>
								</a>
							<?php else: ?>
							
							<?php if($params->get('itemTitle')): ?>
								<p class="cmt-title-txt"><?php echo substr($comment->title, 0,18); ?>...</p>
							<?php endif; ?>

							<?php endif; ?>

						</div>

						<div class="cmt-meta">
							
							<?php if($params->get('commenterName')): ?>
							<p class="cmt-user"><?php //echo JText::_('K2_WRITTEN_BY'); ?>
								<i class="fa fa-pencil"></i>
								<?php if(isset($comment->userLink)): ?>
								<a rel="author" href="<?php echo $comment->userLink; ?>"><?php echo substr($comment->userName, 0,11); ?></a>
								<?php elseif($comment->commentURL): ?>
								<a target="_blank" rel="nofollow" href="<?php echo $comment->commentURL; ?>"><?php echo substr($comment->userName, 0,11); ?></a>
								<?php else: ?>
								<?php echo substr($comment->userName, 0,11); ?>
								<?php endif; ?>
							</p>
							<?php endif; ?>

							<?php if($params->get('commentDate')): ?>
							<p class="cmt-date">
								<i class="fa fa-calendar"></i>
								<?php if($params->get('commentDateFormat') == 'relative'): ?>
								<?php echo $comment->commentDate; ?>
								<?php else: ?>
								<?php echo JHTML::_('date', $comment->commentDate, JText::_('DATE_FORMAT_LC3')); ?>
								<?php endif; ?>
							</p>
							<?php endif; ?>


						</div>

					</div>

				</div>

				<div class="cmt-content">
					
					<p><?php echo $comment->commentText; ?></p>

				</div>

			</div>

			<?php if($params->get('itemCategory')): ?>
			<span class="lcItemCategory"> in <a href="<?php echo $comment->catLink; ?>"><?php echo $comment->categoryname; ?></a></span>
			<?php endif; ?>
			<div class="clr"></div>
		</li>
		<?php endforeach; ?>
		<li class="clearList"></li>
	</ul>
	<?php endif; ?>
	<?php if($params->get('feed')): ?>
	<div class="k2FeedIcon"> <a href="<?php echo JRoute::_('index.php?option=com_k2&view=itemlist&format=feed&moduleID='.$module->id); ?>" title="<?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?>"> <i class="jsn-icon-rss"></i><span><?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?></span> </a>
		<div class="clr"></div>
	</div>
	<?php endif; ?>
</div>
