<div style="margin-left: 1em; margin-right: 1em">

  <form id="password_recovery_form" class="cmxform" method="post" action="{url_for name='auth/recovery'}">

    <div class="rounded_div" style="margin: 0pt auto; width: 40em; height: 45em">

      <div style="width: 23em; margin-left: 3em; margin-top: 5em;">
      <fieldset>
          <legend>{__ text='Please enter your login email'}</legend>

          <ol>
            <li>
            </li>

            <li>
              {$form.email->renderRow()}
            </li>

            {if $message}
             <li>
              <span class="white_text">{$message}</span>
             </li>
            {/if}

            <li>
              <button type="submit" class="fg-button ui-state-default ui-corner-all">{__ text='Recover password'}</button>
              <a href="{url_for name='auth/index}">Back to login</a>
            </li>

          </ol>

        </fieldset>
      </div>

    </div>

  </form>
</div>
