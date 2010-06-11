
$(document).ready ( function(){
  bgswitch();updatewidget();showhideadvanced();
}); 

/**
 *These two functions are used to strip the "px" from given widths to
 *generate iframe widths
 */
function strip(val) {
  var chars = val.split("");
  var out = "";
  for (var i = 0; i < chars.length; i++) {
	  thing = chars[i];
	  if (isdigit(thing)) {
		  out += thing;
	  }
  }
  return out;
}

function isdigit(val) {
  var string="1234567890.";
  if (string.indexOf(val) != -1){
	  return true;
  }
  return false;
}

/**
 *Cycles through the lang check boxes and generates a comma deleminated string
 */
function getdbs() {
  dbs = {};
  var fields = document.getElementsByName('dbs');
  for (i = 0; i < fields.length; i++) {
	if (fields[i].checked == true) {
		dbs[fields[i].id] =
			document.getElementById('label_'+fields[i].id).innerHTML;
	}
  }
  return dbs;
}
/**
 *Called when show/hide advanced options is clicked.
 *Sets height/width to 0 or auto and sets all advanced options visibility
 *attributes to hidden or visible respectively
 */
function showhideadvanced() {
  if (document.getElementById('advancedoptions').style.visibility == "visible"
	  || document.getElementById('advancedoptions').style.visibility == "") {
	document.getElementById('advancedoptions').style.height = "0px";
	document.getElementById('advancedoptions').style.width = "0px";
	document.getElementById('advancedoptions').style.visibility = "hidden";
	document.getElementById('backgroundlink').style.visibility = "hidden";
	document.getElementById('backgroundcolor').style.visibility = "hidden";
  }
  else {
	document.getElementById('advancedoptions').style.height = "auto";
	document.getElementById('advancedoptions').style.width = "auto";
	document.getElementById('advancedoptions').style.visibility = "visible";
	if (document.getElementsByName('bgstuff')[0].checked == true) {
	  document.getElementById('backgroundlink').style.visibility = "visible";
	  document.getElementById('backgroundcolor').style.visibility = "hidden";
	}
	else {
	  document.getElementById('backgroundlink').style.visibility = "hidden";
	  document.getElementById('backgroundcolor').style.visibility = "visible";
	}
  }
}

/**
 *Called whenever a form element changes, takes all the values from the form,
 *composes them into the iframe string , and puts them into the copy box
 */
