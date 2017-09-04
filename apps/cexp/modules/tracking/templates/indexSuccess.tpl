<div style="margin-left: 1em; margin-right: 1em">

  <div class="rounded_div" style="width: 50em; height:42em">

    <form class="cmxform" method="post" action="{url_for name='tracking/index'}">
      <fieldset>
        <legend>{__ text='Tracking information'}</legend>
        <ol>
          
          <li>
            <span>{$form->renderGlobalErrors()}</span>
          </li>
          
          <li>
            {$form.shipment_number->renderRow()}
          </li>

          <li>
            <button class="fg-button ui-state-default ui-corner-all" 
            style="margin-left: 145px" type="submit">Submit</button>
            <a href="{url_for name='landing_page/index'}">main page</a>
          </li>

        </ol>
  
      </fieldset>
      {$form->renderHiddenFields()}

    </form>



    {if !$tracked_packages}
      <div class="white_text" style='margin-left: 10em'>{__ text='no packages found'}</div><br />
    {else}
    

    {section name=i loop=$tracked_packages}
      {if $tracked_packages[i].status == 'delivered'}
        {assign var='text_color' value='#CEF6CE'}
      {else}
        {assign var='text_color' value='#FAAC58} {/if}

          <div class="rounded_div" style="margin-top: 1em">
            <table class='tracking_info'>
              <tr style="margin-top: 1em;">
                <td>
                  <div class="white_text tracking_header">
                    {__ text='Package tracking number'}: {$tracked_packages[i].package_code}<br />
                    {__ text='From address'}: {$tracked_packages[i].from_address}<br />
                    {__ text='To address'}: {$tracked_packages[i].to_address}<br />
                    {__ text='Delivery status'}: {$tracked_packages[i].status}
                  </div>
                </td>
              </tr>
            </table>
          </div>
        {/section}
      {/if}
        
        <br />
        <br />
        {if $page_links|@sizeof > 1}
          <fieldset>
              <span>
                <span style="font-weight: bold">&nbsp;&nbsp;Page:</span>&nbsp;&nbsp;
                {foreach name=outer item=link from=$page_links}
                  <a href="{url_for name='tracking/index'}?page={$link}">{$link}</a>&nbsp;
                {/foreach}
              </span>
          </fieldset>
        {/if}

    </div>
</div>
