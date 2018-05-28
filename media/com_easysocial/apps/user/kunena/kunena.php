<?php
/**
* @package		%PACKAGE%
* @subpackge	%SUBPACKAGE%
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'admin:/includes/apps/apps' );

/**
 * Friends application for EasySocial.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUserAppKunena extends SocialAppItem
{
	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Determines if Kunena is installed on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function exists()
	{
		$file = JPATH_ADMINISTRATOR . '/components/com_kunena/api.php';

		if( !JFile::exists( $file ) )
		{
			return false;
		}

		require_once( $file );

		return true;
	}

	public function createParent( $messageId = null )
	{
		$parent = new stdClass();
		$parent->forceSecure	= true;
		$parent->forceMinimal	= false;


		if( $messageId )
		{
			$message 	= KunenaForumMessage::getInstance( $messageId );

			$parent->attachments 	= $message->getAttachments();
		}

		return $parent;
	}

	/**
	 * Prepares the stream item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStreamItem	The stream object.
	 * @param	bool				Determines if we should respect the privacy
	 */
	public function onPrepareStream( SocialStreamItem &$item, $includePrivacy = true )
	{

		if( $item->context != 'kunena' )
		{
			return;
		}

		// Test if Kunena exists;
		if( !$this->exists() )
		{
			return;
		}

		$verb	 	= $item->verb;

		// Load app's css file.
		$this->getApp()->loadCss();

		$element	= $item->context;
		$uid     	= $item->contextId;

		// New forum posts
		if( $verb == 'create' )
		{
			$this->processNewTopic( $item , $includePrivacy );
		}

		if( $verb == 'reply' )
		{
			$this->processReply( $item , $includePrivacy );
		}

		if( $verb == 'thanked' )
		{
			$this->processThanked( $item , $includePrivacy );
		}

		if( $includePrivacy )
		{
			$my 		= Foundry::user();
			$privacy 	= Foundry::privacy( $my->id );
			$item->privacy 	= $privacy->form( $uid, $element, $item->actor->id, 'core.view' );
		}
	}

	/**
	 * Processes the stream item for new topics
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStreamItem	The stream item object
	 * @param	bool				Determine if we should include the privacy or not.
	 * @return
	 */
	private function processNewTopic( &$item , $includePrivacy = true )
	{
		$topic 	= KunenaForumTopicHelper::getTopics( array( $item->contextId ) );
		$topic 	= $topic[ key($topic) ];

		// Apply likes on the stream
		$likes 			= Foundry::likes()->get( $item->contextId , 'kunena-create' );
		$item->likes	= $likes;

		// Apply comments on the stream
		$comments			= Foundry::comments( $item->contextId , 'kunena-create' , SOCIAL_APPS_GROUP_USER );
		$item->comments 	= $comments;

		// Define standard stream looks
		$item->display 	= SOCIAL_STREAM_DISPLAY_FULL;
		$item->color 	= '#6f90b5';

		// Set the actor
		$actor 			= $item->actor;

		JFactory::getLanguage()->load( 'com_kunena' , JPATH_ROOT );

		$parent 	= $this->createParent( $topic->first_post_id );

		$topic->message 	= KunenaHtmlParser::parseBBCode( $topic->first_post_message , $parent , 250 );
		$topic->message		= $this->filterContent( $topic->message );

		$this->set( 'actor'	, $actor );
		$this->set( 'topic' , $topic );

		$item->title	= parent::display( 'streams/' . $item->verb . '.title' );
		$item->content	= parent::display( 'streams/' . $item->verb . '.content' );

	}

	/**
	 * Processes the stream item for new topics
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStreamItem	The stream item object
	 * @param	bool				Determine if we should include the privacy or not.
	 * @return
	 */
	private function processReply( &$item , $includePrivacy = true )
	{
		$message 	= KunenaForumMessageHelper::get( $item->contextId );
		$topic 		= $message->getTopic();

		// Apply likes on the stream
		$likes 			= Foundry::likes()->get( $item->contextId , 'kunena-reply' );
		$item->likes	= $likes;

		// Apply comments on the stream
		$comments			= Foundry::comments( $item->contextId , 'kunena-reply' , SOCIAL_APPS_GROUP_USER );
		$item->comments 	= $comments;

		// Define standard stream looks
		$item->display 	= SOCIAL_STREAM_DISPLAY_FULL;
		$item->color 	= '#6f90b5';

		// Set the actor
		$actor 			= $item->actor;
		$parent 		= $this->createParent( $message->id );

		$message->message	= KunenaHtmlParser::parseBBCode( $message->message , $parent , 250 );
		$message->message	= $this->filterContent( $message->message );

		$this->set( 'actor'	, $actor );
		$this->set( 'topic' , $topic );
		$this->set( 'message' , $message );

		$item->title	= parent::display( 'streams/' . $item->verb . '.title' );
		$item->content	= parent::display( 'streams/' . $item->verb . '.content' );
	}

	/**
	 * Processes the stream item for new thanks
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStreamItem	The stream item object
	 * @param	bool				Determine if we should include the privacy or not.
	 * @return
	 */
	private function processThanked( &$item , $includePrivacy = true )
	{
		$message 	= KunenaForumMessageHelper::get( $item->contextId );
		$topic 		= $message->getTopic();

		// Apply likes on the stream
		$likes 			= Foundry::likes()->get( $item->contextId , 'kunena-reply' );
		$item->likes	= $likes;

		// Apply comments on the stream
		$comments			= Foundry::comments( $item->contextId , 'kunena-reply' , SOCIAL_APPS_GROUP_USER );
		$item->comments 	= $comments;

		// Define standard stream looks
		$item->display 	= SOCIAL_STREAM_DISPLAY_MINI;
		$item->color 	= '#6f90b5';

		// Set the actor
		$actor 			= $item->actor;
		$target 		= $item->targets[0];

		$parent 		= $this->createParent( $message->id );
		$message->message	= KunenaHtmlParser::parseBBCode( $message->message , $parent , 250 );
		$message->message	= $this->filterContent( $message->message );

		$this->set( 'actor'	, $actor );
		$this->set( 'target', $target );
		$this->set( 'topic' , $topic );
		$this->set( 'message' , $message );

		$item->title	= parent::display( 'streams/' . $item->verb . '.title' );
	}

	private function filterContent( $content )
	{
		// // @rule: Apply filtering on contents
		// jimport('joomla.filter.filterinput');

		// $filterTags 					= array( 'script' );
		// $filterAttributes 				= explode( ',', 'onclick,onblur,onchange,onfocus,onreset,onselect,onsubmit,onabort,onkeydown,onkeypress,onkeyup,onmouseover,onmouseout,ondblclick,onmousemove,onmousedown,onmouseup,onerror,onload,onunload' );

		// $inputFilter 					= JFilterInput::getInstance( $filterTags , $filterAttributes , 1 , 1 , 0 );
		// $inputFilter->tagBlacklist		= $filterTags;
		// $inputFilter->attrBlacklist		= $filterAttributes;
		// $filterTpe                      = 'html';

		// if( ( count($filterTags) > 0 && !empty($filterTags[0]) ) || ( count($filterAttributes) > 0 && !empty($filterAttributes[0]) ) )
		// {
		// 	$content  = $inputFilter->clean( $content, $filterTpe );
		// }

		/*
		 * temporary fix to prevent email cloaking causing ajax to failed.
		 *
		 */
		$content = strip_tags( $content );

		return $content;
	}

	/**
	 * Prepares the activity log
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStreamItem	The stream object.
	 * @param	bool				Determines if we should respect the privacy
	 */
	public function onPrepareActivityLog( SocialStreamItem &$item, $includePrivacy = true )
	{
	}


}
