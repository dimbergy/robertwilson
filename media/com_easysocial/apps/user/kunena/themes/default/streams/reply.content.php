<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div class="row-fluid mb-10 mt-10 kunena-stream-content">
	<div class="span12">
		<div class="post-details">
			<h3>
				<?php echo $topic->getIcon();?>
				<a href="<?php echo $topic->getUrl();?>"><?php echo $message->subject;?></a>
			</h3>
			<?php echo $message->message;?>
			<br /><br />
			<a href="<?php echo $topic->getPermaUrl();?>#<?php echo $message->id;?>" class="btn btn-es"><?php echo JText::_( 'APP_KUNENA_BTN_VIEW_REPLY' ); ?></a>
			<a href="<?php echo $topic->getPermaUrl();?>" class="btn btn-es-primary"><?php echo JText::_( 'APP_KUNENA_BTN_VIEW_THREAD' ); ?></a>
		</div>
	</div>
</div>
