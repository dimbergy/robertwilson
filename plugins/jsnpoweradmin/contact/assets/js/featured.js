/**
 * @subpackage	com_poweradmin (JSN POWERADMIN JoomlaShine - http://www.joomlashine.com)
 * @copyright	Copyright (C) 2001 BraveBits,Ltd. All rights reserved.
 **/
(function($){
	$.com_contact_featured = function(Itemid){
		/**
		 * Variable to store context menu
		 */
		this.contextMenu;
		this.contextElements;
		this.currentElement;
		/**
		 * Init all variables
		 */
		this.initVariables = function(){
			this.setData( 'option', 'com_contact' );
			this.setData( 'view', 'featured' );
			this.setData( 'layout', '' );
			this.setData( 'id', $('#contact_id').val()  );
			this.setData( 'Itemid', Itemid );
			this.setData( 'requestType', 'only' );			

			//Scan elements approved context menu
			this.contextElements = new Array( $( '.'+this.classApprovedContextMenu ).length );
			
			var $this = this;
			$( '.'+this.classApprovedContextMenu ).each(function(){
				if ( $(this).hasClass('display-default') ){
					$(this).data('show', true);
				}else{
					$(this).data('show', false);
				}				
				$this.contextElements[$(this).attr('id')] = $(this);
			});
		};
		/**
		 * Ajax request task function
		 */
		this.beforeAjaxRequest = function(task){
			this.currentElement.showImgStatus({status : 'request'});
			this.setData( 'requestTask', task );
			this.ajaxRequest();
		};
		
		
		/**
		 * Add context menu
		 */
		this.addContextMenu = function(){
			this.contextMenu = this.getContextMenu();
			var $this = this;
			if ( this.contextMenu != null ){
				if ( $this.contextMenu.isNew() ){
									
					/**
					 * Add parent item 
					 */
					var parentHideItem = $this.contextMenu.addParentItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_HIDE_ARTICLE') );
					/**
					 * Add item and assign to process hide globally for all articles
					 */
					parentHideItem.addItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_SHOWHIDE_ARTICLE_GLOBAL_FOR_ALL_PAGES') ).addEventHandler("click", function(){
						$this.contextMenu.hide({});
						$this.setData( 'requestType', 'globally' );	
						$this.setParams( $this.currentElement.attr('parname'), 0);
						$this.beforeAjaxRequest('hide');
					});
					/**
					 * Add item and assign to process hide this article
					 */
					parentHideItem.addItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_SHOWHIDE_ARTICLE_ONLY_THIS_PAGE') ).addEventHandler("click", function(){
						$this.contextMenu.hide({});
						$this.setData( 'requestType', 'only' );
						$this.setParams( $this.currentElement.attr('parname'), 0);
						$this.beforeAjaxRequest('hide');
					});

					/**
					 * Add parent item 
					 */
					var parentShowItem = $this.contextMenu.addParentItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_SHOW_ARTICLE') );
					/**
					 * Add item and assign to process show globally for all articles
	 				 */
					parentShowItem.addItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_SHOWHIDE_ARTICLE_GLOBAL_FOR_ALL_PAGES') ).addEventHandler("click", function(){
						$this.contextMenu.hide({});
						$this.setData( 'requestType', 'globally' );
						$this.setParams( $this.currentElement.attr('parname'), 1);						
						$this.beforeAjaxRequest('show');
					});
					/**
					 * Add item and assign to process show this article
					 */
					parentShowItem.addItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_SHOWHIDE_ARTICLE_ONLY_THIS_PAGE') ).addEventHandler("click", function(){
						$this.contextMenu.hide({});
						$this.setData( 'requestType', 'only' );
						$this.setParams( $this.currentElement.attr('parname'), 1);
						$this.beforeAjaxRequest('show');
					});
				}
				
				$this.container.unbind("mousedown").mousedown(function(e){
					if ($(e.target).hasClass($this.classApprovedContextMenu.replace('.', ''))){
						$this.currentElement = $(e.target);
					}else{
						$this.currentElement = $(e.target).parents('.'+$this.classApprovedContextMenu);
					}
					var tagId = ( $this.currentElement.attr('id') != undefined ? $this.currentElement.attr('id') : '' );
					if ( e.which == 1 && $this.contextElements[tagId] != undefined ){
						var current = $this.contextElements[tagId];
						$this.contextMenu.hideAllItems();

 						if ( current.data('show')){
							var itemAction = $this.contextMenu.showItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_HIDE_ARTICLE'));
						}else{
							var itemAction = $this.contextMenu.showItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_SHOW_ARTICLE'));
						}
						
						$this.contextMenu.show({
							x : e.pageX,
							y : e.pageY
						});
						
						$this.contextMenu.trigger('component.context.show');
					}else{
						$this.currentElement = $({});
					}
				});
			}
		};
	};
})(JoomlaShine.jQuery);