<div style="margin-left: 1em; margin-right: 1em">

	<form name="login_form" action="{url_for name='auth/index'}" method="post" id="login_form" class="cmxform">
		<input type="hidden" name="login_type" value="{$login_type}" />

    <div class="rounded_div" style="margin: 0pt auto; width: 40em; height: 45em">

      <div style="width: 23em; margin-left: 3em; margin-top: 5em;">

      <fieldset>
        <legend>{__ text='Enter login information'}</legend>
          <ol>
            {if $form->hasErrors()}
              <li>
                <span>{$form->renderGlobalErrors()}</span>
              </li>
            {/if}
						<li>
						<br />
						<fb:login-button perms='email, sms, publish_stream, create_event, user_about_me, user_activities, friends_activities, user_events, user_groups, user_hometown, user_interests, user_likes, friends_likes, user_location, friends_location, user_website, read_friendlists, read_insights, read_stream'>Login with Facebook</fb:login-button>
						<br />
						<div id="fb-root"></div>
						{literal}
    				<script src="http://connect.facebook.net/en_US/all.js"></script>
    				<script>
      					FB.init({appId: '134730206585553', status: true,
             		cookie: true, xfbml: true});
      					FB.Event.subscribe('auth.login', function(response) {
        				window.location = {/literal}'{url_for name='main_page/index'}'{literal};
      			});
    				</script>
						{/literal}
						</li>
            <li>
              {$form.email->renderRow()}
            </li>
            <li>
              {$form.password->renderRow()}
            </li>

            <li>
              <button tabindex='10' type="submit" id="login_button"
                      class="fg-button ui-state-default ui-corner-all">{__ text='Login'}</button>
            </li>

            <li>
              <div class="white_text">
								{__ text='Forgot password'}?
								<a href="{url_for name='auth/recovery'}">{__ text='click here'}</a></div>
              <div class="white_text">
								{__ text='Not registered'}?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="{url_for name='register/client'}">{__ text='click here'}</a></div>
 
            </li>


          </ol>

        </fieldset>
      </div>
    </div>
    <input type='hidden' name='in_process' value='{$in_process}' />
    {$form->renderHiddenFields()}
  </form>
</div>

