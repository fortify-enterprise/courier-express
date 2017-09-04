<div style="margin-left: 1em; margin-right: 1em">

	<form class="cmxform" method="post" action="{url_for name='partner/zones'}">
	 	<div class="rounded_div">

			<fieldset>
    			<legend>{__ text='Create / edit zone'}:</legend>

					<ol>
						<li>
						</li>

            <li>
              <label style="width: 9em" for="zone_list">{__ text='Zone list'}:</label>
              <select style="width: 10em" id="zone_list" name="zone_list">
								{html_options values=$zone_ids output=$zone_names selected="1"}
              </select>
            </li>

            <li>
              <label style="width: 9em" for="zone_type">{__ text='Zone type'}:</label>
              <select style="width: 10em" id="zone_type" name="zone_type">
								{html_options values=$zone_type_ids output=$zone_type_names selected="1"}
              </select>
            </li>


						<li>
							<label style="width: 9em" for="zone_name">{__ text='zone name'}: </label>
							<input style="width: 9.8em" type="text" id="zone_name" name="zone_name"/>
						</li>

						<li>
							<label style="width: 9em" for="is_new_zone">{__ text='New zone?'}</label>
							<input type="checkbox" id="is_new_zone" name="is_new_zone"/>
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

		</div> <!-- rounded div -->
	</form>

</div>
