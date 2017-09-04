<div style="margin-left: 1em; margin-right: 1em">

  <div class="rounded_div" style='height: 45em'>

    <form class="cmxform" method="post"
					action="{url_for name='form/partner_contact'}" style="float: left; margin-right: 100px;">

		<fieldset>
       <legend>{__ text='Partnership information request'}</legend>
          <ol>
						<li>
							<span>{$form->renderGlobalErrors()}</span>
						</li>
            <li>
							{$form.name->renderRow()}
            </li>
						<li>
							{$form.company->renderRow()}
						</li>
						<li>
							{$form.phone->renderRow()}
						</li>
						<li>
							{$form.email->renderRow()}
            </li>

            <li>
								{$form.details->renderRow()}
            </li>

            <li>
              <button tabindex='15' class="fg-button ui-state-default ui-corner-all" 
							style="margin-left: 145px" type="submit">Submit</button>
              <a href="{url_for name='landing_page/index'}">main page</a>
						</li>
          </ol>

        </fieldset>
				{$form->renderHiddenFields()}
    </form>
	</div>
</div>
