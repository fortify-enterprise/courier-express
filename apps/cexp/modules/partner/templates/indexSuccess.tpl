<div style="margin-left: 1em; margin-right: 1em">

  <div class="rounded_div" style='height: 45em; width: 50em'>
    <br />
    <div class="white_text">
      Welcome to Courier Express partner interface!<br /><br /><br />

      {section name=i loop=$menu}
        {if $menu[i].link && $menu[i].description}
          <span><a href='{url_for name=$menu[i].link}'>{$menu[i].name}</a></span> <br />
          {$menu[i].description}<br /><br /><br />
        {else}
          {section name=j loop=$menu[i]}
            {if $menu[i][j] && $menu[i][j].description}
              <span><a href='{url_for name=$menu[i][j].link}'>{$menu[i].name}/{$menu[i][j].name}</a></span> <br />
              {$menu[i][j].description}<br /><br /><br />
               
            {/if}
          {/section}
        {/if}
      {/section}


       {if $page_links|@sizeof > 1}
         <span>
           <span style="font-weight: bold">
             {__ text='Page'}:
             </span>&nbsp;&nbsp;
             {foreach name=outer item=link from=$page_links}
               <a href="{url_for name='partner/index'}?page={$link}">{$link}</a>&nbsp;
             {/foreach}
          </span>
        {/if}


    </div>

  <div>
    <form>
      <input value="1" type="hidden" id="client_home" />
    </form>
  </div>

</div>
