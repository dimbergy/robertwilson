<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div class="es-story-attachments">
	<ul class="es-story-attachment-buttons col-<?php echo count($story->attachments); ?>"
		data-story-attachment-buttons>

		<li class="es-story-attachment-button for-text"
			data-story-attachment-clear-button><i class="ies-cancel"></i> <?php echo JText::_('COM_EASYSOCIAL_STORY_REMOVE_ATTACHMENT'); ?></li>

		<?php foreach ($story->attachments as $attachment) { ?>
			<li class="es-story-attachment-button <?php echo $attachment->button->classname; ?>"
				data-story-attachment-button
				data-story-plugin-name="<?php echo $attachment->name; ?>"
			><?php echo $attachment->button->html; ?></li>
		<?php } ?>
	</ul>
</div>