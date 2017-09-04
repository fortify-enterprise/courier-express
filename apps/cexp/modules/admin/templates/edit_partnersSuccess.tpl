<div style="margin-left: 2em; margin-right: 1em">
	
	<form id="admin_partner_form" class="cmxform" method="post" action="{url_for name="admin/edit_partners"}">
	 	<div class="rounded_div">
			<span>{$form->renderGlobalErrors()}</span>

			<fieldset>
    			<legend>{__ text='Edit partners'}</legend>

					<ol>
						<li>
						</li>

						<li>
							<div>
								<label for="courier_id">{__ text='partner'}:</label><br />
             	  <select id='couriers_list';
								style="width: 15em" id="courier_id" name="courier[courier_id]">
									{html_options values=$courier_ids output=$couriers selected=$courier_id}
              	</select>
							</div>
					</li>

					<li>
						<div>
							<label style="width: 20em" for="is_new_partner">{__ text='create new partner?'}</label><br />
							<input type="checkbox" name="is_new_partner" id="is_new_partner" {if $is_new_partner} checked='checked'{/if} />
						</div>
					</li>


					<li>
						{$form->render()}
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
		
		{$form->renderHiddenFields()}
		<input type="hidden" name="submitted" value="1" />

		</div> {* rounded div *}

	</form>
</div>
