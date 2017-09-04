<div style="margin-left: 1em; margin-right: 1em">

	<form id="partner_details_form" class="cmxform" method="post" action="{url_for name='client/address'}">
	 	<div class="rounded_div" style='height: 45em'>

			<fieldset>
    			<legend>{__ text='Your address information'}</legend>

					<ol>
						<li>
              <span>{$form->renderGlobalErrors()}</span>
						</li>

						<li>
            {$form.apt_unit->renderRow()}
            </li>
						
						<li>
							{$form.street_number->renderRow()}
            </li>
						
						<li>
							{$form.street_name->renderRow()}
            </li>

						<li>
							{$form.Country.id->renderRow()}
            </li>
						
						<li>
							{$form.postal_code->renderRow()}
            </li>
						
						<li>
							{$form.city->renderRow()}
            </li>
						
						<li>
							{$form.province_state_id->renderRow()}
						</li>

						<li>
						</li>

						<li>
   						<button tabindex='100'
											type="submit" id="login_button"
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
