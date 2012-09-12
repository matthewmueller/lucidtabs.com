Classic = function() {
	this.master = null;
	this.theme = "classic";
	this.name = "Theme";
	
	return this;
}

Classic.prototype = {
	
	start : function() {
		// Buttons
		this.loginButton = $(".theme_"+this.theme+"_login-btn");
		this.loginButton.bind('click', [this, 'login']);
		
		this.signupButton = $(".theme_"+this.theme+"_signup-btn");
		this.signupButton.bind('click', [this, 'signup']);
		
		// Start search, pass the theme as the caller
		S = new Search(this);

		// Make the search button clickable.
		$('.theme_'+this.theme+'_search-button').bind("click", function() {
			S.select();
		});
		
		if ($('.theme_classic_name').get(0)) {
			this.fitTitle($('.theme_classic_name'), 230);
		}
		
		// IE check..
		if('\v'=='v') $('.theme_classic_notice').show();
		
	},
	
	fitTitle : function(elem, maxWidth) {
		var elem = $(elem);
		elem.show();
		
		var textWidth = elem.width();
		var boxWidth = maxWidth;
		var currentFontSize = elem.css('font-size');
		currentFontSize = parseInt(currentFontSize.substring(0, currentFontSize.length - 2)); // get rid of px
		var newSize = Math.floor(boxWidth / (textWidth / currentFontSize));
		// Max out at 34px
		(currentFontSize < newSize) ? newSize = currentFontSize : newSize = newSize; 
		elem.css('font-size', newSize + 'px');
		// Fix margin to center - Found linear equation
		var newMargin = Math.round((-2/3)*newSize + 20);
		elem.css('margin-top', newMargin + 'px');
	},
	
	login : function() {
		if(!this.loginButton.hasClass('down')) {
			this.signupButton.toggleClass("down");
			this.loginButton.toggleClass("down");
		}	
		
		
		// Use the master to determine what to do when login is pressed.
		this.master.login();
	},
	
	signup : function() {
		if(!this.signupButton.hasClass("down")) {
			this.loginButton.toggleClass("down");
			this.signupButton.toggleClass("down");
		}
		
		// Use the master to determine what to do when signup is pressed.
		this.master.signup();
	},
	
	_Search_create : function() {
		$('div.Search.box').fadeOut('fast');

		window.location = "../Create/?view=new";
	},
	
	_Search_select : function(tab) {
		var song = tab.song;
		var artist = tab.artist;
		song = song.replace(" ", "-");
		artist = artist.replace(" ", "-");
		
		window.location = "../View/?t="+song+"_"+artist;
	},
	
	toString : function() {
		return "Classic Theme";
	}
}