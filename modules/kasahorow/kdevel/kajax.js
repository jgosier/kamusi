/**
 * Change the url value according to the path specified
 * in the URL. Make the AJAX callback an event handler that detects clicks
 * and changes the 'url' to that of the current location.href if it's a local
 * path.
 * TODO(paakwesi) : Update the breadcrumb div with the current menu path.
 *                  Need to implement a special callback that generates
 *                  a string version of the breadcrumb when given a menu path
 */ 
$(document).ready (function() { alert('ebei');
 //but what if someone just lands on a jsviewer page?
 //let's create a hidden a href and trigger a click on it
 var initpath = Drupal.settings.basePath+location.hash.replace('#', '');
 $('#kjson').html("<a href='"+initpath+"'>" +
                  Drupal.t('Redirecting you to @path', {'@path': initpath}) +
                  "</a>");
 initializelinks('');
 $('#kjson a').triggerHandler('click'); //Now simulate a click
});

function initializelinks(div_id){
  var div = '';
  if(div_id){
    div = '#' + div_id + ' ';
  }
  //change the url of every <a> tag
  $(div+'a').attr('href', function (arr) {
      return this.href.replace(Drupal.settings.basePath,
          Drupal.settings.basePath + 'jsviewer#' );
  });
  //And then bind the click event handler
  $(div+'a').bind('click', function(e){
    var path = this.href.substring(this.href.indexOf('jsviewer#'));
    path = path.replace('jsviewer#', 'js/');
    $.ajax({
      url: path,
      //cache: false,
      success: function(data){
        if(data == 3) {
          data = Drupal.t('Access denied');
        }else if(data == 4) {
          data = Drupal.t('Page not found');
        }
        $("#kjson").html(data);
        initializelinks('kjson');  //now reprocess the links in this div
      },
      dataType: 'json'
    });
    //Update other sections of the page that should be refreshed after a click
    //updatesections(location.hash);
  });
}

/**
 *Update breadcrumbs, messages, blocks divs
 */
function updatesections(path) {alert(path); return;
  //$('.breadcrumb').html(path);
  $.ajax({
    url: Drupal.settings.basePath + 'jsmessages',
    success: function(data){
      alert(data);
    },
    dataType: 'json'
  });
  $('.messages').hide();
  $('.messages').fadeIn('slow');
}