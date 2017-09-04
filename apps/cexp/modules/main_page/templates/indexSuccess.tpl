<div style="margin-left: 1em; margin-right: 1em;">

  <form id="mainform" class="cmxform" method="post" action="{url_for name='main_page/index'}">


    <div class="rounded_div" style="margin: 0px auto; float: left; margin-bottom: 1em">

      <div id="delivery_type_div">

         <fieldset>
          <legend {if $edit_package != ""}class='edit_package'{/if}>
          &nbsp;{__ text='Delivery Details'}{if $edit_package}&nbsp;[{$edit_package}]{/if}</legend>
            <ol>

              <li>
                <br />
                {if $cant_be_delivered}
                  <div class='info_section_error ui-corner-all' style='width: 470px'>{$cant_be_delivered}</div>
                {/if}
 
                {if $form->hasGlobalErrors()}
                  <div class='info_section_error ui-corner-all' style='width: 470px'>{$form->renderGlobalErrors()}</div>
                {/if}
                <br />
              </li>

							<li>
               {$form.PackageDetail.DeliveryType.id->renderRow()}
							</li>

							<li>
                <div style='float: left'>
                  {$form.PackageDetail.ServiceLevelType.id->renderRow()}
                </div>

                <div style="float: left">
                  {$form.PackageDetail.ready_date->renderRow()}
                </div>

                {$form.PackageDetail.ready_time->renderRow()}
              <br />
							</li>

							<li>
              	<div style='clear: both; width: 210px' class='info_section ui-corner-all'>
                <div>{__ text='Expected Delivery'}:&nbsp;</div>
                <div style='font-weight: strong' id="delivered_by"></div>
              	</div>

							</li>

          </ol>
        </fieldset>
      </div>


    <br />

    <div style="margin: 0px auto;">
      <div style="float: left">
       
        <fieldset style="width: 24em">
           <legend {if $edit_package != ""}class='edit_package'{/if}>
           &nbsp;{__ text='Pickup At'}</legend>

             <ol>
             
              <li>
                <br /><br />
              </li>

              <li>
                <div style='float: left'>
                  {$form.PackageDetail.sender_contact->renderRow()}
                </div>
                <div>
                  {$form.PackageDetail.sender_phone->renderRow()}
                </div>
              </li>

              <li>
                {$form.sender.apt_unit->renderRow()}
                <div style='float: left'>
                  {$form.sender.street_number->renderRow()}
                </div>
                <div>
                  {$form.sender.street_name->renderRow()}
                </div>
              </li>

              <li>
                {$form.sender.Country.id->renderRow()}
                {$form.sender.postal_code->renderRow()}
              </li>

              <li>
                {$form.sender.city->renderRow()}
              </li>

              <li>
                {$form.sender.Province.id->renderRow()}
              </li>


              <li>
              </li>
             </ol>
          </fieldset>
      </div>


      <div>

        <fieldset style="width: 24em">
          <legend {if $edit_package != ""}class='edit_package'{/if}>
          &nbsp;{__ text='Deliver To'}</legend>

            <ol>
              <li>
                <br /><br />
              </li>

              <li>
                <div style='float: left'>
                  {$form.PackageDetail.contact->renderRow()}
                </div>
                <div>
                  {$form.PackageDetail.phone->renderRow()}
                </div>
              </li>

              <li>
                {$form.recep.apt_unit->renderRow()}
                <div style='float: left'>
                  {$form.recep.street_number->renderRow()}
                </div>
                <div>
                  {$form.recep.street_name->renderRow()}
                </div>
              </li>

              <li>
                <div style='float: left'>
                  {$form.recep.Country.id->renderRow()}
                </div>
                <div>
                  {$form.recep.postal_code->renderRow()}
                </div>
              </li>

              <li>
                {$form.recep.city->renderRow()}
              </li>

              <li>
                 {$form.recep.Province.id->renderRow()}
              </li>


              <li>
              </li>
            </ol>

          </fieldset>

      </div>
    </div>

    <br />



    {* parcel details div *}


      <div id="parcel_details_inner1" style="margin: 0px auto;">

        <fieldset>
          <legend {if $edit_package != ""}class='edit_package'{/if}>Parcel Details</legend>

          <ol>

            <li>
              <br /><br />
            </li>

            <li>
              <div style="float: left">
                {$form.PackageDetail.PackageType.id->renderRow()}
              </div>

              <div style="float: left">
                {$form.PackageDetail.weight->renderRow()}
              </div>

              <div style="float: left">
                {$form.PackageDetail.weight_type_id->renderRow()}
              </div>

              <div>
                {$form.PackageDetail.num_pieces->renderRow()}
              </div>
            </li>

            <li>
              <br />
              <br />
              <div style="clear: left; float: left">
                {$form.PackageDetail.reference->renderRow()}
              </div>

              <div>
                {$form.PackageDetail.round_trip->renderRow()}
              </div>
            </li>

            <li>
              <br />
              <br />
              <div>
                {$form.PackageDetail.instructions->renderRow()}
              </div>
            </li>

          </ol>
        </fieldset>

      </div>
      <br />

      <div id="submit_buttons_div" style="margin-bottom: 2em">
        <button tabindex='100' id="get_prices_button" type="submit" class="fg-button ui-state-default ui-corner-all">
          {if $edit_package == ""}
            {__ text='Add to shopping cart'}
          {else}
            {__ text='Update shopping cart'}
          {/if}
        </button>

        <button tabindex='102' class="fg-button ui-state-default ui-corner-all"
        onclick='window.location="{url_for name='main_page/index' default='true'}"; return false;'>
        {__ text='New package'}</button>

        {if $packages_cart}
          <button tabindex='102' class="fg-button ui-state-default ui-corner-all"
          onclick='window.location="{url_for name='checkout/index' default='true'}"; return false;'>
          {__ text='Checkout &rarr;'}</button>
        {/if}

      </div>

    </div>


    <div>
      <fieldset style='height: 550px; width: 170px; border: 1px solid #936334'>
        <legend>Shopping cart</legend>

          <li>
            <br />
          </li>

          <li>


          <a style='margin-left: 5px' href="{url_for name='cart/index'}">{__ text='Shopping cart'}</a>
          <span>({$packages_cart|@sizeof} {__ text='items'})</span><br />
          {if $total_price > 0}
          <br /><span class='info_section ui-corner-all'>Total price: ${math equation=$total_price format="%.2f"} CAN</span><br />{/if}<br />
          {if $packages_cart|@sizeof == 0}
            <a style='margin-left: 5px' href="{url_for name='cart/index'}"><img src="/images/cart/cart2.png" alt="" /></a>
          {else}
            <a style='margin-left: 5px' href="{url_for name='cart/index'}"><img src="/images/cart/cart_with_item_small.png" alt="" /></a>
          {/if}



          </li>

          {if $packages_cart}

            <br />
            {foreach from=$current_packages key=package_id item=package}
              <li style='padding: 5px' class='{if $edit_package == $package_id}edit_package{else}default_package{/if}'>

                {__ text='Package'}:
                <a class="tooltip"
                href='{url_for name='main_page/index?edit_package='}{$package_id}'>Edit</a>
                {$package_id}<br />
                {__ text='To name'}: {$package.PackageDetail.contact|default:'Not specified'}<br />
                {__ text='Phone'}: {$package.PackageDetail.phone|default:'Not specified'}
                <br /><br />
              </li>
            {/foreach}

          {/if}

          {if $page_links|@sizeof > 1}
          <li>
            <span>
              <span style="font-weight: bold">
                {__ text='Page'}:
              </span>&nbsp;&nbsp;
              {foreach name=outer item=link from=$page_links}
                <a href="{url_for name='main_page/index?page='}{$link}">{$link}</a>&nbsp;
              {/foreach}
            </span>
          </li>
          {/if}

      </fieldset>

    </div>


    <div>
      {$form->renderHiddenFields()}
      <input type="hidden" id="edit_package" name="edit_package" value="{$edit_package}" />
      <input type="hidden" id="username" value="{$userid_enc}" />
      <input type="hidden" id="main_page" value="1" />
    </div>
  </form>
</div>

