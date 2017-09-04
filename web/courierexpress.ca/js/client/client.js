$(document).ready(function()
{

	// grid for the client home
  if ($('#client_home').val())
  {
    $.ajaxSetup({ cache: false });
    $('#client_home_grid').firescope_grid({
      rows: 8,
      url: '/client/firegrid_received_data', // your server side file
      filterCols: [0,1,2,3,4],
      sortCols: ['auto'],
      sortCol: 0,
      navBarShow: 'always',
      navBarAlign: 'let',
      navBarLocation: 'top',
      data: {
        yourparm: 'ok'
      },
      ignore: null
    });
  }


	// grid for client delivered
  if ($('#client_delivered').val())
  {

  // fimction for delivered in partner
    $.ajaxSetup({ cache: false });
    $('#client_delivered_grid').firescope_grid({
      rows: 10,
      url: '/client/firegrid_delivered_data', // your server side file
      filterCols: [0,1,2,3,4],
      sortCols: ['auto'],
      sortCol: 0,
      navBarShow: 'always',
      navBarAlign: 'let',
      navBarLocation: 'top',
      data: {
        yourparm: 'ok'
      },
      ignore: null
    });
  }


});


// payment details
// auto complete city/province from

$('#payment_Address_postal_code').blur(function ()
{
  autocomplete_city_province
    ('#payment_Address_postal_code',
     '#payment_Address_city',
     'payment_Address_province_state_id',
     '#payment_Address_Country_id');
});


// change the sender province by country
$('#payment_Address_Country_id').change(function ()
{
  change_provinces_by_country('payment_Address_Country_id', 'payment_Address_province_state_id');
});

// change the sender province by country
$('#address_Country_id').change(function ()
{
  change_provinces_by_country('address_Country_id', 'address_province_state_id');
});

// auto complete generic city/province from

$('#address_postal_code').blur(function ()
{
  autocomplete_city_province(
			'#address_postal_code',
			'#address_city',
			'address_province_state_id',
			'#address_Country_id'
	);
});



function toggle_enabled_status(control_element_id, element_to_toggle_id)
{
  if ($(control_element_id).is(':checked')) {
      $(element_to_toggle_id).attr('disabled', true);
  } else {
      $(element_to_toggle_id).removeAttr('disabled');
  }   
}

