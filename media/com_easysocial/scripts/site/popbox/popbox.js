EasySocial.module("notifications/popbox", function($){

	this.resolve(function(popbox)
	{
		return {
			content: EasySocial.ajax( "site/controllers/notifications/getSystemItems",
			{
				layout	: "popbox.notifications"
			}),
			id: "es-wrap",
			type: "notifications",
			cache: false
		};
	});
});

EasySocial.module("conversations/popbox", function($){

	this.resolve(function(popbox)
	{
		return {
			content: EasySocial.ajax( "site/controllers/notifications/getConversationItems",
			{
				usemax 	: "1",
				layout	: "popbox.conversations"
			}),
			id: "es-wrap",
			type: "notifications",
			cache: false
		};
	});
});

EasySocial.module("friends/popbox", function($){

	this.resolve(function(popbox){

		return {
			content: EasySocial.ajax( "site/controllers/notifications/friendsRequests",
			{
				layout	: "popbox.friends"
			}),
			id: "es-wrap",
			type: "notifications",
			cache: false
		};
	});
});
