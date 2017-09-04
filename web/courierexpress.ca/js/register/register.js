$(document).ready(function()
{

	$('#client_ClientDetail_phone').mask('9999999999');

	// auto complete city/province from
	$('#client_ClientDetail_email').blur(function ()
	{
    $('#client_ClientLogin_email').val($('#client_ClientDetail_email').val());
  });
  
  // auto complete city/province from

  $('#client_Address_postal_code').blur(function ()
  {
    autocomplete_city_province
      ('#client_Address_postal_code',
       '#client_Address_city',
       'client_Address_Province_id',
       '#client_Address_Country_id');
  });


});

