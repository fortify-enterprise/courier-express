$(document).ready(function()
{
  $("#generic_form").validate({
  rules: {

 	   'email1': {required: true, email: true},
     'email2': {required: true, email: true}
		}
  });

});
