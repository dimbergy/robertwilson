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
<div class="es-stream-preview row-fluid">
	<div class="mt-10">
		<div class="stream-preview-title">
			<a target="_blank" href="<?php echo $assets->get( 'link' );?>"><i class="ies-link"></i> <?php echo $assets->get( 'title' ); ?></a>
		</div>

		<p class="mt-5 small">
			<?php if( $assets->get( 'image' ) ){ ?>

				<?php if( isset( $oembed->html ) ){ ?>
					<a href="javascript:void(0);" class="stream-preview-image" data-es-links-embed-item data-es-stream-embed-player="<?php echo $this->html( 'string.escape' , $oembed->html );?>">
						<img src="<?php echo $oembed->thumbnail;?>" />
						<i class="icon-es-video-play"></i>
					</a>
				<?php } else { ?>
					<a href="<?php echo $assets->get( 'link' );?>" class="stream-preview-image" target="_blank">
						<img src="<?php echo $assets->get( 'image' );?>" />
					</a>
				<?php } ?>
			<?php } ?>

			<?php echo $assets->get( 'content' , 'No description available.'); ?>
		</p>
	</div>
</div>
