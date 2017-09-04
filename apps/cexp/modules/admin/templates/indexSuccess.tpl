<div style="margin-left: 2em; margin-right: 1em">


	<form id="admin_partner_form" class="cmxform" method="post" action="{url_for name='admin/index'}">

	 	<div class="rounded_div" style='height: 42em'>
			<fieldset>
    			<legend>{__ text='Edit properties'}:</legend>

					<ol>
						<li>
						</li>

						<li>
							<div style="float: left; margin-right: {$dis}">
								<label for="tax_amount">{__ text='tax amount'}: </label><br />
								<input style="width: 7em" type="text" name="tax_amount" id="tax_amount" value="{$info.tax_amount}" />
							</div>

							<div style="float: left; margin-right: {$dis}">
								<label for="package_id_length">{__ text='package id length'}: </label><br />
								<input style="width: 10em" type="text" name="package_id_length" id="package_id_length" value="{$info.package_id_length}" />
							</div>
						
							<div>
								<label for="payment_id_length">{__ text='payment id length'}: </label><br />
								<input style="width: 10em" type="text" name="payment_id_length" id="payment_id_length" value="{$info.payment_id_length}" />
							</div>
						</li>


						<li>
						</li>
						<li>
   						<button type="submit" id="login_button" class="fg-button ui-state-default ui-corner-all">{__ text='Save changes'}</button>
						</li>

            <li>
							 {if $message}
						  	<span class="highlight">{$message}</span>
							{/if}
						</li>

		  		</ol>

			  </fieldset>

		<input type="hidden" name="submitted" value="1" />
		</div> {* rounded div *}
	</form>
</div>

