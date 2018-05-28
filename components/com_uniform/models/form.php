<?php

/**
 * @version     $Id:
 * @package     JSNUniform
 * @subpackage  Helpers
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2016 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modelitem');
jimport('joomla.filesystem.file');

/**
 * JSNUniform model Form
 *
 * @package     Modales
 * @subpackage  Form
 * @since       1.6
 */
class JSNUniformModelForm extends JModelItem
{

	/**
	 * @var object item
	 */
	protected $item;
	protected $form_page;

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 *
	 * to be called on the first call to the getState() method unless the model
	 *
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	protected function populateState()
	{
		$input = JFactory::getApplication()->input;
		$getData = $input->getArray($_GET);
		// Get the message id
		$id = isset($getData['form_id']) ? (int) $getData['form_id'] : 0;
		$this->setState('form.id', $id);
		parent::populateState();
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   String  $type    The table name. Optional.
	 * @param   String  $prefix  The class prefix. Optional.
	 * @param   Array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   11.1
	 */
	public function getTable($type = 'JsnForm', $prefix = 'UniformTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Get data config default folder upload
	 *
	 * @return object
	 */
	private function _getDataConfig()
	{
		$this->_db->setQuery($this->_db->getQuery(true)->select('*')->from("#__jsn_uniform_config")->where("name='folder_upload'"));
		return $this->_db->loadObject();
	}

	/**
	 * Get action form
	 *
	 * @param   String  $formAction  Form action
	 * @param   String  $formData    Action data
	 * @param   Array   &$return     Return messages
	 *
	 * @return  string
	 */
	private function _getActionForm($formAction, $formData, &$return)
	{
		switch($formAction)
		{
			case 1:
				$return->actionForm = "url";
				$return->actionFormData = $formData;
				break;
			case 2:
				$this->_db->setQuery($this->_db->getQuery(true)->select('link')->from("#__menu")->where("id = " . (int) $formData));
				$menuItem = $this->_db->loadObject();
				$return->actionForm = "url";
				$return->actionFormData = isset($menuItem->link) ? $menuItem->link : '';
				break;
			case 3:
				require_once JPATH_SITE . '/components/com_content/helpers/route.php';
				$this->_db->setQuery($this->_db->getQuery(true)->select('a.catid,CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug')->from("#__content AS a")->join("LEFT", "#__categories AS cc ON a.catid = cc.id")->where('a.id = ' . (int) $formData));
				$article = $this->_db->loadObject();
				$return->actionForm = "url";
				$return->actionFormData = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid));
				break;
			case 4:
				$return->actionForm = "message";
				$return->actionFormData = $formData;
				break;
		}
	}

	/**
	 * Setting field upload and validation field type upload
	 *
	 * @param   object  $fieldSettings    Field settings
	 * @param   String  $fieldIdentifier  Field indentifier
	 * @param   String  $fieldTitle       Field title
	 * @param   Array   &$validationForm  Validation form
	 * @param   Array   $post             Post form
	 *
	 * @return void
	 */
	private function _fieldUpload($fieldSettings, $fieldIdentifier, $fieldTitle, &$validationForm, $post)
	{
		if (!empty($_FILES[$fieldIdentifier]) && count($_FILES[$fieldIdentifier]) > 0)
		{
			$dataConfig = $this->_getDataConfig();
			$url = isset($dataConfig->value) ? $dataConfig->value : "images/jsnuniform/";
			$folderRoot = JPath::clean(JPATH_ROOT . "/" . $url . "/" . '/jsnuniform_uploads/');
			$data = array();

			foreach ($_FILES[$fieldIdentifier]['name'] as $index => $fileName)
			{
				$file = array();
				$file['name'] = $_FILES[$fieldIdentifier]['name'][$index];
				$file['type'] = $_FILES[$fieldIdentifier]['type'][$index];
				$file['tmp_name'] = $_FILES[$fieldIdentifier]['tmp_name'][$index];
				$file['error'] = $_FILES[$fieldIdentifier]['error'][$index];
				$file['size'] = $_FILES[$fieldIdentifier]['size'][$index];
				$file['name'] = JFile::makeSafe($file['name']);
				if (!empty($file['name']))
				{
					$err = null;
					if (JSNUniformUpload::canUpload($file, $err, $fieldSettings))
					{
						$nameFile = strtolower(date('YmdHiS') .$fieldIdentifier. '_' .rand(100000000,9999999999999) .'_'. $file['name']);
						$filepath = JPath::clean($folderRoot . '/' . $post['form_id'] . '/' .$nameFile );
						if (JFile::upload($file['tmp_name'], $filepath))
						{
							/*
							if (!JFile::exists($folderRoot . '/.htaccess'))
							{
								$file = JPath::clean($folderRoot . '/.htaccess');
								$buffer = "RemoveHandler .php .phtml .php3 \nRemoveType .php .phtml .php3 \nphp_flag engine off \n ";
								JFile::write($file, $buffer, true);
							}
							*/
							$data[] = array("name" => $file['name'], "link" => $nameFile);
						}
						else
						{
							$validationForm[$fieldIdentifier] = JText::_('JSN_UNIFORM_ERROR_UNABLE_TO_UPLOAD_FILE');
						}
					}
					else
					{
						$validationForm[$fieldIdentifier] = $err;
					}
				}
			}
			if (!empty($data))
			{
				return json_encode($data);
			}
		}
	}

	/**
	 * Setting field type json
	 *
	 * @param   Array   $post             Post form
	 * @param   String  $fieldType        Field type
	 * @param   String  $fieldIdentifier  Field indentifier
	 *
	 * @return void
	 */
	private function _fieldJson($post, $fieldType, $fieldIdentifier)
	{
		if ($fieldType == "address" || $fieldType == "name" || $fieldType == "likert")
		{
			$postFieldIdentifier = isset($post[$fieldType][$fieldIdentifier]) ? $post[$fieldType][$fieldIdentifier] : '';
			$data = array();
			foreach ($postFieldIdentifier as $key => $item)
			{
				if (isset($item))
				{
					if ( $fieldType == "likert" ) {
						$data[ $key ] = $item;
					}
					else {
						$data[ $key ] = ( get_magic_quotes_gpc() == true || get_magic_quotes_runtime() == true ) ? stripslashes( $item ) : $item;
					}
				}
			}
			return $data ? json_encode($data) : "";
		}
		else
		{
			$postFieldIdentifier = isset($post[$fieldIdentifier]) ? $post[$fieldIdentifier] : '';
			$data = array();
			foreach ($postFieldIdentifier as $key => $item)
			{
				if (isset($item))
				{
					$data[$key] = (get_magic_quotes_gpc() == true || get_magic_quotes_runtime() == true) ? stripslashes($item) : $item;
				}
			}
			return $data ? json_encode(array_filter($data, 'strlen')) : "";
		}
	}

	/**
	 *  Setting field type Email and check validaion field type upload
	 *
	 * @param   Array   $post             Post form
	 * @param   String  $fieldIdentifier  Field indentifier
	 * @param   String  $fieldTitle       Field Title
	 * @param   Array   &$validationForm  Validation form
	 *
	 * @return array
	 */
	private function _fieldEmail($post, $fieldIdentifier, $fieldTitle, &$validationForm)
	{
		$postFieldIdentifier = isset($post[$fieldIdentifier]) ? $post[$fieldIdentifier] : '';
		$postFieldIdentifier = (get_magic_quotes_gpc() == true || get_magic_quotes_runtime() == true) ? stripslashes($postFieldIdentifier) : $postFieldIdentifier;
		$postEmail = $postFieldIdentifier;
		if ($postEmail)
		{
			jimport('joomla.mail.helper');
			$isEmailAddress = JMailHelper::isEmailAddress($postEmail);
			if (!$isEmailAddress)
			{
				$validationForm[$fieldIdentifier] = JText::sprintf('JSN_UNIFORM_FIELD_EMAIL', $fieldTitle);
			}
			else
			{
				return $postFieldIdentifier ? $postFieldIdentifier : "";
			}
		}
		else
		{
			return $postFieldIdentifier ? $postFieldIdentifier : "";
		}

	}

