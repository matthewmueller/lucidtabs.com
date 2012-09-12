/**
 * BindO for jQuery
 * Adds support for binding Objects to jQuery events
 * Written by Raymond Irving - April 2, 2008
 *
 * Visit http://xwisdomhtml.com/bindo.html
 *
 * Version 1.0.3    - return callback value -  submitted by Morten Fangel
 * Version 1.0.2    - added support for extra arguments by Jonathan Vitela
 * Version 1.0.1    - added chain support as suggested by Thomas Carcaud
 *
 */


$.fn.__bindo = $.fn.bind;   // old jQuery bind function
$.fn.bind = function(evt,data,fn){
    var cb,nodata = false;
    if (typeof fn!='undefined') cb = fn;
    else {nodata = true; cb = fn = data;}
    if (typeof cb == 'object') fn = function(event,data){
        event.args = cb[2]; // extra argument
        return cb[0][cb[1]](event,data);
    }
    if (nodata) return this.__bindo(evt,fn);
    else return this.__bindo(evt,data,fn);
}