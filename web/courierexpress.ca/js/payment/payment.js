$(document).ready(function()
{
  $('#generic_form').validate({
    rules:
    {
      email: "required",
      gateway: "required"
    }
  });
}
});
