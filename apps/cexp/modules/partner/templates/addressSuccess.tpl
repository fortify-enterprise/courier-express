<div style="margin-left: 1em; margin-right: 1em">

	<form id="partner_details_form" class="cmxform" method="post" action="{url_for name='partner/address'}">
	 	<div class="rounded_div" style='height: 45em'>

			<fieldset>
    			<legend>{__ text='Your address information'}</legend>

					<ol>
						<li>
              <span>{$form->renderGlobalErrors()}</span>
						</li>

            {$form.apt_unit->renderRow()}
            {$form.street_number->renderRow()}
            {$form.street_name->renderRow()}
            {$form.Country.id->renderRow()}
            {$form.postal_code->renderRow()}
            {$form.city->renderRow()}
            {$form.province_state_id->renderRow()}
						<li>
						</li>

						<li>
   						<button type="submit" id="login_button"
											tabindex='100'
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
		<input type="hidden" name="submitted" value="1" />


	</form>

</div>
