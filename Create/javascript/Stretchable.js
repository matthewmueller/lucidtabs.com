Stretchable = function(Guitar, options) {
	// Save Guitar
	this.Guitar = Guitar;
	
	// ID for saving/updating
	this.id = null;

	// Enabled by default
	this.enabled = 1;

	this.element = $('<div></div>').addClass('stretchable');
	this.input = $('<input>').attr("type", "text").addClass("stretchable_input");

	$(this.element).append(this.input);

	
	$(this.input).hide();
	
	if (options) {
		this.pool = options.pool || null;
	}

	var Stretchable = this;
	
	// Initialize Stretchable w/ options
	$(this.element).resizable({
		handles : 'w, e',
		minWidth : 57,
		grid : [15, 0],
		start : function(e, ui) { Stretchable.input.blur(); },
		
		stop : function(e, ui) { 
			Stretchable.input.focus();
			Stretchable.Guitar.Tab.saveButton.removeClass("down");
		}
	});

	// Initialize Draggable as well w/ options
	$(this.element).draggable({
		cancel : null
	});
	
	// Defining the Events
	$(this.element).bind('dragstart', [this, 'dragstart']);
	$(this.element).bind('dragstop', [this, 'dragstop']);
	$(this.element).bind('click', [this, 'click']);
	
	// $(this.input).bind('keyup', [this, 'resizeInput'])
	
	// If dropped right away, we want it to disappear.
	this.box = Guitar.stretchablebox;
	this.limbo = $('.guitar_stretchable_limbo_box', Guitar.toolbox);
	this.dropbox = $('.guitar_stretchable_drop_box', Guitar.toolbox);
	
	this.inLimbo = true;
	
}

Stretchable.prototype = {
	
	dragstart : function(e, ui) {
		var Guitar = this.Guitar;
		
		// If there is a strut in limbo - remove it.
		this.limbo.empty();
		
		// Initialize
		Guitar.current = this;
		this.inLimbo = true;
		$(this.element).appendTo(this.limbo);
		
		if (this.pool) {
			// Create a new one to fill up pool
			var stretchable = new Stretchable(this.Guitar, {pool:true});
			$(stretchable.element).corners("15px");
			this.box.append(stretchable.element);
			
			// This element that you're dragging will no longer be a pool of struts
			this.pool = false;
		};
	},
	
	dragstop : function(e, ui) {
		// Nothing is being dragged right now.
		this.Guitar.current = null;
		
		if(this.inLimbo) {
			
			$(this.element).fadeOut("slow");
			
			if(this.Guitar.Doodads.contains(this))
				this.enabled = 0;
		}
		else {
			this.Guitar.Tab.saveButton.removeClass("down");
			
			$(this.element).css("position", "absolute");
			$(this.input).show().focus();
			
			// If object is not currently present on the board - then add it to board.
			if(!this.Guitar.Doodads.contains(this))
				this.Guitar.Doodads.push(this);
		}
	},
	
	click : function() {
		this.Guitar.Tab.saveButton.removeClass("down");
		$(this.input).show().focus();
	},
	
	// Doesn't work... Not important right now. // 
	//resizeInput : function() {
	// 	console.log(this.input.val().length * 9);
	// 	console.log(this.input.width());
	// 	if(this.input.val().length * 10 > this.input.width()) {
	// 		this.element.width(this.element.width()+15);
	// 		this.input.width(this.input.width()+15);
	// 	}
	// },

	setGrid : function() {
		$(this.element).draggable('option', 'grid', [15, 30]);
	},
	
	removeGrid : function() {
		$(this.element).draggable('option', 'grid', false);
	},
	
	position : function() {
		// Get absolute position relative to parent
		var position = $(this.element).position();
		//console.log(position);
		// Determine span (length) [default : 58]
		position['span'] = this.element.width() || 58;
		
		// Normalize Position
			// Top [1st String : 0]
			position['top'] = Math.floor(position['top']/30)-1;
			// Left [1st Column : 0]
			/*
				TODO Do not allow stretchable to go one block too far left or right. Fixed here though.
			*/
			position['left'] = (position['left'] > -45) ? position['left']+45 : 0;
			position['left'] /= 15;
			
			// Span - [default : 1]
			position['span'] = Math.floor((position['span']-58)/15)+4;
		
		return position;
	},
	
	serialize : function() {
		// Get position information
		var output = this.position();
		// Get input information
		output.input = this.input.val();
		output.type = "Stretchable";
		output.id = this.id;
		
		// Add to output if its disabled.
		if(!this.enabled) {
			output.enabled = 0;
			
			// Resetting positioning *OPTIONAL*
			output.left = -1;
			output.top = -1;
		}
		
		// Convert to JSON and return.
		return $.toJSON(output);
	},
	
	toString : function() {
		return "Stretchable Class";
	}
}