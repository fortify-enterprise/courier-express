<div style="margin-left: 1em; margin-right: 1em">

	<form id="discount_form" class="cmxform" method="post" action="{url_for name='partner/discounts'}">
	 	<div class="rounded_div" style='height: 45em'>

			<fieldset>
   			<legend>{__ text='Discounts'}</legend>

				<ol>
					<li>
					</li>

           <li>
             <label style="width: 19em" for="courier_name">{__ text='Percentage discount (% off shippments)'}:</label>
						<input type="text" style="width: 3em" id="discount_percentage"
						value="{$discounts.discount_percentage}" name="discount_percentage"/>
						<span class="white_text">%</span>
           </li>

					<li>
					</li>

					<li>
  					<button type="submit" id="login_button"
						class="fg-button ui-state-default ui-corner-all">{__ text='Save'}</button>
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
