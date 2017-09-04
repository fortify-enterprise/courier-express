<div style="margin-left: 2em; margin-right: 1em">


	<form id="admin_partner_form" class="cmxform" method="post" onsubmit='return false;' action="{url_for name='admin/who_accessed'}">

	 	<div class="rounded_div" style='height: 65em'>
			<fieldset>
    			<legend>{__ text='Access detail for ip '}{$ip}</legend>

				  		<ol>
								
			<li>
   			<button onclick='window.location = "{url_for name="admin/who_accessed"}/page/{$current_page}"'
				id="login_button"
				class="fg-button ui-state-default ui-corner-all">{__ text='Back'}</button>
			</li>

							</ol>
			  </fieldset>

			<table>
				<tr>
        <th style='text-align: center'>{__ text='Ip address'}</th>
        <th style='text-align: center'>{__ text='Country name'}</th>
        <th style='text-align: center'>{__ text='Region name'}</th>
        <th style='text-align: center'>{__ text='City'}</th>
        <th style='text-align: center'>{__ text='Agent'}</th>
        <th style='text-align: center'>{__ text='Time'}</th>
        </tr>

			{foreach from=$visitors item=visitor}
				<tr>
					<td style='padding: 5px; border:1px solid'>{$visitor.ip}</td>
					<td style='padding: 5px; border:1px solid'>{$visitor.country_name|default:'-'}</td>
					<td style='padding: 5px; border:1px solid'>{$visitor.region_name|default:'-'}</td>
					<td style='padding: 5px; border:1px solid'>{$visitor.city|default:'-'}</td>
					<td style='padding: 5px; border:1px solid'>{$visitor.agent|default:'-'}</td>
					<td style='padding: 5px; border:1px solid'>{$visitor.updated_ts|date_format:'%I:%M %p - %A, %B %e %Y'}</td>
				</tr>
			{/foreach}
			</table>

		</div> {* rounded div *}
	</form>
</div>

