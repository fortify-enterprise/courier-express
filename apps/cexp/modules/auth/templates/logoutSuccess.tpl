{if $fb_uid}
  {literal}
    <script>
      FB.init({appId: '{/literal}{$fb_appid}{literal}', status: true, cookie: true, xfbml: true});
      FB.Event.subscribe('auth.logout', function(response) {
      window.location.href='{/literal}{url_for name='landing_page/index'}{literal}';
    });
  </script>
 {/literal}
{/if}
