<div style="margin-left: 1em; margin-right: 1em">

	<form class="cmxform" method="post" action="{url_for name='partner/service_levels'}">
	 	<div class="rounded_div" style='height: 45em'>
			<fieldset>
    			<legend>{__ text='Service levels'}</legend>

					<ol>
						<li>
						</li>

						{section name=i loop=$ids}
							<li>
								<label style="width: 11em" for="service_levels">{$types[i]}: </label>
								<input type="checkbox" style='width: 10px' value="{$ids[i]}" name="service_levels[]" {if $enabled[i] == 1} checked="checked"{/if}/>
							</li>
						{/section}

						<li>
						</li>

						<li>
   						<button type="submit" id="login_button" class="fg-button ui-state-default ui-corner-all">{__ text='Save'}</button>
						</li>

            <li>
              <span class="highlight">{$message}</span>
            </li>

		  		</ol>
			  </fieldset>

		</div> {* rounded div *}
		<input type="hidden" name="submitted" value="1" />
	</form>
</div>
