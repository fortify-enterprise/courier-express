<div style='padding:0px;text-align:center; width:700px; margin:0px auto; margin-bottom: 10px; margin-top: 15px'>

  {section name=i loop=$menu}
    {if $menu[i].link}
      <a href="{url_for name=$menu[i].link}">{$menu[i].name}</a>
      {if $smarty.section.i.iteration != $menu|@sizeof}  
       &nbsp;&nbsp; |&nbsp;&nbsp;
      {/if}  
    {/if}
  {/section}

</div>


<div style='text-align: center'>
<script type="text/javascript" language="javascript">
var t = document.title;
var u = document.URL;
var scriptUrlAux = 'http://blogplay.com/servers/sociable_web.php';
var scriptUrl = scriptUrlAux + '?jq=1&id=10064&amp;u=' + u + '&amp;t=' + t;
var sociableSrc=String.fromCharCode(60) + 'scr' +'ipt type="text/javascript" language="javascript" src="' + scriptUrl + '"' + String.fromCharCode(62,60) + '/scr' + 'ipt>';
document.write(sociableSrc); 
</script>
</div>


<table style="width: 770px; margin-left: auto; margin-right: auto" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="26px" align="center">Courier Express is a registered trademark of <a href="http://www.amadeos.ca">Amadeos Inc.</a> &copy; {$smarty.now|date_format:"%Y"}
		<br />
  </tr>
</table>
