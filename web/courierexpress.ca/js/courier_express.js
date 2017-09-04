// country id - id of html element
// province_id - id of province element
// change_to_prov_id - actual id of the province or state to set to

function change_provinces_by_country (country_id, province_id, change_to_prov_id)
{
 	// load the provinces or states
	// call ajax call to get states or provinces give the country id as parameter
	//$.ajaxSetup({ cache: false });
	if (country_id.match(/^#/))
		country_id = country_id.substring(1);

	$.getJSON("/dynamic/get_states_provinces_for_country/country_id/" + $('#' + country_id + " option:selected").val(),
	function (data)
 	{
		// remove all options
		remove_html_options(document.getElementById(province_id));
 		$('#'+province_id).addItems(data);
		$("#"+province_id+" option[value='"+change_to_prov_id+"']").attr('selected', 'selected');
 	});
}


// autocomplete city and state or province

function autocomplete_city_province(postal_id, city_id, province_id, country_id)
{
  $.ajaxSetup({ cache: false });

	
	var is_country_mismatch = false;

	// TODO: do not hardcode the country id
	// if we detect that country is wrong for postal code, then update country
	if ($(postal_id).val().match(/[ABCEGHJKLMNPRSTVXY]\d[A-Z][ ]*\d[A-Z]\d$/i) && $(country_id).val() == 2)
	{
		//country_id = 1;
		// change country to canada
		$(country_id+" option[value='1']").attr('selected', 'selected');
		is_country_mismatch = true;
	}

	if ($(postal_id).val().match(/\d{5}(-\d{4})?/i) && $(country_id).val() == 1)
	{
		//country_id = 2;
		$(country_id+" option[value='2']").attr('selected', 'selected');
		is_country_mismatch = true;
	}

	// and change provinces by country for this new country
	if (is_country_mismatch)
	{
		change_provinces_by_country (country_id, province_id);
	}
	


	// get city and province from postal code
  $.getJSON("/services/get_city_province_from_postal/postal/" + $(postal_id).val() + "/country_id/" + $(country_id).val(),
  function (data)
  {
     if (data != "")
        $(city_id).attr("value", data["city"]);
     else
        $(city_id).attr("value", "");


      var stateprovince =  document.getElementById(province_id);
      for (i = 0; i < stateprovince.length; i++)
      {
        if (stateprovince[i].value == data["province"])
          stateprovince[i].selected = true;
      }
   });
}



$(document).ready(function()
{
	$("#phone").mask("9999999999");

	$('a.tip').aToolTip({  
      clickIt: true,                     // set to true for click activated tooltip  
      closeTipBtn: 'aToolTipCloseBtn',    // you can set custom class name for close button on tooltip  
      fixed: true,                       // Set true to activate fixed position  
      inSpeed: 400,                       // Speed tooltip fades in  
      outSpeed: 100,                      // Speed tooltip fades out  
      tipContent: '',                     // Pass in content or it will use objects 'title' attribute  
      toolTipClass: 'aToolTip',           // Set custom class for tooltip  
      xOffset: 5,                         // x Position  
      yOffset: 5                          // y position  
  });  

	/*$('.tip').click(function()
	{
		return false;
	});*/


  // change the sender province by country
	$('#package_sender_Country_id').change(function ()
	{
		change_provinces_by_country('package_sender_Country_id', 'package_sender_Province_id');
	});


  // change the recepient province by country
	$('#package_recep_Country_id').change(function ()
	{
		change_provinces_by_country('package_recep_Country_id', 'package_recep_Province_id');
	});




  // change the generic country
	$('#country_id').change(function ()
	{
		change_provinces_by_country('country_id', 'state_province_id');
	});


  // auto complete generic city/province from

  $('#postal_code').blur(function ()
  {
    autocomplete_city_province('#postal_code', '#city', 'state_province_id', '#country_id');
  });




	// themes for buttons

  $(".fg-button:not(.ui-state-disabled)").hover(function()
	{
    $(this).addClass("ui-state-hover");
  },
  function()
  {
    $(this).removeClass("ui-state-hover");
  }).mousedown(function()
  {
    $(this).parents('.fg-buttonset-single:first').find(".fg-button.ui-state-active").removeClass("ui-state-active");

    if( $(this).is('.ui-state-active.fg-button-toggleable, .fg-buttonset-multi .ui-state-active') )
    {
      $(this).removeClass("ui-state-active");
    }
    else
    {
      $(this).addClass("ui-state-active");
    }
  }).mouseup(function()
  {
    if(! $(this).is('.fg-button-toggleable, .fg-buttonset-single .fg-button,  .fg-buttonset-multi .fg-button') )
    {
      $(this).removeClass("ui-state-active");
    }
  });


  // pruices pages javascript
  $('#clear_form').click(function()
	{
    $('#generic_form').clear();
    return false;
  });
});


// libraries
// clear the form

$.fn.clearForm = function()
{
 return this.each(function() {
  var type = this.type, tag = this.tagName.toLowerCase();

  if (tag == 'form')
    return $(':input',this).clearForm();

  if (type == 'text' || type == 'password' || tag == 'textarea')
    this.value = '';

  else if (type == 'checkbox' || type == 'radio')
    this.checked = false;

  else if (tag == 'select')
    this.selectedIndex = -1;
	});
};


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


function remove_html_options(elSel)
{
  var i;
  for (i = elSel.length - 1; i>=0; i--) {
      elSel.remove(i);
  }
}

