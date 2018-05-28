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
			<span class="vote-count" 
				data-original-title="<?php echo JText::_( 'APP_KUNENA_TOTAL_HITS' );?>" 
				data-es-provide="tooltip"
				data-placement="bottom"
			><?php echo $post->hits;?></span>
		</div>

		<div class="post-info">
			<div class="post-title">
				<a href="#"><?php echo $post->subject ?></a>
			</div>

			<div class="post-meta">
				<?php echo JText::_( 'APP_KUNENA_IN' );?> <a href="#"><?php echo $post->name;?></a>
			</div>
		</div>
	</div>
</li>