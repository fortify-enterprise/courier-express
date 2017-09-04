<div style="margin-left: 1em; margin-right: 1em">

  <form id="generic_form" class="cmxform" method="post" action="{url_for name='payment/index'}">
    <div class="rounded_div" style="margin: 0px auto; margin-bottom: 1em; height: 45em">



		{* Here we have all the packages listed *}
        <fieldset>
          <legend>{__ text='Items summary, total price:'}&nbsp;${math equation=$total_price format="%.2f}</legend>
            <ol>
              <li>
                <br />
              </li>

              <li>
              {if $current_packages|@sizeof > 0}
              <table  class="white_text" style="width: 750px;text-transform: none; text-align: center; font-weight: normal">
                <tr>
                  <th style='text-align: center'>{__ text='Package code'}</th>
                  <th style='text-align: center'>{__ text='From address'}</th>
                  <th style='text-align: center'>{__ text='To address'}</th>
                  <th style='text-align: center'>{__ text='Delivery company'}</th>
                  <th style='text-align: center'>{__ text='Price $CAN'}</th>
                </tr>

                <tr><td><br /></td></tr>

              {foreach from=$current_packages key=package_id item=package}
                <tr>
                  <td style="padding: 5px; width: 10%;border:1px solid;">
                   <span class="tooltip" style=" border-bottom: 1px dashed; color:"
                      title="Package details ({$package_id})"
                      rel="#">{$package_id}</span>
                  </td>

                  <td style="padding: 5px; width: 15%;border:1px solid;">
                    {$package.sender.apt_unit}&nbsp;
                    {$package.sender.street_number}&nbsp;
                    {$package.sender.street_name}&nbsp;
                    {$package.sender.postal_code}&nbsp;
                    {$package.sender.city}
                  </td>
                  <td style="padding: 5px; width: 15%;border:1px solid;">
                    {$package.recep.apt_unit}&nbsp;
                    {$package.recep.street_number}&nbsp;
                    {$package.recep.street_name}&nbsp;
                    {$package.recep.postal_code}&nbsp;
                    {$package.recep.city}
                  <td style="padding: 5px; width: 30%;border:1px solid;">{$package.couriers[0].name|default:'No courier available'}</td>
                  <td style="padding: 5px; width: 10%;border:1px solid;">{math equation=$package.couriers[0].price format="%.2f"}</td>
                </tr>
              {/foreach}
              </table>

              {if $page_links|@sizeof > 1}
          			<fieldset>
          				<ol>
               	 		<li>
                			<span>
                   			<span style="font-weight: bold">{__ text='Page'}:</span>&nbsp;&nbsp;
                     			{foreach name=outer item=link from=$page_links}
                     			<a href="{url_for name='checkout/index'}?page={$link}">{$link}</a>&nbsp;
                     			{/foreach}
                  			</span>
                		</li>
          				</ol>
          			</fieldset>
              {/if}


              <br />

              {else}
                <span class="white_text">{__ text='No packages in your shopping cart'}</span>
              {/if}

            </li>
          </ol>
        </fieldset>


		{* end of packages listing *}


    <div  style="margin: 0px auto;">
      <fieldset>
          <legend>{__ text='Payment information'}</legend>

          <ol>
            <li>
            </li>

            <li>

							<div class="rounded_div credit_card_info">
              	<input style='width: 15px' type="radio" value="direct_payment" id="credit_card" name="payment_type" checked="checked"/>
              	<label for="credit_card" class="white_text">{__ text='Credit Card Payment'}</label>&nbsp;&nbsp;
								<br /><br />
								
									Card owner: {$payment_profile.name}<br />
									{$payment_profile.address1} {$payment_profile.address2}
									{$payment_profile.Address.postal_code|upper}  {$payment_profile.Address.city}
									<br /><br />
									Credit card: {$payment_profile.card_number} / Exp:
									{$payment_profile.exp_month} /{$payment_profile.exp_year}
				


								<br />
								<br />
								<a href='{url_for name='client/payment'}?checkout_in_process=1'>Edit card</a><br /><br />
								<img src="/images/credit_cards/credit_card_logos_43.gif" alt="" />
							</div>

            </li>

            <li>
						</li>

          </ol>

        </fieldset>
      </div>

    </div>

    <div>
     <button class="fg-button ui-state-default ui-corner-all"
      onclick='window.location="{url_for name='cart/index' default='true'}"; return false;'>
      {__ text='&larr; Back to cart'}</button>

			<button id="submit_form" type="submit" class="fg-button ui-state-default ui-corner-all">{__ text='Place order'}</button>
      <input type="hidden" id="payment_page" name="payment_page" value="1" />
    </div>

  </form>
</div>
