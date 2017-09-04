<div style="margin-left: 1em; margin-right: 1em">

  <form id="mainform" class="cmxform" method="post" action="{url_for name='cart/index'}">

    <div class="rounded_div" style="height: 45em">

      <span class="white_text">{$error_string}</span>
      <div id="delivery_type_div firescope_grid" style="margin: 0px auto">
        <fieldset>
          <legend>{__ text='Shopping cart packages'}</legend>
  
          <li>
            <br />
          </li>

          <ol>

            <li style="text-align: center">

              {if $current_packages|@sizeof > 0}
              <table  class="white_text" style="width: 750px;text-transform: none; font-weight: normal">
                <tr>
                  <th style='text-align: center'>{__ text='Package code'}</th>
                  <th style='text-align: center'>{__ text='From address'}</th>
                  <th style='text-align: center'>{__ text='To address'}</th>
                  <th style='text-align: center'>{__ text='Delivery company'}</th>
                  <th style='text-align: center'>{__ text='Delete'}</th>
                </tr>

                <tr><td><br /></td></tr>

              {foreach from=$current_packages key=package_id item=package}
                <tr>
                  <td style="padding: 5px; width: 21%;border:1px solid;">
                   <a class="tooltip" style=" border-bottom: 1px dashed; color:"
                      title="Package details ({$package_id})"
                      rel="#" href="{url_for name='main_page/index'}?edit_package={$package_id}"
                      >{$package_id} ({__ text='edit'})</a>
                  </td>

                  <td style="padding: 5px; width: 22%;border:1px solid;">
                    {$package.sender.apt_unit}&nbsp;
                    {$package.sender.street_number}&nbsp;
                    {$package.sender.street_name}&nbsp;
                    {$package.sender.postal_code}&nbsp;
                    {$package.sender.city}
                  </td>
                  <td style="padding: 5px; width: 22%;border:1px solid;">
                    {$package.recep.apt_unit}&nbsp;
                    {$package.recep.street_number}&nbsp;
                    {$package.recep.street_name}&nbsp;
                    {$package.recep.postal_code}&nbsp;
                    {$package.recep.city}
                  </td>
                  <td style="padding: 5px; width: 30%;border:1px solid;">
									
										{* create dropdown *}
										{html_options }
										<select onchange="this.form.submit();" style='width: 100%' name="courier_selection_{$package_id}">
											{foreach from=$package.couriers key=courier_id item=courier}
												<option label="${math equation=$courier.price format="%.2f"} - {$courier.name}"
												value="{$courier.id}">${math equation=$courier.price format="%.2f"} - {$courier.name}</option>
											{/foreach}
										</select>
									</td>

                  <td style="padding: 5px; width: 5%;border:1px solid;">
                    <input type="checkbox" name="remove_packages[]" value="{$package_id}" />
                  </td>
                </tr>

              {/foreach}
              </table>
              
             {else}
                <div style='text-align: left; margin-top: 5em; margin-left: 24em; width: 150px'
                class="info_section ui-corner-all">{__ text='No packages in your shopping cart'}</div>
              {/if}

            </li>

            {if $page_links|@sizeof > 1}
            <li>
              <span>
                <span style="font-weight: bold">{__ text='Page'}:</span>&nbsp;&nbsp;
                  {foreach name=outer item=link from=$page_links}
                    <a href="{url_for name='cart/index'}?page={$link}">{$link}</a>&nbsp;
                  {/foreach}
              </span>
            </li>
            {/if}


            <li>
              <br />
            </li>

          </ol>
        </fieldset>
           
        {if $current_packages|@sizeof > 0}
          {* add the bottom total price bar *}
          <fieldset>
            <legend>Total price: ${math equation=$total_price format="%.2f"}</legend>
          </fieldset>
          <br /><br />
				{/if}
      </div>


    {if $current_packages|@sizeof > 0}
    <div id="submit_buttons_div">

      <button class="fg-button ui-state-default ui-corner-all"
      onclick='window.location="{url_for name='main_page/index' default='true'}"; return false;'>
      {__ text='&larr; Back'}</button>

      <button type='submit' class="fg-button ui-state-default ui-corner-all">{__ text='Update cart'}</button>

      <button class="fg-button ui-state-default ui-corner-all"
      onclick='window.location="{url_for name='cart/empty' default='true'}"; return false;'>
      {__ text='Empty cart'}</button>

      <button class="fg-button ui-state-default ui-corner-all"
      onclick='window.location="{url_for name='checkout/index' default='true'}"; return false;'>
      {__ text='Checkout &rarr;'}</button>


      {if $message}
        <div class="highlight" style="margin-top: 1em">{$message}</div>
      {/if}

    </div>
    {/if}



    </div> {* rounded div *}




    <input type="hidden" value="1" name="submitted" />
  </form>
</div>

