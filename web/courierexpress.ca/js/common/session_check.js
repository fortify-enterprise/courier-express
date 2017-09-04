$(document).ready(function(){

  //..
  // session management
  // if detected that session expired redirect to home page
  if ( !($(location).attr('pathname').match('auth$') || $(location).attr('pathname').match('auth/index$')) )
  	setTimeout(session_update, $('#session_timeout').val() * 1000 + 5);


});

// ...
// session function for checking if session has expired

function session_update ()
{
  $.post('/' + $('#environment').val() + "/dynamic/session_check", function(data)
	{
    //if the session marker not there, logout
    if (!data.match(/session marker/))
        window.location = '/' + $('#environment').val() + '/auth/logout';
  });

  setTimeout(session_update, $('#session_timeout').val() * 1000 + 5);
}
