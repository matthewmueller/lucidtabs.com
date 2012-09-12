Create = function() {
	// This will hold all the guitars
	this.Guitars = [];
	this.name = "Master";
	this.element = $('#tab');
	
	// Initialize Buttons
	this.addButton = $('.tab_add_button').bind('click', [this, 'addStep']);
	this.saveButton = $('.tab_save_button').bind('click', [this, 'save']);
	this.createButton = $('.tab_create_button').bind('click', [this, 'create']);
	
	// Grab artist fields
	this.song = $('.tab_new_song');
	this.artist = $('.tab_new_artist');
	
	// Problems can come up when browsers save text clear them right away
	this.song.val("");
	this.artist.val("");
	// Causes it to tab down to description right after tabbing out of artist
	this.artist.blur(function() {$('.tab_description_text').focus()});
	
}

Create.prototype = {
	
	start : function() {
		// this.addStep();
		$('.Search.input', this.element).focus();
		new Search(this);
		
	},
	
	addStep : function() {
		var tab = this;
		// Create a new Guitar
		var guitar = new Guitar(this);

		// Insert it before the add button but after everything else.
		$(this.addButton).before(guitar.element);
		$(guitar.element).hide().slideDown("slow");
		this.saveButton.removeClass("down");
		
		// Add guitar to array
		this.Guitars.push(guitar);
	},
	
	save : function() {
		if (this.saveButton.hasClass('down')) return;
		var post = {};
		post.tab_id = this.id;
		var postData = [];
		
		var guitars = [];
		
		$(this.Guitars).each(function(i) {
			var board = this.serialize();
			guitars.push(board);
		});
		post.guitars = $.toJSON(guitars);
		postData = $(post).formatPost();		
		// console.log(postData);

		// Save a reference to the Tab object for the AJAX functions.
		var Create = this;
		$.ajax({
			  url: "?action=save",
			  type: "POST",
			  data: postData,
			  success: function(response) {
					//called when ajax successful
					Create.saveButton.addClass('down');
					// Works with save now update..
					var ids = $.secureEvalJSON(response);
					$('div#output').text(response);
					// Updating IDs
					$(Create.Guitars).each(function(i) {
						if(!this.id) 
							this.id = ids[i]['Guitar'];
						
						// Quick fix to create "reverse" pop
						ids[i]['Doodads'].reverse();
						
						// Run through and update ids for Doodads
						$(this.Doodads).each(function(j) {
							if(!this.id) {
								this.id = ids[i]['Doodads'].pop();
							}
						});
					});
			  },
			  error: function(error) {
				  //called when there is an error
				  alert(error);
	  		  }
		});
	},
	
	create : function() {
		var data = {};
		if(this.artist.val() && this.song.val()) {
			data.artist = this.artist.val();
			data.song = this.song.val();
		}
		else if(this.artistID && this.songID) {
			data.artist_id = this.artistID;
			data.song_id = this.songID;
		}
		else if($('.tab_new_container').css('display') == "none") {
			$('.Search.input', this.element).addClass("tab_error").focus();
			return;
		}
		else {
			if ($('.tab_new_song').val() == "")
				$('.tab_new_song').addClass("tab_error").focus();
			if ($('.tab_new_artist').val() == "")
				$('.tab_new_artist').addClass("tab_error").focus();
			return;
		}
		$('.tab_new_song,.tab_new_artist').removeClass("tab_error");
		
		this.createButton.addClass('down');
		this.createButton.unbind('click');
		
		var desc = $('textarea#description_text').val();
		if (desc) {data.description = desc};
		
		var scale = [];
		$('input.tab_key').each(function(index) {
			scale.push($(this).val());
		});
		data.scale = scale.join(" ");
		
		data.capo = $('input.capo_input').val();
		
		var postData = $(data).formatPost();
		
		// Save Tab to reference in Ajax
		var Create = this;
		$.ajax({
		  url: "?action=create",
		  type: "POST",
		  data: postData,
		  success : function(response) {
			Create.id = response;
			Create.addStep();
			
			// Add buttons
			Create.addButton.show();
			Create.saveButton.show();
		  },
		  error : function(error) {
			alert("Error: " + error);
		  }
		});
	},
	
	_Search_create : function() {
		var input = $('.Search.input', this.element).val();
		$('div.'+this.name+'_Search.box, span.Search.label').fadeOut('fast', function() {
			$('.tab_new_container').fadeIn('fast', function() {
				$('.tab_new_song').val(input);
				$('.tab_new_song').focus();
			});			
		});
		
	},
	
	_Search_select : function(tab) {
		this.artistID = tab.artist_id;
		this.songID = tab.song_id;
		$('.Search.input', this.element).val(tab.song + " - " + tab.artist).removeClass("tab_error");
		$('.tab_description_text').focus();
	},
	
	login : function() {
		window.location = "../Main/?view=login";
	},
	
	signup : function() {
		window.location = "../Main/?view=signup";
	},
}
