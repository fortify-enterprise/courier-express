$(document).ready(function() {
  $("#ship_using_us").click(function () {
    window.location = $('#shipping_link').html();
  });

  $("#shipping_partner").click(function () {
    window.location = $('#shipping_link').html() + '/contact';
  });

  $("#courier_model").click(function () {
    window.location = $('#shipping_link').html() + '/questions';
  });


  $('.mouse_over').hover(function() {
    $(this).addClass('pretty-hover');
  }, function() {
    $(this).removeClass('pretty-hover');
  });

	$(".beacome-partner").click(function() {
		window.location = $('#shipping_link').html() + '/contact';
	});

});


