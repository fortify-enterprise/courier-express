function autocomplete_address_by_name (name, type)
{
	$.ajaxSetup({ cache: false });
  $.getJSON("client/fetch_name_address/username/" + $('#username').val() + "/name/" + name,
  function (data)
  {
		fill_address_data (data, type);
  });
}


function fill_contact_information (client_id, type)
{
	$.ajaxSetup({ cache: false });
  $.getJSON("client/contact_information/client_id/" + client_id,
  function (data)
  {
		fill_address_data (data, type);
	});
}


function fill_address_data (data, type)
{
  if (data != "")
  {
    $('#package_' + type + '_apt_unit').attr("value", data["apt_unit"]);
    $('#package_' + type + '_street_number').attr("value", data["street_number"]);
    $('#package_' + type + '_street_name').attr("value", data["street_name"]);
    $('#package_' + type + '_postal_code').attr("value", data["postal_code"]);
    $('#package_' + type + '_province_state_id').attr("value", data["state_province_id"]);
    $('#package_' + type + '_city').attr("value", data["city"]);
    $('#package_' + type + '_Country_id').attr("value", data["country_id"]);

		phone_type = '';
		if (type == 'sender')
			phone_type = 'sender_';

    $('#package_PackageDetail_' + phone_type + 'phone').attr("value", data["phone"]);
		
		// load the provinces based on province id provided
		change_provinces_by_country('package_' + type + '_Country_id', 'package_' + type + '_Province_id', data["province_state_id"]);
  }
}


function clear (type)
{
  $('#package_' + type + '_name').val('');
  $('#package_' + type + '_apt_unit').val('');
  $('#package_' + type + '_street_number').val('');
  $('#package_' + type + '_street_name').val('');
  $('#package_' + type + '_postal_code').val('');
  $('#package_' + type + '_province_state_id').val('');
  $('#package_' + type + '_city').val('');

	phone_type = '';
	if (type == 'sender')
		phone_type = 'sender_';

  $('#package_PackageDetail_' + phone_type + 'phone').val('');
}


function delivery_by_update (ready_date, ready_time, service)
{
	$.ajaxSetup({ cache: false });
  $.getJSON("services/get_delivered_by/date/" + ready_date + "/time/" + ready_time + "/service/" + service, function (data)
  {
     $("#delivered_by").html(data["delivered_by"]);
  });
}


function prefill_contact_information (do_clear)
{
	switch ($('#delivery_type').val())
  {
  	case '1':
			// provided you logged in and we have client_id
			fill_contact_information ($('#username').val(), 'sender');
			if (do_clear == 1)
    			clear('recep');
		break;

		case '2':
			// provided you logged in and we have client_id
			fill_contact_information ($('#username').val(), 'recep');
			if (do_clear == 1)
          clear('sender');
		break;

    case '3':

			if (do_clear == 1)
			{
    	 	clear('sender');
        clear('recep');
      }
		break;
  }
}



