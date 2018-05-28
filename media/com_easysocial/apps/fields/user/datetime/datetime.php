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
 * Field application for date time
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserDateTime extends SocialFieldItem
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Displays the field input for user when they register their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegister( &$post, &$registration )
	{
		$data = new stdClass();

		if( !empty( $post[ $this->inputName ] ) )
		{
			$data = $this->getDatetimeValue( $post[ $this->inputName ] );
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
			$date = Foundry::date( $data->year . '-' . $data->month . '-' . $data->day, false );
		}

		if( $this->params->get( 'calendar' ) )
		{
			if( $date )
			{
				$format 	= $this->getDateFormat();
				$value		= $date->toFormat( $format );
			}
			$this->set( 'date', $value );
		}
		else
		{
			$maxDay	= 31;

			if( $date )
			{
				$maxDay	= $date->toFormat( 't' );
			}

			$this->set( 'day' 	, $data->day );
			$this->set( 'month'	, $data->month );
			$this->set( 'year'	, $data->year );
			$this->set( 'maxDay', $maxDay );
		}

		// Check for errors
		$error		= $registration->getErrors( $this->inputName );

		// Set errors.
		$this->set( 'error', $error );

		$this->set( 'yearPrivacy', false );

		$yearRange = $this->getYearRange();

		$range = array();

		if( $yearRange !== false )
		{
			$range = range( $yearRange->min, $yearRange->max );
		}

		$this->set( 'yearRange', $yearRange );

		$this->set( 'range', $range );

		// Display the output.
		return $this->display();
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialTableRegistration		The registration ORM table.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegisterValidate( &$post, SocialTableRegistration &$registration )
	{
		return $this->validateDatetime( $post );
	}

	/**
	 * Executes before a user's registration is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegisterBeforeSave( &$post )
	{
		return $this->saveDatetime( $post );
	}

	/**
	 * Displays the field input for user when they edit their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array		The post data
	 * @param	SocialUser	The user object
	 * @param	Array		The error data.
	 * @return	string		The html output.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		$data = new stdClass();

		if( !empty( $post[ $this->inputName ] ) )
		{
			$data = $this->getDatetimeValue( $post[ $this->inputName ] );
		}
		else
		{
			if( !empty( $this->value ) )
			{
				if( substr( $this->value, 0, 1 ) === '{' && substr( $this->value, -1, 1 ) === '}' )
				{
					$data = Foundry::json()->decode( $this->value );
				}
				else
				{
					$tmp = Foundry::date( $this->value, false );
					$data->year = $tmp->toFormat( 'Y' );
					$data->month = $tmp->toFormat( 'n' );
					$data->day = $tmp->toFormat( 'j' );
				}
			}
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
			$date = Foundry::date( $data->year . '-' . $data->month . '-' . $data->day, false );
		}

		if( $this->params->get( 'calendar' ) )
		{
			$value = '';

			if( $date )
			{
				$format 	= $this->getDateFormat();
				$value = $date->toFormat( $format );
			}

			$this->set( 'date', $value );
		}
		else
		{
			$maxDay	= 31;

			if( $date )
			{
				$maxDay	= $date->toFormat( 't' );
			}

			$this->set( 'day' 	, $data->day );
			$this->set( 'month'	, $data->month );
			$this->set( 'year'	, $data->year );
			$this->set( 'maxDay', $maxDay );
		}

		// Check for errors
		$error = $this->getError( $errors );

		// Set errors.
		$this->set( 'error'	, $error );

		$this->set( 'yearPrivacy', $this->params->get( 'year_privacy' ) );

		$yearRange = $this->getYearRange();

		$range = array();

		if( $yearRange !== false )
		{
			$range = range( $yearRange->min, $yearRange->max );
		}

		$this->set( 'yearRange', $yearRange );

		$this->set( 'range', $range );

		// Display the output.
		return $this->display();
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEditValidate( &$post )
	{
		return $this->validateDatetime( $post );
	}

	/**
	 * Executes before a user's registration is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialUser	The user object
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEditBeforeSave( &$post, SocialUser &$user )
	{
		return $this->saveDatetime( $post );
	}

	/**
	 * Responsible to output the html codes that is displayed to
	 * a user when their profile is viewed.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser	The user object
	 * @return	string	The html output.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onDisplay( $user )
	{
		if( empty( $this->value ) )
		{
			return;
		}

		if( !$this->allowedPrivacy( $user ) )
		{
			return;
		}

		$data = new stdClass();

		if( substr( $this->value, 0, 1 ) === '{' && substr( $this->value, -1, 1 ) === '}' )
		{
			$data = Foundry::json()->decode( $this->value );
		}
		else
		{
			$tmp = Foundry::date( $this->value, false );
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
			$date = Foundry::date( $data->year . '-' . $data->month . '-' . $data->day, false );
		}

		if( !$date )
		{
			return;
		}

		$allowYear = true;

		if( $this->params->get( 'year_privacy' ) )
		{
			$lib = Foundry::privacy( Foundry::user()->id );
			$allowYear = $lib->validate( 'core.view', $this->field->id, 'field.datetime.year', $user->id );
		}

		$format = $allowYear ? 'd M Y' : 'd M';

		switch( $this->params->get( 'date_format' ) )
		{
			case 2:
			case '2':
				$format = $allowYear ? 'M d, Y' : 'M d';
				break;
			case 3:
			case '3':
				$format = $allowYear ? 'Y d M' : 'd M';
				break;
			case 4:
			case '4':
				$format = $allowYear ? 'Y M d' : 'M d';
				break;
		}

		// Push variables into theme.
		$this->set( 'date', $date );
		$this->set( 'format', $format );

		$this->set( 'allowYearSettings', Foundry::user()->id === $user->id );

		return $this->display();
	}

	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onSample()
	{
		$this->set( 'yearPrivacy', $this->params->get( 'year_privacy' ) );

		$yearRange = $this->getYearRange();

		$range = array();

		if( $yearRange !== false )
		{
			$range = range( $yearRange->min, $yearRange->max );
		}

		$this->set( 'yearRange', $yearRange );

		$this->set( 'range', $range );

		return $this->display();
	}

	private function getDatetimeValue( $data )
	{
		// If current setup is to use calendar, we need to obtain the proper values
		if( $this->params->get( 'calendar' ) )
		{
			if( empty( $data ) )
			{
				return false;
			}

			$obj = $this->reconstructCalendarDate( $data );

			return $obj;
		}

		// Since the values are stored differently we need to compute the date back.
		if( is_array( $data ) )
		{
			$data = (object) $data;
		}

		if( is_string( $data ) )
		{
			$data = Foundry::json()->decode( $data );
		}

		$year 	= isset( $data->year ) ? $data->year : '';
		$month	= isset( $data->month ) ? $data->month : '';
		$day 	= isset( $data->day ) ? $data->day : '';

		$obj 		= new stdClass();
		$obj->year	= $year;
		$obj->month	= $month;
		$obj->day 	= $day;

		return $obj;
	}

	private function reconstructCalendarDate( $data )
	{
		$format = $this->getDateFormat();

		$format = explode( '/', $format );

		$data = explode( '/', $data );

		$date = new stdClass();

		foreach( $format as $i => $f )
		{
			if( $f === 'd' )
			{
				$date->day = $data[$i];
			}

			if( $f === 'm' )
			{
				$date->month = $data[$i];
			}

			if( $f === 'Y' )
			{
				$date->year = $data[$i];
			}
		}

		return $date;
	}

	/**
	 * Performs php validation on this field
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function validateDatetime( &$post )
	{
		$value 		= isset( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		// Determines if this field is required
		$required 	= $this->isRequired();

		if( $required && empty( $value ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_PLEASE_ENTER_DATE' ) );
		}

		$data	= $this->getDatetimeValue( $value );

		if( $required && ( empty( $data->year ) || empty( $data->month ) || empty( $data->day ) || !strtotime( $data->day . '-' . $data->month . '-' . $data->year ) ) )
		{
			if( $this->params->get( 'calendar' ) )
			{
				return $this->setError( JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_PLEASE_SELECT_DATETIME' ) );
			}

			return $this->setError( JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_PLEASE_ENTER_DATE' ) );
		}

		// Check for year range
		$range = $this->getYearRange();
		if( $range !== false && !empty( $data->year ) && ( $data->year < $range->min || $data->year > $range->max ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_YEAR_OUT_OF_RANGE' ) );
			return false;
		}

		if( empty( $data->year ) && empty( $data->month ) && empty( $data->day ) )
		{
			// If all data are empty, then just unset it
			$post[ $this->inputName ] = '';

			return true;
		}
		else
		{
			if( empty( $data->year ) || empty( $data->month ) || empty( $data->day ) || !strtotime( $data->day . '-' . $data->month . '-' . $data->year ) )
			{
				$post[ $this->inputName ] = '';

				// We set the error msg on info instead of field error because the value will be reverted to the original value
				Foundry::info()->set( (object) array( 'message' => JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_INVALID_DATE_FORMAT' ), 'type' => SOCIAL_MSG_ERROR ) );

				// Manually set this flag to true instead of using setError
				$this->hasErrors = true;

				return false;
			}
		}

		return true;
	}

	private function saveDatetime( &$post )
	{
		$value 		= isset( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		// Since the values are stored differently we need to compute the date back.
		$data		= $this->getDatetimeValue( $value );

		if( !empty( $data->year ) && !empty( $data->month ) && !empty( $data->day ) )
		{
			// Let's set this value back to the proper element.
			$date = Foundry::date( $data->year . '-' . $data->month . '-' . $data->day, false );
			$raw = $data->year . ' ' . $date->toFormat( 'F' ) . ' ' . $data->day;
			$post[ $this->inputName ] = array( 'data' => Foundry::json()->encode( $data ), 'raw' => $raw );
		}
		else
		{
			unset( $post[ $this->inputName ] );
		}

		return true;
	}

	private function getDateFormat()
	{
		$format = '';

		switch( $this->params->get( 'date_format') )
		{
			case 2:
			case '2':
				$format = 'm/d/Y';
				break;

			case 3:
			case '3':
				$format = 'Y/d/m';
				break;

			case 4:
			case '4':
				$format = 'Y/m/d';
				break;

			case 1:
			case '1':
			default:
				$format = 'd/m/Y';
				break;
		}

		return $format;
	}

	public function getYearRange()
	{
		$currentYear = Foundry::date()->toFormat( 'Y' );

		$minyear = $this->params->get( 'yearfrom' );
		$maxyear = $this->params->get( 'yearto' );

		if( empty( $minyear ) && empty( $maxyear ) )
		{
			return false;
		}

		if( empty( $minyear ) )
		{
			$minyear = '1930';
		}

		if( empty( $maxyear ) )
		{
			$maxyear = $currentYear;
		}

		if( stristr( $minyear, '-' ) || stristr ( $minyear, '+' ) )
		{
			$minyear = $currentYear + $minyear;
		}

		if( stristr( $maxyear, '-' ) || stristr ( $maxyear, '+' ) )
		{
			$maxyear = $currentYear + $maxyear;
		}

		$range = (object) array(
			'min' => $minyear,
			'max' => $maxyear
		);

		return $range;
	}
}
