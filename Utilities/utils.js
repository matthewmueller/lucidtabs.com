$.fn.update = function(newView, options) {
	var oldView = $(this);
	oldView.fadeOut("slow", function() {
		$.ajax({
			url: '?view='+newView,
			type: 'POST',

			success: function() {
				//called when successful
			},

			error: function() {
				//called when there is an error
			}
		});
		
	});
}


// Contains - which checks where the specified element or object exists in the array or not.

Array.prototype.contains = function (obj){
	for (var i = 0; i < this.length; i++) {
	    if (this[i] == obj){
	            return true;
	    }
	}
	return false;
};

// Remove - which will remove a given object from the array.

Array.prototype.remove = function (obj){
	for (var i = 0; i < this.length; i++) 
		if(this[i] == obj) this.splice(i, 1);
};

// Since jQuery jacked Object.prototype we'll extend jQuery
jQuery.fn.extend ({
	// formatPost - Takes an associative array (object) and turns it into string that can be send to the server
	formatPost: function() {
		// Original Object
		var obj = this.get(0);
		
		var serials = [];
		for (key in obj) {
			var component = key + "=" + obj[key];
			serials.push(component);
		}
		return serials.join("&");
	}
});


// Inject - Puts a string within a string.
// Optional Param - length, allowing for string replacement.

String.prototype.inject = function(str, start, length) {
	var beg = this.substring(0, start); var end = (length) ? this.substring(start+length) : this.substring(start);
	return beg+str+end;
}

// Clean - Removes all instances of each substring from string
// elems - An array of substrings. 

String.prototype.clean = function(elems) {
	var output = this;
	for (var i=0; i < elems.length; i++) {
		var stop = false;
		
		for (var j=0; (j < output.length) && !stop; j++) {
			var index = output.indexOf(elems[i]);
			if (index >= 0)
				output = output.substring(0, index) + output.substring(index+elems[i].length);
			else
				stop = true;
		}
	}
	return output;
}

Math.uuid = (function() {
  // Private array of chars to use
  var CHARS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split(''); 

  return function (len, radix) {
    var chars = CHARS, uuid = [], rnd = Math.random;
    radix = radix || chars.length;

    if (len) {
      // Compact form
      for (var i = 0; i < len; i++) uuid[i] = chars[0 | rnd()*radix];
    } else {
      // rfc4122, version 4 form
      var r;

      // rfc4122 requires these characters
      uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
      uuid[14] = '4';

      // Fill in random data.  At i==19 set the high bits of clock sequence as
      // per rfc4122, sec. 4.1.5
      for (var i = 0; i < 36; i++) {
        if (!uuid[i]) {
          r = 0 | rnd()*16;
          uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r & 0xf];
        }
      }
    }

    return uuid.join('');
  };
})();

/* jQuery Delay - A delay function * Copyright (c) 2009 Clint Helfers - chelfers(at)gmail(dot)com | http://blindsignals.com * Dual licensed under MIT and GPL. * Date: 7/01/2009 * @author Clint Helfers * @version 1.0.0 * http://blindsignals.com/index.php/2009/07/jquery-delay/ */

$.fn.delay=function(t,n){return this.queue((n||"fx"),function(){var s=this;setTimeout(function(){$.dequeue(s);},t);})};

jQuery.fn.dump = function(showTypes, showAttributes) {
	jQuery.dump($(this), showTypes, showAttributes);
	return this;
};