	/**
	 * Setting filed type Number and check validation filed type Number
	 *
	 * @param   Array   $post             Post form
	 * @param   String  $fieldIdentifier  Field indentifier
	 * @param   String  $fieldTitle       Field Title
	 * @param   Array   &$validationForm  Validation form
	 *
	 * @return array
	 */
	private function _fieldNumber($post, $fieldIdentifier, $fieldTitle, &$validationForm)
	{
		$valueNumber = array();
		if (trim($post['number'][$fieldIdentifier]) != '' && !is_array($post['number'][$fieldIdentifier]))
		{
			$valueNumber[] = $post['number'][$fieldIdentifier];
		}
		if (trim($post['number'][$fieldIdentifier]['value']) != '')
		{
			
			$valueNumber[] = $post['number'][$fieldIdentifier]['value'];
		}
		if (trim($post['number'][$fieldIdentifier]['decimal']) != '')
		{
			$valueNumber[] = $post['number'][$fieldIdentifier]['decimal'];
		}
		
		if ($valueNumber)
		{
			return implode(".", $valueNumber);
		}
	}

	/**
	 * Setting filed type Date
	 *
	 * @param   Array   $post             Post form
	 * @param   String  $fieldIdentifier  Field indentifier
	 * @param   String  $fieldTitle       Field Title
	 * @param   Array   &$validationForm  Validation form
	 *
	 * @return array
	 */
	private function _fieldDate($post, $fieldIdentifier, $fieldTitle, &$validationForm)
	{
		$valueDate = array();
		if (!empty($post['date'][$fieldIdentifier]['date']))
		{
			$valueDate[] = $post['date'][$fieldIdentifier]['date'];
		}
		if (!empty($post['date'][$fieldIdentifier]['daterange']))
		{
			$valueDate[] = $post['date'][$fieldIdentifier]['daterange'];
		}
		if ($valueDate)
		{
			return implode(" - ", $valueDate);
		}
	}

	/**
	 * Setting filed type Currency
	 *
	 * @param   Array   $post             Post form
	 * @param   String  $fieldIdentifier  Field indentifier
	 * @param   String  $fieldTitle       Field Title
	 * @param   Array   &$validationForm  Validation form
	 *
	 * @return array
	 */
	private function _fieldCurrency($post, $fieldIdentifier, $fieldTitle, &$validationForm)
	{
		$valueCurrency = array();
		if (!empty($post['currency'][$fieldIdentifier]['value']))
		{
			$valueCurrency[] = $post['currency'][$fieldIdentifier]['value'];
		}
		if (!empty($post['currency'][$fieldIdentifier]['cents']))
		{
			$valueCurrency[] = $post['currency'][$fieldIdentifier]['cents'];
		}

		if ($valueCurrency)
		{
			return implode(",", $valueCurrency);
		}
	}

	/**
	 * Setting filed type Password
	 *
	 * @param   Array   $post             Post form
	 * @param   String  $fieldIdentifier  Field indentifier
	 * @param   String  $fieldTitle       Field Title
	 * @param   Array   &$validationForm  Validation form
	 *
	 * @return array
	 */
	private function _fieldPassword($post, $fieldIdentifier, $fieldTitle, $fieldSettings, &$validationForm)
	{
		$value = "";
		if (count($post['password'][$fieldIdentifier]) > 1)
		{
			if ($post['password'][$fieldIdentifier][0] == $post['password'][$fieldIdentifier][1])
			{
				$value = !empty($post['password'][$fieldIdentifier][0]) ? $post['password'][$fieldIdentifier][0] : "";
			}
			else
			{
				$validationForm['password'][$fieldIdentifier] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_PASSWORD_CONFIRM');
			}
		}
		else
		{
			$value = !empty($post['password'][$fieldIdentifier][0]) ? $post['password'][$fieldIdentifier][0] : "";
		}
		if (!empty($value))
		{
			if (!empty($fieldSettings->options->encrypt) && $fieldSettings->options->encrypt == "md5")
			{
				$value = md5($value);
			}
			else if (!empty($fieldSettings->options->encrypt) && $fieldSettings->options->encrypt == "sha1")
			{
				$value = sha1($value);
			}
		}
		return $value;
	}

	/**
	 * Setting filed type Phone
	 *
	 * @param   Array   $post             Post form
	 * @param   String  $fieldIdentifier  Field indentifier
	 * @param   String  $fieldTitle       Field Title
	 * @param   Array   &$validationForm  Validation form
	 *
	 * @return array
	 */
	private function _fieldPhone($post, $fieldIdentifier, $fieldTitle, &$validationForm)
	{
		$valuePhone = array();
		if (!empty($post['phone'][$fieldIdentifier]['default']))
		{
			$valuePhone[] = $post['phone'][$fieldIdentifier]['default'];
		}
		if (!empty($post['phone'][$fieldIdentifier]['one']))
		{
			$valuePhone[] = $post['phone'][$fieldIdentifier]['one'];
		}
		if (!empty($post['phone'][$fieldIdentifier]['two']))
		{
			$valuePhone[] = $post['phone'][$fieldIdentifier]['two'];
		}
		if (!empty($post['phone'][$fieldIdentifier]['three']))
		{
			$valuePhone[] = $post['phone'][$fieldIdentifier]['three'];
		}
		if ($valuePhone)
		{
			return implode(" - ", $valuePhone);
		}
		else
		{
			return "";
		}
	}

	/**
	 * Check field duplicates
	 *
	 * @param   Array   $post             Post form
	 * @param   String  $tableSubmission  Table submission
	 * @param   String  $fieldIdentifier  Field indentifier
	 * @param   String  $fieldTitle       Field Title
	 * @param   Array   &$validationForm  Validation form
	 *
	 * @return  array
	 */
	private function _checkDuplicates($post, $fieldIdentifier, $fieldTitle, &$validationForm)
	{
		$formId = isset($post['form_id']) ? $post['form_id'] : '0';
		$postFieldIdentifier = isset($post[$fieldIdentifier]) ? $post[$fieldIdentifier] : '';
		$postIndentifier = (get_magic_quotes_gpc() == true || get_magic_quotes_runtime() == true) ? stripslashes($postFieldIdentifier) : $postFieldIdentifier;
		if ($postIndentifier)
		{
			$this->_db->setQuery($this->_db->getQuery(true)->select('sd.submission_id')->from('#__jsn_uniform_submission_data AS sd')->where("sd.form_id = " . (int) $formId . " AND sd.submission_data_value = " . $this->_db->quote($postIndentifier)));
			if ($this->_db->loadObject())
			{
				$validationForm[$fieldIdentifier] = JText::sprintf('JSN_UNIFORM_FIELD_EXISTING', $fieldTitle);
			}
		}
	}

	/**
	 * Check filed limit char
	 *
	 * @param   Array   $post             Post form
	 * @param   String  $fieldSettings    Field settings
	 * @param   String  $fieldIdentifier  Field indentifier
	 * @param   String  $fieldTitle       Field Title
	 * @param   Array   &$validationForm  Validation form
	 *
	 * @return  array
	 */
	private function _checkLimitChar($post, $fieldSettings, $fieldIdentifier, $fieldTitle, &$validationForm)
	{
		if (isset($fieldSettings->type) && $fieldSettings->type != "password")
		{
			$postFieldIdentifier = isset($post[$fieldIdentifier]) ? $post[$fieldIdentifier] : '';
		}
		else
		{
			$postFieldIdentifier = isset($post[$fieldIdentifier][0]) ? $post[$fieldIdentifier][0] : '';
		}
		$postIndentifier = (get_magic_quotes_gpc() == true || get_magic_quotes_runtime() == true) ? stripslashes($postFieldIdentifier) : $postFieldIdentifier;
		if (isset($fieldSettings->options->limitType) && $fieldSettings->options->limitType == "Words")
		{
			$countValue = explode(" ", str_replace("  ", " ", $postIndentifier));

			if (count($countValue) < $fieldSettings->options->limitMin)
			{
				$validationForm[$fieldIdentifier] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_MIN_LENGTH') . ' ' . $fieldSettings->options->limitMin . ' Words';
			}
			else if (count($countValue) > $fieldSettings->options->limitMax)
			{
				$validationForm[$fieldIdentifier] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_MAX_LENGTH') . ' ' . $fieldSettings->options->limitMax . ' Words';
			}
		}
		else
		{
			if (isset($fieldSettings->type) && $fieldSettings->type != "password")
			{
				if (strlen($postIndentifier) < $fieldSettings->options->limitMin)
				{
					$validationForm[$fieldIdentifier] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_MIN_LENGTH') . ' ' . $fieldSettings->options->limitMin . ' Character';
				}
				else if (strlen($postIndentifier) > $fieldSettings->options->limitMax)
				{
					$validationForm[$fieldIdentifier] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_MAX_LENGTH') . ' ' . $fieldSettings->options->limitMax . ' Character';
				}
			}
			else
			{
				if (strlen($postIndentifier) < $fieldSettings->options->limitMin)
				{
					$validationForm['password'][$fieldIdentifier] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_MIN_LENGTH') . ' ' . $fieldSettings->options->limitMin . ' Character';
				}
				else if (strlen($postIndentifier) > $fieldSettings->options->limitMax)
				{
					$validationForm['password'][$fieldIdentifier] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_MAX_LENGTH') . ' ' . $fieldSettings->options->limitMax . ' Character';
				}
			}
		}
	}

