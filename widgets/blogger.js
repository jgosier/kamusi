function twitterCallback2(obj) {
	var regular = /((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g;
	var atregular = /\B@([_a-z0-9]+)/ig;
	var twitters = obj;
	var statusHTML = "";
	var username = "";

	for (var i=0; i<twitters.length; i++){
		var posttext = "";
		posttext = twitters[i].text.replace(regular, '<a href="$1">$1</a>');
		posttext = posttext.replace(atregular, '@<a href="http://twitter.com/$1">$1</a>');
		
		username = twitters[i].user.screen_name
		statusHTML += ('<li><span>'+posttext+'</span> <a style="font-size:85%" href="http://twitter.com/'+username+'/statuses/'+twitters[i].id+'" title="Tweet Permalink">'+relative_time(twitters[i].created_at)+'</a></li>')
	}
	document.getElementById('twitter_update_list').innerHTML = statusHTML;
}

function relative_time(time_value) {
  var values = time_value.split(" ");
  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
  var parsed_date = Date.parse(time_value);
  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
  delta = delta + (relative_to.getTimezoneOffset() * 60);

  if (delta < 60) {
    return 'less than a minute ago';
  } else if(delta < 120) {
    return 'about a minute ago';
  } else if(delta < (60*60)) {
    return (parseInt(delta / 60)).toString() + ' minutes ago';
  } else if(delta < (120*60)) {
    return 'about an hour ago';
  } else if(delta < (24*60*60)) {
    return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
  } else if(delta < (48*60*60)) {
    return '1 day ago';
  } else {
    return (parseInt(delta / 86400)).toString() + ' days ago';
  }
}