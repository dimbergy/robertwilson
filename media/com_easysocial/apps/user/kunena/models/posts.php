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

Foundry::import( 'admin:/includes/model' );

class PostsModel extends EasySocialModel
{
	/**
	 * Retrieves a list of tasks created by a particular user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		$userId		The user's / creator's id.
	 *
	 * @return	Array				A list of notes item.
	 */
	public function getPosts( $userId , $total = 10 )
	{
		$db 	= Foundry::db();

		$sql 	= $db->sql();


		$sql->select( '#__kunena_messages' , 'a' );
		$sql->column( 'a.thread' );
		$sql->where( 'a.parent' , 0 );
		$sql->where( 'a.userid' , $userId );
		$sql->where( 'a.hold' , '0' , '=' );
		$sql->order( 'a.time' , 'DESC' );
		$sql->limit( 0 , $total );

		$db->setQuery( $sql );

		$result	= $db->loadColumn();

		if( !$result )
		{
			return array();
		}
// dump( $result );
		$posts 	= KunenaForumTopicHelper::getTopics( $result );

		return $posts;
	}

	/**
	 * Retrieves replies posted in Kunena
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int			The author's id.
	 *
	 * @return	Array		A list of notes item.
	 */
	public function getReplies( $userId )
	{
		$db 	= Foundry::db();

		$sql 	= $db->sql();


		$sql->select( '#__kunena_messages' , 'a' );
		$sql->column( 'a.*' );
		$sql->column( 'b.*' );
		$sql->column( 'c.message' , 'content' );
		$sql->join( '#__kunena_categories' , 'b' );
		$sql->on( 'a.catid' , 'b.id' );

		$sql->join( '#__kunena_messages_text' , 'c' );
		$sql->on( 'a.id' , 'c.mesid' );

		$sql->where( 'a.parent' , 0 , '!=' );
		$sql->where( 'a.userid' , $userId );
		$sql->where( 'b.published' , 1 );

		$sql->order( 'a.time' , 'DESC' );

		$db->setQuery( $sql );

		$posts	= $db->loadObjectList();

		return $posts;
	}

}