	/**
	 * Settings filed type choices and check validadion filed type choicces
	 *
	 * @param   Array   $post             Post form
	 * @param   String  $fieldSettings    Field settings
	 * @param   String  $fieldIdentifier  Field indentifier
	 *
	 * @return array
	 */
	private function _fieldOthers($post, $fieldSettings, $fieldIdentifier)
	{
		if (isset($fieldSettings->options->allowOther) && $fieldSettings->options->allowOther == 1 && isset($post[$fieldIdentifier]) && $post[$fieldIdentifier] == "Others")
		{
			return isset($post["fieldOthers"][$fieldIdentifier]) ? $post["fieldOthers"][$fieldIdentifier] : "";
		}
		else
		{
			return isset($post[$fieldIdentifier]) ? $post[$fieldIdentifier] : "";
		}
	}

	private function _validData($post)
	{
		$postID = str_replace('[', '', $post['list_choosen_field']);
		$postID = str_replace(']', '', $postID);
		$postFormId = isset($post['form_id']) ? $post['form_id'] : "";

		if ( $postFormId == "")
		{
			return array();
		}

		$idChoises = explode(',', $postID);
		foreach ($idChoises as $idChoise)
		{
			$id[] = $idChoise;
		}

		$validId = array_unique($id);

		$this->_db->setQuery($this->_db->getQuery(true)->select('*')->from('#__jsn_uniform_fields')->where("form_id = " . (int) $postFormId)->order("field_id  ASC"));
		$fields = $this->_db->loadObjectList();
		$showFields =  array();
		$invalidId = array();
		$tempFieldId = array();

		foreach ($fields  as $field)
		{
			if(! in_array($field->field_id, $validId))
			{
				$invalidId[] = $field->field_id;
			}
			$fieldSettings = isset($field->field_settings) ? json_decode($field->field_settings) : "";
			if ($fieldSettings  != '')
			{
				foreach ($fieldSettings as $fieldSetting)
				{
					$fieldActions = isset($fieldSetting->itemAction) ? json_decode($fieldSetting->itemAction) : "";
					if ($fieldActions != '')
					{
						foreach ($fieldActions as $fieldAction)
						{
							foreach ($fieldAction as $i =>$identifierName)
							{
								if ($i == 'showField' || $i == 'hideField')
								{
									foreach ($identifierName as $identifier)
									{
										$showFields[] = '"' . $identifier . '"';
									}
								}
							}
						}
					}
				}
			}
		}

		if(count($showFields))
		{
			$showFields = array_unique($showFields);
			$showFields = implode(',', $showFields);
			$this->_db->setQuery($this->_db->getQuery(true)->select('field_id')->from('#__jsn_uniform_fields')->where("form_id = " . (int) $postFormId)->where("field_identifier IN (" . $showFields . ")")->order("field_id  ASC"));
			$tempFields = $this->_db->loadObjectList();
			foreach ($tempFields as $tempField)
			{
				$tempFieldId[] = $tempField->field_id;
			}
		}
			$rArrayDiff = array_diff($invalidId, $tempFieldId);
			$validId    = array_merge($rArrayDiff, $validId);
			$validId = array_unique($validId);
		return $validId;
	}

