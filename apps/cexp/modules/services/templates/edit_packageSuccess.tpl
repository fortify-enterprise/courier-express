<div style="margin-left: 1em; margin-right: 1em">
  <div class="rounded_div" style='height: 45em'>
	  <form class="cmxform" method="post" onsubmit="return confirmSubmit();" action="{url_for name='services/edit_package'}"
					style="float: left; margin-right: 100px;">

			<input type='hidden' name='package_code' value='{$package_code}' />
			<input type='hidden' name='cancel_order' value='1' />

   		<fieldset>
      	<legend>Package order details [{$package_code}]</legend>
        	<ol>

						<li>
						</li>

            <li>

							<table style='font-family: "Lucida Grande","Bitstream Vera Sans",Verdana,Arial,sans-serif; text-align: left'>
  <tr>
    <td>Client name</td>
    <td style='padding-left: 3em'>{$info.name|default:'Unknown'}</td>
  </tr>

  <tr>
    <td>Client email</td>
    <td style='padding-left: 3em'>{$info.email|default:'Unknown'}</td>
  </tr>

	<tr>
		<td><br /></td>
		<td></td>
	</tr>

  <tr>
    <td>Client address</td>
    <td style='padding-left: 3em'>{$info.client_address|default:'Unknown'}</td>
  </tr>

  <tr>
    <td>Source address</td>
    <td style='padding-left: 3em'>{$info.from_address|default:'Unknown'}</td>
  </tr>

  <tr>
    <td>Destination address</td>
    <td style='padding-left: 3em'>{$info.to_address|default:'Unknown'}</td>
  </tr>

	<tr>
		<td><br /></td>
		<td></td>
	</tr>

  <tr>
    <td>Ready time</td>
    <td style='padding-left: 3em'>{$info.package_datetime|date_format:'%I:%M %p - %A %B %e %Y'}</td>
  </tr>

	<tr>
		<td><br /></td>
		<td></td>
	</tr>


  <tr>
    <td>Number of pieces</td>
    <td style='padding-left: 3em'>{$info.num_pieces|default:'Not specified'}</td>
  </tr>

  <tr>
    <td>Weight</td>
    <td style='padding-left: 3em'>{$info.weight|default:'Unknown'}&nbsp;{$info.weight_type}</td>
  </tr>

  <tr>
    <td>Reference</td>
    <td style='padding-left: 3em'>{$info.reference|default:'Unknown'}</td>
  </tr>

  <tr>
    <td>Package type</td>
    <td style='padding-left: 3em'>{$info.package_type|default:'Standard parcel'}</td>
  </tr>

  <tr>
    <td>Service level type</td>
    <td style='padding-left: 3em'>{$info.service_level_type|default:'Unknown'}</td>
  </tr>

  <tr>
    <td>Delivery type</td>
    <td style='padding-left: 3em'>{$info.delivery_type|default:'Unknown'}</td>
  </tr>

  <tr>
    <td>Round trip</td>
    <td style='padding-left: 3em'>{$info.round_trip|default:'Unknown'}</td>
  </tr>

  <tr>
    <td>Instructions</td>
    <td style='padding-left: 3em'>{$info.instructions|default:'Not specified'}</td>
  </tr>

  <tr>
    <td>Signed by</td>
    <td style='padding-left: 3em'>{$info.signed_by|default:'Not specified'}</td>
  </tr>

	<tr>
		<td><br /></td>
		<td></td>
	</tr>


  <tr>
    <td>Last updated</td>
    <td style='padding-left: 3em'>{$info.last_updated|date_format:'%I:%M %p - %A %B %e %Y'}</td>
  </tr>

	<tr>
		<td>
			<br />
		</td>
	</tr>

	<tr>
		<td>
			<br />
				<a href='{url_for name='client/pending'}'>Back</a>
		</td>
		<td style='padding-left: 3em'>
		{if $can_cancel_package}
			<button type='submit' class="fg-button ui-state-default ui-corner-all">Cancel order</button>
		{else}
			Package delivery can not be canceled because the package ready time<br />
			exceeds the current time, please be patient and your order will be delivered<br />
		{/if}
		</td>
</tr>
</table>

</li>
</ol>
</fieldset>
</form>

</div>
</div>
