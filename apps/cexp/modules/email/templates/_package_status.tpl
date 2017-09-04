<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>{$account.subject}</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="{url_for name='main_page/index' default='true'}css/email/email.css" rel="stylesheet" type="text/css" />
</head>
<body>


<div id="archives" class="obox">

		<h2 class="title" style="color: #FF6500">{$subject}</h2>
		<h3 class="posted" style="color: #FF6500">Sent on: {$smarty.now|date_format:'%I:%M %p - %A %B %e %Y'} </h3>

			<div class="story">
				<p>

					Your package  {$package_code} status has changed<br />
					From address: {$info.from_address}<br />
          To address:   {$info.to_address}<br />
          Status:       <span style='color: #FF6500'>{$status}</span><br /><br />

					Courier Express
					<a href="{url_for name='main_page/index' default='true'}">{url_for name='main_page/index' default='true'}</a>
				</p>
			</div>

			<div class="meta">
				<p></p>
			</div>

</div>


</body>
</html>
