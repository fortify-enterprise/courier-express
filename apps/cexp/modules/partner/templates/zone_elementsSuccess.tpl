<div style="margin-left: 1em; margin-right: 1em">

	<form class="cmxform" method="post" action="{url_for name='partner/zone_elements'}">
	 	<div class="rounded_div">

			<fieldset>
    			<legend>{__ text='Edit zone elements'}:</legend>

					<ol>
						<li>
						</li>

						<li>
							<label style="width: 9em" for="zone_name">{__ text='select zone'}:</label>
							<select style="width: 10em" id="zone_name" name="zone_name">
								{html_options values=$zone_ids output=$zone_names selected=$zone_id}
							</select>
						</li>


						<li>
							<label style="width: 9em" for="zone_element_list">{__ text='element list'}:</label>
							<select style="width: 12.4em" id="zone_element_list" name="zone_element_list">
								{html_options values=$element_ids output=$element_names selected=$zone_element_list}
							</select>
						</li>


						<li>
							<label style="width: 9em" for="element">{__ text='element'}:</label>
							<input style="width: 12em" type="text" id="element" name="element" value="{$element}"/>
						</li>
 
						 <li>
							<label style="width: 9em" for="is_new_element">{__ text='New element?'}</label>
							 <input id="is_new_element" type="checkbox" name="is_new_element" {if $is_new_element} checked="checked"{/if}/>
						 </li>


						<li>
						</li>

						<li>
   						<button type="submit" id="login_button" class="fg-button ui-state-default ui-corner-all">{__ text='Save changes'}</button>
						</li>

            <li>
              <span class="highlight">{$message}</span>
            </li>


		  		</ol>
			  </fieldset>


		</div> <!-- rounded div -->
		<input type="hidden" name="submitted" value="1" />
		<input type="hidden" name="is_explorer" id="is_explorer" value="{$explorer}" />

	</form>

</div>
