<div style="margin-left: 1em; margin-right: 1em;">

  <form id="mainform" class="cmxform" method="post"
		action="{url_for name='register/client'}{if $in_process}/in_process/1{/if}">

		<input type="hidden" name="in_process" value="{$in_process}" />



		{if $in_process}
    <div class="rounded_div" style="margin: 0px auto; margin-bottom: 1em">
				{__ text='In process registration.
        Please enter registration details in order to complete the delivery or click'}
        <a href="{url_for name='auth/index'}?in_process=1">login</a> to existing account
		</div>
		{/if}
  
  
    <div class="rounded_div" style="width: 54em">
      <div style="margin: 0px auto; margin-bottom: 1em">

			<span>{$form->renderGlobalErrors()}</span>

      <div style="float: left">

        <fieldset style="width: 27em;">
           <legend>&nbsp;{__ text='contact information'}:</legend>

             <ol>
             
								{$form.ClientDetail.name->renderRow()}

              <div>
								<div style='float: left'>{$form.Address.apt_unit->renderRow()}</div>
								<div>{$form.Address.street_number->renderRow()}</div>
								<div>{$form.Address.street_name->renderRow()}</div>
              </div>

								{$form.Address.Country.id->renderRow()}
								{$form.Address.postal_code->renderRow()}

              <div>
								{$form.Address.city->renderRow()}
              </div>

              <li>
								{$form.Address.Province.id->renderRow()}
              </li>

             </ol>
          </fieldset>
      
      </div>


      <div style='width: 27em; margin-left: 30em'>

          <fieldset>
            <legend>&nbsp;{__ text='sender contact details'}</legend>
							<ol>
								<div>
									{$form.ClientDetail.contact->renderRow()}
									{$form.ClientDetail.phone->renderRow()}
									{$form.ClientDetail.email->renderRow()}
								</div>
							</ol>
					</fieldset>

          <fieldset>
            <legend>&nbsp;{__ text='client login details'}</legend>

              <ol>

								<div>
									{$form.ClientLogin.email->renderRow()}
									<div style='float: left'>{$form.ClientLogin.password->renderRow()}</div>
									<div>{$form.ClientLogin.password_again->renderRow()}</div>
									<div>{$form.ClientDetail.how_did_u_hear->renderRow()}</div>
							</div>

              </ol>
            </fieldset>
						{$form->renderHiddenFields()}
				</div>
    	</div>

    	<div id="submit_buttons_div" style="margin-bottom: 2em">
       	<button tabindex='100' id="get_prices_button" type="submit"
			 		class="fg-button ui-state-default ui-corner-all">{__ text='Register'}</button>
       	<a tabindex='101' href='{url_for name='landing_page/index'}'>main page</a>
    	</div>
	

    </div>

  </form>
</div>
