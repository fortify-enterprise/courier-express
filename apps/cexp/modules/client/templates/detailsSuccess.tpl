<div style="margin-left: 1em; margin-right: 1em">

  <form id="client_details_form" class="cmxform" method="post" action="{url_for name='client/details'}">
    <div class="rounded_div" style='height: 45em'>

      <fieldset>
          <legend>{__ text='Your details information'}</legend>

          <ol>
            <li>
              <span>{$form->renderGlobalErrors()}</span>
            </li>
						
						<li>
              {$form.details->renderRow()}
						</li>

						<li>
              {$form.name->renderRow()}
						</li>

            <li>
						</li>

						<li>
              {$form.phone->renderRow()}
            </li>

						<li>
							{$form.contact->renderRow()}
            </li>

						<li>
							{$form.email->renderRow()}
            </li>

						<li>
							{$form.ClientLogin.email->renderRow()}
            </li>
						
						<li>
						</li>

						<li>
              {$form.ClientLogin.password->renderRow()}
            </li>

						<li>
							{$form.ClientLogin.password_again->renderRow()}
           	</li>
					 
					 	<li>
            </li>

            <li>
              <button tabindex='100' type="submit" id="login_button"
                      class="fg-button ui-state-default ui-corner-all">{__ text='Save'}</button>
            </li>

            <li>
               {if $message}
                <span class="highlight">{$message}</span>
              {/if}
            </li>

          </ol>

        </fieldset>
      {$form->renderHiddenFields()}

    </div> {* rounded div *}

  </form>
</div>

