$(document).ready(function()
{

  $("#contact_form_id").validate({
  rules: {

     'contact_info[name]': {required: true},
     'contact_info[company]': {required: true},
     'contact_info[phone]': {required: true},
     'contact_info[email]': {required: true, email: true},
     'contact_info[details]': {required: true},
    }
  });


  $('#addressmap').click(function ()
  {
    window.open('http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=133-2688+Shell+Rd+++Richmond+BC++V6X+4E1&sll=37.0625,-95.677068&sspn=49.176833,114.169922&ie=UTF8&z=16&iwloc=A"');
  });

  $('#addressmap').mouseover(function ()
  {
    document.body.style.cursor = 'pointer';
  });

  $('#addressmap').mouseout(function ()
  {
    document.body.style.cursor = 'default';
  });
});
