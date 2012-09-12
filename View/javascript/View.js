View = function() {
	
	// Determine the best width for the title maximum width is 316px
	this.fitTitle(280);
	this.initRatings();
	
	return this;
}

View.prototype = {
	
	fitTitle : function(maxWidth) {
		var textWidth = $('div#tab-title').width();
		var boxWidth = maxWidth;
		var currentFontSize = $('div#tab-title').css('font-size');
		currentFontSize = parseInt(currentFontSize.substring(0, currentFontSize.length - 2)); // get rid of px
		var newSize = Math.floor(boxWidth / (textWidth / currentFontSize));
		// Max out at 34px
		(currentFontSize < newSize) ? newSize = currentFontSize : newSize = newSize; 
		$('div#tab-title').css('font-size', newSize + 'px');
		// Fix margin to center - Found linear equation
		var newMargin = Math.round((-7/13)*newSize + 20.38);
		$('div#tab-title').css('margin-top', newMargin + 'px');
		$('div#tab-title').show()
	},
	
	initRatings : function(r) {
		this.currentWidth = 0;

		// Determine offset and find element beforehand
		o = $('div#rating-container').offset();

		// Set up rating system	
		$('div#rating-container').bind('mouseover', function(e) {
			$('div#current_rating, span#num_ratings').hide();
			$('div#my_rating').show();		
		});

		$('div#rating-container').bind('mouseout', function() {
			$('div#current_rating, span#num_ratings').show();
			$('div#current_rating').width(this.currentWidth);
			$('div#my_rating').hide();
		});

		$('div#rating-container').bind('mousemove', function(e) {
			var x = (e.pageX ? e.pageX : e.clientX + document.body.scrollLeft) - o.left;
			var w =  Math.round(24.4+Math.floor(x/24.4)*24.4);
			$('div#my_rating').width(w);
		});

		var View = this;
		$('div#rating-container').bind('click', function(e) {
			var x = (e.pageX ? e.pageX : e.clientX + document.body.scrollLeft) - o.left;
			var w =  Math.round(24.4+Math.floor(x/24.4)*24.4);
			// Normalize
			var n = Math.round(w/24.4);

			$.ajax({
				url: '?action=rate',
				type: 'POST',	
				data: {'rating' : n, 'id': View.id},

				success: function(rating) {
					View.setRating(rating);

					// Increment Rating
					var ratingText = $('span#num_ratings').text();
					var rating = parseInt(ratingText.split(" ")[0]) + 1;

					// Remove listeners
					$('div#rating-container').unbind("click");
					$('div#rating-container').unbind("mouseover");
					$('div#rating-container').unbind("mouseout");
					$('div#rating-container').unbind("mousemove");

					$('span#num_ratings').text("Thank you!");

					$('div#my_rating').fadeOut('slow');
					$('div#current_rating, span#num_ratings').fadeTo(750, 1).fadeIn('slow');

					$('span#num_ratings').fadeTo(2000, 1).fadeOut('slow', function() {
						$(this).text(rating + " ratings");
						$(this).fadeIn('slow');
					});



				},

				error: function(error) {
					alert(error);
				}
			});

		});
	},

	initAlternatives : function() {
		View = this;
		$('div.alternative a').removeAttr("href");
		
		$('div.alternative').bind('click', function() {
				var tab = $('div.tab');
				var menu = $('div#menu');
				var alternative = this;
				
				// Somewhat scrubby code.. oh well- it works.
				menu.fadeOut('slow', function() {
				
					tab.fadeOut('slow', function() {
									
						// Build ajax updater next to communicate with view controller.
						data = {};
						var count = $(alternative).text()-1;
						data.id = View.alternatives[count];
						data.alternatives = $.toJSON(View.alternatives);
						// data.song = View.song;
						// data.artist = View.artist;
			
						var postData = $(data).formatPost();
			
			
						$.ajax({
							url: '?action=alternative',
							type: 'POST',
							data: postData,

							success: function(response) {
								//// console.log(response);
								var pieces = response.split("<!--Split-->");
						
						
								menu.html(pieces[0]);
						
								tab.html(pieces[1]);
								menu.fadeIn('slow', function() {
									tab.fadeIn('slow');
								});
								View.id = data.id;
								View.initRatings();
								View.setRating(pieces[2]);
								View.initAlternatives();
								$('.stretchable').corners("15px");
							},

							error: function(error) {
								// console.log(error);
							}
						});
					});
				});
				
		});
	},
	
	setRating : function(r) {
		this.rating = (r) ? r : this.rating;
		// Denormalize
		var rating = Math.round(this.rating*24.4);
		
		$('div#current_rating').width(rating);
		this.currentWidth = rating;
	},
	
	login : function() {
		window.location = "../Main/?view=login";
	},
	
	signup : function() {
		window.location = "../Main/?view=signup";
	},
}