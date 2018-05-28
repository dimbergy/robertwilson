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
<div data-field-datetime
	data-calendar="<?php echo $params->get( 'calendar' ); ?>"
	data-format="<?php echo $params->get('date_format' ); ?>"
	<?php if( $yearRange ) { ?>
	data-yearfrom="<?php echo $yearRange->min; ?>"
	data-yearto="<?php echo $yearRange->max; ?>"
	<?php } ?>
>
	<?php if( $params->get( 'calendar' ) ){ ?>
		<div <?php if(empty($date)) { ?>style="display: none;"<?php } ?>>
			<?php echo JText::_( 'PLG_FIELDS_DATETIME_SELECTED_DATE' ); ?>: <a href="javascript:void(0);" data-field-datetime-selected><?php echo !empty($date) ? $date : ''; ?></a>
		</div>
		<div id="fd_" class="ui">
			<div class="datepicker-wrap" data-field-datetime-select data-date="<?php echo $date; ?>"></div>
		</div>
		<input type="hidden" id="<?php echo $inputName; ?>" name="<?php echo $inputName; ?>" value="<?php echo $date; ?>" data-field-datetime-value />
	<?php } else { ?>
		<?php if( $params->get( 'date_format' ) == 1 ){ ?>
			<!-- DD/MM/YYYY -->
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.day' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.month' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.year' ); ?>
		<?php } ?>

		<?php if( $params->get( 'date_format' ) == 2 ){ ?>
			<!-- MM/DD/YYYY -->
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.month' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.year' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.day' ); ?>
		<?php } ?>

		<?php if( $params->get( 'date_format' ) == 3 ){ ?>
			<!-- YYYY/DD/MM -->
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.year' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.day' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.month' ); ?>
		<?php } ?>

		<?php if( $params->get( 'date_format' ) == 4 ){ ?>
			<!-- YYYY/MM/DD -->
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.year' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.month' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.day' ); ?>
		<?php } ?>

	<?php } ?>

	<?php if( $yearPrivacy ) { ?>
		<div class="data-field-datetime-yearprivacy mt-10">
			<h4><?php echo JText::_( 'PLG_FIELDS_DATETIME_YEAR_PRIVACY_TITLE' ); ?></h4>
			<div>
				<?php echo JText::_( 'PLG_FIELDS_DATETIME_YEAR_PRIVACY_INFO' ); ?>
				<div class="es-privacy pull-right">
					<?php echo Foundry::privacy()->form( $field->id, 'field.datetime.year', $this->my->id, 'core.view' );?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