jQuery.dump = function(object, showTypes, showAttributes) {
  var dump = '';
  var st = typeof showTypes == 'undefined' ? true : showTypes;
  var sa = typeof showAttributes == 'undefined' ? true : showAttributes;  
  var winName = 'dumpWin';
  var w = 760;
  var h = 500;
  var leftPos = screen.width ? (screen.width - w) / 2 : 0;
  var topPos = screen.height ? (screen.height - h) / 2 : 0;
  var settings = 'height=' + h + ',width=' + w + ',top=' + topPos + ',left=' + leftPos + ',scrollbars=yes,menubar=yes,status=yes,resizable=yes';
  var title = 'Dump';
  var script = 'function tRow(s) {t = s.parentNode.lastChild;tTarget(t, tSource(s)) ;}function tTable(s) {var switchToState = tSource(s) ;var table = s.parentNode.parentNode;for (var i = 1; i < table.childNodes.length; i++) {t = table.childNodes[i] ;if (t.style) {tTarget(t, switchToState);}}}function tSource(s) {if (s.style.fontStyle == "italic" || s.style.fontStyle == null) {s.style.fontStyle = "normal";s.title = "click to collapse";return "open";} else {s.style.fontStyle = "italic";s.title = "click to expand";return "closed" ;}}function tTarget (t, switchToState) {if (switchToState == "open") {t.style.display = "";} else {t.style.display = "none";}}';	
  
 var _recurse = function (o, type) {
    var i;
	var j = 0;
	var r = '';
	type = _dumpType(o);
	switch (type) {		
	  case 'regexp':
	    var t = type;
	    r += '<table' + _dumpStyles(t,'table') + '><tr><th colspan="2"' + _dumpStyles(t,'th') + '>' + t + '</th></tr>';
	    r += '<tr><td colspan="2"' + _dumpStyles(t,'td-value') + '><table' + _dumpStyles('arguments','table') + '><tr><td' + _dumpStyles('arguments','td-key') + '><i>RegExp: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o + '</td></tr></table>';  
	    j++;
	    break;
	  case 'date':
	    var t = type;
	    r += '<table' + _dumpStyles(t,'table') + '><tr><th colspan="2"' + _dumpStyles(t,'th') + '>' + t + '</th></tr>';
	    r += '<tr><td colspan="2"' + _dumpStyles(t,'td-value') + '><table' + _dumpStyles('arguments','table') + '><tr><td' + _dumpStyles('arguments','td-key') + '><i>Date: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o + '</td></tr></table>';  
	    j++;
	    break;
	  case 'function':
	    var t = type;
	    var a = o.toString().match(/^.*function.*?\((.*?)\)/im); 
	    var args = (a == null || typeof a[1] == 'undefined' || a[1] == '') ? 'none' : a[1];
	    r += '<table' + _dumpStyles(t,'table') + '><tr><th colspan="2"' + _dumpStyles(t,'th') + '>' + t + '</th></tr>';
	    r += '<tr><td colspan="2"' + _dumpStyles(t,'td-value') + '><table' + _dumpStyles('arguments','table') + '><tr><td' + _dumpStyles('arguments','td-key') + '><i>Arguments: </i></td><td' + _dumpStyles(type,'td-value') + '>' + args + '</td></tr><tr><td' + _dumpStyles('arguments','td-key') + '><i>Function: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o + '</td></tr></table>';  
	    j++;
	    break;
	  case 'domelement':
	    var t = type;
		var attr = '';
		if (sa) {
		  for (i in o) {if (!/innerHTML|outerHTML/i.test(i)) {attr += i + ': ' + o[i] + '<br />';}}
		}
	    r += '<table' + _dumpStyles(t,'table') + '><tr><th colspan="2"' + _dumpStyles(t,'th') + '>' + t + '</th></tr>';
	    r += '<tr><td' + _dumpStyles(t,'td-key') + '><i>Node Name: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o.nodeName.toLowerCase() + '</td></tr>';  
		r += '<tr><td' + _dumpStyles(t,'td-key') + '><i>Node Type: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o.nodeType + '</td></tr>'; 
		r += '<tr><td' + _dumpStyles(t,'td-key') + '><i>Node Value: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o.nodeValue + '</td></tr>';
		if (sa) {
		  r += '<tr><td' + _dumpStyles(t,'td-key') + '><i>Attributes: </i></td><td' + _dumpStyles(type,'td-value') + '>' + attr + '</td></tr>';  		
		  r += '<tr><td' + _dumpStyles(t,'td-key') + '><i>innerHTML: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o.innerHTML + '</td></tr>'; 
		  if (typeof o.outerHTML != 'undefined') {
		    r += '<tr><td' + _dumpStyles(t,'td-key') + '><i>outerHTML: </i></td><td' + _dumpStyles(type,'td-value') + '>' + o.outerHTML + '</td></tr>'; 
		  }
		}
	    j++;
	    break;		
	}
	if (/object|array/.test(type)) {
      for (i in o) {
	    var t = _dumpType(o[i]);
	    if (j < 1) {
	      r += '<table' + _dumpStyles(type,'table') + '><tr><th colspan="2"' + _dumpStyles(type,'th') + '>' + type + '</th></tr>';
		  j++;	  
	    }
	    if (typeof o[i] == 'object' && o[i] != null) { 
		  r += '<tr><td' + _dumpStyles(type,'td-key') + '>' + i + (st ? ' [' + t + ']' : '') + '</td><td' + _dumpStyles(type,'td-value') + '>' + _recurse(o[i], t) + '</td></tr>';	
	    } else if (typeof o[i] == 'function') {
		  r += '<tr><td' + _dumpStyles(type ,'td-key') + '>' + i + (st ? ' [' + t + ']' : '') + '</td><td' + _dumpStyles(type,'td-value') + '>' + _recurse(o[i], t) + '</td></tr>';  	
		} else {
		  r += '<tr><td' + _dumpStyles(type,'td-key') + '>' + i + (st ? ' [' + t + ']' : '') + '</td><td' + _dumpStyles(type,'td-value') + '>' + o[i] + '</td></tr>';  
	    }
	  }
	}
	if (j == 0) {
	  r += '<table' + _dumpStyles(type,'table') + '><tr><th colspan="2"' + _dumpStyles(type,'th') + '>' + type + ' [empty]</th></tr>'; 	
	}
	r += '</table>';
	return r;
  };	
  var _dumpStyles = function(type, use) {
  var r = '';
  var table = 'font-size:xx-small;font-family:verdana,arial,helvetica,sans-serif;cell-spacing:2px;';
  var th = 'font-size:xx-small;font-family:verdana,arial,helvetica,sans-serif;text-align:left;color: white;padding: 5px;vertical-align :top;cursor:hand;cursor:pointer;';
  var td = 'font-size:xx-small;font-family:verdana,arial,helvetica,sans-serif;vertical-align:top;padding:3px;';
  var thScript = 'onClick="tTable(this);" title="click to collapse"';
  var tdScript = 'onClick="tRow(this);" title="click to collapse"';
  switch (type) {
	case 'string':
	case 'number':
	case 'boolean':
	case 'undefined':
	case 'object':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#0000cc;"';
		  break;
		case 'th':
		  r = ' style="' + th + 'background-color:#4444cc;"' + thScript;
		  break;
		case 'td-key':
		  r = ' style="' + td + 'background-color:#ccddff;cursor:hand;cursor:pointer;"' + tdScript;
		  break;
		case 'td-value':
		  r = ' style="' + td + 'background-color:#fff;"';
		  break;
	  }
	  break;
	case 'array':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#006600;"';
		  break;
		case 'th':
		  r = ' style="' + th + 'background-color:#009900;"' + thScript;
		  break;
		case 'td-key':
		  r = ' style="' + td + 'background-color:#ccffcc;cursor:hand;cursor:pointer;"' + tdScript;
		  break;
		case 'td-value':
		  r = ' style="' + td + 'background-color:#fff;"';
		  break;
	  }	
	  break;
	case 'function':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#aa4400;"';
		  break;
		case 'th':
		  r = ' style="' + th + 'background-color:#cc6600;"' + thScript;
		  break;
		case 'td-key':
		  r = ' style="' + td + 'background-color:#fff;cursor:hand;cursor:pointer;"' + tdScript;
		  break;
		case 'td-value':
		  r = ' style="' + td + 'background-color:#fff;"';
		  break;
	  }	
	  break;
	case 'arguments':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#dddddd;cell-spacing:3;"';
		  break;
		case 'td-key':
		  r = ' style="' + th + 'background-color:#eeeeee;color:#000000;cursor:hand;cursor:pointer;"' + tdScript;
		  break;	  
	  }	
	  break;
	case 'regexp':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#CC0000;cell-spacing:3;"';
		  break;
		case 'th':
		  r = ' style="' + th + 'background-color:#FF0000;"' + thScript;
		  break;
		case 'td-key':
		  r = ' style="' + th + 'background-color:#FF5757;color:#000000;cursor:hand;cursor:pointer;"' + tdScript;
		  break;
		case 'td-value':
		  r = ' style="' + td + 'background-color:#fff;"';
		  break;		  
	  }	
	  break;
	case 'date':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#663399;cell-spacing:3;"';
		  break;
		case 'th':
		  r = ' style="' + th + 'background-color:#9966CC;"' + thScript;
		  break;
		case 'td-key':
		  r = ' style="' + th + 'background-color:#B266FF;color:#000000;cursor:hand;cursor:pointer;"' + tdScript;
		  break;
		case 'td-value':
		  r = ' style="' + td + 'background-color:#fff;"';
		  break;		  
	  }	
	  break;
	case 'domelement':
	case 'document':
	case 'window':
	  switch (use) {
		case 'table':  
		  r = ' style="' + table + 'background-color:#FFCC33;cell-spacing:3;"';
		  break;
		case 'th':
		  r = ' style="' + th + 'background-color:#FFD966;"' + thScript;
		  break;
		case 'td-key':
		  r = ' style="' + th + 'background-color:#FFF2CC;color:#000000;cursor:hand;cursor:pointer;"' + tdScript;
		  break;
		case 'td-value':
		  r = ' style="' + td + 'background-color:#fff;"';
		  break;		  
	  }	
	  break;	  
  }
  return r;
  };
  var _dumpType = function (obj) {
    var t = typeof(obj);
    if (t == 'function') {
      var f = obj.toString();
      if ( ( /^\/.*\/[gi]??[gi]??$/ ).test(f)) {
        return 'regexp';
      } else if ((/^\[object.*\]$/i ).test(f)) {
        t = 'object'
      }
    }
    if (t != 'object') {
      return t;
    }
    switch (obj) {
      case null:
        return 'null';
      case window:
        return 'window';
	  case document:
	    return 'document';
      case window.event:
        return 'event';
    }
    if (window.event && (event.type == obj.type)) {
      return 'event';
    }
    var c = obj.constructor;
    if (c != null) {
      switch(c) {
        case Array:
          t = 'array';
          break;
        case Date:
          return 'date';
        case RegExp:
          return 'regexp';
        case Object:
          t = 'object';	
        break;
        case ReferenceError:
          return 'error';
        default:
          var sc = c.toString();
          var m = sc.match(/\s*function (.*)\(/);
          if (m != null) {
            return 'object';
          }
      }
    }
    var nt = obj.nodeType;
    if (nt != null) {
      switch(nt) {
        case 1:
          return 'domelement';
        case 3:
          return 'string';
      }
    }
    if (obj.toString != null) {
      var ex = obj.toString();
      var am = ex.match(/^\[object (.*)\]$/i);
      if (am != null) {
        var am = am[1];
        switch(am.toLowerCase()) {
          case 'event':
            return 'event';
          case 'nodelist':
          case 'htmlcollection':
          case 'elementarray':
            return 'array';
          case 'htmldocument':
            return 'htmldocument';
        }
      }
    }
    return t;
  };  
  dump += (/string|number|undefined|boolean/.test(typeof(object)) || object == null) ? object : _recurse(object, typeof object);
  winName = window.open('', '', settings);
  if (jQuery.browser.msie || jQuery.browser.browser == 'opera' || jQuery.browser.browser == 'safari') {
	winName.document.write('<html><head><title> ' + title + ' </title><script type="text/javascript">' + script + '</script><head>');
	winName.document.write('<body>' + dump + '</body></html>');
  } else {
	winName.document.body.innerHTML = dump;
	winName.document.title = title;
	var ffs = winName.document.createElement('script');
	ffs.setAttribute('type', 'text/javascript');
	ffs.appendChild(document.createTextNode(script));
	winName.document.getElementsByTagName('head')[0].appendChild(ffs);
  }
  winName.focus();  
};

