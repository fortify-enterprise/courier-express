<div style="margin-left: 1em; margin-right: 1em">

  <form id="mainform" class="cmxform" method="post" action="{url_for name='partner/payment'}">
    <div class="rounded_div">

        <fieldset>
          <legend>{__ text='payment options'}:</legend>

          <ol>
            <li></li>
						<li>
							<img src="/images/credit_cards/cards4.gif" alt="" />
						</li>
            <li></li>

            <li>
              <div style="float: left; margin-right: {$dis}">
                <label for="payment_card_type">{__ text='credit cart type'}:</label><br />
            		<select name="payment[card_type]" id="payment_card_type">
                	<option value="Visa">Visa</option>
                	<option value="MasterCard">Mater Card</option>
                	<option value="Amex">American Express</option>
                	<option value="Discover">Discover Card</option>
								</select>
              </div>

              <div style="float: left; margin-right: {$dis}">
                <label for="payment_card_number">{__ text='credit card number'}: </label><br />
                <input style="width: 12em;" type="text" id="payment_card_number" value="{$payment.card_number}" name="payment[card_number]"/>
              </div>

              <div style="float: left; margin-right: {$dis}">
                <label for="exp_month">{__ text='Exp month'}:</label><br />
								<select id="exp_month" name="payment[exp_month]" style="width: 5em">
               		<option value="01">01</option>
                	<option value="02">02</option>
               		<option value="03">03</option>
                	<option value="04">04</option>
                	<option value="05">05</option>
                	<option value="06">06</option>
               		<option value="07">07</option>
                	<option value="08">08</option>
                	<option value="09">09</option>
                	<option value="10">10</option>
                	<option value="11">11</option>
                	<option value="12">12</option>
								</select>
 
              </div>

							<div style="float: left; margin-right: {$dis}">
								<label for="exp_year">{__ text='Exp year'}:</label><br />
              	<select name="exp_year" name="payment[exp_year]" style="width: 5em">
                  <option value="2009">2009</option>
                  <option value="2010">2010</option>
                  <option value="2011">2011</option>
                  <option value="2012">2012</option>
                  <option value="2013">2013</option>
                  <option value="2014">2014</option>
                  <option value="2015">2015</option>
                  <option value="2016">2016</option>
                  <option value="2017">2017</option>
                  <option value="2018">2018</option>
                  <option value="2019">2019</option>
                  <option value="2020">2020</option>
                  <option value="2021">2021</option>
								</select>
							</div>

              <div style="margin-right: {$dis}">
                <label for="payment_ccv_number">{__ text='ccv number'}: </label><br />
                <input style="width: 5em;" type="text" id="payment_ccv_number" value="{$payment.ccv_number}" name="payment[ccv_number]"/>
              </div>
            </li>

					</ol>
				</fieldset>


			<fieldset>
  	    <legend>{__ text='billing information'}:</legend>

          <ol>
            <li>
							<hr />
						</li>

            <li>
              <div style="float: left; margin-right: {$dis}">
                <label for="payment_first_name">{__ text='First name'}:</label><br />
                <input style="width: 10em;" type="text" value="{$payment.first_name}" id="payment_first_name" name="payment[first_name]"/>
              </div>

              <div style="margin-right: {$dis}">
                <label for="payment_last_name">{__ text='Last name'}:</label><br />
                <input style="width: 10em;" type="text" value="{$payment.last_name}" id="payment_last_name" name="payment[last_name]"/>
              </div>

            </li>

            <li>
              <div>
                <label for="payment_addr1">{__ text='Street address 1'}: </label><br />
                <input style="width: 21em;" type="text" value="{$payment.addr1}" id="payment_addr1" name="payment[addr1]"/>
              </div>

            </li>

            <li>
              <div>
                <label for="payment_addr2">{__ text='Street address 2'}: </label><br />
                <input style="width: 30.75em;" type="text" value="{$payment.addr2}" id="payment_addr2" name="payment[addr2]"/>
              </div>
            </li>

            <li>
                <div style="float: left; margin-right: {$dis}">
                  <label for="payment_city">{__ text='city'}: </label><br />
                  <input style="width: 15em" type="text" value="{$payment.city}" id="payment_city" name="payment[city]"/>
                </div>

                <div>
                	<label for="payment_state_province_id">{__ text='province / state'}: </label><br />
                 	<select style="width: 15em;" name="payment[state_province_id]" id="payment_state_province_id">
                   	{html_options values=$province_ids output=$province_names selected=$payment.state_province_id}
                 	</select>
                </div>

            </li>


						<li>
                <div style="float: left; margin-right: {$dis}">
                  <label for="payment_postal_code">{__ text='postal / zip code'}: </label><br />
                  <input type="text" maxlength="10" style="width: 15em;" value="{$payment.postal_code}"
                         id="payment_postal_code" name="payment[postal_code]"/>
                </div>

							 <div  style="margin-right: {$dis}">
                  <label for="payment_country_id">{__ text='country'}: </label><br />
                  <select style="width: 15em;" id="payment_country_id" name="payment[country_id]">
                    {html_options values=$country_ids output=$country_names selected="$payment.country_id"}
                  </select>
               </div>
						</li>



						<li>
							<hr />
						</li>

						<li>
							<div>
								<input type="checkbox" value="{$payment.is_default}" name="payment[is_default]" />&nbsp;&nbsp;
								<label for="payment_is_default">{__ text='Use as default payment mehtod?'}</label>
							</div>
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
  </form>
</div>

