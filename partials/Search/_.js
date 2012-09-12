Search = function(caller, options) {
	this.o = (options) ? options : {};
	var Search = this;
	
	this.caller = caller;

	// console.log(this.caller);
	
	this.box = $("."+caller.name+"_Search.box");
	this.input = $(".Search.input", this.box);
	this.results = $(".Search.results", this.box);
	this.create = $(".Search.create", this.box);
	
	// Have icon? - Do Later
	// If clicked off input, hide
	this.input.blur(function(e) {
		$(Search.results).fadeOut("fast");
	});
	
	// Bind actions
	this.input.bind('keydown', function(e) {
		if(e.keyCode == 9) {
			e.preventDefault();
			Search.select();
		}
	});
	
	this.input.bind('keyup', [this, 'route']);
	
	// Make create button clickable
	$(this.create).click(function() {
		Search.select();
	});
	
	// Basic options
	this.o.min = this.o.min || 2;
	
	// Positioning our results
	var l = $(this.input).position().left + 'px';
	this.results.css({ left : l });	
	
	this.lastQ = "";
}

Search.prototype = {
	
	route : function(e) {
		
		var q = this.input.val();
		q.length
		if(q.length <= this.o.min) { this.results.hide(); return; }
		
		var key = e.keyCode;
		var inputElem = this.input.get(0);
		// Left/Right - do nothing
		if(key == 37 || key == 39) return;
		

		// Up
		if(key == 38) { 
			this.up();
			return;
		}
		// Down
		if(key == 40) {
			this.down();
			return;
		}
		
		// Escape
		if(key == 27) {
			$(this.results).fadeOut('fast');
			return;
		}
		
		
		// Return
		if(key == 13 || key == 9) { e.preventDefault();
		 this.select(); return; }
		
		if(q == this.lastQ) return;
		this.lastQ = q;
		
		this.search(q);
	},
	
	search : function(q) {
		// console.log(q);
		var Search = this;
		
		$.ajax({
			url: '?action=search',
			type: 'POST',
			data: {query : q},
			
			success: function(response) {
				var results = eval(response);
				$('div.Search.result').remove();
				Search.create.removeClass('hover');
				var firstResult = null;
		  		
				$.each(results, function(i, result) {
				  var elem = $("<div></div>").addClass('Search result').text(result.song + " - " + result.artist);
				  if(i==0){$(elem).addClass('hover'); firstResult = elem;}
				  $(elem).data("result", result);
				  $(Search.create).before(elem);
				  
				  $(elem).mouseover(function() {
						$(firstResult).removeClass('hover');
						$(this).addClass('hover');
				  });
				
		     	  $(elem).mouseout(function() {
						$(this).removeClass('hover');
						$(firstResult).addClass('hover');
				  });
				
				  $(elem).click(function() {
						Search.select();
				  });
				
				});
				
				var create = Search.create;
				
				if(!firstResult) create.addClass('hover');
				
				create.mouseover(function() {
					$(firstResult).removeClass('hover');
					$(this).addClass('hover');
				});
				create.mouseout(function() {
					$(firstResult).addClass('hover');
					$(this).removeClass('hover');
				});
				create.click(function() {
					//Tab.newSong($('input#input_song').val());
				});
				
				$(Search.results).show();
				
			},

			error: function(error) {
				alert(error);
			}
		});
		
		
	},
	
	select : function() {
		var elem = $('.Search.hover').data('result');
		$(this.results).fadeOut('fast');
		if(elem) {
			this.caller._Search_select(elem);
		}
		else
			this.caller._Search_create();
	},
	
	up : function() {
		var elem = $('.Search.hover');
		if(!$(elem).prev().get(0)) return;
		$(elem).removeClass('hover').prev().addClass('hover');
	},
	
	down : function() {
		var elem = $('.Search.hover');
		if(!$(elem).next().get(0)) return;
		$(elem).removeClass('hover').next().addClass('hover');
	}
	
}