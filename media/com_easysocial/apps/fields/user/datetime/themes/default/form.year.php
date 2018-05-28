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
<?php if( $yearRange === false ) { ?>
<input type="text"
class="input input-mini"
name="<?php echo $inputName;?>[year]"
value="<?php echo $year; ?>"
placeholder="YYYY"
data-field-datetime-year />
<?php } else { ?>
<select data-field-datetime-year name="<?php echo $inputName; ?>[year]" style="width: 80px;">
	<option value=""></option>

	<?php foreach( $range as $v ) { ?>
	<option value="<?php echo $v; ?>" <?php if( $year == $v ) { ?>selected="selected"<?php } ?>><?php echo $v; ?></option>
	<?php } ?>
</select>
<?php }
