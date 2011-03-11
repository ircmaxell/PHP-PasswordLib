Array.prototype.indexOf = IndexOf;
//Finds the index of an item in an array
function IndexOf(item) {
	for (var i=0; i < this.length; i++) {        
		if (this[i] == item) {
			return i;
		}
	}
	return -1;
}

var toggler = {
	//CSS class names
	states: Array('Collapsed','Expanded'),
	//Caption
	statesLib: Array('Collapse', 'Expand'),
	//Current state
	curState: 0,
	//Name of the cookie that stores the current state between pages
	cookieName: 'apiTreeviewState',
	//Sets all the elements to a new state, and updates the current state variable
	toggleAll: function(treeId, btnId)
	{
		this.curState = 1-this.curState;
		this.toggleAllTree(treeId, this.curState)
		var btn = document.getElementById(btnId);
		btn.innerHTML = this.statesLib[1-this.curState]+' all';
		setCookie(this.cookieName, this.curState);
	},
	//Sets all the elements to a given state
	toggleAllTree: function(treeId, stateId)
	{
		var tree = document.getElementById(treeId);
		if(!tree) return;
		var treeElements = tree.getElementsByTagName('li');
		for (var i=0; i<treeElements.length; i++) {
			this.replaceInClass(treeElements[i], this.states[stateId], this.states[1-stateId]);
		}
	},
	//Sets the element to the firstClass given, in place of the second
	replaceInClass: function(element, firstClass, secondClass)
	{
		var classes = element.className.split(" ");
		var firstClassIndex = classes.indexOf(firstClass);
		var secondClassIndex = classes.indexOf(secondClass);

		if (secondClassIndex>-1) {
			classes[secondClassIndex] = firstClass;
		}

		element.className = classes.join(" ");
	},
	//Toggles between two classes
	toggleClass: function(element, firstClass, secondClass, event)
	{
		event.cancelBubble = true;

		var classes = element.className.split(" ");
		var firstClassIndex = classes.indexOf(firstClass);
		var secondClassIndex = classes.indexOf(secondClass);
		
		if (firstClassIndex == -1 && secondClassIndex == -1) {
			classes[classes.length] = firstClass;
		}
		else if (firstClassIndex != -1) {
			classes[firstClassIndex] = secondClass;
		}
		else {
			classes[secondClassIndex] = firstClass;
		}
		element.className = classes.join(" ");
	},
	
	//Toggle event handler for each expandable/collapsable node
	toggleNodeStateHandler: function(event)
	{
		toggler.toggleClass(this, toggler.states[0], toggler.states[1], (event==null) ? window.event : event);
	},
	
	//Prevents the onclick event from bubbling up
	preventBubbleHandler: function(event)
	{
		if (!event)
			event = window.event;
		event.cancelBubble = true;
	},
	
	//Adds the relevant onclick handlers for the nodes in the tree view
	setupTreeView: function(treeId)
	{
		var tree = document.getElementById(treeId);
		if(!tree) return;
		var treeElements = tree.getElementsByTagName("li");
	
		for (var i=0; i<treeElements.length; i++) {
			if (treeElements[i].getElementsByTagName("ul").length>0) {
				treeElements[i].onclick = toggler.toggleNodeStateHandler; 
			}
			else {
				treeElements[i].onclick = toggler.preventBubbleHandler; 
			}
		}

		var h = window.location.hash;
		if(h!='') {
			var s = document.getElementById(h.substring(1));
			if(s) {
				this.replaceInClass(s, this.states[1], this.states[0]);
			}
		}
	},
	backToMemorizedState: function(treeId, btnId)
	{
		var x = getCookie(this.cookieName);
		if (x==0 || x==1) {
			this.curState = x;
			var btn = document.getElementById(btnId);
			btn.innerHTML = this.statesLib[1-this.curState]+' all';
			this.toggleAllTree(treeId, this.curState);
		}
	}
}

function setCookie(name, value, expires, path, domain, secure)
{
	var today = new Date();
	today.setTime( today.getTime() );
	if (expires) {
		expires = expires*1000*60*60*24;
	}
	var expires_date = new Date(today.getTime() + (expires) );
	document.cookie = name + "=" +escape(value) +
	( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
	( ( path ) ? ";path=" + path : "" ) +
	( ( domain ) ? ";domain=" + domain : "" ) +
	( ( secure ) ? ";secure" : "" );
}


function getCookie(check_name)
{
	var a_all_cookies = document.cookie.split(';');
	var a_temp_cookie = '';
	var cookie_name = '';
	var cookie_value = '';
	var b_cookie_found = false;

	for (i=0; i<a_all_cookies.length; i++) {
		a_temp_cookie = a_all_cookies[i].split( '=' );
		cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');
		if (cookie_name == check_name){
			b_cookie_found = true;
			if (a_temp_cookie.length>1) {
				cookie_value = unescape( a_temp_cookie[1].replace(/^\s+|\s+$/g, '') );
			}
			return cookie_value;
			break;
		}
		a_temp_cookie = null;
		cookie_name = '';
	}
	if (!b_cookie_found) {
		return null;
	}
}