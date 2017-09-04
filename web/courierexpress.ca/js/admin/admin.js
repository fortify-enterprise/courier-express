function fill_courier_data (data)
{
  if (data != "")
  {

   	$('#apt_unit').attr("value", data["apt_unit"]);
   	$('#street_number').attr("value", data["street_number"]);
   	$('#street_name').attr("value", data["street_name"]);
   	$('#country_id').attr("value", data["country_id"]);
   	$('#state_province_id').attr("value", data["state_province_id"]);
   	$('#postal_code').attr("value", data["postal_code"]);
   	$('#city').attr("value", data["city"]);

   	$('#name').attr("value", data["name"]);
   	$('#email').attr("value", data["email"]);
   	$('#phone').attr("value", data["phone"]);
   	$('#contact').attr("value", data["contact"]);
   	$('#details').attr("value", data["details"]);
   	$('#email').attr("value", data["email"]);
   	$('#password').attr("value", data["password"]);
   	$('#profit_cut').attr("value", data["profit_cut"]);

    if(data["enabled"] == 1)
      $('#enabled').attr('checked', 'checked');
    else
      $('#enabled').attr('checked', '');
  }
}


function fill_courier_information (courier_id)
{
	$.ajaxSetup({ cache: false });
	$.getJSON("/admin/get_courier_information/courier_id/" + courier_id,
	function (data)
	{
		fill_courier_data (data);
	});
}



$(document).ready(function()
{
	$('#couriers_list').change(function()
	{
		var base = window.location.protocol + '//' + window.location.host + '/' +
		$('#environment').val() + '/' + 'admin/edit_partners';
		window.location = base + '?id=' + $("#couriers_list :selected").val();
	});

});


function toggle_enabled_status(control_element_id, element_to_toggle_id)
{
  if ($(control_element_id).is(':checked')) {
      $(element_to_toggle_id).attr('disabled', true);
  } else {
      $(element_to_toggle_id).removeAttr('disabled');
  }   
}

