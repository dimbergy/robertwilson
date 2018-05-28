<?php
/**
 * Kunena Component
 * @package     Kunena.Site
 * @subpackage  Controller.Application
 *
 * @copyright   (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link        https://www.kunena.org
 **/
defined('_JEXEC') or die;

/**
 * Class ComponentKunenaControllerApplicationAjaxDefaultDisplay
 *
 * @since  K4.0
 */
class ComponentKunenaControllerApplicationAjaxDefaultDisplay extends KunenaControllerApplicationDisplay
{
	/**
	 * Return true if layout exists.
	 *
	 * @return bool
	 */
	public function exists()
	{
		return KunenaFactory::getTemplate()->isHmvc();
	}

	/**
	 * Return AJAX for the requested layout.
	 *
	 * @return string  String in JSON or RAW.
	 *
	 * @throws RuntimeException
	 * @throws KunenaExceptionAuthorise
	 */
	public function execute()
	{
		$format = $this->input->getWord('format', 'html');
		$function = 'display' . ucfirst($format);

		if (!method_exists($this, $function))
		{
			// Invalid page request.
			throw new KunenaExceptionAuthorise(JText::_('COM_KUNENA_NO_ACCESS'), 404);
		}

		// Run before executing action.
		$result = $this->before();

		if ($result === false)
		{
			$content = new KunenaExceptionAuthorise(JText::_('COM_KUNENA_NO_ACCESS'), 404);
		}
		elseif (!JSession::checkToken())
		{
			// Invalid access token.
			$content = new KunenaExceptionAuthorise(JText::_('COM_KUNENA_ERROR_TOKEN'), 403);
		}
		elseif ($this->config->board_offline && !$this->me->isAdmin())
		{
			// Forum is offline.
			$content = new KunenaExceptionAuthorise(JText::_('COM_KUNENA_FORUM_IS_OFFLINE'), 503);
		}
		elseif ($this->config->regonly && !$this->me->exists())
		{
			// Forum is for registered users only.
			$content = new KunenaExceptionAuthorise(JText::_('COM_KUNENA_LOGIN_NOTIFICATION'), 401);
		}
		else
		{
			$display = $this->input->getCmd('display', 'Undefined') . '/Display';

			try
			{
				$content = KunenaRequest::factory($display, $this->input, $this->options)
					->setPrimary()->execute()->render();
			}
			catch (Exception $e)
			{
				$content = $e;
			}
		}

		return $this->$function($content);
	}

	/**
	 * Prepare AJAX display.
	 *
	 * @return void
	 */
	protected function before()
	{
		// Load language files.
		KunenaFactory::loadLanguage('com_kunena.sys', 'admin');
		KunenaFactory::loadLanguage('com_kunena.templates');
		KunenaFactory::loadLanguage('com_kunena.models');
		KunenaFactory::loadLanguage('com_kunena.views');

		$this->me = KunenaUserHelper::getMyself();
		$this->config = KunenaConfig::getInstance();
		$this->document = JFactory::getDocument();
		$this->template = KunenaFactory::getTemplate();
		$this->template->initialize();
	}

	/**
	 * Display output as RAW.
	 *
	 * @param   mixed  $content  Content to be returned.
	 *
	 * @return  string
	 */
	public function displayRaw($content)
	{
		if ($content instanceof Exception)
		{
			$this->setResponseStatus($content->getCode());

			return $content->getCode() . ' ' . $content->getMessage();
		}

		return (string) $content;
	}

	/**
	 * Display output as JSON.
	 *
	 * @param   mixed  $content  Content to be returned.
	 *
	 * @return  string
	 */
	public function displayJson($content)
	{
		// Tell the browser that our response is in JSON.
		header('Content-type: application/json', true);

		// Create JSON response.
		$response = new KunenaResponseJson($content);

		// In case of an error we want to set HTTP error code.
		if (!$response->success)
		{
			// We want to wrap the exception to be able to display correct HTTP status code.
			$error = new KunenaExceptionAuthorise($response->message, $response->code);
			header('HTTP/1.1 ' . $error->getResponseStatus(), true);
		}

		echo json_encode($response);

		// It's much faster and safer to exit now than let Joomla to send the response.
		JFactory::getApplication()->close();
	}
}
