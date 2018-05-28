<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

/**
 * Profile view for Notes app.
 *
 * @since	1.0
 * @access	public
 */
class BirthdayFieldWidgetsProfile
{
	/**
	 * Renders the age of the user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAge( $value )
	{
		$birthDate 		= new DateTime( $value );
		$age			= $birthDate->diff( new DateTime );

		return $age->y;
	}

	/**
	 * Displays the age in the position profileHeaderA
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function profileHeaderA( $key , $user , $field )
	{
		$my = Foundry::user();
		$privacyLib = Foundry::privacy( $my->id );
		if( !$privacyLib->validate( 'core.view' , $field->id, SOCIAL_TYPE_FIELD , $user->id ) )
		{
			return;
		}

		// Get the current stored value.
		$value 	= $field->data;

		if( empty( $value ) )
		{
			return false;
		}

		$data = new stdClass();

		if( substr( $value, 0, 1 ) === '{' && substr( $value, -1, 1 ) === '}' )
		{
			$data = Foundry::json()->decode( $value );
		}
		else
		{
			$tmp = Foundry::date( $value, false );
			$data->year = $tmp->toFormat( 'Y' );
			$data->month = $tmp->toFormat( 'n' );
			$data->day = $tmp->toFormat( 'j' );
		}

		if( !isset( $data->year ) )
		{
			$data->year = '';
		}

		if( !isset( $data->month ) )
		{
			$data->month = '';
		}

		if( !isset( $data->day ) )
		{
			$data->day = '';
		}

		$date = null;

		if( !empty( $data->year ) && !empty( $data->month ) && !empty( $data->day ) )
		{
			$date = $data->year . '-' . $data->month . '-' . $data->day;
		}

		if( !$date )
		{
			return;
		}

		// Compute the age now.
		$age 	= $this->getAge( $date );

		$theme 	= Foundry::themes();
		$theme->set( 'value'	, $age );
		$theme->set( 'params'	, $field->getParams() );

		echo $theme->output( 'fields/user/birthday/widgets/display' );
	}
}
