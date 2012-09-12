Strut = function(Guitar, options) {
	// Save Guitar
	this.Guitar = Guitar;
	
	// ID for saving/updating
	this.id = null;
	
	// Enabled by default
	this.enabled = 1;
	
	this.element = $('<input>').addClass("strut").attr({
		'type' : 'text',
		'maxlength' : 2
	});
	$(this.element).blur();

	if(options) {
		// Allows the struts to be replenished from a pool
		this.pool = options.pool || null;
		
		// Allows you to place a strut on click.
		if (options.position) {
			this.element.css("left", options.position.x + "px");
			this.element.css("top", options.position.y + "px");
			this.element.css("position", "absolute");
			this.element.focus();
		}
	}

	// Initialize Draggable w/ options
	$(this.element).draggable({
		cancel : null
	});
	
	// Defining the Events
	$(this.element).bind('dragstart', [this, 'dragstart']);
	$(this.element).bind('dragstop', [this, 'dragstop']);
	$(this.element).bind('click', [this, 'click']);
	
	
	// If dropped right away, we want it to disappear.
	this.box = Guitar.strutbox;
	this.limbo = $('.guitar_strut_limbo_box', Guitar.toolbox);
	this.dropbox = $('.guitar_strut_drop_box', Guitar.toolbox);
	
	this.inLimbo = true;
}

Strut.prototype = {
	dragstart : function(e, ui) {
		//console.log("Started Dragging..");
		var Guitar = this.Guitar;
		
		// If there is a strut in limbo - remove it.
		this.limbo.empty();
		
		// Initialize
		Guitar.current = this;
		this.inLimbo = true;
		$(this.element).appendTo(this.limbo);
		
		if (this.pool) {
			// Create a new one to fill up pool
			var strut = new Strut(this.Guitar, {pool:true});
			this.box.append(strut.element);
			
			// This element that you're dragging will no longer be a pool of struts
			this.pool = false;
		}
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
			this.Guitar.Tab.saveButton.removeClass('down');
			
			$(this.element).css("position", "absolute").focus();
			
			// If object is not currently present on the board - then add it to board.
			if(!this.Guitar.Doodads.contains(this))
				this.Guitar.Doodads.push(this);
		}
	},
	
	click : function() {
		this.Guitar.Tab.saveButton.removeClass('down');
		$(this.element).focus();
	},

	setGrid : function() {
		$(this.element).draggable('option', 'grid', [15, 30]);
	},
	
	removeGrid : function() {
		$(this.element).draggable('option', 'grid', false);
	},
	
	position : function() {
		// Get absolute position relative to parent
		var position = $(this.element).position();

		// Normalize Position
			// Top [1st String : 0]
			position['top'] = Math.floor(position['top']/30)-1;
			// Left [1st Column : 0]
			position['left'] = Math.floor((position['left']-23)/15);
			// Span [default : 2] - Probably not needed.
			//position['span'] = 2;
		
		return position;
	},
	
	serialize : function() {
		// Get position information
		var output = this.position();
		// Get input information
		output.input = this.element.val();
		output.type = "Strut";
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
		return "Strut Class";
	}

}
