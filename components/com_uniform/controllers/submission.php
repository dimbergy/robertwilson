<?php

/**
 * @version     $Id: submission.php 19013 2012-11-28 04:48:47Z thailv $
 * @package     JSNUniform
 * @subpackage  Controller
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2016 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');

/**
 * Layout controllers of JControllerAdmin
 *
 * @package     Controllers
 * @subpackage  Submission
 * @since       1.6
 */
class JSNUniformControllerSubmission extends JControllerForm
{

	protected $option = JSN_UNIFORM;

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   11.1
	 */
	public function save($key = null, $urlVar = null)
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$input = JFactory::getApplication()->input;
		$postData = $input->getArray($_POST);
		// Get the model.
		$model = $this->getModel('submission');

		// Remove the items.
		if ($model->save($postData))
		{
			$this->setMessage(JText::_("JLIB_APPLICATION_SAVE_SUCCESS"));
		}
		else
		{
			$this->setMessage($model->getError());
		}
		$task = $this->getTask();

		if ($task == "save")
		{
			// Redirect to the list screen.
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend(), false));
		}
		else
		{
			$dataId = isset($postData['cid'])?$postData['cid']:0;
			$this->setRedirect(JRoute::_('index.php?option=com_uniform&view=submission&submission_id=' . (int) $dataId, false), JText::_('JLIB_APPLICATION_SAVE_SUCCESS'),'message');
		}
	}

	/**
	 * Method to save and next a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   11.1
	 */
	public function saveNext($key = null, $urlVar = null)
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$input = JFactory::getApplication()->input;
		$postData = $input->getArray($_POST);
		// Get the model.
		$model = $this->getModel('submission');

		// Remove the items.
		if ($model->save($postData))
		{
			$this->setMessage(JText::_("JLIB_APPLICATION_SAVE_SUCCESS"));
		}
		else
		{
			$this->setMessage($model->getError());
		}

		$dataId = isset($postData['nextId'])?$postData['nextId']:0;
		$this->setRedirect(JRoute::_('index.php?option=com_uniform&view=submission&submission_id=' . (int) $dataId, false), JText::_('JLIB_APPLICATION_SAVE_SUCCESS'),'message');
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   11.1
	 */
	public function cancel($key = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$table = $model->getTable();
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";

		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		$recordId = JRequest::getInt($key);

		// Clean the session data and redirect.
		$this->releaseEditId($context, $recordId);
		$app->setUserState($context . '.data', null);

		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend(), false));

		return true;
	}
}
