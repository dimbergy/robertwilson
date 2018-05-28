EasySocial.module( 'site/toolbar/system' , function($){

	var module 				= this;

	EasySocial.require()
	.view( 'site/notifications/system.empty' )
	.library( 'tinyscrollbar' )
	.done(function($){

		EasySocial.Controller(
			'Notifications.System',
			{
				defaultOptions:
				{

					// Check every 10 seconds by default.
					interval	: 30,

					// Elements within this container.
					"{counter}"			: "[data-notificationSystem-counter]"
				}
			},
			function(self){ return{

				init: function()
				{
					// Start the automatic checking of new notifications.
					self.startMonitoring();
				},

				/**
				 * Start running checks.
				 */
				startMonitoring: function()
				{
					var interval 	= self.options.interval * 1000;

					// Debug
					if( EasySocial.debug )
					{
						// console.info( 'Start monitoring system notifications at interval of ' + self.options.interval + ' seconds.' );
					}

					self.options.state	= setTimeout( self.check , interval );
				},

				/**
				 * Stop running any checks.
				 */
				stopMonitoring: function()
				{
					// Debug
					if( EasySocial.debug )
					{
						// console.info( 'Stop monitoring system notifications.' );
					}

					clearTimeout( self.options.state );
				},

				/**
				 * Check for new updates
				 */
				check: function(){

					// Stop monitoring so that there wont be double calls at once.
					self.stopMonitoring();

					var interval 	= self.options.interval * 1000;

					// Needs to run in a loop since we need to keep checking for new notification items.
					setTimeout( function(){

						EasySocial.ajax( 'site/controllers/notifications/getSystemCounter' , {},
						{
							type : "jsonp"
						})
						.done( function( total ){

							if( total > 0 )
							{
								// Update toolbar item element
								self.element.addClass( 'has-notice' );

								// Update the counter's count.
								self.counter().html( total );
							}
							else
							{
								self.element.removeClass( 'has-notice' );
							}
							// Continue monitoring.
							self.startMonitoring();
						});

					}, interval );

				},

				'{window} easysocial.clearSystemNotification': function() {
					self.element.removeClass('has-notice');
					self.counter().html(0);
				}
			}}
		);

		EasySocial.Controller('Notifications.System.Popbox', {
			defaultOptions: {
				"{readall}"	: "[data-notificationsystem-readall]",
				"{items}"	: "[data-notificationsystem-items]",

				view: {
					empty	: "site/notifications/system.empty"
				}
			}
		}, function(self) {
			return {
				init: function() {

				},

				"{readall} click": function()
				{
					EasySocial.ajax( 'site/controllers/notifications/setAllState' ,
					{
						"state"	: "read"
					})
					.done(function()
					{
						self.items().html('');

						self.items().append(self.view.empty());

						$(window).trigger('easysocial.clearSystemNotification');
					});
				}
			}
		})

		module.resolve();
	});

});