function updatewidget() {
  var width = document.getElementById('width').value;
  var height = document.getElementById('height').value;
  var language = document.getElementById('il').value;
  var textcolor = document.getElementById('textcolor').value;
  var linkcolor = document.getElementById('linkcolor').value;
  var imageon = document.getElementById('backgroundlink').style.visibility;
  var coloron = document.getElementById('backgroundcolor').style.visibility;
  var backgroundlink = '';
  var backgroundcolor = '';
  var borderstyle = document.getElementById('borderstyle').options[document.getElementById('borderstyle').selectedIndex].value;
  var borderwidth = document.getElementById('borderwidth').value;
  var bordercolor = document.getElementById('bordercolor').value;
  var padding = document.getElementById('padding').value;
  var dbs = getdbs();
  var dbselect = "<select name='db'>";
  var linkselect = "<select name='link'>";
  for(k in dbs ){
    dbselect+= "<option value='"+k+"'>"+dbs[k]+'</option>';
    linkselect+= "<option value='"+k+"'>"+dbs[k]+'</option>';
  }
  dbselect+= '</select>';
  linkselect+= '</select>';
  
  var key = document.getElementById('key').value;

  if (document.getElementsByName('bgstuff')[0].checked == false) {
      backgroundlink = '';
      backgroundcolor = document.getElementById('backgroundcolor').value;
      document.getElementById('wijitidemo').style.backgroundColor = backgroundcolor;
      document.getElementById('wijitidemo').style.backgroundImage = 'url()';
  }
  else {
      backgroundlink = document.getElementById('backgroundlink').value;
      backgroundcolor = '';
      document.getElementById('wijitidemo').style.backgroundImage = 'url('+backgroundlink+')';
  }
	
  document.getElementById('wijitidemo').style.width = width;
  document.getElementById('wijitidemo').style.height = height;
  document.getElementById('wijitidemo').style.backgroundImage = 'url('+backgroundlink+')';
  document.getElementById('wijitidemo').style.border = borderwidth+" "+borderstyle+" "+bordercolor;
  document.getElementById('word').style.width = width;
  document.getElementById('fromto').style.width = width;
  document.getElementById('fromto').innerHTML = ' in ' + dbselect +
												' limit to' + linkselect +
												'<input type="button" value="Search"/>';
  document.getElementById('fromto').style.color = textcolor;
  document.getElementById('link').style.color = linkcolor;
  document.getElementById('results').style.color = textcolor;

  //iframe string is constructed, using the smallest named variables possible that still hold meaning
  var iframeheight = parseInt(strip(padding)) * 2 + parseInt(strip(borderwidth)) * 2 + parseInt(strip(height));
  
  var out = document.getElementById('output');
  if (backgroundcolor == "") {
    var options = "&"+escape("w="+width+"&h="+height+"&tc="+textcolor+"&lc="+linkcolor+"&bi="+backgroundlink+"&bw="+borderwidth+"&bs="+borderstyle+"&boc="+bordercolor+"&p="+padding+"&l="+"&d="+key);
  }
  else {
    var options = "&"+escape("w="+width+"&h="+height+"&tc="+textcolor+"&lc="+linkcolor+"&bc="+backgroundcolor+"&bw="+borderwidth+"&bs="+borderstyle+"&boc="+bordercolor+"&p="+padding+"&l="+"&d="+key);
  }
  
  while (options.search("%3D") != -1) {
    options = options.replace("%3D","=");
  }
  
  while (options.search("%26") != -1) {
    options = options.replace("%26","&");
  }
  
  while (options.search("%2C") != -1) {
    options = options.replace("%2C",",");
  }

  if (options.length - 1 > 255) {
    document.getElementById('spaceused').style.color = "#FF0000"
  }
  else {
    document.getElementById('spaceused').style.color = "#000000"
  }
  document.getElementById('spaceused').innerHTML = (options.length-1) + " of 255";
  var domain = window.location.protocol+'//'+window.location.host+'/'; 
  var outstring = '<scr'+'ipt type="text/javascript">'+
      'function swap(){' +
      'var db = document.getElementsByName("link")[0].selectedIndex;' +
      'var link = document.getElementsByName("db")[0].selectedIndex;' +
      'document.getElementsByName("db")[0].selectedIndex = db;' +
      'document.getElementsByName("link")[0].selectedIndex = link;' +
      'return false;}' +
      'function gup(n){' +
      'n = n.replace(/[\\[]/,"\\\\\\[").replace(/[\\]]/,"\\\\\\\]");' +
      'var r = "[\\?&]"+n+"=([^&#]*)";' +
      'var x = new RegExp(r);' +
      'var s = x.exec( window.location.href );' +
      'if( s == null )'+
      'return "";'+
      'else '+
      'return unescape(s[1].replace(/\\+/g,  " "));'+
      '}'+
      'function ss(n,v){o=document.getElementsByName(n)[0];for(i=0;i<o.length;i++){if(o[i].value==v)o.selectedIndex=i;}}\n'+
      'kclient_key = "'+key+'";\n'+
      'kclient_lim = 5;\n'+
      'kclient_db = gup("db");\n'+
      'kclient_link = gup("link");\n'+
      'kclient_dom = "'+domain+'";\n'+
      'kclient_query = gup("kcq");\n'+
	  'kclient_height ="'+height+'";\n'+
	  'kclient_width ="'+width+'";\n'+
	  'kclient_il ="'+language+'";\n'+
      '</scr'+'ipt>';
  outstring+= '<scr'+'ipt type="text/javascript" src="'+domain+'get_links.js"></scr'+'ipt>';
  outstring+= '<scr'+'ipt type="text/javascript">'+
      'wait = "<img src=\'"+kclient_dom+"/sites/all/modules/kasahorow/kdictionary/ajax-loader.gif"+"\'/>";'+
      'document.write("<div id=\'kclientform\'><form method=\'get\'>"+'+
      '"<input type=\'text\' name=\'kcq\' value=\'"+gup(\'kcq\')+"\'/>'+
      '<div style=\'display:inline\'>'+Drupal.t(' in ')+dbselect+'<br/>'+Drupal.t(' limit to ')+linkselect+
      '</div>'+
      '<input type=\'submit\' value=\''+Drupal.t('Search')+'\'/>'+
      '<a href=\'#\' onclick=\'swap();\'>swap</a></form></div><div id=\'kclientresults\'>"+wait+"</div>");'+
      'kData.response();'+

      'var output = "";'+
      'function kasahorow_callback(feed){'+
        'var kitems = feed.results;'+
        'for(var i = 0; i < kitems.length; i++) {'+
          'output+= \'<div class="get-links-result">\';'+
          'output+= \'<u>\'+kitems[i].type+\': <a href="\'+kitems[i].link+\'">\'+kitems[i].title+\'</a></u>\';'+
          'output+= \'<p><img width=50px height=50px src="\'+kitems[i].node[\'thumbnail\']+\'" align=left />\'+kitems[i].node[\'defn\']+kitems[i].snippet+\'</p>\';'+
          'output+= \'<p><a href="\'+kitems[i].link+\'?utm_source=kclient&utm_medium=html&utm_campaign=wijiti">\'+kitems[i].link+\'</a> \';'+
	  'output+= \' <a href="\'+kclient_dom+\'comment/reply/\'+kitems[i].node[\'nid\']+\'?utm_source=kclient&utm_medium=html&utm_campaign=wijiti">'+Drupal.t('Discuss')+'</a></p>\';'+
          'output+= "</div>";'+
        '}'+
        'document.getElementById("kclientresults").innerHTML = output;'+
      '}'+
      "ss('db', gup('db'));ss('link', gup('link'));"+
      '</scr'+'ipt>';
  out.value = outstring;
}

