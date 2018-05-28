<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Template.Crypsis
 * @subpackage      Layout.User
 *
 * @copyright   (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die;

/** @var KunenaUser $user */
$user   = $this->user;
$avatar = $user->getAvatarImage('img-polaroid', 'thumb');
$show   = KunenaConfig::getInstance()->showuserstats;

if ($show)
{
	$rankImage    = $user->getRank($this->category_id, 'image');
	$rankTitle    = $user->getRank($this->category_id, 'title');
	$personalText = $user->getPersonalText();
}
?>

<div class="span2">
	<ul class="profilebox center">
		<li>
			<strong><?php echo $user->getLink(null, null, 'nofollow', '', null, $this->category_id); ?></strong>
		</li>
		<?php if ($avatar) : ?>
			<li>
				<?php echo $user->getLink($avatar); ?>
				<?php if (isset($this->topic_starter) && $this->topic_starter) : ?>
					<span class="topic-starter"><?php echo JText::_('COM_KUNENA_TOPIC_AUTHOR') ?></span>
				<?php endif;?>
				<?php if (!$this->topic_starter && $user->isModerator()) : ?>
					<span class="topic-moderator"><?php echo JText::_('COM_KUNENA_MODERATOR') ?></span>
				<?php endif;?>
			</li>
		<?php endif; ?>
		<?php if ($user->exists()) : ?>
			<li>
				<span class="label label-<?php echo $user->isOnline('success', 'important') ?>">
					<?php echo $user->isOnline(JText::_('COM_KUNENA_ONLINE'), JText::_('COM_KUNENA_OFFLINE')); ?>
				</span>
			</li>
		<?php endif; ?>
	</ul>
</div>
<div class="span2">
	<br>
	<?php if (!empty($rankTitle)) : ?>
		<li>
			<?php echo $this->escape($rankTitle); ?>
		</li>
	<?php endif; ?>

	<?php if (!empty($rankImage)) : ?>
		<li>
			<?php echo $rankImage; ?>
		</li>
	<?php endif; ?>

	<?php if (!empty($personalText)) : ?>
		<li>
			<?php echo $personalText; ?>
		</li>
	<?php endif; ?>
</div>

<div class="span2">
	<br>
	<?php if ($user->posts >= 1) : ?>
		<li>
			<strong> <?php echo JText::_('COM_KUNENA_POSTS'); ?> </strong>
			<span> <?php echo JText::sprintf((int)$user->posts); ?> </span>
		</li>
	<?php endif; ?>

	<?php if ($show && isset($user->thankyou)) : ?>
		<li>
			<strong> <?php echo JText::_('COM_KUNENA_THANK_YOU_RECEIVED'); ?>:</strong>
			<span> <?php echo JText::sprintf((int)$user->thankyou); ?> </span>
		</li>
	<?php endif; ?>
	<?php if (isset($user->points)) : ?>
		<li>
			<strong> <?php echo JText::_('COM_KUNENA_AUP_POINTS'); ?> </strong>
			<span> <?php echo (int)$user->points; ?> </span>
		</li>
	<?php endif; ?>
	<?php if ($show && !empty($user->medals)) : ?>
		<li>
			<?php echo implode(' ', $user->medals); ?>
		</li>
	<?php endif; ?>
</div>
<div class="span3">
	<br>
	<li>
		<strong> <?php echo JText::_('COM_KUNENA_MYPROFILE_GENDER'); ?>:</strong>
		<span> <?php echo $user->getGender(); ?> </span>
	</li>
	<li>
		<strong> <?php echo JText::_('COM_KUNENA_MYPROFILE_BIRTHDATE'); ?>:</strong>
		<span> <?php echo KunenaDate::getInstance($user->birthdate)->toSpan('date', 'ago', 'utc'); ?> </span>
	</li>
</div>
<?php echo $this->subLayout('Widget/Module')->set('position', 'kunena_profile_horizontal'); ?>
