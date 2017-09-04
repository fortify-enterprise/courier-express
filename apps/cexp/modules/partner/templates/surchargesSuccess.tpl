<div style="margin-left: 1em; margin-right: 1em">

	<form id="surcharges_form" class="cmxform" method="post" action="{url_for name='partner/surcharges'}">
	 	<div class="rounded_div" style='height: 45em'>

			<fieldset>
    		<legend>{__ text='Surcharges'}</legend>

				<ol>
					<li>
					</li>

           <li>
            <label style="width: 8em" for="gas_surcharge">{__ text='Gas surchage'}:</label>
						<input style="width: 5em;" value="{$surcharges.gas}" type="text" id="gas_surcharge" name="surcharge[gas]"/>
						<span class="white_text">{__ text='% from total price'}</span>
           </li>

           <li>
           	<label style="width: 8em" for="weight_surcharge">{__ text='Weight surchage'}:</label>
						<input style="width: 5em;" value="{$surcharges.weight}"
									 type="text" id="weight_surcharge" name="surcharge[weight]"/>

								<span class="white_text">{__ text='dollars per (lb) over'}</span>
								<input style="width: 5em;" value="{$surcharges.weight_limit}" type="text" id="weight_limit" name="surcharge[weight_limit]"/>
								<span class="white_text">{__ text='lb'}</span>
            </li>

						<li>
						</li>

						<li>
   						<button type="submit" id="login_button" class="fg-button ui-state-default ui-corner-all">{__ text='Save'}</button>
						</li>

            <li>
							 {if $message}
             	 	<span class="highlight">{$message}</span>
            	{/if}
						</li>


		  		</ol>

			  </fieldset>


			<input type="hidden" name="submitted" value="1" />
		</div> <!-- rounded div -->

	</form>
</div>
