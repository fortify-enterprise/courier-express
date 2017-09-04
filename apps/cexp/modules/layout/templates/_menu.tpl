<div style="margin-left: auto; width: 900px; background: #f2f2f2">
<ul id="nav" class="dropdown dropdown-horizontal">
  {section name=i loop=$menu}
    {if $menu[i].link}
      <li><a href="{url_for name=$menu[i].link}">{$menu[i].name}</a></li>
    {else}
	    <li><span class="dir">{$menu[i].name}</span>
	  	  <ul>
        {section name=j loop=$menu[i]}
          {if $menu[i][j]}
            <li><a href="{url_for name=$menu[i][j].link}">{$menu[i][j].name}</a></li>
          {/if}
        {/section}
		    </ul>
	    </li>
    {/if}
  {/section}
</ul>
</div>

<br />
<br />
<br />
