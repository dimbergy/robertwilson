<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<a href="<?php echo $actor->getPermalink();?>"><?php echo $actor->getName();?></a> <?php echo JText::sprintf( 'APP_KUNENA_REPLIED_TOPIC_IN' , $topic->getUrl() , $topic->subject );?> 
<a href="<?php echo $topic->getCategory()->getUrl();?>"><?php echo $topic->getCategory()->name;?></a>.