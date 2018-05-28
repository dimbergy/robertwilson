<?php
/**
 * Kunena Component
 * @package     Kunena.Site
 * @subpackage  Controller.Topic
 *
 * @copyright   (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link        https://www.kunena.org
 **/
defined('_JEXEC') or die;

/**
 * Class ComponentKunenaControllerTopicListDisplay
 *
 * @since  K4.0
 */
abstract class ComponentKunenaControllerTopicListDisplay extends KunenaControllerDisplay
{
	protected $name = 'Topic/List';

	/**
	 * @var KunenaUser
	 */
	public $me;

	/**
	 * @var array|KunenaForumTopic[]
	 */
	public $topics;

	/**
	 * @var KunenaPagination
	 */
	public $pagination;

	/**
	 * @var string
	 */
	public $headerText;

	/**
	 * Prepare topics by pre-loading needed information.
	 *
	 * @param   array  $userIds  List of additional user Ids to be loaded.
	 * @param   array  $mesIds   List of additional message Ids to be loaded.
	 *
	 * @return  void
	 */
	protected function prepareTopics(array $userIds = array(), array $mesIds = array())
	{
		// Collect user Ids for avatar prefetch when integrated.
		$lastIds = array();

		foreach ($this->topics as $topic)
		{
			$userIds[(int) $topic->first_post_userid] = (int) $topic->first_post_userid;
			$userIds[(int) $topic->last_post_userid] = (int) $topic->last_post_userid;
			$lastIds[(int) $topic->last_post_id] = (int) $topic->last_post_id;
		}

		// Prefetch all users/avatars to avoid user by user queries during template iterations.
		if (!empty($userIds))
		{
			KunenaUserHelper::loadUsers($userIds);
		}

		$topicIds = array_keys($this->topics);
		KunenaForumTopicHelper::getUserTopics($topicIds);
		/* KunenaForumTopicHelper::getKeywords($topicIds); */
		$mesIds += KunenaForumTopicHelper::fetchNewStatus($this->topics);

		// Fetch also last post positions when user can see unapproved or deleted posts.
		// TODO: Optimize? Take account of configuration option...
		if ($this->me->isAdmin() || KunenaAccess::getInstance()->getModeratorStatus())
		{
			$mesIds += $lastIds;
		}

		// Load position information for all selected messages.
		if ($mesIds)
		{
			KunenaForumMessageHelper::loadLocation($mesIds);
		}
	}

	/**
	 * Prepare document.
	 *
	 * @return void
	 */
	protected function prepareDocument()
	{
		$page = $this->pagination->pagesCurrent;
		$total = $this->pagination->pagesTotal;
		$headerText = $this->headerText . ($total > 1 ? " ({$page}/{$total})" : '');

		$app = JFactory::getApplication();
		$menu_item   = $app->getMenu()->getActive(); // get the active item

		if ($menu_item)
		{
			$params             = $menu_item->params; // get the params
			$params_title       = $params->get('page_title');
			$params_keywords    = $params->get('menu-meta_keywords');
			$params_description = $params->get('menu-meta_description');

			if (!empty($params_title))
			{
				$title = $params->get('page_title');
				$this->setTitle($title);
			}
			else
			{
				$this->title = $this->headerText;
				$this->setTitle($headerText);
			}

			if (!empty($params_keywords))
			{
				$keywords = $params->get('menu-meta_keywords');
				$this->setKeywords($keywords);
			}
			else
			{
				$keywords = $this->config->board_title;
				$this->setKeywords($keywords);
			}

			if (!empty($params_description))
			{
				$description = $params->get('menu-meta_description');
				$this->setDescription($description);
			}
			else
			{
				$description = JText::_('COM_KUNENA_THREADS_IN_FORUM') . ': ' . $this->config->board_title;
				$this->setDescription($description);
			}
		}
	}

	/**
	 * Get Topic Actions.
	 *
	 * @return array
	 */
	protected function getTopicActions(
		array $topics,
		$actions = array('delete', 'approve', 'undelete', 'move', 'permdelete')
	)
	{
		if (!$actions) return null;

		$options = array();
		$options['none'] = JHtml::_('select.option', 'none', JText::_('COM_KUNENA_BULK_CHOOSE_ACTION'));
		$options['unsubscribe'] = JHtml::_('select.option', 'unsubscribe', JText::_('COM_KUNENA_UNSUBSCRIBE_SELECTED'));
		$options['unfavorite'] = JHtml::_('select.option', 'unfavorite', JText::_('COM_KUNENA_UNFAVORITE_SELECTED'));
		$options['move'] = JHtml::_('select.option', 'move', JText::_('COM_KUNENA_MOVE_SELECTED'));
		$options['approve'] = JHtml::_('select.option', 'approve', JText::_('COM_KUNENA_APPROVE_SELECTED'));
		$options['delete'] = JHtml::_('select.option', 'delete', JText::_('COM_KUNENA_DELETE_SELECTED'));
		$options['permdelete'] = JHtml::_('select.option', 'permdel', JText::_('COM_KUNENA_BUTTON_PERMDELETE_LONG'));
		$options['undelete'] = JHtml::_('select.option', 'restore', JText::_('COM_KUNENA_BUTTON_UNDELETE_LONG'));

		// Only display actions that are available to user.
		$actions = array_combine($actions, array_fill(0, count($actions), false));
		array_unshift($actions, $options['none']);

		foreach ($topics as $topic)
		{
			foreach ($actions as $action => $value)
			{
				if ($value !== false)
				{
					continue;
				}

				switch ($action) {
					case 'unsubscribe':
					case 'unfavorite':
						$actions[$action] = isset($options[$action]) ? $options[$action] : false;
						break;
					default:
						$actions[$action] = isset($options[$action]) && $topic->isAuthorised($action) ? $options[$action] : false;
				}
			}
		}

		$actions = array_filter($actions, function($item) { return !empty($item); });

		if (count($actions) == 1) return null;

		return $actions;
	}

	/**
	 * Get Message Actions.
	 *
	 * @return array
	 */
	protected function getMessageActions(
		array $messages,
		$actions = array('approve', 'undelete', 'delete', 'permdelete')
	)
	{
		if (!$actions) return null;

		$options = array();
		$options['none'] = JHtml::_('select.option', 'none', JText::_('COM_KUNENA_BULK_CHOOSE_ACTION'));
		$options['approve'] = JHtml::_('select.option', 'approve_posts', JText::_('COM_KUNENA_APPROVE_SELECTED'));
		$options['delete'] = JHtml::_('select.option', 'delete_posts', JText::_('COM_KUNENA_DELETE_SELECTED'));
		$options['permdelete'] = JHtml::_('select.option', 'permdel_posts', JText::_('COM_KUNENA_BUTTON_PERMDELETE_LONG'));
		$options['undelete'] = JHtml::_('select.option', 'restore_posts', JText::_('COM_KUNENA_BUTTON_UNDELETE_LONG'));

		// Only display actions that are available to user.
		$actions = array_combine($actions, array_fill(0, count($actions), false));
		array_unshift($actions, $options['none']);

		foreach ($messages as $message)
		{
			foreach ($actions as $action => $value)
			{
				if ($value !== false)
				{
					continue;
				}

				$actions[$action] = isset($options[$action]) && $message->isAuthorised($action) ? $options[$action] : false;
			}
		}

		$actions = array_filter($actions, function($item) { return !empty($item); });

		if (count($actions) == 1) return null;

		return $actions;
	}
}
