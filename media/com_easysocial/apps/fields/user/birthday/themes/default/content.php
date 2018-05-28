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
<div data-field-birthday data-calendar="<?php echo $params->get( 'calendar', false ); ?>" data-format="<?php echo $params->get( 'date_format' ); ?>" data-yearfrom="<?php echo $params->get( 'yearfrom' ); ?>" data-yearto="<?php echo $params->get( 'yearto' ); ?>">
	<?php if( $params->get( 'calendar' , false ) ){ ?>
		<div <?php if(empty($date)) { ?>style="display: none;"<?php } ?>>
			<?php echo JText::_( 'PLG_FIELDS_BIRTHDAY_SELECTED_DATE' ); ?>: <a href="javascript:void(0);" data-field-birthday-selected><?php echo !empty($date) ? $date : ''; ?></a>
		</div>
		<div id="fd_" class="ui">
			<div class="datepicker-wrap" data-field-birthday-select data-date="<?php echo $date; ?>"></div>
		</div>
		<input type="hidden" id="<?php echo $inputName; ?>" name="<?php echo $inputName; ?>" value="<?php echo $date; ?>" data-field-birthday-value />

	<?php } else { ?>
		<?php if( $params->get( 'date_format' , 1 ) == 1 ){ ?>
			<!-- DD/MM/YYYY -->
			<?php echo $this->includeTemplate( 'fields/user/birthday/form.day' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/birthday/form.month' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/birthday/form.year' ); ?>
		<?php } ?>

		<?php if( $params->get( 'date_format' , 1 ) == 2 ){ ?>
			<!-- MM/DD/YYYY -->
			<?php echo $this->includeTemplate( 'fields/user/birthday/form.month' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/birthday/form.day' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/birthday/form.year' ); ?>
		<?php } ?>

		<?php if( $params->get( 'date_format' , 1 ) == 3 ){ ?>
			<!-- YYYY/DD/MM -->
			<?php echo $this->includeTemplate( 'fields/user/birthday/form.year' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/birthday/form.day' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/birthday/form.month' ); ?>
		<?php } ?>

		<?php if( $params->get( 'date_format' , 1 ) == 4 ){ ?>
			<!-- YYYY/MM/DD -->
			<?php echo $this->includeTemplate( 'fields/user/birthday/form.year' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/birthday/form.month' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/birthday/form.day' ); ?>
		<?php } ?>

	<?php } ?>

	<?php if( $yearPrivacy ) { ?>
		<div class="data-field-birthday-yearprivacy mt-10">
			<h4><?php echo JText::_( 'PLG_FIELDS_BIRTHDAY_YEAR_PRIVACY_TITLE' ); ?></h4>
			<div>
				<?php echo JText::_( 'PLG_FIELDS_BIRTHDAY_YEAR_PRIVACY_INFO' ); ?>
				<div class="es-privacy pull-right">
					<?php echo Foundry::privacy()->form( $field->id, 'field.birthday.year', $this->my->id, 'core.view' );?>
				</div>
			</div>
		</div>
 	<?php } ?>
</div>
