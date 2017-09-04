$.fn.addItems = function(data)
{
  return this.each(function() {
    var list = this;
    $.each(data, function(index, itemData) {
    var option = new Option(itemData, index);
    if ($('#is_explorer').val())
    {
      list.add(option);
    }
    else {
      list.add(option, null);
    }
  });
  });
};


function get_price_pair()
{
  var from_zone = $('#from_zone option:selected').val();
  var to_zone = $('#to_zone option:selected').val();
  var service_level = $('#service_level option:selected').val();
  $.ajax({
     type: "POST",
     url: "/partner/get_zone_pricepair/from_zone/" + from_zone + '/to_zone/' + to_zone + '/service_level/' + service_level,
     success: function(msg)
     {
       var stripedMsg = jQuery.trim(msg);
       if (stripedMsg != "")
           $('#pair_price').attr("value", stripedMsg);
       else
           $('#pair_price').attr("value", 0.00);
     }
  });
}


function get_zone_elements()
{
  $.ajaxSetup({ cache: false });
  $.getJSON("/partner/get_zone_elements/zone_id/" + $("#zone_name option:selected").val(),
   function (data)
    {

     // remove all options
     remove_html_options(document.getElementById('zone_element_list'));

		 $("#zone_element_list").addItems(data);

     if($("#zone_element_list").length == 0)
     {
        $('#is_new_element').attr('checked', 'checked');
        toggle_enabled_status('#is_new_element', '#zone_element_list');
     }
     else
     {
        $('#is_new_element').attr('checked', '');
        toggle_enabled_status('#is_new_element', '#zone_element_list');
     }

     $("#element").attr("value", $("#zone_element_list option:selected").text());
  });
}



function get_zone_type()
{
  $.ajaxSetup({ cache: false });
  $.getJSON("/partner/get_zone_type/zone_id/" + $("#zone_list option:selected").val(),
  function (data)
  {
     $("#zone_type").attr("value", data["zone_type_id"]);
  });
}




$(document).ready(function()
{
  if ($('#from_zone').val())
  {
    get_price_pair();
  }


 if ($('#zone_name option:selected').val())
  {
    get_zone_elements();
  }


 // set zone prices

  $('#from_zone').change().click( function ()
  {
     get_price_pair();
  });

  $('#to_zone').change().click( function ()
  {
     get_price_pair();
  });

  $('#service_level').change().click( function ()
  {
     get_price_pair();
  });


  // zone element change
  $('#zone_name').change( function()
  {
    get_zone_elements();
  });

  if ($('#zone_list').val())
  {
    $('#zone_name').attr("value", $('#zone_list option:selected').text());
    get_zone_type();
  }


  // partner zone pages

  $('#zone_list').click().change( function()
  {
    $('#zone_name').attr("value", $('#zone_list option:selected').text());
    // get the zone type fill it in
    get_zone_type();
  });


   // set zone element on click
   $('#zone_element_list').change().click( function()
   {
     $('#element').attr("value", $('#zone_element_list option:selected').text());
   });


  $('#is_new_zone').click(function() {
     toggle_enabled_status('#is_new_zone', '#zone_list');
  });

   $('#is_new_element').click(function() {
     toggle_enabled_status('#is_new_element', '#zone_element_list');
  });

  // patner zone pages



// grid for the partner home
  if ($('#partner_home').val())
  {
    $.ajaxSetup({ cache: false });
    $('#partner_home_grid').firescope_grid({
      rows: 5,
      url: '/partner/firegrid_received_data', // your server side file
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



// grid for partner delivered
  if ($('#partner_delivered').val())
  {

  // fimction for delivered in partner
    $.ajaxSetup({ cache: false });
    $('#partner_delivered_grid').firescope_grid({
      rows: 5,
      url: '/partner/firegrid_delivered_data', // your server side file
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



  // change the recepient province by country
  $('#address_Country_id').change(function ()
  {
    change_provinces_by_country('address_Country_id', 'address_province_state_id');
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


});


function toggle_enabled_status(control_element_id, element_to_toggle_id)
{
  if ($(control_element_id).is(':checked')) {
      $(element_to_toggle_id).attr('disabled', true);
  } else {
      $(element_to_toggle_id).removeAttr('disabled');
  }   
}

