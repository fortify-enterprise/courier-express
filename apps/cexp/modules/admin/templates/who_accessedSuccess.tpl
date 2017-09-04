<div style="margin-left: 2em; margin-right: 1em">


	<form id="admin_partner_form" class="cmxform" method="post" action="{url_for name='admin/index'}">

	 	<div class="rounded_div" style='height: 65em'>
			<fieldset>
    			<legend>{__ text='Who accessed site'}</legend>

				  		<ol>
								
								<li>	
									<br />
								</li>
								<li>

      {if $page_links|@sizeof > 1}
         <span>
           <span style="font-weight: bold">
             {__ text='Page'}:
             </span>&nbsp;&nbsp;
             {foreach name=outer item=link from=$page_links}
               <a href="{url_for name='admin/who_accessed'}?page={$link}">{$link}</a>&nbsp;
             {/foreach}
          </span>
        {/if}

								</li>
							</ol>
			  </fieldset>

			<table>
				<tr>
        <th style='text-align: center'>{__ text='Ip address'}</th>
        <th style='text-align: center'>{__ text='Country name'}</th>
        <th style='text-align: center'>{__ text='Region name'}</th>
        <th style='text-align: center'>{__ text='City'}</th>
        <th style='text-align: center'>{__ text='Time'}</th>
        </tr>

			{foreach from=$visitors item=visitor}
				<tr>
					<td style='padding: 5px; border:1px solid'>
					<a href='{url_for name='admin/who_accessed_detail'}/ip/{$visitor.ip}/page/{$current_page}'>{$visitor.ip}</a></td>
					<td style='padding: 5px; border:1px solid'>{$visitor.country_name|default:'-'}</td>
					<td style='padding: 5px; border:1px solid'>{$visitor.region_name|default:'-'}</td>
					<td style='padding: 5px; border:1px solid'>{$visitor.city|default:'-'}</td>
					<td style='padding: 5px; border:1px solid'>{$visitor.updated_ts|date_format:'%I:%M %p - %A, %B %e %Y'}</td>
				</tr>
			{/foreach}
			</table>

			{*
			<li>
   			<button type="submit" id="login_button"
				class="fg-button ui-state-default ui-corner-all">{__ text='Submit'}</button>
			</li>

      <li>
				{if $message}
				<span class="highlight">{$message}</span>
				{/if}
			</li>
		*}

		<input type="hidden" name="submitted" value="1" />
		</div> {* rounded div *}
	</form>
</div>

