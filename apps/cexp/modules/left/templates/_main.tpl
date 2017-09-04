<div id="left_items" class="rounded_div" style="margin-top: 12.1em; height: 43.2em">

  {if $page_links|@sizeof > 1}
    <fieldset>
       <li>
        <span>
          <span style="font-weight: bold">{__ text='Page'}:</span>&nbsp;&nbsp;
           {foreach name=outer item=link from=$page_links}
             <a style="color: #F5D0A9" href="{url_for name='main_page/index'}?page={$link}">{$link}</a>&nbsp;
           {/foreach}
          </span>
       </li>
     </fieldset>
   {/if}

   <table>
     <tr>
       <td>

					<a href="{url_for name='cart/index'}">{__ text='Shopping cart'} &nbsp;
<span style="color: #E6E4C9">({$packages_cart|@sizeof} {__ text='items'})</span><br />
{if $packages_cart|@sizeof == 0}
<img src="/images/cart/cart2.png" alt="" />
{else}
<img src="/images/cart/cart_with_item_small.png" alt="" />
{/if}
</a>


          </td>
        </tr>

				<tr>
					<td>
						<br />
					</td>
				</tr>

  {if $packages_cart}

        {foreach name=outer item=package from=$current_packages}
          <tr>
            <td style="padding: 3px; width: 20%;">
              <a class="tooltip" href='{url_for name='main_page/index?edit_package='}{$package.package_id}'
                 style="font-family:Trebuchet MS; border-bottom: 1px dashed; color: #E6E4C9; font-weight: normal;"
                 title="{__ text='Package details'}
								 ({$package.package_id})" rel="#" href="#">
								 {__ text='To'}:
								 {$package.to_address}
								 ({__ text='edit'})</a>
								 <br />
							 <br />
            </td>
          </tr>
        {/foreach}

  {/if}

      </table>
    </div>
