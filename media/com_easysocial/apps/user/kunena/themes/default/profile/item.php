<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<li class="post-item">
	<div class="row-fluid">
		<div class="pull-left">
			<span class="hits-count" 
				data-original-title="<?php echo JText::_( 'APP_KUNENA_TOTAL_HITS_TOPIC' );?>" 
				data-es-provide="tooltip"
				data-placement="bottom"
			><?php echo $topic->hits;?></span>

			<?php echo $kTemplate->getTopicIcon( $topic ); ?>
		</div>

		<div class="post-info">
			<div class="post-title">
				<a href="<?php echo $topic->getUri( $topic->category_id );?>"><?php echo $topic->subject ?></a>
			</div>

			<div class="post-meta">
				<?php echo JText::_( 'APP_KUNENA_IN' );?> <a href="<?php echo $topic->getCategory()->getUrl();?>"><?php echo $topic->getCategory()->name;?></a> 
				 &middot; <?php echo KunenaDate::getInstance($topic->last_post_time)->toKunena('config_post_dateformat'); ?>
			</div>
		</div>
	</div>
</li>