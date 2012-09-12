Guitar = function(Tab) {
	// Save Tab
	this.Tab = Tab;
	
	// Guitar element
	this.template = $("<div/>").addClass("template");
	this.element = this.template.append(Tab.guitar_tpl);
	
	
	// Initialize
	this.Doodads = [];
	this.id = null;
	this.current = null;
	
	// 3 Possible states a Doodad may be in..
	this.toolbox = $(this.template).find('.guitar_tool_box');
		this.strutbox = this.toolbox.find('.guitar_strut_tool_box');
		this.stretchablebox = this.toolbox.find('.guitar_stretchable_tool_box');
	this.dropbox = $(this.template).find('.guitar_drop_area');
	this.limbo = $(this.template).find('.guitar_limbo');
	
	// this.removeButton = $(this.template).find('.remove_button');
	
	this.createToolBox();

	// Initialize Droppable
	$(this.dropbox).droppable({});

	// Set up events
	// $(this.removeButton).bind('click', [this, 'remove']);
	$(this.dropbox).bind('dropover', [this, 'dropover']);
	$(this.dropbox).bind('dropout', [this, 'dropout']);
	
	 $(this.dropbox).bind('click', [this, 'allowClickableStruts']);

	this.enableInlineEditing();
	
	return this;
}

Guitar.prototype = {

	createToolBox : function() {
		
		// Create Strut
		var strut = new Strut(this, {
			pool : true
		});
		// Add element to toolbox
		$(this.strutbox).append(strut.element);		
		
		// Create Stretchable
		var stretchable = new Stretchable(this, {
			pool : true
		});
		// Add element to toolbox
		$(stretchable.element).corners("15px");
		$(this.stretchablebox).append(stretchable.element);
	},
	
	dropover : function(e, ui) {
		this.current.inLimbo = false;
		this.current.setGrid();
		
		$(this.current.element).appendTo(this.current.dropbox);
		
	},
	
	dropout : function(e, ui) {
		this.current.inLimbo = true;
		this.current.removeGrid();
		
		$(this.current.element).appendTo(this.current.limbo);
		
	},
	
	
	allowClickableStruts : function(e) {
		this.Tab.saveButton.removeClass("down");
		
		// Make struts droppable from anywhere.
			var x = e.pageX ? e.pageX : e.clientX + document.body.scrollLeft;
			var y = e.pageY ? e.pageY : e.clientY + document.body.scrollTop;
			var o = $(this.dropbox).offset();
			
			var x = x - o.left;
			var y = y - o.top;
			
			var yo = 30+(Math.floor((y/30))*30);
			var xo = (Math.floor((x/30))*30);
			var strut = new Strut(this, {position: {y: yo, x: xo}});
			
			// Add to board
			$(this.strutbox).append(strut.element);
			this.Doodads.push(strut);
			$(strut.element).focus();
	},

	enableInlineEditing : function() {
		
		// Temporary - save reference to Guitar Object
		var Guitar = this;
		
		$('span.header-title', this.template).click(function() {
			Guitar.Tab.saveButton.removeClass("down");
			
			$(this).hide();
			// Context span.header-title : next - input
			$(this).next().show().focus();
		});
		
		$('input.header-input', this.template).blur(function() {
			$(this).hide();
			
			// Context input.header-input : prev - span
			$(this).prev().show().text($(this).val());
			
		});
	},
	
	addtoToolBox : function (el) {
		$(this.toolbox).append(el);
	},

	remove : function() {
		if(!this.id) {
			$(this.template).hide('blind', 'slow');
			return;
		} 
		
		var Guitar = this;
		$.ajax({
			url: "?action=remove",
			type: "POST",
			data: {id : this.id},
		
	  		success: function(response) {
			    //called when successful
				if(Guitar.Tab.Guitars.contains(Guitar))
					Guitar.Tab.Guitars.remove(this);
				
				Guitar.template.hide('blind', 'slow');
	 		},
		
	  		error: function(error) {
			    //called when there is an error
				alert(error);
	  		}
		});
		
	},

	serialize : function() {
		var output = {};
		output.board = [];

		$(this.Doodads).each(function(index){
			output.board.push(this.serialize());
		});
		
		output.title = $('span.header-title', this.template).text();
		if(output.title == "Add Tab:") {
			output.title = $('input.header-input', this.template).val();
			if (output.title == "")
				output.title = "Tab";
		}
		output.id = this.id;
		
		return output;
	},

	// No need for it so far - batch all the requests when you hit save. 
	// Might come in handy for saving individual 'drafts' at some point
	save : function() {},

	toString : function() {
		return 'Guitar Class';
	}
	
}