<div style="margin-left: 1em; margin-right: 1em">

  <form class="cmxform" method="post" action="{url_for name='client/payment'}">
    <div class="rounded_div" style="margin: 0px auto; margin-bottom: 1em;width: 60em">

			<div style="margin: 0px auto; margin-bottom: 1em;width: 52em">

        <fieldset>
          <legend>{__ text='Payment options'}:</legend>

          <ol>
            <li></li>

						{* credit card exists on file *}

						{if $client_payment.card_number}

      	      <li>
								<input type="radio" name="credit_card" value="on_file" checked="checked"/>
								<span class="white_text">&nbsp;&nbsp;credit card on file: {$client_payment.card_number}</span>
							</li>

							<li>
								<img src="/images/credit_cards/cards4.gif" alt="" />
								<hr /><br />
							</li>

						{/if}

						<li>
							<input type="radio" name="credit_card" value="new" {if !$client_payment.card_number} checked="chcked" {/if}/>
							<span class="white_text">&nbsp;&nbsp;new credit card:</span>
						</li>


            <li>
              <div style="float: left; margin-right: {$dis}">
                <label for="payment_card_type">credit cart type:</label><br />
            		<select name="payment[card_type]" id="payment_card_type">
                	<option value="Visa">Visa</option>
                	<option value="MasterCard">Mater Card</option>
                	<option value="Amex">American Express</option>
                	<option value="Discover">Discover Card</option>
								</select>
              </div>

              <div style="float: left; margin-right: {$dis}">
                <label for="payment_card_number">credit card number: </label><br />
                <input style="width: 12em;" type="text" id="payment_card_number" value="{$payment.card_number}" name="payment[card_number]"/>
              </div>

              <div style="float: left; margin-right: {$dis}">
                <label for="exp_month">Exp month:</label><br />
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
								<label for="exp_year">Exp year:</label><br />
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
                <label for="payment_ccv_number">ccv number: </label><br />
                <input style="width: 5em;" type="text" id="payment_ccv_number" value="{$payment.ccv_number}" name="payment[ccv_number]"/>
              </div>
            </li>

					</ol>
				</fieldset>


			<fieldset>
  	    <legend>billing information:</legend>

          <ol>
            <li>
							<hr />
						</li>

            <li>
              <div style="float: left; margin-right: {$dis}">
                <label for="payment_first_name">First name:</label><br />
                <input style="width: 10em;" type="text" value="{$payment.first_name}" id="payment_first_name" name="payment[first_name]"/>
              </div>

              <div style="margin-right: {$dis}">
                <label for="payment_last_name">Last name:</label><br />
                <input style="width: 10em;" type="text" value="{$payment.last_name}" id="payment_last_name" name="payment[last_name]"/>
              </div>

            </li>

            <li>
              <div>
                <label for="payment_addr1">Street address 1: </label><br />
                <input style="width: 21em;" type="text" value="{$payment.addr1}" id="payment_addr1" name="payment[addr1]"/>
              </div>

            </li>

            <li>
              <div>
                <label for="payment_addr2">Street address 2: </label><br />
                <input style="width: 30.75em;" type="text" value="{$payment.addr2}" id="payment_addr2" name="payment[addr2]"/>
              </div>
            </li>

            <li>
                <div style="float: left; margin-right: {$dis}">
                  <label for="payment_city">city: </label><br />
                  <input style="width: 15em" type="text" value="{$payment.city}" id="payment_city" name="payment[city]"/>
                </div>

                <div>
                	<label for="payment_state_province_id">Province / state: </label><br />
                 	<select style="width: 15em;" name="payment[state_province_id]" id="payment_state_province_id">
                   	{html_options values=$province_ids output=$province_names selected=$payment.state_province_id}
                 	</select>
                </div>

            </li>


						<li>
                <div style="float: left; margin-right: {$dis}">
                  <label for="payment_postal_code">postal / zip code: </label><br />
                  <input type="text" maxlength="10" style="width: 15em;" value="{$payment.postal_code}"
                         id="payment_postal_code" name="payment[postal_code]"/>
                </div>

							 <div  style="margin-right: {$dis}">
                  <label for="payment_country_id">country: </label><br />
                  <select style="width: 15em;" id="payment_country_id" name="payment[country_id]">
                    {html_options values=$country_ids output=$country_names selected="$payment.country_id"}
                  </select>
               </div>
						</li>

            <li>
               {if $message}
                <span class="highlight">{$message}</span>
              {/if}
            </li>

          </ol>

        </fieldset>
			</div>
    </div>

			<div style="text-align: center;">
        <button type="submit" id="login_button" class="fg-button ui-state-default ui-corner-all">Complete order</button>
				<a href="/prices"><button onclick='location.href="/checkout/index"; return false;' id="back_to_prices" class="fg-button ui-state-default ui-corner-all">Back</button></a>
			</div>
  </form>
</div>

