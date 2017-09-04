<div style="margin-left: 1em; margin-right: 1em">

	<form class="cmxform" method="post" action="{url_for name='partner/availability'}">
	 	<div class="rounded_div" style='height: 45em'>

			<fieldset>
    			<legend>{__ text='Availability to deliver parcels'}</legend>

					<ol>
						<li>
						</li>

						<li>
							<label for="is_available">{__ text='Are you available for deliveries?'}</label><br />
							<input type="checkbox" value="available" id="is_available" style='width: 10px'
							name="is_available" {if $availability == '1'} checked='checked'{/if} />
						</li>

						<li>
   						<button type="submit" id="login_button" class="fg-button ui-state-default ui-corner-all">{__ text='Save'}</button>
						</li>

						<li>
							<input type="hidden" name="submitted" value="1" />
						</li>

	          <li>
					    <span class="highlight">{$message}</span>
					  </li>
		  		</ol>

			  </fieldset>

		</div> {* rounded div *}
	</form>
</div>
