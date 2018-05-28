<?php

/**
 * @version     $Id: $
 * @package     JSNUniform
 * @subpackage  Submissions
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2016 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted access');
set_time_limit(999999999999);

$input 		= JFactory::getApplication()->input;
$getData 	= $input->getArray($_GET);
$formId 	= $input->get('form_id');
if ($formId)
{
	$fieldIdentifier = $this->_viewField['fields']['identifier'];
	$listViewField = $this->_viewField['field_view'];
	$fieldTitle = $this->_viewField['fields']['title'];
	$fieldType = $this->_viewField['fields']['type'];
	$arrayField = explode(",", str_replace("&quot;", '', $listViewField));
	$data = array();
	$dataItem = array();
	$dataItemLikert = array();
	$dataLikertTitle = array();
	for ($i = 0; $i < count($fieldIdentifier); $i++)
	{
		if (in_array($fieldIdentifier[$i], $arrayField))
		{
			if ($fieldType[$fieldIdentifier[$i]] == 'likert')
			{
				if ( $this->_dataExport)
				{
					$foo = json_decode(json_decode($this->_dataExport[0]->$fieldIdentifier[$i])->settings)->rows;
					$countItems = count($foo) - 1;
					foreach($foo as $bar)
					{
						$dataItemLikert[] = $bar->text;
						$dataLikertTitle[] = $countItems.':::' .$fieldTitle[$i];
					}

				}

			}
			else
			{
				$dataItem[] = JText::_($fieldTitle[$i]);
			}

		}
	}
	$likertDataTitle = array();
	$data[] = array_merge(array_unique($dataLikertTitle), $likertDataTitle);
	$dataItem = array_merge($dataItemLikert, $dataItem);
	$dataItem[] = JText::_("JGRID_HEADING_ID");
	$data[] = $dataItem;
	if (is_array($arrayField))
	{
		if ($this->_dataExport)
		{
			$listExport = $getData['list_export'];
			if (isset($listExport) && $listExport != '')
			{
				$listExport = explode(',', $listExport);
				foreach ($this->_dataExport as $i => $item)
				{
					if (in_array($item->submission_id, $listExport))
					{
						$dataItem = array();
						$dataLikert = array();
						foreach ($arrayField as $j => $field)
						{
							$contentField = "";
							if (isset($fieldType[$field]))
							{
								if ($fieldType[$field] == 'likert')
								{
									$contentField = JSNUniformHelper::getDataField($fieldType[$field], $item, $field, $formId, false, false, 'export');
									$contentField = $contentField ? $contentField : "";
									if ($contentField)
									{
										$contentField = explode('<br/>', $contentField);
										foreach ( $contentField as $content)
										{
											$content = strip_tags($content);
											$content = explode(":", $content);
											$dataLikert[] = $content[1];
										}

									}
								}
								else
								{
									$contentField = JSNUniformHelper::getDataField($fieldType[$field], $item, $field, $formId, false, false, 'export');
									$contentField = $contentField ? strip_tags($contentField) : "";
									if ($field == 'submission_created_by' && !$item->$field)
									{
										$contentField = isset($listUser[$item->$field]) ? $listUser[$item->$field] : "Guest";
									}
									$dataItem[] = $contentField;
								}
							}
						}
						$dataItem = array_merge($dataLikert, $dataItem);
						$dataItem[] = $item->submission_id;
						$data[]     = $dataItem;
					}
				}
			}
			else{
				foreach ($this->_dataExport as $i => $item)
				{
					$dataItem = array();
					$dataLikert = array();
					foreach ($arrayField as $j => $field)
					{
						$contentField = "";
						if (isset($fieldType[$field]))
						{
							if ($fieldType[$field] == 'likert')
							{
								$contentField = JSNUniformHelper::getDataField($fieldType[$field], $item, $field, $formId, false, false, 'export');
								$contentField = $contentField ? $contentField : "";
								if ($contentField)
								{
									$contentField = explode('<br/>', $contentField);
									foreach ( $contentField as $content)
									{
										$content = strip_tags($content);
										$content = explode(":", $content);
										$dataLikert[] = $content[1];
									}

								}
							}
							else
							{
								$contentField = JSNUniformHelper::getDataField($fieldType[$field], $item, $field, $formId, false, false, 'export');
								$contentField = $contentField ? strip_tags($contentField) : "";
								if ($field == 'submission_created_by' && !$item->$field)
								{
									$contentField = isset($listUser[$item->$field]) ? $listUser[$item->$field] : "Guest";
								}
								$dataItem[] = $contentField;
							}

						}
					}
					$dataItem = array_merge($dataLikert, $dataItem);
					$dataItem[] = $item->submission_id;
					$data[] = $dataItem;
				}
			}
		}
	}
	if (isset($getData['e']) && $getData['e'] == "excel")
	{
		include_once JSN_UNIFORM_LIB_PHPEXCEL;
		// generate file (constructor parameters are optional)
		$xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
		$xls->addArray($data);
		$xls->generateXML('jsn-uniform-' . $this->_infoForm->form_title . '-excel-' . date("Y-m-d"));
		exit();
	}
	else if (isset($getData['e']) && $getData['e'] == "csv")
	{
		$fileName = 'jsn-uniform-' . $this->_infoForm->form_title . '-csv-' . date("Y-m-d");
		$fileName = preg_replace('/[^aA-zZ0-9\_\-]/', '', $fileName);
		header("Content-type:text/octect-stream; charset=UTF-8");
		header("Content-Disposition:attachment;filename={$fileName}.csv");
		$output = fopen('php://output', 'w');
		foreach ($data as $items)
		{
			fputcsv($output, $items);
		}
		fclose($output);
		exit();
	}
}
