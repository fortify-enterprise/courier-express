<div style="margin-left: auto; width: 900px; height: 103px; background-image: url('/images/landing/header/logo_menu.jpg'); background-repeat: no-repeat;">
  <div style="padding-top: 46px; padding-left: 465px; color:#ffffff; text-decoration:none;">
    <span style="margin-left: 15.5em">
      <a style="color: #ffffff; text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);"
  
      {if $client_id}
        	href="{url_for name='auth/logout'}">Logout</a>
			{else}
        href="{url_for name='auth/index'}">Login</a>
      {/if}
    </span>
  </div>
</div>
