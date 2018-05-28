EasySocial.module("site/stream/video", function($){

	$(document).on("click", "[data-es-links-embed-item]", function()
	{
		var button 	= $( this ),
			player 	= $( '<div>' ).html( button.data( 'es-stream-embed-player' ) );


		var embedCode 	= '<div class="video-container mb-15 mt-15">' + player.html() + '</div>';

		button.replaceWith( embedCode );
	});

	this.resolve();
});
