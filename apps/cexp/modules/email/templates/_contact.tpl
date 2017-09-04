<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>{$info.subject}</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="http://www.courierexpress.ca/css/email/email.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="posts">

	<div class="post">
		<h2 class="title">{$info.subject}</h2>
		<h3 class="posted">Sent on: {$smarty.now|date_format:'%I:%M %p - %A %B %e %Y'} </h3>

			<div class="story">
				<p>

					Contact name: {$info.name|default:'No contact name given'}<br />
					Company: {$info.company|default:'No company given'}<br />
					Phone: {$info.phone|default:'No phone given'}<br />
					Email: {$info.email|default:'No email given'}<br />
					Details: {$info.details|default:'No details given'}<br /><br />

				Courier Express
				<a href="{url_for name='landing_page/index' default='true'}">{url_for name='landing_page/index' default='true'}</a>
				</p>
			</div>

			<div class="meta">
				<p>
				</p>
			</div>

		</div>

</div>

</body>
</html>
