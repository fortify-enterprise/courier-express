<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>{$account.subject}</title>
<link href="http://{$smarty.server.SERVER_NAME}/css/email/email.css" rel="stylesheet" type="text/css" />
</head>
<body>


<div id="posts">

	<div class="post">
		<h2 class="title">{$account.subject}</h2>
		<h3 class="posted">Sent on: {$smarty.now|date_format:'%I:%M %p - %A %B %e %Y'} </h3>

			<div class="story">
				<p>

					Login username: {$client.ClientLogin.email}<br />
					Notification email: {$client.ClientDetail.email}<br />
					Password: <span style='font-weight: bold'>{$client.ClientLogin.password}</span><br /><br />
					Click here to login: <a href='{url_for name='auth/index' default=true}'>{url_for name='auth/index' default=true}</a>

				</p>
			</div>

			<div class="meta">
				<p></p>
			</div>

		</div>

</div>


</body>
</html>
