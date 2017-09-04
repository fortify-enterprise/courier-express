<table style='font-family: "Lucida Grande","Bitstream Vera Sans",Verdana,Arial,sans-serif; text-align: left'>
  <tr>
    <td>Client name</td>
    <td style='padding-left: 3em'>{$info.name|default:'No name on file'}</td>
  </tr>

	<tr>
		<td><br /></td>
		<td></td>
	</tr>

  <tr>
    <td>From address</td>
    <td style='padding-left: 3em'>{$info.from_address|default:'Address not present'}</td>
  </tr>


  <tr>
    <td>To address</td>
    <td style='padding-left: 3em'>{$info.to_address|default:'Address not present'}</td>
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
    <td style='padding-left: 3em'>{$info.num_pieces|default:'Unknown'}</td>
  </tr>

  <tr>
    <td>Weight</td>
    <td style='padding-left: 3em'>{$info.weight|default:'Unknown'}&nbsp;{$info.weight_type}</td>
  </tr>

  <tr>
    <td>Reference</td>
    <td style='padding-left: 3em'>{$info.reference|default:'No reference specified'}</td>
  </tr>

  <tr>
    <td>Package type</td>
    <td style='padding-left: 3em'>{$info.package_type|default:'Unknown'}</td>
  </tr>

  <tr>
    <td>Service level type</td>
    <td style='padding-left: 3em'>{$info.service_level_type|default:'Regular service'}</td>
  </tr>

  <tr>
    <td>Delivery type</td>
    <td style='padding-left: 3em'>{$info.delivery_type|default:'Regular delivery'}</td>
  </tr>

  <tr>
    <td>Round trip</td>
    <td style='padding-left: 3em'>{$info.round_trip|default:'No round trip specified'}</td>
  </tr>

  <tr>
    <td>Instructions</td>
    <td style='padding-left: 3em'>{$info.instructions|default:'No special instructions'}</td>
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

	{if $pending_orders}
	<tr>
		<td>
			<br />
			<a href='{url_for name='services/edit_package'}?package_code={$package_code}'>Edit package</a>
		</td>
		<td>
		</td>
	</tr>
	{/if}
</table>

