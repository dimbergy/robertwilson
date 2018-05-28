<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div class="row-fluid app-user-kunena-profile">
	
	<div class="row-fluid stat-meta">
		<div class="span4 stat-item">
			<div class="total-posts">
				<div class="center"><?php echo JText::_( 'APP_KUNENA_TOTAL_POSTS' ); ?></div>

				<div class="stat-points"><?php echo $totalPosts;?>k</div>
			</div>
		</div>

		<div class="span4 stat-item">
			<div class="total-replies">
				<div class="center"><?php echo JText::_( 'APP_KUNENA_KARMA' ); ?></div>

				<div class="stat-points"><?php echo $karma;?></div>
			</div>
		</div>

		<div class="span4 stat-item">
			<div class="total-votes">
				<div class="center"><?php echo JText::_( 'APP_KUNENA_CHART_RECENT_USER_ACTIVITY' ); ?></div>

				<div class="stat-points">
					<span data-kunena-posts-chart><?php echo implode( ',' , array( 5, 10 , 5 , 3 , 4 , 5 , 6 ) ); ?></span>
				</div>
			</div>
		</div>
	</div>

	<?php if( $params->get( 'discuss-recent' , true ) ){ ?>
	<div class="discussions-list">
		<h4><?php echo JText::_( 'APP_KUNENA_RECENT_FORUM_POSTS' ); ?></h4>

		<?php if( $posts ){ ?>
		<ul class="post-items unstyled">
			<?php foreach( $posts as $topic ){ ?>
				<?php echo $this->loadTemplate( 'apps:/user/kunena/themes/default/profile/item' , array( 'topic' => $topic , 'kTemplate' => $kTemplate ) ); ?>
			<?php } ?>
		</ul>
		<?php } else { ?>
		<div class="empty">
			<?php echo JText::sprintf( 'APP_KUNENA_EMPTY_POSTS' , $user->getName() ); ?>
		</div>
		<?php } ?>
	</div>
	<?php } ?>


</div>
