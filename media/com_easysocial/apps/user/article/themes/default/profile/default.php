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
<div class="app-article-wrapper profile" data-article>
	<?php if( $articles ){ ?>
	<ul class="unstyled article-list" data-article-lists>
		<?php foreach( $articles as $article ){ ?>
			<?php echo $this->loadTemplate( 'themes:/apps/user/article/profile/item' , array( 'article' => $article ) ); ?>
		<?php } ?>
	</ul>
	<hr />
	<?php } else { ?>
	<div class="empty center">
		<?php echo $user->getName();?> <?php echo JText::_( 'APP_BLOG_PROFILE_NO_BLOG_POSTS_CURRENTLY' ); ?>
	</div>
	<?php } ?>

</div>