//decides which background selector to show in the generator form
function bgswitch(val) {
  if (val == "bgcolor") {
	document.getElementById('backgroundlink').style.visibility = "hidden";
	document.getElementById('backgroundcolor').style.visibility = "visible";
  }
  else {
	document.getElementById('backgroundcolor').style.visibility = "hidden";
	document.getElementById('backgroundlink').style.visibility = "visible";
  }
}

//changes the visibility of the link in the example widget
function swapout() {
  var link = document.getElementById('link');
  if (link.style.visibility == "hidden" || link.style.visibility == "" ) {
	link.style.visibility = "visible";
  }
  else {
	link.style.visibility = "hidden";
  }
}

var http_request = false;
function makePOSTRequest(url, parameters) {
	http_request = false;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		http_request = new XMLHttpRequest();
		if (http_request.overrideMimeType) {
		// set type accordingly to anticipated content type
		//http_request.overrideMimeType('text/xml');
			http_request.overrideMimeType('text/html');
		}
	} 
	else if (window.ActiveXObject) { // IE
		try {
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} 
		catch (e) {
			try {
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} 
			catch (e) {}
		}
	}
	if (!http_request) {
		alert('Cannot create XMLHTTP instance');
		return false;
	}
	http_request.onreadystatechange = alertContents;
	http_request.open('POST', url, true);
	http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_request.setRequestHeader("Content-length", parameters.length);
	http_request.setRequestHeader("Connection", "close");
	http_request.send(parameters);
}

function alertContents() {
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			result = http_request.responseText;
			document.getElementById('results').innerHTML = result;
		} 
		else {
			alert('There was a problem with the request.');
		}
		//shows the results, hides the loader
		document.getElementById('loader').style.visibility = "hidden";
		document.getElementById('results').style.visibility = "visible";
	}
}

function get(obj) {
    //hides the resutls, turns on the loading animation
    document.getElementById('results').innerHTML = "";
    document.getElementById('results').style.visibility = "hidden";
    document.getElementById('loader').style.visibility = "visible";
    //sets up the values to be passed by POST...
    var poststr = 'word='+document.getElementById('word').value+"&fromto="+document.getElementById('fromto').options[document.getElementById('fromto').selectedIndex].value+"&devkey="+document.getElementById('devkey').value;
    //and then sends them to the script specified here
    makePOSTRequest('kclient/callback', poststr);
}
