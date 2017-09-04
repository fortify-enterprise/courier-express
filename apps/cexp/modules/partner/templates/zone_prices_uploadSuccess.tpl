<div style="margin-left: 1em; margin-right: 1em">

	<form id="partner_prices_upload" class="cmxform" method="post" enctype="multipart/form-data" action="{url_for name='partner/zone_prices_upload'}">
	 	<div class="rounded_div">
			<fieldset>
    			<legend>{__ text='upload zone prices'}:</legend>

					<ol>
						<li>
						</li>

						<li>
							<label style="width: 18em" for="upload_file">{__ text='CSV comma separated file'}: </label>
							<input type="file" name="file" id="file" value="" />
							<br />
						</li>

						<li>
   						<button type="submit" class="fg-button ui-state-default ui-corner-all">{__ text='Save changes'}</button>
						</li>

            <li>
							{if $message}
              <span class="highlight">{$message}</span>
            	{/if}
						</li>

		  		</ol>
			  </fieldset>
		<input type="hidden" name="submitted" value="1" />
	</form>


				<div class="white_text highlight">
				<pre class="white_text highlight" style="font-size: small">
<span style="color: #E6E4C9">
{__ text='Syntax'}:
------------------------------------------------------------------------------------------------------------------------------------------
Zone_Name (element_type: ElementName1, ElementName2, ElementNameN)
price [(service_type) (Zone_Name1; Zone_Name2 : price_value)]
------------------------------------------------------------------------------------------------------------------------------------------

Service types:
------------------------------------------------------------------------------------------------------------------------------------------
'1 Hour'
'2 Hour'
'3 Hour'
'4 Hour'
'Same Day'
'Overnight'
------------------------------------------------------------------------------------------------------------------------------------------

Zone element types:
------------------------------------------------------------------------------------------------------------------------------------------
'postal'
'city'
'country'
------------------------------------------------------------------------------------------------------------------------------------------
</span>

<span style="color: rgb(206, 246, 206);">{__ text='Sample file'}:</span>
------------------------------------------------------------------------------------------------------------------------------------------
Zone_A (city: Vancouver; Richmond; Burnaby)
Zone_B (city: Delta; Surrey)
price [(Overnight) (Zone_A; Zone_A : 15)]
price [(Overnight) (Zone_A; Zone_B : 30)]
price [(1 Hour Express) (Zone_A; Zone_A : 50)]
------------------------------------------------------------------------------------------------------------------------------------------
</span>
</div>
</pre>

</div>
</div>
