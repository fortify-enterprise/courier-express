$(document).ready(function() {

  var new_level_selected = false;

  $('#price_level_new_level').click(function()
  {
    if (!new_level_selected)
    {
      $('ol > li:first').hide(1000);
      $('#price_level_new_level').attr('checked', true);
      new_level_selected = true;
    }
    else
    {
      $('ol > li:first').show(1000);
      $('#price_level_new_level').attr('checked', false);
      new_level_selected = false;
    }
  });

});
