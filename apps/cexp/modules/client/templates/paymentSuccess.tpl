<div style="margin-left: 1em; margin-right: 1em">

  <form id="mainform" class="cmxform" method="post" action="{url_for name='client/payment}">

    {if $in_process}
    <div class="rounded_div" style="margin: 0px auto; margin-bottom: 1em">
        {__ text='In process credit card registration.
        Please enter your credit card details to complete the delivery, or click back to the cart page'}
        <a href="{url_for name='cart/index'}">shopping cart</a>
    </div>
    {/if}

    {if $checkout_in_process}
    <div class="rounded_div" style="margin: 0px auto; margin-bottom: 1em">
        {__ text='Checkout credit card update.
        Please update your credit card details to complete the checkout
				'}
    </div>
		<input type='hidden' name='checkout_in_process' value='1' />
    {/if}


    <div class="rounded_div" style='width: 750px'>

        <fieldset>
  	    	<legend>{__ text='Billing information'}</legend>

          <ol>
          	
						<li style='font-size: 9px; color: #936334'>
				Note, as a part of PCI compliance we do not store any of your sensitive information on file<br />
				all of the credit card information is being securely stored remotely with a credit card processor
						</li>

						<li>
							<img src="/images/credit_cards/cards4.gif" alt="" />
						</li>

            {if $message}
							<li {if $resp.responseCode != 1} class='vertical_form_row form_row_error'{/if}>
             		<span class="highlight">{$message}</span>
         			</li>
           	{/if}

						{if $form->hasGlobalErrors()}
             <li>
								<span>{$form->renderGlobalErrors()}</span>
						</li>
						{/if}


            <li>
							{$form.CreditCardType.id->renderRow()}

              <div style="float: left">
								{$form.card_number->renderRow()}
							</div>

              <div style="float: left">
								{$form.exp_month->renderRow()}
              </div>

							<div style="float: left">
								{$form.exp_year->renderRow()}
							</div>

              <div>
								{$form.ccv_number->renderRow()}
              </div>
            </li>


            <li>
							<br />
						</li>

            <li>
								{$form.name->renderRow()}
								{$form.address1->renderRow()}
								{$form.address2->renderRow()}
								{$form.Address.Country.id->renderRow()}
	
								{$form.Address.postal_code->renderRow()}
	
								{$form.Address.city->renderRow()}
								{$form.Address.province_state_id->renderRow()}
            </li>


						<li>
					</li>

          <li>
            <button tabindex='100' type="submit" id="login_button"
						class="fg-button ui-state-default ui-corner-all">{__ text='Save changes'}</button>
          </li>


          </ol>

        </fieldset>
				
				{$form->renderHiddenFields()}

    </div> {* rounded div *}
  </form>
</div>
