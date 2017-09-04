<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>{$subject}</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="{url_for name='main_page/index' default='true'}css/email/email.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="posts">

  <div class="post">
    <h2 class="title">{$subject}</h2>
    <h3 class="posted">Sent on: {$smarty.now|date_format:'%I:%M %p - %A %B %e %Y'} </h3>

		{section name=i loop=$packages}
      <div class="story">
        <p>

     Package code: <span>{$packages[i].package_code} </span><br />
     Courier name: <span>{$packages[i].courier_name} </span><br />
     From address: <span>{$packages[i].from_text_address|default:'None'} </span><br />
     To address:   <span>{$packages[i].to_text_address|default:'None'} </span><br />
     Reference:    <span>{$packages[i].PackageDetail.reference|default:'None'}</span> <br />
     Package type: <span>{$packages[i].package_type}</span> <br />
     Package weight: <span>{$packages[i].PackageDetail.weight} {$packages[i].PackageDetail.weight_type}</span> <br />
     Round trip:   <span>{if $packages[i].PackageDetail.round_trip} Yes {else} No {/if}</span> <br />
     Instructions: <span>{$packages[i].PackageDetail.instructions|default:'None'}</span> <br /><br />

        </p>
      </div>
		{/section}

			<br />

        <p>
				Courier Express
        <a href='{url_for name='main_page/index' default='true'}'>{url_for name='main_page/index' default='true'}</a>
				</p>

    </div>
</div>


</body>
</html>