	/**
	 * Save form submission
	 *
	 * @param   Array  $post  Post form
	 *
	 * @return  Messages
	 */
	public function save($post)
	{
		$input = JFactory::getApplication()->input;
		$postData = $input->getArray($_POST);
		$return = new stdClass;
		$submissionsData = array();
		$validationForm = array();
		$requiredField = array();
		$fieldData = array();
		$postFormId = isset($post['form_id']) ? $post['form_id'] : "";
		$this->_db->setQuery($this->_db->getQuery(true)->select('*')->from('#__jsn_uniform_forms')->where("form_id = " . (int) $postFormId));
		$dataForms = $this->_db->loadObject();	
		$emptyField = '';
		$dataContentEmail = '';
		$fileAttach = "";
		$recepientEmail = "";
		$nameFileByIndentifier = '';
		$dataArray = $this->_validData($post);
		if (!empty($dataForms->form_captcha))
		{
			if ($dataForms->form_captcha == 1)
			{
				$captchaParams = JPluginHelper::getPlugin('captcha', 'recaptcha');
				$params = json_decode($captchaParams->params);
				if(version_compare($params->version, '2.0', '>=')){
					JPluginHelper::importPlugin('captcha');
					$dispatcher = JEventDispatcher::getInstance();
					$checkAnswer = $dispatcher->trigger('onCheckAnswer');
					if(is_array($checkAnswer))
					{
						if($checkAnswer[0] == false)
						{
							$return->error['captcha'] = JText::_('JSN_UNIFORM_ERROR_CAPTCHA');
							return $return;
						}
					}
					elseif($checkAnswer == false)
					{
						$return->error['captcha'] = JText::_('JSN_UNIFORM_ERROR_CAPTCHA');
						return $return;
					}
				}else{
					require_once JSN_UNIFORM_LIB_CAPTCHA;

					$recaptchaChallenge = isset($postData["recaptcha_challenge_field"]) ? $postData["recaptcha_challenge_field"] : "";
					$recaptchaResponse = isset($postData["recaptcha_response_field"]) ? $postData["recaptcha_response_field"] : "";

					$resp = recaptcha_check_answer(JSN_UNIFORM_CAPTCHA_PRIVATEKEY, $_SERVER["REMOTE_ADDR"], $recaptchaChallenge, $recaptchaResponse);

					if (!$resp->is_valid)
					{
						$return->error['captcha'] = JText::_('JSN_UNIFORM_ERROR_CAPTCHA');

						return $return;
					}
				}
			}
			else if ($dataForms->form_captcha == 2)
			{
				if (!empty($postData['form_name']) && !empty($postData['captcha']))
				{
					$sCaptcha = $_SESSION['securimage_code_value'][$postData['form_name']] ? $_SESSION['securimage_code_value'][$postData['form_name']] : "";
					if (strtolower($sCaptcha) != strtolower($postData['captcha']))
					{
						$return->error['captcha_2'] = JText::_('JSN_UNIFORM_ERROR_CAPTCHA');
						return $return;
					}
				}
				else
				{
					$return->error['captcha_2'] = JText::_('JSN_UNIFORM_ERROR_CAPTCHA');
					return $return;
				}
			}
		}
		$dataFormId = isset($dataForms->form_id) ? $dataForms->form_id : 0;

		$this->_db->setQuery($this->_db->getQuery(true)->select('count(*)')->from('#__jsn_uniform_form_pages')->where("form_id = " . (int) $postFormId));
		$countPages = $this->_db->loadResult();
		if((int) $countPages > 1)
		{
			$fieldOrderId = array('field_id');
			$this->_db->setQuery($this->_db->getQuery(true)->select('*')->from('#__jsn_uniform_form_pages')->where("form_id = " . (int) $postFormId)->order("page_id ASC"));
			$columsPageData = $this->_db->loadObjectList();
			foreach($columsPageData as $columData)
			{
				$pageContents = json_decode($columData->page_content);
				foreach ( $pageContents as $pageContent)
				{
					$fieldOrderId[] = $pageContent->id;
				}
			}
			$fieldOrderId = array_unique($fieldOrderId);
			$fieldOrderId = implode(',', $fieldOrderId);

			$this->_db->setQuery($this->_db->getQuery(true)->select('*')->from('#__jsn_uniform_fields')->where("form_id = " . (int) $postFormId)->order("FIELD(" . $fieldOrderId . ")"));
			$columsSubmission = $this->_db->loadObjectList();
		}
		else
		{
			$this->_db->setQuery($this->_db->getQuery(true)->select('*')->from('#__jsn_uniform_fields')->where("form_id = " . (int) $postFormId)->order("field_ordering  ASC"));
			$columsSubmission = $this->_db->loadObjectList();
		}

		$fieldClear = array();
		if (isset($dataForms->form_type) && $dataForms->form_type == 1)
		{
			$this->_db->setQuery($this->_db->getQuery(true)->select('*')->from('#__jsn_uniform_form_pages')->where("form_id = " . (int) $dataForms->form_id));
			$dataPages = $this->_db->loadObjectList();
			foreach ($dataPages as $index => $page)
			{
				if ($index > 0)
				{
					$contentPage = isset($page->page_content) ? json_decode($page->page_content) : "";
					foreach ($contentPage as $item)
					{
						$fieldClear[] = $item->id;
					}
				}
			}
		}
		$this->_getActionForm($dataForms->form_post_action, $dataForms->form_post_action_data, $return);
		$fieldEmail = array();
		foreach ($columsSubmission as $colum)
		{
			if (!in_array($colum->field_id, $fieldClear))
			{
				$fieldName = "";
				$fieldName = $colum->field_id;
				$fieldSettings = isset($colum->field_settings) ? json_decode($colum->field_settings) : "";

				$value = "";
				$fieldEmail[$colum->field_id] = $colum->field_identifier;

				if ($colum->field_type != 'static-content' && $colum->field_type != 'google-maps')
				{
					if (in_array($colum->field_type, array("single-line-text", "website", "paragraph-text", "country")))
					{
						$postFieldName = isset($post[$fieldName]) ? $post[$fieldName] : '';
						$postName = (get_magic_quotes_gpc() == true || get_magic_quotes_runtime() == true) ? stripslashes($postFieldName) : $postFieldName;
						$value = $postName ? $postName : "";
					}
					elseif ($colum->field_type == "choices" || $colum->field_type == "dropdown")
					{
						$value = $this->_fieldOthers($post, $fieldSettings, $fieldName);
					}
					elseif (in_array($colum->field_type, array("address", "checkboxes", "name", "list", "likert", 'recepient-email')))
					{
						$value = $this->_fieldJson($post, $colum->field_type, $fieldName, true);
						if ($colum->field_type == 'recepient-email')
						{
							$recepientEmail[] = $value;
						}
					}
					elseif ($colum->field_type == "file-upload")
					{
						$value = $this->_fieldUpload($fieldSettings, $fieldName, $colum->field_title, $validationForm, $post);
					}
					elseif ($colum->field_type == "email")
					{
						$value = $this->_fieldEmail($post, $fieldName, $colum->field_title, $validationForm);
					}
					elseif ($colum->field_type == "number")
					{
						$value = $this->_fieldNumber($post, $fieldName, $colum->field_title, $validationForm);
					}
					else if ($colum->field_type == "date")
					{
						$value = $this->_fieldDate($post, $fieldName, $colum->field_title, $validationForm);
					}
					else if ($colum->field_type == "phone")
					{
						$value = $this->_fieldPhone($post, $fieldName, $colum->field_title, $validationForm);
					}
					else if ($colum->field_type == "currency")
					{
						$value = $this->_fieldCurrency($post, $fieldName, $colum->field_title, $validationForm);
					}
					else if ($colum->field_type == "password")
					{
						$value = $this->_fieldPassword($post, $fieldName, $colum->field_title, $fieldSettings, $validationForm);
					}
					elseif ($colum->field_type == "identification-code")
					{
						$value = !empty($post['identification-code'][$fieldName]) ? $post['identification-code'][$fieldName] : '';
					}
					if (in_array($colum->field_id, $dataArray))
					{
						$submissionsData[] = array('form_id' => $dataFormId, 'field_id' => $colum->field_id, 'submission_data_value' => $value, 'field_type' => $colum->field_type);
					}
					$keyField = $colum->field_id;
					$submissions = new stdClass();
					$submissions->$keyField = $value;
					if (in_array($colum->field_id, $dataArray))
					{
						$fieldData[] = array('form_id' => $dataFormId, 'field_id' => $colum->field_id, 'submission_data_value' => $value, 'field_type' => $colum->field_type, 'field_title' => $colum->field_title);
					}
					if (isset($colum->field_type))
					{
						$nameFileByIndentifier[$colum->field_identifier] = $colum->field_title;
						$contentField = JSNUniformHelper::getDataField($colum->field_type, $submissions, $colum->field_id, $postFormId, false, false, 'email');
						if ($colum->field_type == "file-upload")
						{
							$fileAttach[$colum->field_identifier] = JSNUniformHelper::getDataField($colum->field_type, $submissions, $colum->field_id, $postFormId, false, false, 'fileAttach');
						}
						if ($colum->field_type == 'recepient-email')
						{
							if ($fieldSettings->options->showInNotificationEmail == 'Yes')
							{
								if (in_array($colum->field_id, $dataArray))
								{
									$dataContentEmail[$colum->field_identifier] = $contentField ? str_replace("\n", "<br/>", trim($contentField)) : "<span>N/A</span>";
								}
							}
						}
						if ($colum->field_type == 'identification-code')
						{
							if ($fieldSettings->options->showInNotificationEmail == 'Yes')
							{
								if (in_array($colum->field_id, $dataArray))
								{
									$dataContentEmail[$colum->field_identifier] = $contentField ? str_replace("\n", "<br/>", trim($contentField)) : "<span>N/A</span>";
								}
							}
						}
						else
						{
							if (in_array($colum->field_id, $dataArray))
							{
								if ($post['use_payment_gateway'] == 1)
								{
									$dispatcher = JEventDispatcher::getInstance();
									JPluginHelper::importPlugin('uniform', (string) $dataForms->form_payment_type);
									if ($colum->field_type == 'choices' || $colum->field_type == 'list' || $colum->field_type == 'dropdown' || $colum->field_type == 'checkboxes' || $colum->field_type == 'currency')
									{
										$fieldValue = $contentField;

										if ($colum->field_type == 'list' || $colum->field_type == 'checkboxes')
										{
											$fieldValue   = json_decode($value);
											$contentEmail = '<ul style="padding:0 0px 0px 10px;margin:0;">';
											foreach ($fieldValue as $itemValue)
											{
												$tmpMoney = explode('|', $itemValue);
												if (count($tmpMoney) > 1)
												{
													$moneyValue = trim($tmpMoney[1]);
													$moneyValue = $dispatcher->trigger('displayCurrency', $moneyValue);
													$qty        = trim(end($tmpMoney));
													$tmpMoney   = trim($tmpMoney[0]) . ' (' . strip_tags($moneyValue[0]) . ' x ' . $qty . ')';
													$contentEmail .= '<li style="padding:0;">' . $tmpMoney . '</li>';
												}
												else
												{
													$contentEmail .= '<li style="padding:0;">' . $itemValue . '</li>';
												}
											}
											$contentEmail .= '</ul>';
											if (!empty($contentEmail))
											{
												$dataContentEmail[$colum->field_identifier] = $contentEmail ? str_replace("\n", "<br/>", trim($contentEmail)) : "<span>N/A</span>";
											}
										}
										else
										{
											$tmpMoney   = explode('|', $fieldValue);
											$moneyValue = trim($tmpMoney[1]);
											if (count($tmpMoney) > 1)
											{
												$moneyValue                                 = $dispatcher->trigger('displayCurrency', $moneyValue);
												$qty                                        = trim(end($tmpMoney));
												$dataContentEmail[$colum->field_identifier] = trim($tmpMoney[0]) . ' (' . strip_tags($moneyValue[0]) . ' x ' . $qty . ')' ? str_replace("\n", "<br/>", trim($tmpMoney[0]) . ' (' . strip_tags($moneyValue[0]) . ' x ' . $qty . ')') : "<span>N/A</span>";
											}
											else
											{
												$dataContentEmail[$colum->field_identifier] = $fieldValue ? str_replace("\n", "<br/>", trim($fieldValue)) : "<span>N/A</span>";
											}
										}
										if ($colum->field_type == 'currency')
										{
											if (!empty($fieldValue))
											{
												$fieldValue                                 = str_replace(',', '.', $fieldValue);
												$currecyValue                               = $dispatcher->trigger('displayCurrency', $fieldValue);
												$currecyValue                               = strip_tags($currecyValue[0]);
												$dataContentEmail[$colum->field_identifier] = $currecyValue ? str_replace("\n", "<br/>", trim($currecyValue)) : "<span>N/A</span>";
											}
										}
									}
									else
									{
										$dataContentEmail[$colum->field_identifier] = $contentField ? str_replace("\n", "<br/>", trim($contentField)) : "<span>N/A</span>";
									}
								}
								else
								{
									$dataContentEmail[$colum->field_identifier] = $contentField ? str_replace("\n", "<br/>", trim($contentField)) : "<span>N/A</span>";
								}
							}
						}
						$requiredField[$colum->field_identifier] = $fieldSettings->options->required;
					}
					//
					if (!empty($fieldSettings->options->noDuplicates) && (int) $fieldSettings->options->noDuplicates == 1)
					{
						$this->_checkDuplicates($post, $fieldName, $colum->field_title, $validationForm);
					}
					if (isset($fieldSettings->options->limitation) && (int) $fieldSettings->options->limitation == 1 && !empty($post[$fieldName]))
					{
						if ($fieldSettings->options->limitMin <= $fieldSettings->options->limitMax && $fieldSettings->options->limitMax > 0)
						{
							$this->_checkLimitChar($post, $fieldSettings, $fieldName, $colum->field_title, $validationForm);
						}
					}
					if (isset($fieldSettings->options->requiredConfirm) && (int) $fieldSettings->options->requiredConfirm == 1)
					{
						$postData = isset($post[$fieldName]) ? $post[$fieldName] : '';
						$postDataConfirm = isset($post[$fieldName . "_confirm"]) ? $post[$fieldName . "_confirm"] : '';
						if (isset($fieldSettings->options->required) && (int) $fieldSettings->options->required == 1 && $postData != $postDataConfirm)
						{
							$validationForm[$fieldName] = JText::sprintf('JSN_UNIFORM_CONFIRM_FIELD_CONFIRM', $colum->field_title);
						}
						else if (!empty($postData) && !empty($postDataConfirm) && $postData != $postDataConfirm)
						{
							$validationForm[$fieldName] = JText::sprintf('JSN_UNIFORM_CONFIRM_FIELD_CONFIRM', $colum->field_title);
						}
					}
					if (isset($fieldSettings->options->required) && (int) $fieldSettings->options->required == 1 && (int) $fieldSettings->options->hideField != 1)
					{
						switch($colum->field_type)
						{
							case "name":
								$postFieldName = isset($post['name'][$fieldName]) ? $post['name'][$fieldName] : '';
								if ($postFieldName['first'] == "" && $postFieldName['last'] == "" && $postFieldName['suffix'] == "")
								{
									$validationForm['name'][$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
								}
								break;
							case "address":
								$postAddress = $post['address'][$fieldName];
								if ($postAddress['street'] == "" && $postAddress['line2'] == "" && $postAddress['city'] == "" && $postAddress['code'] == "" && $postAddress['state'] == "")
								{
									$validationForm['address'][$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
								}
								break;
							case "email":
								$postFieldEmail = isset($post[$fieldName]) ? $post[$fieldName] : '';
								$postEmail = (get_magic_quotes_gpc() == true || get_magic_quotes_runtime() == true) ? stripslashes($postFieldEmail) : $postFieldEmail;
								jimport('joomla.mail.helper');
								$isEmailAddress = JMailHelper::isEmailAddress($postEmail);
								if (!$isEmailAddress)
								{
									$validationForm[$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_INVALID');
								}
								break;
							case "number":

								if ($post['number'][$fieldName] == "")
								{
									$validationForm[$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
								}
								else
								{
									$postNumber = $post['number'][$fieldName]['value'];
									$postNumberDecimal = $post['number'][$fieldName]['decimal'];

									$regex = '/^[0-9]+$/';
									$checkNumber = false;
									if ($postNumber != "")
									{
										if (preg_match($regex, $postNumber))
										{
											$checkNumber = true;
										}
									}
									if ($postNumberDecimal != "")
									{
										if (preg_match($regex, $postNumberDecimal))
										{
											$checkNumber = true;
										}
									}
									if (!$checkNumber)
									{
										$validationForm[$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
									}
								}
								break;
							case "website":
								$postWebsite = isset($post[$fieldName]) ? $post[$fieldName] : '';
								$regex = "/^((http|https|ftp):\/\/|www([0-9]{0,9})?\.)?([a-zA-Z0-9][a-zA-Z0-9_-]*(?:\.[a-zA-Z0-9][a-zA-Z0-9_-]*)+):?(\d+)?\/?/i";
								if (!preg_match($regex, $postWebsite))
								{
									$validationForm[$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_INVALID');
								}
								break;
							case "file-upload":
								if (empty($_FILES[$fieldName]['name']))
								{
									$validationForm[$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
								}
								break;
							case "date":
								if (isset($fieldSettings->options->enableRageSelection) && $fieldSettings->options->enableRageSelection == "1")
								{
									if ($post['date'][$fieldName]['date'] == "" || $post['date'][$fieldName]['daterange'] == "")
									{
										$validationForm['date'][$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
									}
								}
								else
								{
									if ($post['date'][$fieldName]['date'] == "")
									{
										$validationForm['date'][$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
									}
								}
								break;
							case "currency":
								if ($post['currency'][$fieldName]['value'] == "")
								{
									$validationForm['currency'][$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
								}

								break;
							case "phone":
								if (isset($fieldSettings->options->format) && $fieldSettings->options->format == "3-field")
								{
									if ($post['phone'][$fieldName]['one'] == "" || $post['phone'][$fieldName]['two'] == "" || $post['phone'][$fieldName]['three'] == "")
									{
										$validationForm['phone'][$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
									}
								}
								else
								{
									if ($post['phone'][$fieldName]['default'] == "")
									{
										$validationForm['phone'][$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
									}
								}
								break;
							case "password":
								if (count($post['password'][$fieldName]) > 1)
								{
									if ($post['password'][$fieldName][0] == "" || $post['password'][$fieldName][1] == "")
									{
										$validationForm['password'][$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
									}
									else if ($post['password'][$fieldName][0] != "" && $post['password'][$fieldName][1] != "" && $post['password'][$fieldName][0] != $post['password'][$fieldName][1])
									{
										$validationForm['password'][$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_PASSWORD_CONFIRM');
									}
								}
								else
								{
									if ($post['password'][$fieldName][0] == "")
									{
										$validationForm['password'][$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
									}
								}
								break;
							default:
								if (isset($post[$fieldName]) && $post[$fieldName] == "")
								{
									$validationForm[$fieldName] = JText::_('JSN_UNIFORM_CONFIRM_FIELD_CANNOT_EMPTY');
								}
								break;
						}
					}
				}
				else
				{
					if (isset($colum->field_type) && $colum->field_type != 'file-upload' && $colum->field_type != 'google-maps')
					{
						$nameFileByIndentifier[$colum->field_identifier] = $colum->field_title;
						if ($colum->field_type == 'static-content')
						{
							if ($fieldSettings->options->showInNotificationEmail != 'No')
							{
								if (in_array($colum->field_id, $dataArray))
								{
									$dataContentEmail[$colum->field_identifier] = $fieldSettings->options->value;
								}
							}
						}
						else
						{
							if (in_array($colum->field_id, $dataArray))
							{
								$dataContentEmail[$colum->field_identifier] = $fieldSettings->options->value;
							}
						}
					}
				}
			}
		}

		if (!$validationForm)
		{
			$_save = $this->_save($dataForms, $return, $post, $submissionsData, $dataContentEmail, $nameFileByIndentifier, $requiredField, $fileAttach, $recepientEmail);
		
			if((string)$dataForms->form_payment_type != '')
			{
				$dispatcher = JEventDispatcher::getInstance();
				JPluginHelper::importPlugin('uniform', (string)$dataForms->form_payment_type);
				$dispatcher->trigger('processToPostPaymentGateway', array($post, $fieldData, $_save));
			}
			return $return;
		}
		else
		{
			$return->error = $validationForm;
			return $return;
		}
	}

	/**
	 * Save data
	 *
	 * @param   Array   $dataForms                Data form
	 * @param   Array   &$return                  Return
	 * @param   Array   $post                     Post form
	 * @param   String  $submissionsData          Submission Data
	 * @param   String  $fieldId                  Field Id
	 * @param   String  $dataContentEmail         Data content Email
	 * @param   Strig   $nameFileByIndentifier    Get name Field by Indentifier
	 * @param   String  $requiredField            required field
	 * @param   String  $fileAttach               Email File Attach
	 *
	 * @return boolean
	 */
	private function _save($dataForms, &$return, $post, $submissionsData, $dataContentEmail, $nameFileByIndentifier, $requiredField, $fileAttach, $recepientEmail)
	{
		$input = JFactory::getApplication()->input;
		$postData = $input->getArray($_POST);
		$user = JFactory::getUser();
		$ip = getenv('REMOTE_ADDR');
		$country = $this->countryCityFromIP($ip);

		$archiveIP = JSNUniformHelper::getDataConfig('archive_ip_address');
        $archiveIP = isset($archiveIP->value) ? $archiveIP->value : 1;
        if ((int) $archiveIP != 1)
        {
        	$ip = '';
        }
		$browser = new Browser;
		$checkSaveSubmission = true;
		$formSettings = !empty($dataForms->form_settings) ? json_decode($dataForms->form_settings) : "";

		$plgName = JSNUniformHelper::getPluginUniform();
		if (isset($plgName) && !empty($plgName)) {
			if (is_array($plgName)) {
				foreach ($plgName as $k => $v) {
					$v = (array) $v;

					$name = form_ . '' . $v['value'];
					$data[$v['value']] = $formSettings->$name;
					$data['post'] = $post;
					$data['sub'] = $submissionsData;
					if($v['value'] == 'mailchimp')
					{

						if(isset($post['mailchimp_subcriber']) && $post['mailchimp_subcriber'] == 'on')
						{
							JPluginHelper::importPlugin('uniform', $v['value']);
							$dispatcher = JEventDispatcher::getInstance();
							$dispatcher->trigger('saveFrontEnd', array($data));
						}
					}
				}
			}
		}
		if (!empty($formSettings->action_save_submissions) && $formSettings->action_save_submissions == "No")
		{
			$checkSaveSubmission = false;
		}

		$submissionBrowser          = $browser->getBrowser();
		$submissionBrowserVersion   = $browser->getVersion();
		$submissionBrowserUserAgent = $browser->getUserAgent();

		$archiveBrowser = JSNUniformHelper::getDataConfig('archive_browser');
        $archiveBrowser = isset($archiveBrowser->value) ? $archiveBrowser->value : 1;
        if ((int) $archiveBrowser != 1)
        {
        	$submissionBrowser          = '';
        	$submissionBrowserVersion   = '';
			$submissionBrowserUserAgent = '';
        }
		$submissionOS      = $browser->getPlatform();
        $archiveOS = JSNUniformHelper::getDataConfig('archive_operating_system');
        $archiveOS = isset($archiveOS->value) ? $archiveOS->value : 1;
        if ((int) $archiveOS != 1)
        {
        	$submissionOS = '';
        }

		if ($checkSaveSubmission)
		{
			$location = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
			$dateTime = JHtml::_('date', 'now', JText::_('Y-m-d H:i:s'));
			$table    = JTable::getInstance('JsnSubmission', 'JSNUniformTable');
			$table->bind(array('form_id' => (int) $post['form_id'], 'user_id' => $user->id, 'submission_ip' => $ip, 'submission_country' => $country['country'], 'submission_country_code' => $country['country_code'], 'submission_browser' => $submissionBrowser, 'submission_browser_version' => $submissionBrowserVersion, 'submission_browser_agent' => $submissionBrowserUserAgent, 'submission_os' => $submissionOS, 'submission_created_by' => $user->id, 'submission_created_at' => $dateTime, 'submission_state' => 1, 'submission_form_location' => $location));
			if (!$table->store())
			{
				$return->error = $table->getError();
				return false;
			}
		}

		$this->_db->setQuery($this->_db->getQuery(true)->select('*')->from('#__jsn_uniform_templates')->where("form_id = " . (int) $dataForms->form_id));
		$dataTemplates = $this->_db->loadObjectList();
		$this->_db->setQuery($this->_db->getQuery(true)->select('*')->from('#__jsn_uniform_emails')->where("form_id = " . (int) $dataForms->form_id));
		$dataEmails = $this->_db->loadObjectList();

		if (count($recepientEmail))
		{
			foreach ($recepientEmail as $key => $recepientEmailItem) {
				$recepientEmailItem = json_decode($recepientEmailItem);
				if (count($recepientEmailItem))
				{
					foreach($recepientEmailItem as $recepient)
					{
						$recepient = explode('|', $recepient);
						$emailName = trim(($recepient[0]));
						$email = trim(end($recepient));
						$dataRecepient = (object) array('email_id'=> '', 'form_id'=>'', 'user_id'=>'0', 'email_name'=>$emailName, 'email_address'=>$email, 'email_state'=>'1');

						$dataEmails = array_merge($dataEmails, array($dataRecepient));
					}
				}				
			}
		}

		$formSubmitter = isset($dataForms->form_submitter) ? json_decode($dataForms->form_submitter) : '';
		$checkEmailSubmitter = true;
		$defaultSubject = isset($dataForms->form_title) ? $dataForms->form_title : '';

		if ($dataTemplates)
		{
			foreach ($dataTemplates as $emailTemplate)
			{
				$emailTemplate->template_message = trim($emailTemplate->template_message);
				if (!empty($emailTemplate->template_message))
				{
					preg_match_all('/\{\$([^\}]+)\}/i', $emailTemplate->template_message, $matches, PREG_SET_ORDER);

					if (count($matches))
					{
						for($z = 0, $countz = count($matches); $z < $countz; $z++)
						{
							$emailTemplate->template_message = str_replace($matches[$z][0], @$dataContentEmail[$matches[$z][1]], $emailTemplate->template_message);
						}
					}
				}
				else
				{
					$htmlMessage = array();
					if ($dataContentEmail)
					{
						$htmlMessage = $this->_emailTemplateDefault($dataContentEmail, $nameFileByIndentifier, $requiredField);
					}
					$emailTemplate->template_message = $htmlMessage;
				}
				preg_match_all('/\{\$([^\}]+)\}/i', $emailTemplate->template_subject, $matchesSubject, PREG_SET_ORDER);
				preg_match_all('/\{\$([^\}]+)\}/i', $emailTemplate->template_from, $matchesFrom, PREG_SET_ORDER);
				preg_match_all('/\{\$([^\}]+)\}/i', $emailTemplate->template_from_name, $matchesFromName, PREG_SET_ORDER);
				preg_match_all('/\{\$([^\}]+)\}/i', $emailTemplate->template_reply_to, $matchesReplyTo, PREG_SET_ORDER);
				if (count($matchesSubject))
				{
					for($sub = 0, $countsub = count($matchesSubject); $sub < $countsub; $sub++)
					{
						$emailTemplate->template_subject = str_replace($matchesSubject[$sub][0], @$dataContentEmail[$matchesSubject[$sub][1]], $emailTemplate->template_subject);
					}
				}
				$emailTemplate->template_subject = !empty($emailTemplate->template_subject) ? $emailTemplate->template_subject : $defaultSubject;
				if (count($matchesFrom))
				{
					for ($fr = 0, $countfr = count($matchesFrom); $fr < $countfr; $fr++)
					{
						$emailTemplate->template_from = str_replace($matchesFrom[$fr][0], @$dataContentEmail[$matchesFrom[$fr][1]], $emailTemplate->template_from);
					}

				}

				if (count($matchesFromName))
				{
					for ($frn = 0, $countfrn = count($matchesFromName); $frn < $countfrn; $frn++)
					{
						$emailTemplate->template_from_name = str_replace($matchesFromName[$frn][0], @$dataContentEmail[$matchesFromName[$frn][1]], $emailTemplate->template_from_name);
					}
				
				}
				
				if (count($matchesReplyTo))
				{
					for ($repto = 0, $countrepto = count($matchesReplyTo); $repto < $countrepto;  $repto++)
					{
						$emailTemplate->template_reply_to = str_replace($matchesReplyTo[$repto][0], @$dataContentEmail[$matchesReplyTo[$repto][1]], $emailTemplate->template_reply_to);
					}
				}
				$emailTemplate->template_subject = strip_tags($emailTemplate->template_subject);
				$emailTemplate->template_from = strip_tags($emailTemplate->template_from);
				$emailTemplate->template_from_name = strip_tags($emailTemplate->template_from_name);
				$emailTemplate->template_reply_to = strip_tags($emailTemplate->template_reply_to);

				if ($emailTemplate->template_notify_to == 0 && count($formSubmitter))
				{
					$checkEmailSubmitter = false;

					$listEmailSubmitter = array();
					foreach ($formSubmitter as $item)
					{
						if (!empty($item))
						{
							$emailSubmitter = new stdClass;
							$emailSubmitter->email_address = isset($dataContentEmail[$item]) ? $dataContentEmail[$item] : "";

							if (!empty($emailSubmitter->email_address))
							{
								$listEmailSubmitter[] = $emailSubmitter;
							}
						}
					}
					$sent = $this->_sendEmailList($emailTemplate, $listEmailSubmitter, $fileAttach);
					// Set the success message if it was a success
					if (! ($sent instanceof Exception))
					{
						$msg = JText::_('JSN_UNIFORM_EMAIL_THANKS');
					}
				}
				if ($emailTemplate->template_notify_to == 1)
				{
					$sent = $this->_sendEmailList($emailTemplate, $dataEmails, $fileAttach);
					// Set the success message if it was a success
					if (! ($sent instanceof Exception))
					{
						$msg = JText::_('JSN_UNIFORM_EMAIL_THANKS');
					}
				}
			}
		}
		if ($checkEmailSubmitter && count($formSubmitter))
		{
			$emailTemplate = new stdClass;
			$htmlMessage = array();
			if ($dataContentEmail)
			{
				$htmlMessage = $this->_emailTemplateDefault($dataContentEmail, $nameFileByIndentifier, $requiredField);
			}

			$emailTemplate->template_message = $htmlMessage;

			$listEmailSubmitter = array();
			foreach ($formSubmitter as $item)
			{
				if (!empty($item))
				{
					$emailSubmitter = new stdClass;
					$emailSubmitter->email_address = isset($dataContentEmail[$item]) ? $dataContentEmail[$item] : "";

					if (!empty($emailSubmitter->email_address))
					{
						$listEmailSubmitter[] = $emailSubmitter;
					}
				}
			}
			$sent = $this->_sendEmailList($emailTemplate, $listEmailSubmitter);
			// Set the success message if it was a success
			if (! ($sent instanceof Exception))
			{
				$msg = JText::_('JSN_UNIFORM_EMAIL_THANKS');
			}
		}


		if ($checkSaveSubmission)
		{
			$submissionId = 0;
			foreach ($submissionsData as $submission)
			{
				if (!empty($submission))
				{
					$submission['submission_id'] = $table->submission_id;

					if ($post['use_payment_gateway'] == 1)
					{
						$dispatcher = JEventDispatcher::getInstance();
						JPluginHelper::importPlugin('uniform', (string) $dataForms->form_payment_type);
						if ($submission['field_type'] == 'choices' || $submission['field_type'] == 'list' || $submission['field_type'] == 'dropdown' || $submission['field_type'] == 'checkboxes' || $submission['field_type'] == 'currency' || $submission['field_type'] == 'number')
						{
							$fieldValue = $submission['submission_data_value'];

							if ($submission['field_type'] == 'list' || $submission['field_type'] == 'checkboxes')
							{
								$fieldValue      = json_decode($fieldValue);
								$submissionValue = array();
								foreach ($fieldValue as $itemValue)
								{
									$tmpMoney          = explode('|', $itemValue);
									if (count($tmpMoney) > 1)
									{
										$moneyValue        = trim($tmpMoney[1]);
										$moneyValue        = $dispatcher->trigger('displayCurrency', $moneyValue);
										$qty               = trim(end($tmpMoney));
										$tmpMoney          = trim($tmpMoney[0]) . ' (' . strip_tags($moneyValue[0]) . ' x ' . $qty . ')';
										$submissionValue[] = json_encode($tmpMoney);
									}
									else
									{
										$submissionValue[] = $itemValue;
									}
								}
								if (!empty($submissionValue))
								{
									$submission['submission_data_value'] = '[' . implode(',', $submissionValue) . ']';
								}

							}
							else
							{
								$tmpMoney   = explode('|', $fieldValue);
								$moneyValue = trim($tmpMoney[1]);
								if ($moneyValue != '')
								{
									$moneyValue                          = $dispatcher->trigger('displayCurrency', $moneyValue);
									$qty                                 = trim(end($tmpMoney));
									$submission['submission_data_value'] = trim($tmpMoney[0]) . ' (' . strip_tags($moneyValue[0]) . ' x ' . $qty . ')';
								}
								else
								{
									$submission['submission_data_value'] = $fieldValue;
								}
							}
							if ($submission['field_type'] == 'currency')
							{
								if(!empty($fieldValue))
								{
									$fieldValue                          = str_replace(',', '.', $fieldValue);
									$currecyValue                        = $dispatcher->trigger('displayCurrency', $fieldValue);
									$currecyValue                        = strip_tags($currecyValue[0]);
									$submission['submission_data_value'] = $currecyValue;
								}
							}
						}
					}
					else
					{
						if ($submission['field_type'] == 'choices' || $submission['field_type'] == 'list' || $submission['field_type'] == 'dropdown' || $submission['field_type'] == 'checkboxes' || $submission['field_type'] == 'currency' || $submission['field_type'] == 'number')
						{
							$fieldValue = $submission['submission_data_value'];
							if ($submission['field_type'] == 'list' || $submission['field_type'] == 'checkboxes')
							{
								$fieldValue      = json_decode($fieldValue);
								$submissionValue = array();
								foreach ($fieldValue as $itemValue)
								{
									$tmpMoney          = explode('|', $itemValue);
									if (count($tmpMoney) > 1)
									{
										$moneyValue        = trim($tmpMoney[0]);
										$submissionValue[] = $moneyValue;
									}
									else
									{
										$submissionValue[] = $itemValue;
									}
								}
								if (!empty($submissionValue))
								{
									$submission['submission_data_value'] = '[' . implode(',', $submissionValue) . ']';
								}
							}
							else
							{
								$tmpMoney   = explode('|', $fieldValue);
								if (count($tmpMoney) > 1)
								{
									$moneyValue = trim($tmpMoney[0]);
									$submission['submission_data_value'] = $moneyValue;
								}
								else
								{
									$submission['submission_data_value'] = $fieldValue;
								}
							}
						}

					}
					$tableSubmission = JTable::getInstance('JsnSubmissiondata', 'JSNUniformTable');
					$tableSubmission->bind($submission);
					if (!$tableSubmission->store())
					{
						$return->error = $tableSubmission->getError();
						return false;
					}
					else
					{
						$submissionId =   $tableSubmission->submission_id;
					}

				}
			}
			$this->_db->setQuery($this->_db->getQuery(true)->select('count(submission_id)')->from("#__jsn_uniform_submissions")->where("form_id=" . (int) $post['form_id']));
			$countSubmission = $this->_db->loadResult();
			$edition = defined('JSN_UNIFORM_EDITION') ? strtolower(JSN_UNIFORM_EDITION) : "free";
			if ($countSubmission == 250 && $edition == "free")
			{
				$templateEmail = new stdClass;
				$templateEmail->template_subject = $defaultSubject;
				$templateEmail->template_message = "<p>Hello there,</p>
	    <p>This is a quick message to let you know you're getting lots of submissions of your form which will soon exceed limit. Please upgrade to PRO edition to accept unlimited number of submissions. <a href=\"http://www.joomlashine.com/joomla-extensions/jsn-uniform-download.html\" target=\"_blank\">Upgrade now</a>.</p>
	    <p>Thank you and all the best,</p>
	    <p>The JoomlaShine Team</p>";
				$app = JFactory::getApplication();
				//$mailfrom = $app->getCfg('mailfrom');
				$jconfig = JFactory::getConfig();
				$mailfrom = $jconfig->get('mailfrom');
				$emailMaster = new stdClass;
				$emailMaster->email_address = $mailfrom;

				$this->_sendEmailList($templateEmail, array($emailMaster));
			}
			$table = JTable::getInstance('JsnForm', 'JSNUniformTable');
			$table->bind(array('form_id' => (int) $post['form_id'], 'form_last_submitted' => date('Y-m-d H:i:s'), 'form_submission_cout' => $countSubmission));
			if (!$table->store())
			{
				$return->error = $table->getError();
				return false;
			}
		}
		if (!empty($_SESSION['securimage_code_value'][$postData['form_name']]))
		{
			unset($_SESSION['securimage_code_value'][$postData['form_name']]);
			unset($_SESSION['securimage_code_disp'][$postData['form_name']]);
			unset($_SESSION['securimage_code_ctime'][$postData['form_name']]);
		}
		return $submissionId;
	}

	/**
	 * get content email
	 *
	 * @param type      $emailContent         email content
	 * @param   String  $requiredField        required field
	 *
	 * @return string
	 */
	public function _emailTemplateDefault($emailContent, $nameFileByIndentifier, $requiredField)
	{
		$i = 0;
		$htmlMessage = '';
		
		if (class_exists('JSNConfigHelper'))
		{
			$objUniformConfig = JSNConfigHelper::get('com_uniform');
		}
		else
		{
			$objUniformConfig = new stdClass;
			$objUniformConfig->form_show_empty_value_field_in_email = 0;
		}	
		
		foreach ($emailContent as $key => $value)
		{
			$i++;
			$value = !empty($value) ? $value : 'Null';
			$name = !empty($nameFileByIndentifier[$key]) ? $nameFileByIndentifier[$key] : ' ';
			$required = '';
			if (isset($requiredField[$key]) && $requiredField[$key] == 1)
			{
				$required = '<span style="  color: red;font-weight: bold; margin: 0 5px;">*</span>';
			}
			if ($i % 2 == 0)
			{
				if ((int) $objUniformConfig->form_show_empty_value_field_in_email == 0)
				{
					if (strtolower(strip_tags($value)) != 'n/a')
					{
						$htmlMessage .= '<tr style="background-color: #FEFEFE;">';
						if ($name)
						{
							$htmlMessage .= ' <td style="width: 30%; font-weight: bold;border-left: 1px solid #DDDDDD;line-height: 20px;padding: 8px;text-align: left;vertical-align: top;">' . $name . $required . '</td>';
						}
						$htmlMessage .= '<td style="border-left: 1px solid #DDDDDD;line-height: 20px;padding: 8px;text-align: left;vertical-align: top;">' . $value . '</td></tr>';
					}
				}
				else
				{
					$htmlMessage .= '<tr style="background-color: #FEFEFE;">';
					if ($name)
					{
						$htmlMessage .= ' <td style="width: 30%; font-weight: bold;border-left: 1px solid #DDDDDD;line-height: 20px;padding: 8px;text-align: left;vertical-align: top;">' . $name . $required . '</td>';
					}
					$htmlMessage .= '<td style="border-left: 1px solid #DDDDDD;line-height: 20px;padding: 8px;text-align: left;vertical-align: top;">' . $value . '</td></tr>';
				}
			}
			else
			{
				if ((int) $objUniformConfig->form_show_empty_value_field_in_email == 0)
				{
					if (strtolower(strip_tags($value)) != 'n/a')
					{
						$htmlMessage .= '<tr style="background-color: #F6F6F6;">';
						if ($name)
						{
							$htmlMessage .= ' <td style="width: 30%; font-weight: bold;border-left: 1px solid #DDDDDD;line-height: 20px;padding: 8px;text-align: left;vertical-align: top;">' . $name . $required . '</td>';
						}
						$htmlMessage .= '<td style="border-left: 1px solid #DDDDDD;line-height: 20px;padding: 8px;text-align: left;vertical-align: top;">' . $value . '</td></tr>';
					}
				}
				else
				{
					$htmlMessage .= '<tr style="background-color: #F6F6F6;">';
					if ($name)
					{
						$htmlMessage .= ' <td style="width: 30%; font-weight: bold;border-left: 1px solid #DDDDDD;line-height: 20px;padding: 8px;text-align: left;vertical-align: top;">' . $name . $required . '</td>';
					}
					$htmlMessage .= '<td style="border-left: 1px solid #DDDDDD;line-height: 20px;padding: 8px;text-align: left;vertical-align: top;">' . $value . '</td></tr>';
				}
			}
		}
		return '<table style="border-spacing: 0;width: 100%;-moz-border-bottom-colors: none;-moz-border-left-colors: none;-moz-border-right-colors: none; -moz-border-top-colors: none; border-collapse: separate; border-color: #DDDDDD #DDDDDD #DDDDDD -moz-use-text-color; border-image: none; border-radius: 4px 4px 4px 4px;  border-style: solid solid solid none;border-width: 1px 1px 1px 0;"><tbody>' . $htmlMessage . '</tbody></table>';
	}

	/**
	 * Send email by list email
	 *
	 * @param   Object  $dataTemplates         Data tempalte
	 *
	 * @param   Array   $listEmail             List email
	 *
	 * @param   Array   $fileAttach            File Attach
	 *
	 * @return  boolean
	 */
	private function _sendEmailList($dataTemplates, $listEmail, $fileAttach = null)
	{
		jimport('joomla.mail.helper');
		$fromname = '';
		$regex = '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,63})$/';
//		$regex = '/^([\p{L}\.\-\d]+)@([\p{L}\-\.\d]+)(\.[\p{L}])((\.[\p{L}]{2,63})+)$/';
		if (!empty($listEmail) && is_array($listEmail) && count($listEmail))
		{

			$emailContent = $dataTemplates->template_message;
			preg_match_all('#(<img.*?>)#', $emailContent, $results, PREG_SET_ORDER);
			if ( count($results))
			{
				for ($i = 0, $count = count($results); $i < $count; $i++)
				{
					$imageTag = $results[$i][1];

					preg_match_all('# src="([^"]+)"#', $imageTag, $imageSrc, PREG_SET_ORDER);
					if (count($imageSrc))
					{
						for ($j = 0, $subcount = count($imageSrc); $j < $subcount; $j++)
						{
							$imgTag = $imageSrc[$j][1];

							preg_match_all('/^(http|https)/', $imgTag, $imageUrl, PREG_SET_ORDER);
							if (!count($imageUrl))
							{
								$url     = JUri::root() . $imgTag;
								$emailContent = str_replace($imgTag, $url, $emailContent);
							}
						}
					}
				}
			}
			$mail 		= JFactory::getMailer();
			$app 		= JFactory::getApplication();
			$jconfig 	= JFactory::getConfig();
			
			$objUniformConfig 	= JSNConfigHelper::get('com_uniform');
			$mailfromDefault 	= (string) $objUniformConfig->form_set_mail_from_default;
			$mailfrom 			= $jconfig->get('mailfrom');
			
			if ($mailfromDefault != '' && JMailHelper::isEmailAddress($mailfromDefault))
			{
				$mailfrom = $mailfromDefault;
			}
			
			if (empty($dataTemplates->template_from_name))
			{
				if ($dataTemplates->template_from != '')
				{
					$fromname = $dataTemplates->template_from;
				}
				else 
				{
					$fromname = $jconfig->get('fromname');
				}
			}
			else 
			{
				$fromname = $dataTemplates->template_from_name;
			}
			//$fromname = empty($dataTemplates->template_from_name) ? $jconfig->get('fromname') : $dataTemplates->template_from_name;
			
			if ($dataTemplates->template_from != '' && JMailHelper::isEmailAddress($dataTemplates->template_from))
			{
				$mailfrom = $dataTemplates->template_from;
			}

			//$fromname = $jconfig->get('fromname');
			$subject = $dataTemplates->template_subject;
			$body = $emailContent;
			$sent = "";
			// Prepare email body
			$body = stripslashes($body);

			$mail->setSender(array($mailfrom, $fromname));
			$mail->setSubject($subject);
			$mail->isHTML(true);
			$mail->Encoding = 'base64';
			$mail->setBody($body);
			
			
			
			if (!empty($dataTemplates->template_reply_to))
			{
				$isEmailAddress = JMailHelper::isEmailAddress($dataTemplates->template_reply_to);
				if ($isEmailAddress)
				{
					$mail->addReplyTo(array($dataTemplates->template_reply_to));
				}
				
			}
			if (!empty($dataTemplates->template_attach) && !empty($fileAttach))
			{
				$attach = json_decode($dataTemplates->template_attach);
				foreach ($attach as $file)
				{
					if (!empty($fileAttach[$file]))
					{
						foreach ($fileAttach[$file] as $f)
						{
							$mail->addAttachment($f);
						}
					}
				}
			}


			foreach ($listEmail as $email)
			{
				$isEmailAddress = JMailHelper::isEmailAddress($email->email_address);
				if ($isEmailAddress)
				{
					$recipient[] = $email->email_address;
					unset($email->email_address);
				}
			}
			
			if (count($recipient))
			{
				$mail->addRecipient(array_unique($recipient));
				$sent = $mail->Send();
				return $sent;
			}
			
			return false;
		}
	}

	/**
	 * get information client
	 *
	 * @param   String  $ipAddr  Ip address client
	 *
	 * @return  String
	 */
	public function countryCityFromIP($ipAddr)
	{
		$ipDetail 	= array();
		$json		= false;
		if (function_exists('stream_context_create'))
		{
			$ctx = stream_context_create(array(
					'http' => array(
							'timeout' => 5
					)
			));
			$json = @file_get_contents("http://api.hostip.info/get_json.php?ip=" . $ipAddr, false, $ctx);
		}
		else
		{
			$json = @file_get_contents("http://api.hostip.info/get_json.php?ip=" . $ipAddr);
		}

		if ($json === false)
		{
			return $ipDetail;
		}


		if (is_null(json_decode($json)))
		{
			return $ipDetail;
		}

		$data 					= json_decode($json, true);

		$ipDetail['city'] 		= (string) substr( (string) @$data['city'], 0, 43);
		$ipDetail['country'] 	= (string) @$data['country_name'];
		$ipDetail['country_code'] = (string) @$data['country_code'];

		return $ipDetail;
	}

	private function _getEmailConfig()
	{
		$this->_db->setQuery($this->_db->getQuery(true)->select('*')->from("#__jsn_uniform_config")->where("name='form_show_empty_value_field_in_email'"));
		return $this->_db->loadObject();
	}

}
