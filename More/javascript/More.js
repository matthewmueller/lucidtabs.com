More = function() {
	
}

More.prototype = {
	
	start : function() {
		$('.theme_classic_help').css('color', '#0078E5');
		
		// Made slide tabs work.
		$('.more_link').click(function() {
			if ($(this).hasClass('clicked')) return;
			
			$('.more_link').removeClass("clicked");
			$(this).addClass("clicked");
			
			var c = $(this).find('img').attr("class");
			
			$('.more_content').children().each(function(index) {
				if ($(this).css("display") == "block") {
					$(this).fadeOut('fast', function() {
						$("." + c + "_content").fadeIn("fast");
					});	
				}
			});
		});
		
		$('.skip').click(function() {
			window.location = "../Main/";
		});
	},
	
	login : function() {
		window.location = "../Main/?view=login";
	},
	
	signup : function() {
		window.location = "../Main/?view=signup";
	},
}