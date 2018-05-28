EasySocial.module( 'site/dashboard/dashboard' , function($){

	var module 				= this;

	EasySocial.require()
	.script( 'site/dashboard/apps' , 'site/dashboard/feeds' , 'site/dashboard/sidebar', 'site/stream/filter' )
	.done(function($){

		EasySocial.Controller(
			'Dashboard',
			{
				defaultOptions:
				{
					"{heading}"			: "[data-dashboard-heading]",
					"{sidebar}"			: "[data-dashboard-sidebar]",
					"{content}"			: "[data-dashboard-real-content]",

					// Feeds.
					"{feeds}"			: "[data-dashboard-feeds]",

					// Applications.
					"{apps}"			: "[data-dashboard-apps]",

					// hashtag filter save
					"{saveHashTag}"		: "[data-hashtag-filter-save]"
				}
			},
			function(self){

				return{

					init: function()
					{
						// Implement sidebar controller.
						self.sidebar().implement( EasySocial.Controller.Dashboard.Sidebar ,
						{
							"{parent}"	: self
						});

						// Implement app controller on all app items.
						self.feedsController = self.feeds().addController( EasySocial.Controller.Dashboard.Feeds ,
						{
							"{parent}"	: self
						});

						// Implement app controller on all app items.
						self.apps().implement( EasySocial.Controller.Dashboard.Apps ,
						{
							"{parent}"	: self,
							pageTitle	: self.options.pageTitle
						});

					},

					/**
					 * Responsible to update the heading area in the dashboard.
					 */
					updateHeading: function( title , description )
					{
						self.heading().find( '[data-heading-title]' ).html( title );
						self.heading().find( '[data-heading-desc]' ).html( description );
					},

					/**
					 * Add a loading icon on the content layer.
					 */
					updatingContents: function()
					{
						self.element.addClass("loading");
					},

					/**
					 * Responsible to update the content area in the dashboard.
					 */
					updateContents : function( contents )
					{
						self.element.removeClass("loading");

						// Hide the content first.
						self.content().html( contents );
					},



					"{saveHashTag} click": function( el )
					{
						var hashtag = el.data('tag');

						EasySocial.dialog({
							content		: EasySocial.ajax( 'site/views/stream/confirmSaveFilter', { "tag": hashtag } ),
							bindings	:
							{
								"{saveButton} click" : function()
								{
									this.inputWarning().hide();

									filterName = this.inputTitle().val();

									if( filterName == '' )
									{
										this.inputWarning().show();
										return;
									}

									EasySocial.ajax( 'site/controllers/stream/addFilter',
									{
										"title"		: filterName,
										"tag"		: hashtag,
									})
									.done(function( html, msg )
									{
										// self.feeds().append( html );

										var item = $.buildHTML( html );
										self.feedsController.addFilterItem( item );

										// show message
										EasySocial.dialog( msg );

										// auto close the dialog
										setTimeout(function() {
											EasySocial.dialog().close();
										}, 2000);

									});
								}
							}
						});
					}


				}
			});

		module.resolve();
	});

});
