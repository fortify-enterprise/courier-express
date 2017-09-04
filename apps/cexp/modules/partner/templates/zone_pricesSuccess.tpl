<div style="margin-left: 1em; margin-right: 1em">


	<form class="cmxform" method="post" action="{url_for name='partner/zone_prices'}">
	 	<div class="rounded_div">

			<fieldset>
    			<legend>{__ text='edit zone service price levels'}:</legend>

					<ol>
						<li></li>

						<li>
							<label style="width: 9em" for="from_zone">{__ text='From zone'}: </label>
              <select style="width: 10em" id="from_zone" name="from_zone">
								{html_options values=$zone_ids output=$zone_names selected=$from_zone_id}
              </select>

							<br />
						</li>

						<li>
							<label style="width: 9em" for="to_zone">{__ text='To zone'}:</label>
							<select style="width: 10em" id="to_zone" name="to_zone">
								{html_options values=$zone_ids output=$zone_names selected=$to_zone_id}
							</select>
							<br />
						</li>
					
						<li>
							<label style="width: 9em" for="service_level">{__ text='Service level'}:</label>
							<select style="width: 10em" id="service_level" name="service_level">
								{html_options values=$level_ids output=$level_names selected=$service_level_id}
							</select>
							<br />
						</li>

						<li>
							<label style="width: 9em" for="pair_price">{__ text='Price level'}:</label>
							<input style="width: 5em" type="text" id="pair_price" size="6" value="{$price}" name="pair_price"/>
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

		</div> <!-- rounded div -->
		<input type="hidden" name="submitted" value="1" />

	</form>

</div>
