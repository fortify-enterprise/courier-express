<div style="margin-left: 1em; margin-right: 1em">

	<form id="partner_details_form" class="cmxform" method="post" action="{url_for name='partner/details'}">
	 	<div class="rounded_div" style='height: 45em'>

			<fieldset>
    			<legend>{__ text='Your details information'}</legend>

					<ol>
            <li>
              <span>{$form->renderGlobalErrors()}</span>
            </li>

              {$form.details->renderRow()}
              {$form.name->renderRow()}
							<br />
              {$form.phone->renderRow()}
              {$form.contact->renderRow()}
              {$form.email->renderRow()}
              {$form.ClientLogin.email->renderRow()}
							<br />
              {$form.ClientLogin.password->renderRow()}
              {$form.ClientLogin.password_again->renderRow()}
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
