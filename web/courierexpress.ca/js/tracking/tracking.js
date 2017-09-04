$(document).ready(function()
{
// order tracking page

// reset the order id on mouse click

  var track_order_input_clicked = false;
  $('#track_number').click(function(){
    if (!track_order_input_clicked)
    {
      //this.value = '';
      track_order_input_clicked = true;
    }
  });


// once the button clicked do tracking ajax call

  $("#tracking_form_id").validate
  ({
    rules: {
    track_number: "required"
  }
  });


  $("#tracking_info_button").click(function()
  {
    $("#tracking_information").fadeIn("slow");
  });
});
