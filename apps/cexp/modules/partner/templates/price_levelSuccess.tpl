<div style="margin-left: 1em; margin-right: 1em">

	<div class="rounded_div" style="height: 31em">
  

	  <form class="cmxform" method="post" action="{url_for name='partner/price_level'}" style="float: left; margin-right: 100px;">
      <fieldset>
    	  <legend>{__ text='edit price levels'}:</legend>

        <ol>
          {$form}
          <li>
   		      <button type="submit" id="login_button" class="fg-button ui-state-default ui-corner-all">{__ text='Save changes'}</button>
          </li>
        </ol>
      </fieldset>
	  </form>

    <table class="infotable">
      <caption>Price levels</caption>
        <tr>
          <th>Level name</th>
          <th>Price ($)</th>
        </tr>

        {section name=i loop=$price_levels}
          {strip}
           <tr bgcolor="{cycle values="#aaafaa,#bbbfbb"}">
             <td>{$price_levels[i].name}</td>
             <td>${$price_levels[i].price}</td>
           </tr>
          {/strip}
        {/section}

    </table>



  </div> {* rounded div *}

</div>
