<?php
/**
 * Kunena Component
 * @package Kunena.Framework
 * @subpackage Email
 *
 * @copyright (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link https://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

/**
 * Class KunenaEmail
 */
abstract class KunenaEmail
{
	/**
	 * @param  JMail  $mail
	 * @param  array  $receivers
	 *
	 * @return boolean
	 */
	public static function send(JMail $mail, array $receivers)
	{
		$config = KunenaFactory::getConfig();

		if (!empty($config->email_recipient_count))
		{
			$email_recipient_count = $config->email_recipient_count;
		}
		else
		{
			$email_recipient_count = 1;
		}

		$email_recipient_privacy = $config->get('email_recipient_privacy', 'bcc');

		// If we hide email addresses from other users, we need to add TO address to prevent email from becoming spam.
		if ($email_recipient_count > 1
			&& $email_recipient_privacy == 'bcc'
			&& JMailHelper::isEmailAddress($config->get('email_visible_address')))
		{
			$mail->AddAddress($config->email_visible_address, JMailHelper::cleanAddress($config->board_title));

			// Also make sure that email receiver limits are not violated (TO + CC + BCC = limit).
			if ($email_recipient_count > 9)
			{
				$email_recipient_count--;
			}
		}

		$chunks = array_chunk($receivers, $email_recipient_count);

		$success = true;
		foreach ($chunks as $emails)
		{
			if ($email_recipient_count == 1 || $email_recipient_privacy == 'to')
			{
				echo 'TO ';
				$mail->ClearAddresses();
				$mail->addRecipient($emails);
			}
			elseif ($email_recipient_privacy == 'cc')
			{
				echo 'CC ';
				$mail->ClearCCs();
				$mail->addCC($emails);
			}
			else
			{
				echo 'BCC ';
				$mail->ClearBCCs();
				$mail->addBCC($emails);
			}

			try
			{
				$mail->Send();
			}
			catch (Exception $e)
			{
				$success = false;
				JLog::add($e->getMessage(), JLog::ERROR, 'kunena');
			}
		}

		return $success;
	}
}
