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

// We need the router
require_once( JPATH_ROOT . '/components/com_content/helpers/route.php' );

/**
 * Profile view for article app
 *
 * @since	1.0
 * @access	public
 */
class ArticleViewProfile extends SocialAppsView
{
	/**
	 * Displays the application output in the canvas.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user id that is currently being viewed.
	 */
	public function display( $userId = null , $docType = null )
	{
		// Get the user params
		$params		= $this->getUserParams( $userId );

		// Get the app params
		$appParams	= $this->app->getParams();

		// Get the blog model
		$total 		= (int) $params->get( 'total' , $appParams->get( 'total' , 5 ) );

		// Get list of blog posts created by the user on the site.
		$model 		= $this->getModel( 'Article' );
		$articles 	= $model->getItems( $userId , $total );
		$user 		= Foundry::user( $userId );

		$this->format( $articles , $appParams );

		$this->set( 'user'		, $user );
		$this->set( 'articles'	, $articles );
			
		echo parent::display( 'profile/default' );
	}

	private function format( &$articles , $params )
	{
		if( !$articles )
		{
			return;
		}

		foreach( $articles as $article )
		{
			$category	= JTable::getInstance( 'Category' );
			$category->load( $article->catid );

			$article->category 				= $category;
			$article->permalink	 			= ContentHelperRoute::getArticleRoute( $article->id . ':' . $article->alias , $article->catid );
			$article->category->permalink	= ContentHelperRoute::getCategoryRoute( $category->id . ':' . $category->alias );
			$article->content 				= empty( $article->introtext ) ? $article->fulltext : $article->introtext;

			$titleLength 	= $params->get( 'title_length' );
			$contentLength	= $params->get( 'content_length' );

			if( $titleLength )
			{
				$article->title 	= JString::substr( $article->title , 0 , $titleLength );
			}

			if( $contentLength )
			{
				$article->content 	= JString::substr( strip_tags( $article->content ) , 0 , $contentLength );
			}
		}
	}
}