$(document).ready(function()
{
	$("#package_PackageDetail_sender_phone").mask("9999999999");
	$("#package_PackageDetail_phone").mask("9999999999");


	// prefil if username exists and we are not editing package
  if ($('#username').val() != "" && $('#edit_package').val() == "")
  {
    prefill_contact_information (0);
  }


  $('#package_PackageDetail_ready_date').DatePicker({
  	format: 'Y-m-d',
  	date: $('#package_PackageDetail_ready_date').val(),
  	current:  $('#package_PackageDetail_ready_date').val(),
  	starts: 1,
  	position: 'b',
  	onBeforeShow: function()
  	{
    	if ($('#package_PackageDetail_ready_date').val() == "") {
      	$('#package_PackageDetail_ready_date').DatePickerSetDate(curr_date, true);
    	}
    	else
      	$('#package_PackageDetail_ready_date').DatePickerSetDate($('#package_PackageDetail_ready_date').val(), true);
  	},

  	onChange: function(formated, dates)
  	{
    	$('#package_PackageDetail_ready_date').val(formated);
    	$('#package_PackageDetail_ready_date').DatePickerHide();

    	// call function to update delivery by
    	delivery_by_update(formated,
        $('#package_PackageDetail_ready_time').val(),
        $('#package_PackageDetail_ServiceLevelType_id').val());
  	}
	});


	// change the delivery type

  $('#package_PackageDetail_DeliveryType_id').change( function ()
	{
		if ($('#username').val() != "")
		{
			prefill_contact_information(1);
 		}
  });


	
	// auto complete city/province from
  
	$('#package_sender_postal_code').blur(function ()
	{
    autocomplete_city_province
      ('#package_sender_postal_code',
       '#package_sender_city',
       'package_sender_Province_id',
       '#package_sender_Country_id');
  });

  $('#package_recep_postal_code').blur(function ()
	{
    autocomplete_city_province
      ('#package_recep_postal_code',
       '#package_recep_city',
       'package_recep_Province_id',
       '#package_recep_Country_id');
  });


  // prices page javascript
  $('#back_button').click(function()
	{
    history.go(-1);
    return false;
  });



	// get current date
	var d = new Date();
	var currMonth = parseInt(d.getMonth()) + 1;
	var curr_date = d.getFullYear() + '-' + currMonth + '-' + (parseInt(d.getDate()) + 1);


	if ($('#package_PackageDetail_ready_date').val() == "")
 		$('#package_PackageDetail_ready_date').attr('value', curr_date);


	if ($('#package_PackageDetail_ready_time').val() == "")
	{
  	$('#package_PackageDetail_ready_time').attr('value', '09.00AM');
  	delivery_by_update ($('#package_PackageDetail_ready_date').val(), '09.00AM', $('#service').val());
	}


	// date picker on front page

  $('#package_PackageDetail_ready_time, #package_PackageDetail_ServiceLevelType_id').change(function(){
    delivery_by_update
      (
        $('#package_PackageDetail_ready_date').val(),
        $('#package_PackageDetail_ready_time').val(),
        $('#package_PackageDetail_ServiceLevelType_id').val());
  });
  $('#package_PackageDetail_ready_time').keypress().keyup(function(){
    delivery_by_update
      (
        $('#package_PackageDetail_ready_date').val(),
        $('#package_PackageDetail_ready_time').val(),
        $('#package_PackageDetail_ServiceLevelType_id').val());
  });

	// time picker


	// on start of main page calculate delivery time
	delivery_by_update
    (
      $('#package_PackageDetail_ready_date').val(),
      $('#package_PackageDetail_ready_time').val(),
      $('#package_PackageDetail_ServiceLevelType_id').val());	


	if ($('#username').val() != "")
	{
		// autocomplete the sender
		$("#package_PackageDetail_sender_contact").autocomplete(
		"main_page/autocomplete_address_by_name/query_str/",
		{
			delay:10,
			minChars:1,
			matchSubset:1,
			matchContains:1,
			cacheLength:10,
			onItemSelect:selectSenderItem,
			onFindValue:findSenderValue,
			formatItem:formatSenderItem,
			autoFill:true
		}
		);

		// autocomplete the recepient
		$("#package_PackageDetail_contact").autocomplete(
		"main_page/autocomplete_address_by_name/query_str/",
		{
			delay:10,
			minChars:1,
			matchSubset:1,
			matchContains:1,
			cacheLength:10,
			onItemSelect:selectRecepItem,
			onFindValue:findRecepValue,
			formatItem:formatRecepItem,
			autoFill:true
		}
		);

		$('#package_PackageDetail_sender_contact').blur(function ()
		{
			autocomplete_address_by_name($('#package_PackageDetail_sender_contact').val(), 'sender');
		});

		$('#package_PackageDetail_contact').blur(function ()
		{
			autocomplete_address_by_name($('#package_PackageDetail_contact').val(), 'recep');
		});
	}


});

// sender autocomplete helpers
function findSenderValue(li)
{
	if( li == null )
		return alert("No match!");

	if( !!li.extra ) var sValue = li.extra[0];
	else var sValue = li.selectValue;
	//fill_contact_information (sValue, 'sender');
}

function selectSenderItem(li) {
	findSenderValue(li);
}

function formatSenderItem(row) {
	return row[0];
}

// recepient autocomplete helpers
function findRecepValue(li) {
	if( li == null )
		return alert("No match!");

	if( !!li.extra ) var sValue = li.extra[0];
	else var sValue = li.selectValue;
	//fill_contact_information (sValue, 'recepient');
}

function selectRecepItem(li) {
	findRecepValue(li);
}

function formatRecepItem(row) {
	return row[0];
}
